import {PresetInterface} from '.';

export interface PresetsInterface {
    add(item: PresetInterface): PresetsInterface;

    addMultiple(items: PresetInterface[]): PresetsInterface;

    get(key: string, defaultValue: unknown): unknown;

    parse(content: string): string;

    findAllByType(type: string): PresetInterface[] | [] | {};
}
