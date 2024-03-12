export type CommandType = {
    configure(): void;
    execute(): CommandType;
};
