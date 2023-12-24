<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain;

/**
 * @psalm-api
 */
final class SectionNames
{
    public const SCHEMA = '$schema';
    public const VERSION = 'version';
    public const TITLE = 'title';
    public const SETTINGS = 'settings';
    public const STYLES = 'styles';
    public const CUSTOM_TEMPLATES = 'customTemplates';
    public const TEMPLATE_PARTS = 'templateParts';
    public const PATTERNS = 'patterns';
}
