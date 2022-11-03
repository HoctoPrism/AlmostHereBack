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
    protected $description = 'Command to check if hashes are corresponding';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $file = Storage::path('gtfs/zip/gtfs-smtc.zip');
        $hash = hash_file( "sha256", $file);
        $api = json_decode(file_get_contents('https://transport.data.gouv.fr/api/datasets/616d6116452cadd5c04b49b7'), true);

        if (isset($api) && isset($api['resources'][0]['content_hash'])){
            if ($api['resources'][0]['content_hash'] === $hash){
                $this->line('<fg=blue>Data is already up to date');
                $code = 60;
            } else {
                $this->line('<fg=yellow>Hash is not corresponding, may need an update');
                $code = 61;
            }
            return $code;
        } else {
            $this->line("<fg=red>Can't fetch data from API");
            return Command::FAILURE;
        }

    }
}
