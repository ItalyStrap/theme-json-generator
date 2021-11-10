<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

final class AchromaticSchemeInterface implements SchemeInterface {


	public function generate(): iterable {
		foreach ( ['#000000', '#ffffff'] as $color ) {
			yield new ColorValue( $color );
		}
	}
}
