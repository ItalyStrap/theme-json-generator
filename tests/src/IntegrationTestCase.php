<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\TestCase\WPTestCase;

class IntegrationTestCase extends WPTestCase
{
    protected \IntegrationTester $tester;

    protected function setUp(): void
    {
        // Before...
        parent::setUp();

        // Your set up methods here.
    }

    protected function tearDown(): void
    {
        // Your tear down methods here.

        // Then...
        parent::tearDown();
    }
}
