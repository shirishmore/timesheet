<?php
use App\Project;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {               
        DB::table('projects')->truncate();

        //insert some dummy records
        Project::create([
            'name' => 'Cup52',
            'client_id' => '1',
            'status' => 'active',            
        ]);

        Project::create([
            'name' => 'DocNet',
            'client_id' => '2',
            'status' => 'active',            
        ]);

        Project::create([
            'name' => 'Mahindra FES',
            'client_id' => '1',
            'status' => 'active',            
        ]);
    }
}
