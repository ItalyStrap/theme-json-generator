<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Factory;

use ItalyStrap\ThemeJsonGenerator\Styles\Typography as BaseTypo;

/**
 * @psalm-api
 */
final class Typography
{
    public static function make(): BaseTypo
    {
        return new BaseTypo();
    }

//  public static function fontFamily( string $value ): BaseTypo {
//      return self::make()->fontFamily( $value );
//  }
//
//  public static function fontSize( string $value ): BaseTypo {
//      return self::make()->fontSize( $value );
//  }
//
//  public static function fontStyle( string $value ): BaseTypo {
//      return self::make()->fontStyle( $value );
//  }
//
//  public static function fontWeight( string $value ): BaseTypo {
//      return self::make()->fontWeight( $value );
//  }
//
//  public static function lineHeight( string $value ): BaseTypo {
//      return self::make()->lineHeight( $value );
//  }
//
//  public static function textDecoration( string $value ): BaseTypo {
//      return self::make()->textDecoration( $value );
//  }
//
//  public static function textTransform( string $value ): BaseTypo {
//      return self::make()->textTransform( $value );
//  }
}
