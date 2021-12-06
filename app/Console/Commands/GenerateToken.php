<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateToken extends Command
{
    protected $signature = 'shop:generate-token';

    protected $description = '快速用户生成 token';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->ask('输入用户 id');

        $user = User::find($userId);
        if (!$user) {
            $this->error('用户不存在');
        }
        $this->info($user->createToken('test')->accessToken);
    }
}
