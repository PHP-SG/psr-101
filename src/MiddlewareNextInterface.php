<?php

declare(strict_types=1);

namespace Psg\Http\Server;

use Psg\Http\Message\ResponseInterface;
use Psg\Http\Message\ServerRequestInterface;

/**
 * Middleware that expects a `next` callback
 *
 * Some middleware has been written with the expectation receiving a `next`
 * callback.  This interface is available to accomodate such with the next
 * callback receiving the request
 */
interface MiddlewareNextInterface
{
    /**
     * Process an incoming server request.
     */
    public function process(ServerRequestInterface $request, \Closure $next): ResponseInterface;
}
