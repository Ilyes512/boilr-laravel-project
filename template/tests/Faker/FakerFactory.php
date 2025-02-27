<?php

declare(strict_types=1);

namespace Tests\Faker;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Base;

class FakerFactory
{
    /**
     * Add custom providers to the Faker instance.
     *
     * @var list<class-string<Base>>
     **/
    protected static array $providers = [];

    public static function addProviders(Generator $faker): Generator
    {
        foreach (self::$providers as $provider) {
            $faker->addProvider(new $provider($faker));
        }

        return $faker;
    }

    public static function create(string $locale = 'nl_NL'): Generator
    {
        return self::addProviders(Factory::create($locale));
    }
}
