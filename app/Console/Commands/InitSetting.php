<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitSetting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:setting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化系统配置项';


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
        DB::table('setting')->insert([
            ['id' => 1, 'sn' => 'mini_program', 'name' => '小程序设置', 'config' => '', 'updated_at' => time()]
        ]);
    
        return 0;
    }
}
