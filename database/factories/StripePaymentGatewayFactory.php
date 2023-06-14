<?php

namespace Database\Factories;

use App\Models\StripePaymentGateway;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\stripe_payment_gateways>
 */
class StripePaymentGatewayFactory extends Factory
{
//    protected $faker;
//    public function __construct()
//    {
//        $this->faker=new Faker();
//    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'secret'=>$this->faker->text($maxNbChars = 50),
            'key'=>$this->faker->text($maxNbChars = 50),
            'cc_enabled'=>$this->faker->boolean(),
            'ach_enabled'=>$this->faker->boolean()
        ];
    }
}
