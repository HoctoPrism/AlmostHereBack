<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ConsoleTest extends TestCase
{
    /**
     * Compare hashes and define code status.
     *
     * @return void
     */
    public function test_compare_hash_and_they_are_equal()
    {
        $hash = hash_file( "sha256", Storage::path('gtfs/zip/gtfs-smtc.zip'));
        $hash2 = hash_file( "sha256", Storage::path('gtfs/zip/gtfs-smtc.zip'));

        $code = 0;

        if ($hash === $hash2){
            $code = 60;
        }

        $this->assertIsString($hash2);
        $this->assertIsString($hash);
        $this->assertIsNumeric($code);

        $this->assertEquals(60, $code);

    }

    /**
     * Compare hashes and define code status.
     *
     * @return void
     */
    public function test_compare_hash_and_they_are_not_equal()
    {
        $hash = hash_file( "sha256", Storage::path('gtfs/zip/gtfs-smtc.zip'));
        $apiHash = "333333";

        $apiHash === $hash ? $code = 60 : $code = 61;

        $this->assertIsString($apiHash);
        $this->assertIsString($hash);
        $this->assertIsNumeric($code);
        $this->assertEquals(61, $code);
    }

}
