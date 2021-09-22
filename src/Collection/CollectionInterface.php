<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Collection;

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
	 * @return array[]
	 */
	public function toArray(): array;

	/**
	 * @param CollectionInterface ...$collections
	 * @return void
	 */
	public function withCollection( CollectionInterface ...$collections ): void;
}
