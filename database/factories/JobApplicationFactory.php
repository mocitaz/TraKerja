<?php

namespace Database\Factories;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CustomPlatform;
use App\Models\CustomStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $platform = CustomPlatform::factory()->create();
        $status = CustomStatus::factory()->create();
        $platforms = ['LinkedIn', 'JobStreet', 'Kalibrr', 'Glints', 'Indeed', 'Glassdoor', 'Other'];
        $statuses = ['On Process', 'Rejected', 'Accepted', 'Withdrawn'];
        $stages = ['Applied', 'Phone Screening', 'Technical Interview', 'HR Interview', 'Final Interview', 'Offer'];
        $careerLevels = ['Full Time', 'Part Time', 'Contract', 'Internship', 'Freelance'];
        $companies = ['Google', 'Microsoft', 'Amazon', 'Meta', 'Apple', 'Netflix', 'Spotify', 'Uber', 'Airbnb', 'Tesla'];

        return [
            'user_id' => User::factory(),
            'company_name' => $this->faker->randomElement($companies),
            'position' => $this->faker->jobTitle(),
            'location' => $this->faker->city() . ', ' . $this->faker->state(),
            'platform_id' => $platform->id,
            'status_id' => $status->id,
            'platform' => $this->faker->randomElement($platforms),
            'status' => $this->faker->randomElement(['Applied', 'Interview', 'Rejected', 'Accepted']),
            'application_status' => $this->faker->randomElement($statuses),
            'recruitment_stage' => $this->faker->randomElement($stages),
            'career_level' => $this->faker->randomElement($careerLevels),
            'platform_link' => $this->faker->url(),
            'application_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'notes' => $this->faker->optional(0.7)->paragraph(),
        ];
    }
}
