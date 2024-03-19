import {File} from '../Infrastructure/Filesystem';

export type ValidateMessage = Readonly<{
    rootFolder: string;
    fileSchema: File;
    force?: boolean;
}>;
