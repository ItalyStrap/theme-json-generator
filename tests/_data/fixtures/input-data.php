<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;

return [
    SectionNames::VERSION => 1,
    SectionNames::TEMPLATE_PARTS => [
        [
            'name' => 'header',
            'area' => 'header',
        ],
        [
            'name' => 'footer',
            'area' => 'footer',
        ],
    ],
    SectionNames::CUSTOM_TEMPLATES => [
        [
            'name' => 'blank',
            'title' => 'Blank',
            'postTypes' => [
                'page',
                'post',
            ],
        ],
        [
            'name' => 'header-footer-only',
            'title' => 'Header and Footer Only',
            'postTypes' => [
                'page',
                'post',
            ],
        ],
    ],
    SectionNames::SETTINGS => [
        'border' => [
            'customColor' => true,
            'customRadius' => true,
            'customStyle' => true,
            'customWidth' => true,
        ],
        'color' => [
            'gradients' => [
            ],
            'palette' => [
                [
                    'slug' => 'primary',
                    'color' => '#007cba',
                    'name' => 'Primary',
                ],
                [
                    'slug' => 'secondary',
                    'color' => '#006ba1',
                    'name' => 'Secondary',
                ],
                [
                    'slug' => 'foreground',
                    'color' => '#333333',
                    'name' => 'Foreground',
                ],
                [
                    'slug' => 'background',
                    'color' => '#ffffff',
                    'name' => 'Background',
                ],
                [
                    'slug' => 'selection',
                    'color' => '#c2c2c2',
                    'name' => 'Selection',
                ],
            ],
        ],
        'custom' => [
            'alignment' => [
                'center' => 'center',
                'alignedMaxWidth' => '50%',
            ],
            'button' => [
                'border' => [
                    'color' => 'var(--wp--custom--color--primary)',
                    'radius' => '4px',
                    'style' => 'solid',
                    'width' => '2px',
                ],
                'color' => [
                    'background' => 'var(--wp--custom--color--primary)',
                    'text' => 'var(--wp--custom--color--background)',
                ],
                'hover' => [
                    'color' => [
                        'text' => 'var(--wp--custom--color--background)',
                        'background' => 'var(--wp--custom--color--secondary)',
                    ],
                    'border' => [
                        'color' => 'var(--wp--custom--color--secondary)',
                    ],
                ],
                'spacing' => [
                    'padding' => [
                        'top' => '0.667em',
                        'bottom' => '0.667em',
                        'left' => '1.333em',
                        'right' => '1.333em',
                    ],
                ],
                'typography' => [
                    'fontFamily' => 'var(--wp--custom--body--typography--font-family)',
                    'fontSize' => 'var(--wp--preset--font-size--normal)',
                    'fontWeight' => 'normal',
                    'lineHeight' => 2,
                ],
            ],
            'code' => [
                'typography' => [
                    'fontFamily' => 'monospace',
                ],
            ],
            'color' => [
                'foreground' => 'var(--wp--preset--color--foreground)',
                'background' => 'var(--wp--preset--color--background)',
                'primary' => 'var(--wp--preset--color--primary)',
                'secondary' => 'var(--wp--preset--color--secondary)',
                'selection' => 'var(--wp--preset--color--selection)',
            ],
            'colorPalettes' => [
                [
                    'label' => 'Featured',
                    'slug' => 'palette-1',
                    'colors' => [
                        'primary' => '#C8133E',
                        'secondary' => '#4E2F4B',
                        'foreground' => '#1D1E1E',
                        'background' => '#FFFFFF',
                        'selection' => '#F9F9F9',
                    ],
                ],
                [
                    'label' => 'Featured',
                    'slug' => 'palette-2',
                    'colors' => [
                        'primary' => '#35845D',
                        'secondary' => '#233252',
                        'foreground' => '#242527',
                        'background' => '#EEF4F7',
                        'selection' => '#F9F9F9',
                    ],
                ],
                [
                    'label' => 'Featured',
                    'slug' => 'palette-3',
                    'colors' => [
                        'primary' => '#9FD3E8',
                        'secondary' => '#FBE6AA',
                        'foreground' => '#FFFFFF',
                        'background' => '#1F2527',
                        'selection' => '#364043',
                    ],
                ],
            ],
            'form' => [
                'padding' => 'calc( 0.5 * var(--wp--custom--margin--horizontal) )',
                'border' => [
                    'color' => '#EFEFEF',
                    'radius' => '0',
                    'style' => 'solid',
                    'width' => '2px',
                ],
                'color' => [
                    'background' => 'transparent',
                    'boxShadow' => 'none',
                    'text' => 'var(--wp--custom--color--foreground)',
                ],
                'label' => [
                    'typography' => [
                        'fontSize' => 'var(--wp--preset--font-size--tiny)',
                    ],
                ],
                'typography' => [
                    'fontSize' => 'var(--wp--preset--font-size--normal)',
                ],
            ],
            'gallery' => [
                'caption' => [
                    'fontSize' => 'var(--wp--preset--font-size--small)',
                ],
            ],
            'body' => [
                'typography' => [
                    'fontFamily' => 'var(--wp--preset--font-family--system-font)',
                    'lineHeight' => 1.6,
                ],
            ],
            'heading' => [
                'typography' => [
                    'fontFamily' => 'var(--wp--preset--font-family--system-font)',
                    'fontWeight' => 400,
                    'lineHeight' => 1.125,
                ],
            ],
            'latest-posts' => [
                'meta' => [
                    'color' => [
                        'text' => 'var(--wp--custom--color--primary)',
                    ],
                ],
            ],
            'list' => [
                'spacing' => [
                    'padding' => [
                        'left' => 'calc( 2 * var(--wp--custom--margin--horizontal) )',
                    ],
                ],
            ],
            'margin' => [
                'baseline' => '10px',
                'horizontal' => '30px',
                'vertical' => '30px',
            ],
            'paragraph' => [
                'dropcap' => [
                    'margin' => '.1em .1em 0 0',
                    'typography' => [
                        'fontFamily' => 'var(--wp--custom--body--typography--font-family)',
                        'fontSize' => '110px',
                        'fontWeight' => '400',
                    ],
                ],
            ],
            'post-author' => [
                'typography' => [
                    'fontWeight' => 'normal',
                ],
            ],
            'post-comment' => [
                'typography' => [
                    'fontSize' => 'var(--wp--preset--font-size--normal)',
                    'lineHeight' => 'var(--wp--custom--body--typography--line-height)',
                ],
            ],
            'post-content' => [
                'padding' => [
                    'left' => '20px',
                    'right' => '20px',
                ],
            ],
            'pullquote' => [
                'citation' => [
                    'typography' => [
                        'fontSize' => 'var(--wp--preset--font-size--tiny)',
                        'fontFamily' => 'inherit',
                        'fontStyle' => 'italic',
                    ],
                    'spacing' => [
                        'margin' => [
                            'top' => 'var(--wp--custom--margin--vertical)',
                        ],
                    ],
                ],
                'typography' => [
                    'textAlign' => 'left',
                ],
            ],
            'quote' => [
                'citation' => [
                    'typography' => [
                        'fontSize' => 'var(--wp--preset--font-size--tiny)',
                        'fontStyle' => 'italic',
                        'fontWeight' => '400',
                    ],
                ],
                'typography' => [
                    'textAlign' => 'left',
                ],
            ],
            'separator' => [
                'opacity' => 1,
                'margin' => 'var(--wp--custom--margin--vertical) auto',
                'width' => '150px',
            ],
            'table' => [
                'figcaption' => [
                    'typography' => [
                        'fontSize' => 'var(--wp--preset--font-size--tiny)',
                    ],
                ],
            ],
            'video' => [
                'caption' => [
                    'textAlign' => 'center',
                    'margin' => 'var(--wp--custom--margin--vertical) auto',
                ],
            ],
        ],
        'layout' => [
            'contentSize' => '620px',
            'wideSize' => '1000px',
        ],
        'spacing' => [
            'customPadding' => true,
            'units' => [
                'px',
                'em',
                'rem',
                'vh',
                'vw',
            ],
        ],
        'typography' => [
            'customFontSize' => true,
            'customLineHeight' => true,
            'fontFamilies' => [
                [
					// phpcs:ignore
					'fontFamily' => "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif",
                    'slug' => 'system-font',
                    'name' => 'System Font',
                ],
            ],
            'fontSizes' => [
                [
                    'name' => 'Tiny',
                    'size' => '14px',
                    'slug' => 'tiny',
                ],
                [
                    'name' => 'Small',
                    'size' => '16px',
                    'slug' => 'small',
                ],
                [
                    'name' => 'Normal',
                    'size' => '18px',
                    'slug' => 'normal',
                ],
                [
                    'name' => 'Large',
                    'size' => '24px',
                    'slug' => 'large',
                ],
                [
                    'name' => 'Huge',
                    'size' => '28px',
                    'slug' => 'huge',
                ],
            ],
        ],
    ],
    SectionNames::STYLES => [
        'blocks' => [
            'core/button' => [
                'border' => [
                    'radius' => 'var(--wp--custom--button--border--radius)',
                ],
                'color' => [
                    'background' => 'var(--wp--custom--button--color--background)',
                    'text' => 'var(--wp--custom--button--color--text)',
                ],
                'typography' => [
                    'fontFamily' => 'var(--wp--custom--button--typography--font-family)',
                    'fontSize' => 'var(--wp--custom--button--typography--font-size)',
                    'fontWeight' => 'var(--wp--custom--button--typography--font-weight)',
                    'lineHeight' => 'var(--wp--custom--button--typography--line-height)',
                ],
            ],
            'core/code' => [
                'spacing' => [
                    'padding' => [
                        'left' => 'var(--wp--custom--margin--horizontal)',
                        'right' => 'var(--wp--custom--margin--horizontal)',
                        'top' => 'var(--wp--custom--margin--vertical)',
                        'bottom' => 'var(--wp--custom--margin--vertical)',
                    ],
                ],
                'border' => [
                    'color' => '#CCCCCC',
                    'radius' => '0px',
                    'style' => 'solid',
                    'width' => '2px',
                ],
            ],
            'core/heading' => [
                'typography' => [
                    'fontFamily' => 'var(--wp--custom--heading--typography--font-family)',
                    'fontWeight' => 'var(--wp--custom--heading--typography--font-weight)',
                    'lineHeight' => 'var(--wp--custom--heading--typography--line-height)',
                ],
            ],
            'core/navigation' => [
                'typography' => [
                    'fontSize' => 'var(--wp--preset--font-size--normal)',
                ],
            ],
            'core/post-title' => [
                'typography' => [
                    'fontFamily' => 'var(--wp--custom--heading--typography--font-family)',
                    'fontSize' => 'var(--wp--preset--font-size--huge)',
                    'lineHeight' => 'var(--wp--custom--heading--typography--line-height)',
                ],
            ],
            'core/post-date' => [
                'color' => [
                    'link' => 'var(--wp--custom--color--foreground)',
                    'text' => 'var(--wp--custom--color--foreground)',
                ],
                'typography' => [
                    'fontSize' => 'var(--wp--preset--font-size--small)',
                ],
            ],
            'core/pullquote' => [
                'border' => [
                    'style' => 'solid',
                    'width' => '1px 0',
                ],
                'typography' => [
                    'fontStyle' => 'italic',
                    'fontSize' => 'var(--wp--preset--font-size--huge)',
                ],
                'spacing' => [
                    'padding' => [
                        'left' => 'var(--wp--custom--margin--horizontal)',
                        'right' => 'var(--wp--custom--margin--horizontal)',
                        'top' => 'var(--wp--custom--margin--horizontal)',
                        'bottom' => 'var(--wp--custom--margin--horizontal)',
                    ],
                ],
            ],
            'core/separator' => [
                'color' => [
                    'text' => 'var(--wp--custom--color--foreground)',
                ],
                'border' => [
                    'color' => 'currentColor',
                    'style' => 'solid',
                    'width' => '0 0 1px 0',
                ],
            ],
            'core/site-title' => [
                'typography' => [
                    'fontSize' => 'var(--wp--preset--font-size--normal)',
                    'fontWeight' => 700,
                ],
            ],
            'core/quote' => [
                'border' => [
                    'color' => 'var(--wp--custom--color--primary)',
                    'style' => 'solid',
                    'width' => '0 0 0 1px',
                ],
                'spacing' => [
                    'padding' => [
                        'left' => 'var(--wp--custom--margin--horizontal)',
                    ],
                ],
                'typography' => [
                    'fontSize' => 'var(--wp--preset--font-size--normal)',
                    'fontStyle' => 'normal',
                ],
            ],
        ],
        'color' => [
            'background' => 'var(--wp--custom--color--background)',
            'text' => 'var(--wp--custom--color--foreground)',
        ],
        'elements' => [
            'h1' => [
                'typography' => [
                    'fontSize' => '48px',
                ],
            ],
            'h2' => [
                'typography' => [
                    'fontSize' => '32px',
                ],
            ],
            'h3' => [
                'typography' => [
                    'fontSize' => 'var(--wp--preset--font-size--huge)',
                ],
            ],
            'h4' => [
                'typography' => [
                    'fontSize' => 'var(--wp--preset--font-size--large)',
                ],
            ],
            'h5' => [
                'typography' => [
                    'fontSize' => 'var(--wp--preset--font-size--normal)',
                ],
            ],
            'h6' => [
                'typography' => [
                    'fontSize' => 'var(--wp--preset--font-size--small)',
                ],
            ],
            'link' => [
                'color' => [
                    'text' => 'var(--wp--custom--color--primary)',
                ],
            ],
        ],
        'typography' => [
            'lineHeight' => 'var(--wp--custom--body--typography--line-height)',
            'fontFamily' => 'var(--wp--custom--body--typography--font-family)',
            'fontSize' => 'var(--wp--preset--font-size--normal)',
        ],
    ],
];
