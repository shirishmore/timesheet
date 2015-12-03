<?php

use App\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::create(['name' => 'PHP']);
        Tag::create(['name' => 'CSS']);
        Tag::create(['name' => 'JS']);
        Tag::create(['name' => 'Development']);
        Tag::create(['name' => 'Maintenance']);
        Tag::create(['name' => 'Bug Fixing']);
        Tag::create(['name' => 'Testing']);
        Tag::create(['name' => 'Meeting']);
    }
}
