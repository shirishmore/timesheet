<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        //insert some dummy records
        User::create([
            'name' => 'Amitav Roy',
            'email' => 'amitav.roy@focalworks.in',
            'dob' => '1999-07-14',
            'employee_id' => 'FW-22',
            'joining_date' => '2014-01-01',
            'password' => bcrypt('pass'),
        ]);
    }
}
