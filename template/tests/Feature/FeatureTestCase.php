<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Spatie\Snapshots\MatchesSnapshots;
use Tests\Faker\WithFaker;

abstract class FeatureTestCase extends TestCase
{
    use DatabaseTransactions;
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
