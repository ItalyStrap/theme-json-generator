export interface ColorInterface {
    isDark(): boolean;

    isLight(): boolean;

    luminance(): number;

    toHex(): ColorInterface;

    toHsl(): ColorInterface;

    toHsla(alpha: number): ColorInterface;

    toRgb(): ColorInterface;

    toRgba(alpha: number): ColorInterface;

    red(): string | number;

    green(): string | number;

    blue(): string | number;

    hue(): number;

    saturation(): number;

    lightness(): number;

    alpha(): number;

    type(): string;
}
