<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CustomPlatform;

class CustomPlatformFactory extends Factory
{
    protected $model = CustomPlatform::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
