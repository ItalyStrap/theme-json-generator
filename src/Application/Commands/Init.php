<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

class Init
{
    public function __construct()
    {
    }

    public function process(): void
    {
        // First check if the theme.json file exists in the root of the project
//      if (!\file_exists( $this->path . '/theme.json')) {
//          var_dump('File not exists');
//      }

        // If the file exists, check if it is writable
//      if (!\is_writable( $this->path . '/theme.json')) {
//          var_dump('File not writable');
//      }
    }
}
