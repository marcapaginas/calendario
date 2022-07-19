<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PersonaController
 */
class PersonaControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $personas = Persona::factory()->count(3)->create();

        $response = $this->get(route('persona.index'));
    }


    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $persona = Persona::factory()->create();

        $response = $this->put(route('persona.update', $persona));

        $persona->refresh();
    }


    /**
     * @test
     */
    public function store_saves()
    {
        $response = $this->post(route('persona.store'));

        $this->assertDatabaseHas(personas, [ /* ... */ ]);
    }
}
