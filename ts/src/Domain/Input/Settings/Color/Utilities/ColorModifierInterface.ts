import {ColorInterface} from '.';

export interface ColorModifierInterface {
    tint(weight?: number): ColorInterface;

    shade(weight: number): ColorInterface;

    tone(weight: number): ColorInterface;

    opacity(alpha: number): ColorInterface;

    darken(amount: number): ColorInterface;

    lighten(amount: number): ColorInterface;

    saturate(amount: number): ColorInterface;

    contrast(amount: number): ColorInterface;

    hueRotate(amount: number): ColorInterface;

    greyScale(): ColorInterface;

    complementary(): ColorInterface;

    color(): ColorInterface;
}
