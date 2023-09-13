<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanProductPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyses product page table and adds any furniture items to the furniture_items table.';

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
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
