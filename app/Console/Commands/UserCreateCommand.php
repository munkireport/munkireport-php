<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class UserCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a local user';

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
     *
     */
    public function handle()
    {
        User::create([
            'name' => $this->ask('Name?'),
            'email' => $this->ask('Email address?'),
            'password' => bcrypt($this->secret('Password?'))
        ]);

        $this->info('User saved');
    }
}
