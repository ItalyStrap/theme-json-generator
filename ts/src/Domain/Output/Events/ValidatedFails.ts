import {File} from '../../../Infrastructure/Filesystem';

export class ValidatedFails {
    public constructor(private file: File) {}

    public getFile(): File {
        return this.file;
    }
}
