<?php
use App\Client;
use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->truncate();

        //insert some dummy records
        Client::create([
            'name' => 'Idea7',
            'country' => 'IN',
            'status' => 'active',            
        ]);

        Client::create([
            'name' => 'Sunpharma',
            'country' => 'IN',
            'status' => 'active',            
        ]);
    }
}
