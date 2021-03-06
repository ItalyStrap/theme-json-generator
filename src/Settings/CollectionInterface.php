<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

interface CollectionInterface {

	/**
	 * @return string
	 */
	public function category(): string;

	/**
	 * @param string $slug
	 * @return string
	 */
	public function propOf( string $slug ): string;

	/**
	 * @param string $slug
	 * @return string
	 */
	public function varOf( string $slug ): string;

	/**
	 * @param string $slug
	 * @return string
	 */
	public function value( string $slug ): string;

	/**
	 * @return array<int|string, mixed>
	 */
	public function toArray(): array;
}
