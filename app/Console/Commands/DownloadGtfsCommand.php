<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadGtfsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Downloads the GTFS resources from API";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $hashCode = $this->call('gtfs:check-hash');
        $file = $this->call('gtfs:check-file');
        $api = json_decode(file_get_contents('https://transport.data.gouv.fr/api/datasets/616d6116452cadd5c04b49b7'), true);

        if ($hashCode == 61 && $file == 0 && (isset($api) && gettype($api) == "array")){
            $copy = Storage::copy('gtfs/zip/gtfs-smtc.zip', 'gtfs/zip/'.date('YmdHis').'-gtfs-smtc.zip');
            if ($copy){
                file_put_contents(Storage::path('gtfs/zip/gtfs-smtc.zip'), file_get_contents($api['resources'][0]['original_url']));
                Log::channel('gtfs')->info('File downloaded successfully');
                $this->line('<fg=green;options=bold>File downloaded successfully');
            } else {
                Log::channel('gtfs')->info('File downloading failed');
                $this->line('<fg=red>File downloading failed');
            }
        } elseif($hashCode == 60) {
            Log::channel('gtfs')->info('No download required');
            $this->line("<fg=green>No download required");
        } else {
            Log::channel('gtfs')->info('File downloading failed');
            $this->line("<fg=red>File downloading failed");
        }

        return Command::SUCCESS;
    }
}
