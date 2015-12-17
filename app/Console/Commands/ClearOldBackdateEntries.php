<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearOldBackdateEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-backdates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the backdate entries';

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
    public function handle()
    {
        DB::table('backdate_timeentry')
            ->where('backdate', '<=', Carbon::now()
                    ->toDateString())
            ->delete();
    }
}
