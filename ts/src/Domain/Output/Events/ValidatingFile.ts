import {File} from '../../../Infrastructure/Filesystem';

export class ValidatingFile {
    public constructor(private file: File) {}

    public getFile(): File {
        return this.file;
    }
}
