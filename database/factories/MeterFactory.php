<?php

namespace Database\Factories;

use App\Models\Meter;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeterFactory extends Factory
{
    protected $model = Meter::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'type' => $this->faker->word,
            'timezone' => $this->faker->timezone,
            'current_reading' => $this->faker->randomFloat(2, 0, 1000),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
