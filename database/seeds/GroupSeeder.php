<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'name' => "Admin",
        ]);

        DB::table('group_gates')->insert([
            'groups_id' => 1,
            'users_id' => 1,
            'key'=>'parameters'
        ]);
    }
}
