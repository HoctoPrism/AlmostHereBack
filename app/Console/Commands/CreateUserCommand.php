<?php

namespace App\Console\Commands;

use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user {role=null}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';

    /**
     * Allows to create a user
     *
     * @return int
     */
    public function handle(): int
    {
        $name = $this->ask("Enter a name");
        $email = $this->ask("Enter an email");
        $firstname = $this->ask("Enter a firstname");
        $lastname = $this->ask("Enter a lastname");

        if ($name && $email && $firstname && $lastname){

            $role = 'user';

            if ($this->argument('role') === 'admin'){
                $role = $this->argument('role');
            }

            $genPassword = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%^&'), 15, 15);
            $password = Hash::make($genPassword);

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'password' => $password,
                'role' => $role,
            ]);

            if ($user){
                $this->line('<fg=green;options=bold>User created');
                Mail::to($email)->send(new UserCreated($user, $genPassword));
                return Command::SUCCESS;
            }

        }
        return Command::FAILURE;
    }
}
