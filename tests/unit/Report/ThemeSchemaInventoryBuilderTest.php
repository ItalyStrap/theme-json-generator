<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Report;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Report\ThemeSchemaInventoryBuilder;

final class ThemeSchemaInventoryBuilderTest extends UnitTestCase
{
    public function testItBuildsRowsFromTheThemeSchema(): void
    {
        $sut = new ThemeSchemaInventoryBuilder();

        $inventory = $sut->build(\codecept_root_dir('theme.schema.json'));
        $paths = \array_column($inventory['rows'], 'path');

        $this->assertContains('topLevel.settings', $paths);
        $this->assertContains('settings.color', $paths);
        $this->assertContains('settings.color.background', $paths);
        $this->assertContains('settings.color.heading', $paths);
        $this->assertContains('settings.useRootPaddingAwareAlignments', $paths);
        $this->assertContains('styles.css', $paths);
        $this->assertContains('styles.color.background', $paths);
        $this->assertContains('styles.elements.button', $paths);
        $this->assertContains('styles.variations', $paths);
        $this->assertContains('settings.blocks.*', $paths);
        $this->assertContains('styles.blocks.*', $paths);
        $this->assertNotContains('styles.blocks.core/paragraph', $paths);
        $this->assertNotContains('settings.extraRoots.blocks', $paths);
        $this->assertNotContains('styles.extraRoots.elements', $paths);
    }
}
