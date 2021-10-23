<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Factory;

use ItalyStrap\ThemeJsonGenerator\Styles\Spacing as BaseSpacing;

final class Spacing {

	public static function make(): BaseSpacing {
		return new BaseSpacing();
	}

	/**
	 * One value => 0px => 0px 0px 0px 0px
	 * Two values => 5px 0px => 5px 0px 5px 0px
	 * Three values => 10px auto 0px => 10px auto 0px auto
	 * For values => 1px 2px 3px 4px => 1px 2px 3px 4px
	 * @param array $values
	 * @return array
	 */
	public static function shorthand( array $values ): BaseSpacing {
		$map = [
			'top'		=> '',
			'right'		=> '',
			'bottom'	=> '',
			'left'		=> '',
		];

		$obj = self::make();

		switch ( \count( $values ) ) {
			case 1:
				$map['top'] = $values[0];
				$map['right'] = $values[0];
				$map['bottom'] = $values[0];
				$map['left'] = $values[0];
				break;
			case 2:
				$map['top'] = $values[0];
				$map['right'] = $values[1];
				$map['bottom'] = $values[0];
				$map['left'] = $values[1];
				break;
			case 3:
				$map['top'] = $values[0];
				$map['right'] = $values[1];
				$map['bottom'] = $values[2];
				$map['left'] = $values[1];
				break;
			case 4:
				$map['top'] = $values[0];
				$map['right'] = $values[1];
				$map['bottom'] = $values[2];
				$map['left'] = $values[3];
				break;
			default:
				break;
		}

		foreach ( \array_filter( $map ) as $method => $value ) {
			\call_user_func( [ $obj, $method ], $value );
		}

		return $obj;
	}

	public static function top( string $value ): BaseSpacing {
		return self::shorthand( [$value, '', '', ''] );
	}

	public static function right( string $value ): BaseSpacing {
		return self::shorthand( ['', $value, '', ''] );
	}

	public static function bottom( string $value ): BaseSpacing {
		return self::shorthand( ['', '', $value, ''] );
	}

	public static function left( string $value ): BaseSpacing {
		return self::shorthand( ['', '', '', $value] );
	}

	public static function vertical( string $value ): BaseSpacing {
		return self::shorthand( [$value, '', $value, ''] );
	}

	public static function verticalAsync( string $top, string $bottom ): BaseSpacing {
		return self::shorthand( [$top, '', $bottom, ''] );
	}

	public static function horizontal( string $value ): BaseSpacing {
		return self::shorthand( ['', $value, '', $value] );
	}
}
