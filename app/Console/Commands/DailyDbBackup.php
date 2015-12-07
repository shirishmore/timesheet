<?php

namespace App\Console\Commands;

use App\Services\Interfaces\SendMailInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class DailyDbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:dbdaily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take a backup of the entire DB and upload on S3';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SendMailInterface $mail)
    {
        $date = Carbon::now()->format('Y-m-d_h-i');
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');
        $command = "mysqldump --user={$user} -p{$password} {$database} > {$date}.sql";
        $process = new Process($command);
        $process->start();

        while ($process->isRunning()) {
            $s3 = Storage::disk('s3');
            $s3->put('fw-labs-db/' . $date . ".sql", file_get_contents("{$date}.sql"));
            unlink("{$date}.sql");
            $this->sendEmail($mail);
        }
    }

    private function sendEmail($mail)
    {
        $mail->mail([
            'from' => 'amitav.roy@focalworks.in',
            'fromName' => 'Amitav Roy',
            'to' => 'amitav.roy@focalworks.in',
            'toName' => 'Amitav Roy',
            'subject' => 'Timesheet DB Backup taken',
            'mailBody' => view('mails.dbbackup-mail'),
        ]);
    }
}
