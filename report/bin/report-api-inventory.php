#!/usr/bin/env php
<?php

declare(strict_types=1);

use ItalyStrap\ThemeJsonGenerator\Report\ThemeSchemaApiInventoryGenerator;

require $_composer_autoload_path ?? __DIR__ . '/../../vendor/autoload.php';

$options = \getopt('', ['schema::', 'output-dir::', 'assert-clean']);
$schemaOption = $options['schema'] ?? (__DIR__ . '/../../theme.schema.json');
$outputOption = $options['output-dir'] ?? (__DIR__ . '/..');
$assertClean = \array_key_exists('assert-clean', $options);

$schemaPath = \is_string($schemaOption) ? $schemaOption : (__DIR__ . '/../../theme.schema.json');
$outputDirectory = \is_string($outputOption) ? $outputOption : (__DIR__ . '/..');

$generator = new ThemeSchemaApiInventoryGenerator();
$result = $generator->generate($schemaPath, $outputDirectory);

\fwrite(\STDOUT, \sprintf(
    "Generated %d inventory rows.\nMarkdown: %s\nCSV: %s\n",
    $result['rows'],
    $result['markdown_path'],
    $result['csv_path']
));

if (
    $assertClean
    && ($result['summary']['missing_rows'] > 0 || $result['summary']['stale_attributes'] > 0)
) {
    \fwrite(\STDERR, \sprintf(
        "Schema API coverage is not clean: %d missing rows, %d stale attributes.\n",
        $result['summary']['missing_rows'],
        $result['summary']['stale_attributes']
    ));
    exit(1);
}
