export interface PresetInterface {
    type(): string;

    slug(): string;

    ref(): string;

    prop(): string;

    var(fallback?: string): string;

    toString(): string;

    toObject(): Record<string, string | Record<string, unknown>>;
}
