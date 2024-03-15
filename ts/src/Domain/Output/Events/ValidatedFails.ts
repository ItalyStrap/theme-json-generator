import {File} from '../../../Infrastructure/Filesystem';
import {ValidatorErrorsType} from '../../../Infrastructure/JsonSchema';

export class ValidatedFails {
    public constructor(
        public readonly file: File,
        public readonly errors: ValidatorErrorsType
    ) {}
}
