import {HandlerInterface, MiddlewareInterface} from '.';

export class Bus<T extends object, R> implements HandlerInterface<T, R> {
    private middlewares: MiddlewareInterface<T, R>[] = [];

    public constructor(private handler: HandlerInterface<T, R>) {}

    public addMiddleware(middleware: MiddlewareInterface<T, R>): void {
        this.middlewares.push(middleware);
    }

    public handle(message: T): R {
        if (this.middlewares.length === 0) {
            return this.handler.handle(message);
        }

        const middleware = this.middlewares.shift();
        // @ts-expect-error - TS doesn't understand that middleware is not undefined, see the if statement above
        return middleware.process(message, this);
    }
}
