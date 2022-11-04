<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ListBackupGtfsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:list-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all GTFS backups';

    /**
     * Command that lists all the GTFS backup
     *
     * @return int
     */
    public function handle(): int
    {
        $files = Storage::allFiles('gtfs/zip');

        foreach ($files as $file){
            $this->line("<fg=blue;options=bold>" . explode("/", $file)[2] . '<fg=white> - modifié le : '. date('d/m/Y H:i:s', Storage::lastModified($file)));
        }
        return Command::SUCCESS;
    }
}
