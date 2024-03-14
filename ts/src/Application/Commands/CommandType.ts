export class Command {
    // see https://tldp.org/LDP/abs/html/exitcodes.html
    public static SUCCESS = 0;
    public static FAILURE = 1;
    public static INVALID = 2;
}

export type CommandType = {
    configure(): void;
    execute(): CommandType;
};
