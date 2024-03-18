import type {HandlerInterface} from './HandlerInterface';

export interface MiddlewareInterface<T extends object, R> {
    process(message: T, handler: HandlerInterface<T, R>): R;
}
