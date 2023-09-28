<?php

namespace App\Console\Commands;

use App\Models\FurnitureStore;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportFurnitureStores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stores:import {--R|reduced}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import furniture stores from a spreadsheet.';

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
        $fileName = $this->option('reduced') ? 'reduced-furniture-stores-uk.csv' : 'furniture-stores-uk.csv';
        $this->info("File name: " . $fileName);
        $path = Storage::path($fileName);
        $csv = Reader::createFromPath($path);

        $csv->setHeaderOffset(0);
        
        $statement = Statement::create();

        $records = $statement->process($csv);

        $data = [];

        foreach ($records as $record) {
            $data[] = [
                'name' => $record['Name'],
                'url' => $record['Website']
            ];
        }

        FurnitureStore::insert($data);
    }
}
