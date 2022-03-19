<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:admin
                                {user : 登录名}
                                {password : 登录密码}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化管理员账号';


    /**
     * 默认管理账号id
     *
     * @var int
     */
    private $defalutAdminId = 1;

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

        $isExist = DB::table('admin')->where('id', $this->defalutAdminId)->first();
        $user = $this->argument('user');
        $password = $this->argument('password');
        if (empty($user) || empty($password)) {
            $this->error('参数不完整，请完整数据账号和密码');
            return 0;
        }
        $password = Hash::make($password);
        if (!empty($isExist)) {
            DB::table('admin')->where('id', $this->defalutAdminId)->update(['login_name' => $user, 'password' => $password]);
        } else {
            DB::table('admin')->insert([
                'id' => $this->defalutAdminId,
                'login_name' => $user,
                'password' => $password,
                'created_at' => time(),
                'updated_at' => time(),
                'status' => 1
            ]);
        }
    
        return 0;
    }
}
