<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class initUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:initUserRole';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command use to create preset of user and role';

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
     * @return int
     */
    public function handle()
    {
        $roleAdmin = Role::firstOrCreate(['name' => config('custom.profil_admin_name'),'guard_name'=>'api']);
        $roleDefaultUser = Role::firstOrCreate(['name' => config('custom.profil_default_name'),'guard_name'=>'api']);
        $prompt = $this->ask('create a super Admin [ y / n ] ?');
        $prompt = ($prompt=='y'?true:false);
        if($prompt){
            $promptUser = $this->ask('name [ default : admin ]?');
            $promptUser = (!empty($promptUser))?$promptUser:'admin';
            $promptPassword = $this->ask('password [ default : secret ] ?');
            $promptPassword = ((!empty($promptPassword))?$promptPassword:'secret');
            $promptMail = $this->ask('mail [ default : admin@test.test ] ?');
            $promptMail = filter_var($promptMail, FILTER_VALIDATE_EMAIL)?$promptMail:'admin@test.test';
            $admin = User::create([
               'name'=>$promptUser,
               'email'=>$promptMail,
               'password'=>$promptPassword,
            ]);
            $admin->assignRole($roleAdmin);
        }

        $prompt = $this->ask('create a default user [ y / n ] ?');
        $prompt = ($prompt=='y'?true:false);
        if($prompt){
            $promptUser = $this->ask('name [ default : default ] ?');
            $promptUser = (!empty($promptUser))?$promptUser:'default';
            $promptPassword = $this->ask('password [ default : secret ] ?');
            $promptPassword = ((!empty($promptPassword))?$promptPassword:'secret');
            $promptMail = $this->ask('mail [ default : default@test.test ] ?');
            $promptMail = filter_var($promptMail, FILTER_VALIDATE_EMAIL)?$promptMail:'default@test.test';
            $defaultUser = User::create([
               'name'=>$promptUser,
               'email'=>$promptMail,
               'password'=>$promptPassword,
            ]);
            $defaultUser->assignRole($roleDefaultUser);


        }
    }
}
