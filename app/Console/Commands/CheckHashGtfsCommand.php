<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckHashGtfsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:check-hash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if hashes are corresponding';

    /**
     * Check the hash of the main GTFS archive and the one from the last update at the api
     * It's used in purpose to know if an update may be needed
     *
     * @return int
     */
    public function handle(): int
    {
        $file = Storage::exists('gtfs/zip/gtfs-smtc.zip');

        if ($file){
            $hash = hash_file( "sha256", Storage::path('gtfs/zip/gtfs-smtc.zip'));
            $api = json_decode(file_get_contents('https://transport.data.gouv.fr/api/datasets/6082dacfd1d05e89970795f5'), true);
            // Download a temp file from the api to get the hash
            file_put_contents(Storage::path('gtfs/zip/gtfs-smtc-check.zip'), file_get_contents($api['resources'][0]['original_url']));
            $apiFile = Storage::path('gtfs/zip/gtfs-smtc-check.zip');
            $apiHash = hash_file( "sha256", $apiFile);

            if (isset($api) && isset($apiFile)){
                // Check the api hash with main GTFS archive hash
                if ($apiHash === $hash){
                    $this->line('<fg=blue>Data is already up to date');
                    $code = 60;
                } else {
                    $this->line('<fg=yellow>Hash is not corresponding, may need an update');
                    $code = 61;
                }
                Storage::delete("gtfs/zip/gtfs-smtc-check.zip");
                return $code;
            } else {
                $this->line("<fg=red>Can't fetch data from API");
                Storage::delete("gtfs/zip/gtfs-smtc-check.zip");
                return Command::FAILURE;
            }
        } else {
            $this->line("<fg=red>gtfs-smtc.zip file not found !");
            return Command::FAILURE;
        }

    }
}
