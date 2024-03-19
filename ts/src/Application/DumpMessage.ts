export type DumpMessage = Readonly<{
    rootFolder: string;
    dryRun?: boolean;
    validate?: boolean;
    ext?: string;
}>;
