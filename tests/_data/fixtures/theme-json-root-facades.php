<?php

declare(strict_types=1);

require dirname(__DIR__, 3) . '/vendor/autoload.php';

use ItalyStrap\Config\Config;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

$config = new Config();
$themeJson = new ThemeJson($config, new Presets());

$themeJson
    ->schema('https://schemas.wp.org/trunk/theme.json')
    ->version(3)
    ->title('Moduli')
    ->slug('moduli')
    ->description('Theme metadata');

$themeJson->blockTypes()->add('core/paragraph');
$themeJson->blockTypes()->add('core/heading');
$themeJson->customTemplates()->addTemplate('landing', 'Landing');
$themeJson->templateParts()->addPart('header', 'header', 'Header');
$themeJson->patterns()->add('moduli/hero');

$themeJson->settings()->layout()->contentSize('960px');
$themeJson->settings()->typography()->enableFontStyle();
$themeJson->set('settings.custom.brand.primary', '#111111');

//$themeJson->styles()->background()->backgroundImage('url(hero.jpg)');
$themeJson->styles()->border()->color('red');
$themeJson->styles()->color()->text('#111111');
$themeJson->styles()->typography()->lineHeight('1.5');
$themeJson->styles()->blocks('core/button')->variations('outline')->color()->text('#333333');

echo json_encode($config, JSON_THROW_ON_ERROR);
