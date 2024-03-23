import {Common} from './Common';

export class Typography extends Common<typeof Typography> {
    static readonly FONT_FAMILY = 'fontFamily';
    static readonly FONT_SIZE = 'fontSize';
    static readonly FONT_STYLE = 'fontStyle';
    static readonly FONT_WEIGHT = 'fontWeight';
    static readonly LETTER_SPACING = 'letterSpacing';
    static readonly LINE_HEIGHT = 'lineHeight';
    static readonly TEXT_DECORATION = 'textDecoration';
    static readonly TEXT_TRANSFORM = 'textTransform';

    public fontFamily(value: string): this {
        return this.setProperty(Typography.FONT_FAMILY, value);
    }

    public fontSize(value: string): this {
        return this.setProperty(Typography.FONT_SIZE, value);
    }

    public fontStyle(value: string): this {
        return this.setProperty(Typography.FONT_STYLE, value);
    }

    public fontWeight(value: string): this {
        return this.setProperty(Typography.FONT_WEIGHT, value);
    }

    public letterSpacing(value: string): this {
        return this.setProperty(Typography.LETTER_SPACING, value);
    }

    public lineHeight(value: string): this {
        return this.setProperty(Typography.LINE_HEIGHT, value);
    }

    public textDecoration(value: string): this {
        return this.setProperty(Typography.TEXT_DECORATION, value);
    }

    public textTransform(value: string): this {
        return this.setProperty(Typography.TEXT_TRANSFORM, value);
    }
}
