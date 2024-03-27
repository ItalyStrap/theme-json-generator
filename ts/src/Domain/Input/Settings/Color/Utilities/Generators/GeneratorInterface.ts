import {ColorInterface} from '../';

// https://github.com/twbs/bootstrap/blob/main/scss/_variables.scss
export interface GeneratorInterface {
    generate(): Generator<ColorInterface>;
    toArray(): ColorInterface[];
}
