<?php

namespace App\Console\Commands;

use App\Classes\wpApi;

use Illuminate\Console\Command;

class createWpUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createWpUser {user} {pass} {first_name} {last_name} {marketers_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $user = $this->argument('user');
        $pass = $this->argument('pass');
        $first_name = $this->argument('first_name');
        $last_name = $this->argument('last_name');
        $marketers_id = $this->argument('marketers_id') !== null ? $this->argument('marketers_id') : 0;
        $params = [
            "user_login"   => $user,
            "pass"         => $pass,
            "first_name"   => $first_name,
            "last_name"    => $last_name 
        ];
        if($marketers_id>0){
            $params["marketers_id"] = $marketers_id;
        }
        $wpApi = new wpApi;
        $wpApi->getWpData('user/create',$params);
    }
}
