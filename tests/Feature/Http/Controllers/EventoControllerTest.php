<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Evento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\EventoController
 */
class EventoControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $eventos = Evento::factory()->count(3)->create();

        $response = $this->get(route('evento.index'));
    }


    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $evento = Evento::factory()->create();

        $response = $this->put(route('evento.update', $evento));

        $evento->refresh();
    }


    /**
     * @test
     */
    public function store_saves()
    {
        $response = $this->post(route('evento.store'));

        $this->assertDatabaseHas(eventos, [ /* ... */ ]);
    }
}
