<?php


namespace Transave\ScolaBookstore\console;


use DatabaseSeeder;
use Illuminate\Console\Command;

class Seeder extends Command
{
    protected $signature = 'bookstore:seed';
    protected $description = 'seed package data to tables';

    public function handle()
    {
        $seeders = (new DatabaseSeeder())->definition();
        foreach ($seeders as $index => $seeder) {
            $this->info('seeding '.$index.' begins');
            $seed = new $seeder();
            $seed->run();
            $this->info($index.' seeded successfully');
        }
    }
}