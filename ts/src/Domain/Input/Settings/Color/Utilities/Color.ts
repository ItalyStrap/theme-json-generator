// https://pinetools.com/lighten-color
import BaseColor from 'color';
//
import {ColorInterface} from './ColorInterface';

export class Color implements ColorInterface {
    private readonly color: BaseColor;
    private readonly kind?: string;

    public constructor(color: string) {
        color = color.replace(/\s/g, '');
        this.color = BaseColor(color);
        this.kind = this.findColorKind(color);
    }

    public alpha(): number {
        return this.color.alpha();
    }

    public red(): string | number {
        return this.sanitizeHslRgbValue(this.color.red());
    }

    public green(): string | number {
        return this.sanitizeHslRgbValue(this.color.green());
    }

    public blue(): string | number {
        return this.sanitizeHslRgbValue(this.color.blue());
    }

    public hue(): number {
        return this.sanitizeHslRgbValue(this.color.hue());
    }

    public saturation(): number {
        return this.sanitizeHslRgbValue(this.color.saturationl());
    }

    public lightness(): number {
        return this.sanitizeHslRgbValue(this.color.lightness());
    }

    public isDark(): boolean {
        return this.color.isDark();
    }

    public isLight(): boolean {
        return this.color.isLight();
    }

    public luminance(): number {
        return this.color.luminosity();
    }

    public toHex(): Color {
        return new Color(this.color.hex());
    }

    public toHsl(): Color {
        const color = `hsl(${this.hue()},${this.saturation()}%,${this.lightness()}%)`;
        return new Color(color);
    }

    public toHsla(alpha: number = 1): Color {
        const color = `hsla(${this.hue()},${this.saturation()}%,${this.lightness()}%,${alpha})`;
        return new Color(color);
    }

    public toRgb(): Color {
        const color = `rgb(${this.red()},${this.green()},${this.blue()})`;
        return new Color(color);
    }

    public toRgba(alpha: number = 1): Color {
        const color = `rgba(${this.red()},${this.green()},${this.blue()},${alpha})`;
        return new Color(color);
    }

    public toString(): string {
        if (this.kind === 'hex') {
            return this.color.hex().toLowerCase();
        }

        if (this.kind === 'hsl') {
            return `hsl(${this.hue()},${this.saturation()}%,${this.lightness()}%)`;
        }

        if (this.kind === 'hsla') {
            return `hsla(${this.hue()},${this.saturation()}%,${this.lightness()}%,${this.alpha()})`;
        }

        if (this.kind === 'rgb') {
            return this.color.rgb().toString().replace(/ /g, '');
        }

        if (this.kind === 'rgba') {
            return `rgba(${this.red()},${this.green()},${this.blue()},${this.alpha()})`;
        }

        throw new Error(`Unrecognized color kind: ${this.kind}`);
    }

    public type(): string {
        return this.kind ?? '';
    }

    private findColorKind(color: string): string {
        if (color.match(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/)) {
            return 'hex';
        }

        if (color.match(/^rgba\((\d{1,3}),(\d{1,3}),(\d{1,3}),(\d*(?:\.\d+)?)\)$/)) {
            return 'rgba';
        }

        if (color.match(/^rgb\((\d{1,3}),(\d{1,3}),(\d{1,3})\)$/)) {
            return 'rgb';
        }

        if (color.match(/^hsl\((\d{1,3}),(\d{1,3})%,(\d{1,3})%\)$/)) {
            return 'hsl';
        }

        if (color.match(/^hsla\((\d{1,3}),(\d{1,3})%,(\d{1,3})%,(\d*(?:\.\d+)?)\)$/)) {
            return 'hsla';
        }

        throw new Error(`Unrecognized color kind: ${color}`);
    }

    private sanitizeHslRgbValue(value: string | number): number {
        return Math.round(Number(value));
    }
}
