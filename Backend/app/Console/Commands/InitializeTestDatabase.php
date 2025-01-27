<?php

namespace App\Console\Commands;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;

class InitializeTestDatabase extends Command
{
    protected $signature = 'db:init:test';

    protected $description = 'Initialize the test database';

    public function handle()
    {
        $this->call('migrate', ['--database' => 'testing']);
        $this->call('db:seed', ['--class' => DatabaseSeeder::class, '--database' => 'testing']);
    }
}
