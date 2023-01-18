<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Monolog\Logger;
use ZipArchive;

class ExtractGtfsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:extract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Extracts data from GTFS zip";

    /**
     * Make an extraction of the gtfs-smtc.zip archive to a folder called "data"
     *
     * @return int
     */
    public function handle(): int
    {
        $zip = new ZipArchive;
        $res = $zip->open(Storage::path('gtfs/zip/gtfs-smtc.zip'));

        if ($res === TRUE) {
            $this->info('Data extracted');
            $zip->extractTo(Storage::path('gtfs/data'));
            $zip->close();
            Log::channel('gtfs')->info('Data extracted');
            return Command::SUCCESS;
        } else {
            Log::channel('gtfs')->info('failed, code:' . $res);
            $this->alert('failed, code:' . $res);
            return Command::FAILURE;
        }

    }
}
