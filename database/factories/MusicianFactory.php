<?php

namespace Database\Factories;

use App\Models\Musician;
use Illuminate\Database\Eloquent\Factories\Factory;

class MusicianFactory extends Factory
{
    /**
     * The name of the model associated with this factory.
     *
     * @var string
     */
    protected $model = Musician::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // List of possible instruments for our musicians
        $possibleInstruments = ['guitar', 'bass', 'drums', 'keys', 'other'];

        // Randomly select 1-3 instruments for this musician
        $instruments = $this->faker->randomElements(
            $possibleInstruments,
            $this->faker->numberBetween(1, 3)
        );

        return [
            'name' => $this->faker->name(),
            'instruments' => $instruments,
            'other' => $this->faker->optional()->sentence(),
            'vocalist' => $this->faker->boolean(30), // 30% chance of being a vocalist
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
