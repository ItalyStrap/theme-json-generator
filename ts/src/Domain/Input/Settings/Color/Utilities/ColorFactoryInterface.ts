import {ColorInterface} from '.';

export interface ColorFactoryInterface {
    fromColor(colorValue: ColorInterface): ColorInterface;

    fromColorString(color: string): ColorInterface;
}
