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

	public function mix( string $hex2, int $amount = 0 ): self {
		return new self( '#' . $this->m_color->mix( $hex2, $amount ) );
	}

	public function complementary(): self {
		return new self( '#' . $this->m_color->complementary() );
	}
}
