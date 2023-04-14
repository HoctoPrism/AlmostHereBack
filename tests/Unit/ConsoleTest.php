<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    /**
     * Compare hashes and define code status.
     *
     * @return void
     */
    public function test_compare_hash_and_they_are_equal()
    {
        $apiHash = '123456789';
        $hash = '123456789';

        $code = 0;

        if ($apiHash === $hash){
            $code = 60;
        }

        $this->assertIsString($apiHash);
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
        $apiHash = '123456789';
        $hash = '1234567890';
        $code = 0;
        if ($apiHash === $hash){
            $code = 60;
        } else {
            $code = 61;
        }
        $this->assertIsString($apiHash);
        $this->assertIsString($hash);
        $this->assertIsNumeric($code);
        $this->assertEquals(61, $code);
    }

}
