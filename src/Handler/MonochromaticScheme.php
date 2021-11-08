<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

final class MonochromaticScheme {

	private ColorAdjust $color;

	private array $step;

	public function __construct( ColorAdjust $color, array $step ) {
		$this->color = $color;
		$this->step = $step;
	}

	/**
	 * @param array<int, int> $step
	 * @return iterable<int, ColorValue>
	 */
	public function generate(): iterable {

		\arsort( $this->step );
		foreach ( $this->step as $weight ) {
			yield $this->color->tint( $weight );
		}

		yield $this->color;

		\asort( $this->step );
		foreach ( $this->step as $weight ) {
			yield $this->color->shade( $weight );
		}
	}
}
