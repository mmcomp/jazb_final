<?php

use Illuminate\Database\Seeder;

class SupervisorGateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::table('groups')->where('type', 'supervisor')->first();
        if(!$result) {
            DB::table('groups')->insert([
                "name"=>"Supervisor",
                "type"=>"supervisor"
            ]);
        }
        $result = DB::table('groups')->where('type', 'supervisor')->first();
        $aresult = DB::table('groups')->where('type', 'admin')->first();
        $gresult = DB::table('group_gates')->where('groups_id', $result->id)->where('key', 'supervisor')->first();
        if(!$gresult) {
            DB::table('group_gates')->insert([
                "groups_id"=>$result->id,
                "key"=>"supervisor",
                "users_id"=>1
            ]);
        }
        $agresult = DB::table('group_gates')->where('groups_id', $aresult->id)->get();
        foreach($agresult as $agr) {
            $gresult = DB::table('group_gates')->where('groups_id', $result->id)->where('key', $agr->key)->first();
            if(!$gresult) {
                DB::table('group_gates')->insert([
                    "groups_id"=>$result->id,
                    "key"=>$agr->key,
                    "users_id"=>1
                ]);
            }
        }
        return;
    }
}
