<?php

namespace Tests\Feature;

use Tests\TestCase;

class FavoritesTest extends TestCase
{

    /**
     *
     * Récupère tous les favoris
     *
     * @return void
     */
    public function test_get_all_favorites()
    {
        $response = $this->get('/api/favorites');

        $response->assertStatus(200);

        $response->assertSee('data');
    }

}
