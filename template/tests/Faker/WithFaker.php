<?php

declare(strict_types=1);

namespace Tests\Faker;

use Faker\Generator;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use Webmozart\Assert\Assert;

trait WithFaker
{
    protected const DEFAULT_LOCAL = 'nl_NL';

    private static ?Generator $staticFaker = null;

    protected ?Generator $faker = null;

    /**
     * Setup up the Faker instance.
     */
    protected function setUpWithFaker(): void
    {
        $locale = config()->get('app.faker_locale', self::DEFAULT_LOCAL);

        Assert::string($locale);

        if ($locale === self::DEFAULT_LOCAL && isset($this->faker)) {
            return;
        }

        if ($locale === self::DEFAULT_LOCAL && !is_null(self::$staticFaker)) {
            $this->faker = self::$staticFaker;

            return;
        }

        $this->faker = FakerFactory::create($locale);
    }

    public static function getFaker(): Generator
    {
        if (!is_null(self::$staticFaker)) {
            return self::$staticFaker;
        }

        return self::$staticFaker = FakerFactory::create(self::DEFAULT_LOCAL);
    }

    #[Before()]
    protected function setupFakerForUnitTestCase(): void
    {
        $this->faker = is_null(self::$staticFaker)
            ? self::getFaker()
            : self::$staticFaker;
    }

    #[After()]
    protected function teardownFakerForUnitTestCase(): void
    {
        $this->faker = null;
        self::$staticFaker = null;
    }
}
