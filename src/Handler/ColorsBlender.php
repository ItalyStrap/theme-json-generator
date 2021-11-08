<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

final class ColorsBlender {

	public function __construct( ColorValue ...$colors ) {
		$this->colors = $colors;
	}

	public function mixBy( string $black_or_white_or_gray, float $weight = 0 ): ColorValue {

		$rgb = new ColorValue( $black_or_white_or_gray );
		$rgb2 = $this->colors[0];

		$result = $this->mixRgb(
			$rgb->toRgb(),
			$rgb2->toRgb(),
			$weight > 1 ? $weight / 100 : $weight
		);

		return new ColorValue( \sprintf(
			'rgb(%s)',
			\implode(',', $result)
		) );
	}

	private function mixRgb( ColorValue $color_1, ColorValue $color_2, float $weight = 0.5): array {
		$f = fn( int $x ): float => $weight * $x;

		$g = fn ( int $x ): float => ( 1 - $weight ) * $x;

		$h = fn ( float $x, float $y ): float => \round( $x + $y );

		return \array_map(
			$h,
			\array_map( $f, (array) $color_1->toArray() ),
			\array_map( $g, (array) $color_2->toArray() )
		);
	}

	/**
	 * @author https://gist.github.com/andrewrcollins/4570993
	 * @param int[] $color_1
	 * @param int[] $color_2
	 * @param float $weight
	 * @return array
	 */
//	private function mixRgb( array $color_1 = [0, 0, 0], array $color_2 = [0, 0, 0], float $weight = 0.5 ): array {
//		$f = fn( int $x ): float => $weight * $x;
//
//		$g = fn ( int $x ): float => ( 1 - $weight ) * $x;
//
//		$h = fn ( float $x, float $y ): float => \round( $x + $y );
//
//		return \array_map( $h, \array_map( $f, $color_1 ), \array_map( $g, $color_2 ) );
//	}
}
