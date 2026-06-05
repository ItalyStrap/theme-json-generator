<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Schema;

enum ThemeSchemaCoverageStatus: string
{
    case IMPLEMENTED = 'implemented';

    case PARTIAL = 'partial';

    case ESCAPE_HATCH = 'escape_hatch';
}
