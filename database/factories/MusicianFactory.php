<?php

namespace Database\Factories;

use Faker\Factory;

class MusicianFactory extends Factory
{
    private const VALID_INSTRUMENTS = ['vocals', 'guitar', 'bass', 'keys', 'other'];

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'instruments' => $this->faker->randomElements(self::VALID_INSTRUMENTS, random_int(1, 3)),
            'other' => null,
            'is_active' => true
        ];
    }
}
