import {ColorFactory, ColorFactoryInterface, ColorInterface, ColorModifierInterface} from '.';

export class ColorModifier implements ColorModifierInterface {
    private readonly initialType: string;

    public constructor(
        private readonly initialColor: ColorInterface,
        private readonly factory: ColorFactoryInterface = new ColorFactory()
    ) {
        this.initialType = this.initialColor.type();
    }

    public color(): ColorInterface {
        return this.initialColor;
    }

    public tint(weight: number = 0): ColorInterface {
        return this.mixWith(`rgb(255,255,255)`, weight);
    }

    public shade(weight: number = 0): ColorInterface {
        return this.mixWith('rgb(0,0,0)', weight);
    }

    public tone(weight: number = 0): ColorInterface {
        return this.mixWith('rgb(128,128,128)', weight);
    }

    public opacity(alpha: number = 1): ColorInterface {
        return this.createNewColorWithChangedLightnessOrOpacity(0, alpha);
    }

    public darken(amount: number = 0): ColorInterface {
        this.validateAmount(amount);
        return this.createNewColorWithChangedLightnessOrOpacity(-amount, this.initialColor.alpha());
    }

    public lighten(amount: number = 0): ColorInterface {
        this.validateAmount(amount);
        return this.createNewColorWithChangedLightnessOrOpacity(amount, this.initialColor.alpha());
    }

    public saturate(amount: number = 0): ColorInterface {
        return this.createNewColorWithChangedSaturation(amount);
    }

    public contrast(amount: number = 0): ColorInterface {
        return this.createNewColorWithChangedContrast(amount);
    }

    public complementary(): ColorInterface {
        if (this.initialColor.hue() === 0 && this.initialColor.saturation() === 0) {
            return this.initialColor;
        }
        return this.hueRotate(this.initialColor.hue() + 180);
    }

    public invert(): ColorInterface {
        return this.createNewColorFrom(
            this.initialColor.hue().toString(),
            this.initialColor.saturation().toString(),
            this.sanitizeFromFloatToInteger(100 - this.initialColor.lightness()).toString(),
            this.initialColor.alpha().toString()
        );
    }

    public hueRotate(amount: number = 0): ColorInterface {
        let sumHue = this.initialColor.hue() + amount;
        if (sumHue < 0) {
            sumHue = 360 + sumHue;
        }
        return this.createNewColorFrom(
            sumHue.toString(),
            this.initialColor.saturation().toString(),
            this.initialColor.lightness().toString(),
            this.initialColor.alpha().toString()
        );
    }

    /**
     * https://en.wikipedia.org/wiki/Grayscale#Converting_color_to_grayscale
     * https://github.com/Qix-/color
     * @todo Implement grayscale in the PHP version
     */
    public greyScale(): ColorInterface {
        const r = this.initialColor.red();
        const g = this.initialColor.green();
        const b = this.initialColor.blue();
        const value = Math.round(Number(r) * 0.299 + Number(g) * 0.587 + Number(b) * 0.114);
        const rgba = `rgba(${value},${value},${value},${this.initialColor.alpha()})`;
        const newColor = this.factory.fromColorString(rgba);
        return this.callMethodOnColorObject(newColor);
    }

    private createNewColorWithChangedLightnessOrOpacity(amount: number, alpha: number = 1): ColorInterface {
        return this.createNewColorFrom(
            this.initialColor.hue().toString(),
            this.initialColor.saturation().toString(),
            this.sanitizeFromFloatToInteger(this.initialColor.lightness() + amount).toString(),
            alpha.toString()
        );
    }

    private createNewColorFrom(hue: string, saturation: string, lightness: string, alpha: string): ColorInterface {
        const newColor = this.factory.fromColorString(`hsla(${hue},${saturation}%,${lightness}%,${alpha})`);
        return this.callMethodOnColorObject(newColor);
    }

    private createNewColorWithChangedSaturation(amount: number): ColorInterface {
        return this.createNewColorFrom(
            this.initialColor.hue().toString(),
            this.sanitizeFromFloatToInteger(this.initialColor.saturation() + amount).toString(),
            this.initialColor.lightness().toString(),
            this.initialColor.alpha().toString()
        );
    }

    private createNewColorWithChangedContrast(amount: number): ColorInterface {
        return this.createNewColorFrom(
            this.initialColor.hue().toString(),
            this.sanitizeFromFloatToInteger(this.initialColor.saturation() + amount).toString(),
            this.sanitizeFromFloatToInteger(this.initialColor.lightness() + amount).toString(),
            this.initialColor.alpha().toString()
        );
    }

    private mixWith(colorString: string, weight: number = 0): ColorInterface {
        const result = this.mixRgb(
            this.factory.fromColorString(colorString).toRgba(),
            this.initialColor.toRgba(),
            weight > 1 ? weight / 100 : weight
        );

        result.push(this.initialColor.alpha());

        return this.callMethodOnColorObject(this.factory.fromColorString(`rgba(${result.join(',')})`));
    }

    private callMethodOnColorObject(newColor: ColorInterface): ColorInterface {
        const methodName = `to${this.initialType.charAt(0).toUpperCase() + this.initialType.slice(1)}`;

        // eslint-disable-next-line @typescript-eslint/ban-types
        const callable = (newColor as unknown as {[key: string]: Function})[methodName];

        if (typeof callable === 'undefined') {
            throw new Error('Method not found');
        }

        if (typeof callable === 'function') {
            return callable.call(newColor, newColor.alpha());
        }

        throw new Error('Method not found');
    }

    private mixRgb(color1: ColorInterface, color2: ColorInterface, weight: number = 0.5): number[] {
        const f = (x: number): number => weight * x;
        const g = (x: number): number => (1 - weight) * x;
        const h = (x: number, y: number): number => Math.round(x + y);

        return [
            h(f(Number(color1.red())), g(Number(color2.red()))),
            h(f(Number(color1.green())), g(Number(color2.green()))),
            h(f(Number(color1.blue())), g(Number(color2.blue()))),
        ];
    }

    private sanitizeFromFloatToInteger(value: number): number {
        return value > 100 ? 100 : value < 0 ? 0 : Math.round(value);
    }

    private validateAmount(amount: number): void {
        if (amount % 1 !== 0) {
            new Error('Amount must be an integer');
        }
    }
}
