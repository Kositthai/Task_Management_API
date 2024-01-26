<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => $this->faker->sentence(),
            "description"=> $this->faker->paragraph(),
            "due_date"=> today(),
            "priority"=> $this->faker->numberBetween("1", "5"),
            "reporter_id" => User::inRandomOrder()->first()->id,
        ];
    }
}
