export interface HandlerInterface<T extends object, R> {
    handle(message: T): R;
}
