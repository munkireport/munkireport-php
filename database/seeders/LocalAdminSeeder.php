<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Password;

class LocalAdminSeeder extends Seeder
{
    public function run()
    {
        $admin = new \App\User([
            'name' => 'Administrator',
            'email' => 'admin@localhost',
            'role' => 'admin',
            'password' => 'dummyvalue',
            'display_name' => 'Administrator',
        ]);
        $admin->saveOrFail();
        $resetToken = Password::createToken($admin);
        $this->command->info("Reset the `admin@localhost` password at https://<public url>/password/reset/{$resetToken}");
    }
}
