<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Infrastructure\Filesystem;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\ScssFileWriter;

class SassFileWriterTest extends UnitTestCase
{
    /**
     * @var string
     */
    private string $theme_sass_path;

    protected function makeInstance(): ScssFileWriter
    {
        $this->theme_sass_path = \codecept_output_dir('theme.scss');
        return new ScssFileWriter($this->theme_sass_path);
    }

    public function testItShouldCreateScssFile(): void
    {

        $sut = $this->makeInstance();
        $data = new Config(
            [
                'settings'  => [
                    'custom'    => [
                        'alignment--center' => 'center',
                    ],
                    'color' => [
                        'palette'   => [
                        ],
                    ],
                ]
            ]
        );

        $sut->write($data);

        $this->assertFileExists($this->theme_sass_path, '');
        $this->assertFileIsReadable($this->theme_sass_path, '');
        $this->assertFileIsWritable($this->theme_sass_path, '');

        \unlink($this->theme_sass_path);
    }

    public static function presetSettingsAndCustomProvider(): \Generator
    {
        yield 'Color palette' => [
            [
                'color' => [
                    'palette' => [
                        [
                            "slug" => "primary",
                        ]
                    ],
                ],
            ],
            '$wp--preset--color--primary',
            '--wp--preset--color--primary',
        ];

        yield 'Color gradients' => [
            [
                'color' => [
                    'gradients' => [
                        [
                            "slug" => "blush-light-purple",
                        ],
                    ],
                ],
            ],
            '$wp--preset--gradient--blush-light-purple',
            '--wp--preset--gradient--blush-light-purple',
        ];

        yield 'Typography fontSizes' => [
            [
                'typography' => [
                    'fontSizes' => [
                        [
                            "slug" => "normal",
                        ],
                    ],
                ],
            ],
            '$wp--preset--font-size--normal',
            '--wp--preset--font-size--normal',
        ];

        yield 'Typography fontFamilies' => [
            [
                'typography' => [
                    'fontFamilies' => [
                        [
                            'slug' => 'system-font',
                        ],
                    ],
                ],
            ],
            '$wp--preset--font-family--system-font',
            '--wp--preset--font-family--system-font',
        ];

        yield 'Custom' => [
            [
                'custom' => [
                    'alignmentCenter'   => 'center',
                ],
            ],
            '$wp--custom--alignment-center',
            '--wp--custom--alignment-center',
        ];

        yield 'Custom with child' => [
            [
                'custom' => [
                    'alignment' => [
                        'center'    => 'center',
                    ],
                ],
            ],
            '$wp--custom--alignment--center',
            '--wp--custom--alignment--center',
        ];
    }

    /**
     * @dataProvider presetSettingsAndCustomProvider
     * @return never
     * @throws \Exception
     */
    public function testItShouldIteratePresetAndCustomSettingsFor(
        array $data,
        string $expected_slug,
        string $expected_css_variable
    ): void {
        $sut = $this->makeInstance();

        $sut->write(new Config(['settings' => $data]));
        $this->assertFileExists($this->theme_sass_path, '');

        $this->assertStringEqualsFile(
            $this->theme_sass_path,
            \sprintf(
                '%s: %s;' . PHP_EOL,
                $expected_slug,
                $expected_css_variable
            ),
            ''
        );

        \unlink($this->theme_sass_path);
    }

    public function testItShouldThrowError(): void
    {
        $sut = $this->makeInstance();

        $custom = [
            'alignment' => [
                'global' => [
                    'left'
                ],
            ]
        ];

        $this->expectException('\Throwable');
        $this->expectException('\RuntimeException');

        $sut->write(new Config(['settings' => [ 'custom' => $custom ]]));

        \unlink($this->theme_sass_path);
    }
}
