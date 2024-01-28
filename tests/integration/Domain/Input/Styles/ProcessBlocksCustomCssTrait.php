<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\Domain\Input\Styles;

/**
 * @todo Remove this trait after WP >= 6.2
 * @see \WP_Theme_JSON
 * phpcs:ignoreFile
 */
trait ProcessBlocksCustomCssTrait
{
    /**
     * Processes the CSS, to apply nesting.
     *
     * @param string $css      The CSS to process.
     * @param string $selector The selector to nest.
     * @return string The processed CSS.
     * @since 6.2.0
     * @see \WP_Theme_JSON::process_blocks_custom_css()
     */
    private function process_blocks_custom_css($css, $selector ) {
        $processed_css = '';

        // Split CSS nested rules.
        $parts = explode( '&', $css );
        foreach ( $parts as $part ) {
            $is_root_css = ( ! str_contains( $part, '{' ) );
            if ( $is_root_css ) {
                // If the part doesn't contain braces, it applies to the root level.
                $processed_css .= trim( $selector ) . '{' . trim( $part ) . '}';
            } else {
                // If the part contains braces, it's a nested CSS rule.
                $part = explode( '{', str_replace( '}', '', $part ) );
                if ( count( $part ) !== 2 ) {
                    continue;
                }
                $nested_selector = $part[0];
                $css_value       = $part[1];
                $part_selector   = str_starts_with( $nested_selector, ' ' )
                    ? static::scope_selector( $selector, $nested_selector )
                    : static::append_to_selector( $selector, $nested_selector );
                $processed_css  .= $part_selector . '{' . trim( $css_value ) . '}';
            }
        }
        return $processed_css;
    }

    /**
     * Appends a sub-selector to an existing one.
     *
     * Given the compounded $selector "h1, h2, h3"
     * and the $to_append selector ".some-class" the result will be
     * "h1.some-class, h2.some-class, h3.some-class".
     *
     * @param string $selector  Original selector.
     * @param string $to_append Selector to append.
     * @return string The new selector.
     * @since 5.8.0
     * @since 6.1.0 Added append position.
     * @since 6.3.0 Removed append position parameter.
     *
     */
    private static function append_to_selector($selector, $to_append ) {
        if ( ! str_contains( $selector, ',' ) ) {
            return $selector . $to_append;
        }
        $new_selectors = array();
        $selectors     = explode( ',', $selector );
        foreach ( $selectors as $sel ) {
            $new_selectors[] = $sel . $to_append;
        }
        return implode( ',', $new_selectors );
    }

    /**
     * Function that scopes a selector with another one. This works a bit like
     * SCSS nesting except the `&` operator isn't supported.
     *
     * <code>
     * $scope = '.a, .b .c';
     * $selector = '> .x, .y';
     * $merged = scope_selector( $scope, $selector );
     * // $merged is '.a > .x, .a .y, .b .c > .x, .b .c .y'
     * </code>
     *
     * @param string $scope    Selector to scope to.
     * @param string $selector Original selector.
     * @return string Scoped selector.
     *@since 5.9.0
     *
     */
    private static function scope_selector($scope, $selector ) {
        $scopes    = explode( ',', $scope );
        $selectors = explode( ',', $selector );

        $selectors_scoped = array();
        foreach ( $scopes as $outer ) {
            foreach ( $selectors as $inner ) {
                $outer = trim( $outer );
                $inner = trim( $inner );
                if ( ! empty( $outer ) && ! empty( $inner ) ) {
                    $selectors_scoped[] = $outer . ' ' . $inner;
                } elseif ( empty( $outer ) ) {
                    $selectors_scoped[] = $inner;
                } elseif ( empty( $inner ) ) {
                    $selectors_scoped[] = $outer;
                }
            }
        }

        $result = implode( ', ', $selectors_scoped );
        return $result;
    }
}
