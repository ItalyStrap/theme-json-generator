<?php

declare(strict_types=1);

namespace ItalyStrap\Bus;

/**
 * @https://www.google.it/search?q=php+command+bus+cli&sca_esv=595971146&ei=TRGYZdXYK9uGxc8PkbeFqAs&ved=0ahUKEwiVs6DwucaDAxVbQ_EDHZFbAbUQ4dUDCBA&uact=5&oq=php+command+bus+cli&gs_lp=Egxnd3Mtd2l6LXNlcnAiE3BocCBjb21tYW5kIGJ1cyBjbGkyBRAhGKABMgUQIRigATIEECEYFUi7EVDXBFj9DHAAeAKQAQCYAa8BoAGvBKoBAzAuNLgBA8gBAPgBAcICBBAAGEfCAgYQABgWGB7CAggQIRgWGB4YHeIDBBgAIEGIBgGQBgg&sclient=gws-wiz-serp
 * @link https://tactician.thephpleague.com/installation/
 * @link https://laracasts.com/discuss/channels/laravel/when-to-use-the-command-bus
 * @link https://medium.com/@mgonzalezbaile/implementing-a-use-case-iii-command-bus-9bff58766d28
 * @link https://docs.simplebus.io/en/latest/Guides/command_bus.html
 * @link https://laravel.com/docs/5.0/bus
 * @link https://github.com/remotelyliving/php-command-bus/blob/master/src/CommandBus.php
 * @link https://github.com/php-fig/event-dispatcher-util/blob/master/src/ParameterDeriverTrait.php
 * @link https://steemit.com/php/@crell/psr-14-example-delayed-events-queues-and-asynchronicity
 *
 * @psalm-api
 */
class Bus implements HandlerInterface
{
    private array $middleware = [];
    private HandlerInterface $handler;

    public function __construct(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    public function addMiddleware(MiddlewareInterface ...$middleware): void
    {
        $this->middleware = \array_merge($this->middleware, $middleware);
    }

    public function handle(object $message)
    {
        if ($this->middleware === []) {
            return $this->handler->handle($message);
        }

        /** @var MiddlewareInterface $middleware */
        $middleware = array_shift($this->middleware);
        return $middleware->process($message, $this);
    }
}
