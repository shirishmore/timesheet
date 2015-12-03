<?php
use App\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();

        //insert some dummy records
        Role::create([
            'name' => 'Admin',
            'status' => 'active',
        ]);

        /*Role::create([
        'name' => 'Project Manager',
        'status' => 'active',
        ]);

        Role::create([
        'name' => 'Team Lead',
        'status' => 'active',
        ]);

        Role::create([
        'name' => 'Developer',
        'status' => 'active',
        ]);

        Role::create([
        'name' => 'Designer',
        'status' => 'active',
        ]);

        Role::create([
        'name' => 'Tester',
        'status' => 'active',
        ]);*/

        DB::table('roles_users')->insert([
            'user_id' => 1,
            'role_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
