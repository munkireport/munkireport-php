<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Password;

class LocalAdminSeeder extends Seeder
{
    public function run()
    {
        $existingAdmin = \App\User::where('email', 'admin@localhost');

        if (!$existingAdmin->exists()) {

            $admin = new \App\User([
                'name' => 'Administrator',
                'email' => 'admin@localhost',
                'role' => 'admin',
                'password' => 'dummyvalue',
                'display_name' => 'Administrator',
            ]);
            $admin->saveOrFail();
            $resetToken = Password::createToken($admin);
            $appUrl = env('APP_URL', 'http://localhost:8080');
            $this->command->info(
                "Reset the `admin@localhost` password at {$appUrl}/reset-password/{$resetToken}"
            );
        } else {
            $this->command->info("A local administrator account already exists, not providing password reset link");
        }
    }
}
