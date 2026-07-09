<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('##########'),
            'status' => fake()->randomElement([
                'New',
                'Contacted',
                'Qualified',
                'Won',
                'Lost',
            ]),
            'source' => fake()->randomElement([
                'Website',
                'Facebook',
                'Instagram',
                'Referral',
                'Email',
                'Phone',
                'Walk-in',
            ]),
            'tenant_id' => Tenant::factory(),
            'is_converted' => false,
            'created_by' => User::factory(),
        ];
    }
}
