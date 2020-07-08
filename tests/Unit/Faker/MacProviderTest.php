<?php

namespace Tests\Unit\Faker;
use Faker\Factory;
use App\Faker\MacProvider;
use PHPUnit\Framework\TestCase;

class MacProviderTest extends TestCase
{
    public function testModelIdentifier()
    {
        $faker = Factory::create();
        $faker->addProvider(new MacProvider());

        $this->assertIsString('string', $faker->macModelIdentifier);
    }

    public function testModelName()
    {
        $faker = Factory::create();
        $faker->addProvider(new MacProvider());

        $this->assertIsString('string', $faker->macModelName);
    }
}
