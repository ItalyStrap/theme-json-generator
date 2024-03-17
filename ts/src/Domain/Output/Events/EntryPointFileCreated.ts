import {File} from '../../../Infrastructure/Filesystem';

export class EntryPointFileCreated {
    public constructor(public readonly file: File) {}
}
