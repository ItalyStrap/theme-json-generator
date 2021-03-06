<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

trait ImmutableCollectionTrait {

	/**
	 * @var array<string, string>
	 */
	private array $collection = [];

	final private function setCollection( string $key, string $value ): self {
		$this->assertIsImmutable( $key );
		$this->collection[ $key ] = $value;
		return $this;
	}

	final private function assertIsImmutable( string $key ): void {
		if ( \array_key_exists( $key, $this->collection ) ) {
			$bt = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS );

			$bt[1] ??= [];

			if ( empty( $bt[1] ) ) {
				$bt[1] = ['file' => '', 'line' => ''];
			}

			/**
			 * @psalm-suppress PossiblyUndefinedArrayOffset
			 */
			throw new \RuntimeException( \sprintf(
				'The key "%s" is already provided | File:  %s | Line: %s',
				$key,
				$bt[1]['file'],
				$bt[1]['line']
			) );
		}
	}

	final public function __clone() {
		$this->collection = [];
	}
}
