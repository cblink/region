<?php

namespace Myischen\Region\Console;

use Illuminate\Console\Command;

class RegionUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'region:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regions update';

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
     * @return mixed
     */
    public function handle()
    {
        $this->call('db:seed', ['--class' => \Myischen\Region\RegionTableSeeder::class]);
    }
}
