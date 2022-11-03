<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReplaceFileGtfsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:replace-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command that replaces a file by the asked one';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $files = Storage::allFiles('gtfs/zip');
        $modified = [];

        // Make custom line for the select
        foreach ($files as $file) {
            if ($file != 'gtfs/zip/gtfs-smtc.zip') {
                $modified[] = explode("/", $file)[2] . ' - modifié le : <fg=blue;options=bold>' . date('d/m/Y H:i:s', Storage::lastModified($file)) . "<fg=white>";
            }
        }
        // List all files to the select
        $choice = $this->choice("Choose a file for the replacement", $modified);

        $confirm = $this->confirm('Are you sure to process the replacement with : ' . $choice);

        if ($confirm) {
            $arg = explode(" - ", $choice)[0];
            $delete = Storage::delete('gtfs/zip/gtfs-smtc.zip');
            $copy = Storage::copy('gtfs/zip/'.$arg, 'gtfs/zip/gtfs-smtc.zip');

            if ($delete && $copy) {
                $this->line('<fg=green;options=bold>The file has been changed');
                Log::channel('gtfs')->info('The file has been changed');
                return Command::SUCCESS;
            } else {
                $this->line('<fg=red>Impossible to process the replacement');
                Log::channel('gtfs')->info('Impossible to process the replacement');
                return Command::FAILURE;
            }
        } else {
            $this->line('<fg=yellow>File replacement cancelled');
            Log::channel('gtfs')->info('File replacement cancelled');
            return Command::FAILURE;
        }
    }
}
