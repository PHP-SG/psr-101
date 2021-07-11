<?php

declare(strict_types=1);

namespace Psg\Http\Server;

use Psg\Http\Message\{ResponseInterface, RequestFactoryInterface,
    ResponseFactoryInterface, ServerRequestFactoryInterface, StreamFactoryInterface,
    UploadedFileFactoryInterface, UriFactoryInterface};

interface MiddlewareAppInterface extends RequestFactoryInterface, ResponseFactoryInterface,
    ServerRequestFactoryInterface, StreamFactoryInterface, UploadedFileFactoryInterface, UriFactoryInterface,
    \Psr\Http\Server\RequestHandlerInterface
{
    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): ResponseInterface;
    /** Add middleware.
    App framework should handle the various parameter types.  For instance, a class identifier should result in instantiation of the class, with potential dependency injection
     */

    /** params.
    < middleware > < MiddlewareInterface | MiddlewareNextInterface | closure | class identifier >
     */
    public function add($middleware);

    /** remove middleware */

    /** params.
    < middleware > < MiddlewareInterface | MiddlewareNextInterface | closure | class identifier >
     */
    public function remove($middleware);

    /** returns whether the app currently has the middleware added  */

    /** params.
    < middleware > < MiddlewareInterface | MiddlewareNextInterface | closure | class identifier >
     */
    public function has($middleware);
}