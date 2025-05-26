<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserRole>
 */
class UserRoleFactory extends Factory
{
    protected $model = \App\Models\UserRole::class;

    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle(),  // or any role name you want
        ];
    }
}
