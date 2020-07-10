<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class PasswordResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:reset {email : The e-mail address of the user whose password you want to reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a users password';

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
     */
    public function handle()
    {
        $email = $this->argument('email');

        User::where('email', $email)->update([
            'password' => bcrypt($this->secret('New password?'))
        ]);

        $this->info('password has been updated');
    }
}
