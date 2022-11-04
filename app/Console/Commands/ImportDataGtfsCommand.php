<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ImportDataGtfsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data into database';

    /**
     * Imports data into database
     *
     * @return int
     */
    public function handle()
    {
        $confirm = $this->confirm('Are you sure you want to proceed to an import of datas ?');

        if ($confirm){
            $files = Storage::allFiles('gtfs/data');
            $tableNames = [];

            if (isset($files) && count($files) > 0 && gettype($files) == "array") {

                // Make a backup of the database before everything
                $this->line('<fg=yellow>Start of the MySqlDump');
                $filename = date('YmdHis').'-db-dump.sql';
                $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . Storage::path('gtfs/sql/') . $filename;
                $returnVar = NULL;
                $output  = NULL;
                exec($command, $output, $returnVar);
                $exist = Storage::exists('gtfs/sql/' . $filename);

                $this->line('<fg=green;options=bold>MySQLDump created as ' . $filename);
                Log::channel('gtfs')->info('MySQLDump created as ' . $filename);

                if ($returnVar === 0 && $exist){

                    // Get only the names of files w/o extentions like : [example => example.txt]
                    foreach ($files as $file){
                        $tableNames[explode(".txt", basename($file))[0]] = basename($file);
                    }

                    // Make truncate of the table and copy data into it
                    foreach ($tableNames as $tableName => $fileName){

                        $this->newLine();
                        $start = microtime(true);
                        $this->line('<fg=blue>## Start to truncate the following table : ' . $tableName);
                        Log::channel('gtfs')->info('Start to truncate the following table : ' . $tableName);

                        // Truncate the table
                        Schema::disableForeignKeyConstraints();
                        DB::table($tableName)->truncate();
                        Schema::enableForeignKeyConstraints();

                        $this->line('<fg=green;options=bold>Truncate done for the following table : ' . $tableName);
                        Log::channel('gtfs')->info('Truncate done for the following table : ' . $tableName);
                        $this->line('<fg=blue>## Start to load data the following table : ' . $tableName);
                        Log::channel('gtfs')->info('Start to load data the following table : ' . $tableName);

                        // Load new data
                        Schema::disableForeignKeyConstraints();
                        DB::connection()->getpdo()->exec("SET GLOBAL local_infile = true;");
                        DB::statement("LOAD DATA LOCAL INFILE '" . 'storage/app/gtfs/data/' . $fileName . "' INTO TABLE " . $tableName . " FIELDS TERMINATED BY ',' IGNORE 1 LINES;");
                        DB::connection()->getpdo()->exec("SET GLOBAL local_infile = false;");
                        Schema::enableForeignKeyConstraints();

                        $this->line('<fg=green;options=bold>End of the loading data for the following table : ' . $tableName);
                        Log::channel('gtfs')->info('End of the loading data for the following table : ' . $tableName);
                        $this->line('<fg=magenta;options=bold>' . Db::table($tableName)->count() . ' lines added in ' . round(microtime(true) - $start, 4) . 'sec');

                    }

                    return Command::SUCCESS;

                }

            }

        }
        return Command::FAILURE;
    }
}
