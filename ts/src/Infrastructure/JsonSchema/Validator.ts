// @thanks to https://github.com/DekodeInteraktiv/create-theme-json for some hints using the ajv-draft-04 package
import Ajv, {ErrorObject} from 'ajv-draft-04';

type ValidatorErrorsType = ErrorObject[] | null | undefined;

export class Validator {
    private valid: boolean = false;
    private errors: ValidatorErrorsType;
    public constructor(private ajv: Ajv) {}
    public validate(data: {}, schema: {}): void {
        try {
            const validate = this.ajv.compile(schema);
            this.valid = validate(data);
            this.errors = validate.errors;
        } catch (error) {
            console.error(error);
            this.valid = false;
        }
    }

    public isValid(): boolean {
        return this.valid;
    }

    public getErrors(): ValidatorErrorsType {
        return this.errors;
    }

    public reset(): void {
        this.valid = false;
    }
}
