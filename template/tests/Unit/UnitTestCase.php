<?php

declare(strict_types=1);

namespace Tests\Unit;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use Tests\Faker\WithFaker;

abstract class UnitTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;
    use MatchesSnapshots;
    use WithFaker;

    protected function assertSnapshotShouldBeCreated(string $snapshotFileName): void
    {
        if ($this->shouldCreateSnapshots()) {
            return;
        }

        static::fail(
            "Snapshot \"$snapshotFileName\" does not exist.\n" .
            "You can automatically create it by running \"composer update-test-snapshots\".\n" .
            'Make sure to inspect the created snapshot afterwards to ensure its correctness!',
        );
    }
}
