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

            $confirm = $this->confirm('Do you want to proceed to an update ?');

            if ($confirm){

                $newName = 'gtfs/zip/'.date('YmdHis').'-gtfs-smtc.zip';
                $copy = Storage::copy('gtfs/zip/gtfs-smtc.zip', $newName);

                if ($copy){

                    $this->line('<fg=green>Main GTFS archive backup created as ' . $newName);
                    Log::channel('gtfs')->info('Main GTFS archive backup created as ' . $newName);

                    file_put_contents(Storage::path('gtfs/zip/gtfs-smtc.zip'), file_get_contents($api['resources'][0]['original_url']));

                    Log::channel('gtfs')->info('GTFS archive downloaded successfully as gtfs-smtc.zip');
                    $this->line('<fg=green;options=bold>GTFS archive downloaded successfully as gtfs-smtc.zip');

                } else {
                    Log::channel('gtfs')->info('GTFS archive download failed');
                    $this->line('<fg=red>GTFS archive download failed');
                }
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
