import {Color, ColorFactoryInterface, ColorInterface} from '.';

export class ColorFactory implements ColorFactoryInterface {
    fromColor(colorValue: ColorInterface): ColorInterface {
        return new Color(`${colorValue}`);
    }

    fromColorString(color: string): ColorInterface {
        return new Color(color);
    }
}
