<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CustomStatus;

class CustomStatusFactory extends Factory
{
    protected $model = CustomStatus::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
