<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use Mexitek\PHPColors\Color as PHPColor;
use Spatie\Color\Color;
use Spatie\Color\Factory as ColorFactory;

final class ColorDataType {


	private Color $s_color;

	private PHPColor $m_color;

	/**
	 * @throws \Exception
	 */
	public function __construct( string $color ) {
		$this->s_color = ColorFactory::fromString($color);
		$this->m_color = new PHPColor( (string) $this->s_color->toHex() );
	}

	public function isDark(): bool {
		return $this->m_color->isDark();
	}

	public function isLight(): bool {
		return $this->m_color->isLight();
	}

	public function toHex(): string {
		return (string) $this->s_color->toHex();
	}

	public function toHsl(): string {
		return (string) $this->s_color->toHsl();
	}

	public function toHsla( float $alpha = 1 ): string {
		return (string) $this->s_color->toHsla( $alpha );
	}

	public function toRgb(): string {
		return (string) $this->s_color->toRgb();
	}

	public function toRgba( float $alpha = 1 ): string {
		return (string) $this->s_color->toRgba( $alpha );
	}

	public function darken( int $amount = PHPColor::DEFAULT_ADJUST ): self {
		return new self( '#' . $this->m_color->darken( $amount ) );
	}

	public function lighten( int $amount = PHPColor::DEFAULT_ADJUST ): self {
		return new self( '#' . $this->m_color->lighten( $amount ) );
	}

	public function tint( float $weight = 0 ): self {
		return $this->mix( '#ffffff', $weight );
	}

	public function shade( float $weight = 0 ): self {
		return $this->mix( '#000000', $weight );
	}

	public function tone( float $weight = 0 ): self {
		return $this->mix( '#808080', $weight );
	}

	public function mix( string $mixedWithThisColor, float $weight = 0 ): self {

		$rgb = $this->s_color->toRgb();
		$rgb2 = ColorFactory::fromString( $mixedWithThisColor )->toRgb() ;

		$result = $this->mixRgb(
			[
				$rgb2->red(),
				$rgb2->green(),
				$rgb2->blue(),
			],
			[
				$rgb->red(),
				$rgb->green(),
				$rgb->blue(),
			],
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

	public function complementary(): self {
		return new self( '#' . $this->m_color->complementary() );
	}
}
