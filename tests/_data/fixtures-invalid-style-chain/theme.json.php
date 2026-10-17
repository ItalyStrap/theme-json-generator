<?php

declare(strict_types=1);

use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson): void {
    $themeJson->styles()->elements('button')->elements('button');
};
