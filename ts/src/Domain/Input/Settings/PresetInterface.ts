export type PresetObjectReturnType<T> = Record<string, string | string[] | Record<string, T>>;

export interface PresetInterface {
    type(): string;

    slug(): string;

    ref(): string;

    prop(): string;

    var(fallback?: string): string;

    toString(): string;

    toObject(): Record<string, string | string[] | Record<string, unknown> | null>;
}
