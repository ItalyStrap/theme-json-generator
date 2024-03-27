import {GeneratorInterface} from './';
import {ColorInterface} from '../ColorInterface';

export abstract class GeneratorAbstract implements GeneratorInterface {
    public generate(): Generator<ColorInterface> {
        throw new Error('Method not implemented.');
    }

    public toArray(): ColorInterface[] {
        return Array.from(this.generate());
    }

    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    protected invoke(i: number): ColorInterface {
        throw new Error('Method not implemented.');
    }
}
