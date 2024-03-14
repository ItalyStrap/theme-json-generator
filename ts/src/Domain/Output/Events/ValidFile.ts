import {File} from '../../../Infrastructure/Filesystem';

export class ValidFile {
    public constructor(private file: File) {}

    public getFile(): File {
        return this.file;
    }
}
