<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Handler\ColorAdjust;
use ItalyStrap\ThemeJsonGenerator\Handler\ColorsBlender;
use ItalyStrap\ThemeJsonGenerator\Handler\ComplementaryScheme;
use ItalyStrap\ThemeJsonGenerator\Handler\ColorValue;
use Mexitek\PHPColors\Color as PHPColor;
use Spatie\Color\Factory as ColorFactory;

final class ColorDataType {

	private ColorValue $color_value;

	private PHPColor $m_color;

	private ColorAdjust $color_adjust;

	/**
	 * @throws \Exception
	 */
	public function __construct( string $color ) {
		$this->color_value = new ColorValue( $color );
		$this->m_color = new PHPColor( (string) $this->color_value->toHex() );
		$this->color_adjust = new ColorAdjust( $this->color_value );
	}

	public function red() {
		return $this->color_value->red();
	}

	public function green() {
		return $this->color_value->green();
	}

	public function blue() {
		return $this->color_value->blue();
	}

	public function isDark(): bool {
		return $this->color_value->isDark();
	}

	public function isLight(): bool {
		return $this->color_value->isLight();
	}

	public function toHex(): string {
		return (string) $this->color_value->toHex();
	}

	public function toHsl(): string {
		return (string) $this->color_value->toHsl();
	}

	public function toHsla( float $alpha = 1 ): string {
		return (string) $this->color_value->toHsla( $alpha );
	}

	public function toRgb(): string {
		return (string) $this->color_value->toRgb();
	}

	public function toRgba( float $alpha = 1 ): string {
		return (string) $this->color_value->toRgba( $alpha );
	}

	public function complementary() {
		$schemes = new ComplementaryScheme( $this->color_value );
		$colors = [];
		foreach ( $schemes->generate() as $color ) {
			$colors[] = new self( (string) $color );
		}

		return $colors[1];
	}

	public function darken( int $amount = PHPColor::DEFAULT_ADJUST ): self {
		return new self( (string) $this->color_adjust->darken( $amount )->toHex() );
	}

	public function lighten( int $amount = PHPColor::DEFAULT_ADJUST ): self {
		return new self( (string) $this->color_adjust->lighten( $amount )->toHex() );
	}

	public function tint( float $weight = 0 ): self {
		return new self( (string) $this->color_adjust->tint( $weight )->toHex() );
	}

	public function shade( float $weight = 0 ): self {
		return new self( (string) $this->color_adjust->shade( $weight )->toHex() );
	}

	public function tone( float $weight = 0 ): self {
		return new self( (string) $this->color_adjust->tone( $weight )->toHex() );
	}

	public function mix( string $mixedWithThisColor, float $weight = 0 ): self {
		$rgb2 = ColorFactory::fromString( $mixedWithThisColor )->toRgb() ;
//		$rgb2 = new self( $mixedWithThisColor ) ;

		$result = $this->mixRgb(
			[
				$rgb2->red(),
				$rgb2->green(),
				$rgb2->blue(),
			],
			$this->color_value->toRgb()->toArray(),
			$weight > 1 ? $weight / 100 : $weight
		);

		return new self( \sprintf(
			'rgb(%s)',
			\implode(',', $result)
		) );
	}

	/**
	 * @author https://gist.github.com/andrewrcollins/4570993
	 * @param int[] $color_1
	 * @param int[] $color_2
	 * @param float $weight
	 * @return array
	 */
	private function mixRgb( array $color_1 = [0, 0, 0], array $color_2 = [0, 0, 0], float $weight = 0.5): array {
		$f = fn( int $x ): float => $weight * $x;

		$g = fn ( int $x ): float => ( 1 - $weight ) * $x;

		$h = fn ( float $x, float $y ): float => \round( $x + $y );

		return \array_map( $h, \array_map( $f, $color_1 ), \array_map( $g, $color_2 ) );
	}

	public function toArray(): array {
		return [
			$this->red(),
			$this->green(),
			$this->blue(),
		];
	}

	/**
	 * Calculate the relative luminance of an RGB color.
	 *
	 * @author https://gist.github.com/sebdesign/a65cc39e3bcd81201609e6a8087a83b3
	 *
	 * @return float
	 */
	public function luminance(): float {
		return $this->color_value->luminance();
	}

	/**
	 * Calculate the relative luminance of two colors.
	 *
	 * @param string $color hex color
	 * @return float
	 * @throws \Exception
	 */
	public function relativeLuminance( ColorDataType $color ): float {

		$colors = [
			$this->luminance(),
			$color->luminance(),
		];

		\sort( $colors );

		return ( $colors[1] + 0.05 ) / ( $colors[0] + 0.05 );
	}
}
