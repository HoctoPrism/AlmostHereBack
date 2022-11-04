<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteFileGtfsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:delete-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete one or multiple GTFS backup files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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
        $choices = $this->choice("Choose a file to delete (multiple selection possible separated by a comma)", $modified, null, null, true);

        $confirm = $this->confirm('Are you sure to process the deletion');

        if ($confirm && is_array($choices)) {

            foreach ($choices as $choice){

                $arg = explode(" - ", $choice)[0];
                $delete = Storage::delete('gtfs/zip/' . $arg);

                if ($delete) {
                    $this->line('<fg=green;options=bold>'.$arg.' has been deleted');
                    Log::channel('gtfs')->info($arg.' has been deleted');
                } else {
                    $this->line('<fg=red>Impossible to delete ' . $arg);
                    Log::channel('gtfs')->info('Impossible to delete ' . $arg);
                }
            }
            return Command::SUCCESS;

        } else {
            $this->line('<fg=yellow>File deletion cancelled');
            Log::channel('gtfs')->info('File deletion cancelled');
            return Command::FAILURE;
        }
    }
}
