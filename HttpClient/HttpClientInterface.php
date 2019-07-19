<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\HttpClient;

use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    /**
     * @param string $body
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function send(string $body, array $options = []): ResponseInterface;
}
