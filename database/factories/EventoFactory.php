<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Evento;
use App\Models\Persona;

class EventoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Evento::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4),
            'allDay' => $this->faker->boolean,
            'start' => $this->faker->dateTime(),
            'end' => $this->faker->dateTime(),
            'tipo' => $this->faker->word,
            'persona_id' => Persona::factory(),
        ];
    }
}
