<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Password;

class UserResetLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-link {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a password reset link that you can give to a user for them to reset their password';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $user = User::where('email', $this->argument('email'))->firstOrFail();
            $resetToken = Password::createToken($user);
            $appUrl = env('APP_URL', 'http://localhost:8080');
            $this->info(
                "The user, `{$user->email}`, may reset their password at {$appUrl}/reset-password/{$resetToken}"
            );

            return Command::SUCCESS;
        } catch (ModelNotFoundException $e) {
            $this->info('There was no user found with the given email address, try listing available users with the `user:list` command');
            return Command::FAILURE;
        }
    }
}
