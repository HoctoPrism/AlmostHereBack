<?php

namespace Tests\Feature;

use Tests\TestCase;

class ItinaryTest extends TestCase
{

    /**
     *
     * Récupère tous les favoris
     *
     * @return void
     */
    public function test_get_an_itinary()
    {
        $response = $this->get('api/itinary/45.771580/3.059499/45.775961/3.086793');
        $response->assertStatus(200);
        $response->assertDontSee(null);
    }

}
