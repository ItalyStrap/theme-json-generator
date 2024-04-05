import {NullPresets} from '../Settings';
import {CommonPresets} from './Common';

export class Css {
    public constructor(protected readonly presets: CommonPresets = null) {
        if (presets === null) {
            this.presets = new NullPresets();
        }
    }

    public parseString(css: string, selector: string = ''): string {
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
