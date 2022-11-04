<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CheckFileGtfsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:check-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if gtfs-smtc.zip exists';

    /**
     * Check if the main archive gtfs-smtc.zip exists
     *
     * @return int
     */
    public function handle()
    {
        $zip = new ZipArchive;
        $res = $zip->open(Storage::path('gtfs/zip/gtfs-smtc.zip'));

        if ($res === TRUE) {
            return Command::SUCCESS;
        } else {
            return Command::FAILURE;
        }

    }
}
