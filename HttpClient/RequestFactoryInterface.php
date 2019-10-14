<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\HttpClient;

use Psr\Http\Message\RequestInterface;

interface RequestFactoryInterface
{
    /**
     * @param string $method
     * @param string $uri
     * @param array $headers
     * @param string|null $body
     * @return RequestInterface
     */
    public function create(string $method, string $uri, array $headers = [], string $body = null): RequestInterface;
}
