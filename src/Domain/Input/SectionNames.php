<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input;

/**
 * @psalm-api
 */
final class SectionNames
{
    /**
     * @var string
     */
    public const SCHEMA = '$schema';

    /**
     * @var string
     */
    public const VERSION = 'version';

    /**
     * @var string
     */
    public const TITLE = 'title';

    /**
     * @var string
     */
    public const DESCRIPTION = 'description';

    /**
     * @var string
     */
    public const SETTINGS = 'settings';

    /**
     * @var string
     */
    public const STYLES = 'styles';

    /**
     * @var string
     */
    public const CUSTOM_TEMPLATES = 'customTemplates';

    /**
     * @var string
     */
    public const TEMPLATE_PARTS = 'templateParts';

    /**
     * @var string
     */
    public const PATTERNS = 'patterns';
}
