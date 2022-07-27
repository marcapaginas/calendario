<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Persona;

class PersonaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Persona::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->word,
            'color' => $this->faker->word,
            'diasAsuntos' => $this->faker->word,
            'diasVacaciones' => $this->faker->word,
            'diasAcumulados' => $this->faker->word,
            'diasExtra' => $this->faker->word,
            'activo' => $this->faker->word,
        ];
    }
}
