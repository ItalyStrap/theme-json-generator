export type DumpMessage = Readonly<{
    rootFolder: string;
    dryRun?: boolean;
    ext?: string;
}>;
