import {Config} from '../../../src/Application/Config';
import {SectionNames} from '../../../src/Domain/Input/SectionNames';

export default (config: Config<string, number | string | object>) => {
    config.merge({
        [SectionNames.SCHEMA]: 'https://schemas.wp.org/trunk/theme.json',
        [SectionNames.VERSION]: 2,
        [SectionNames.SETTINGS]: {},
        [SectionNames.STYLES]: {},
    });
};
