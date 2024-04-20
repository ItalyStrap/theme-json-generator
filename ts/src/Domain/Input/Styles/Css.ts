import {NullPresets} from '../Settings';
import {CommonPresets} from './Common';

export class Css {
    public static readonly M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING = 'CSS cannot begin with an ampersand (&)';

    public constructor(protected readonly presets: CommonPresets = null) {
        if (presets === null) {
            this.presets = new NullPresets();
        }
    }

    public parseString(css: string, selector: string = ''): string {
        if (css.trim().startsWith('&')) {
            throw new Error(Css.M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING);
        }

        css = this.presets?.parse(css) ?? css;

        if (selector === '') {
            return css;
        }

        if (!css?.includes(selector)) {
            return css;
        }

        const exploded = css.split(selector);

        const rootRule = [];
        const explodedNew = [];

        for (const rule of exploded) {
            if (rule.trim().startsWith('{')) {
                let value = rule.trimEnd().replace('{', '').replace('}', '');
                value = value.replace(/^ +/m, '');
                rootRule.push(value);
                continue;
            }

            explodedNew.push(rule);
        }

        const result = (rootRule.join('') + explodedNew.join('&')).trimStart();

        if (result.startsWith('&')) {
            return result.substring(1);
        }

        return result;
    }
}
