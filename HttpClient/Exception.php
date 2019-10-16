<?php

namespace Trilix\EventsApiBundle\HttpClient;

use Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;

class Exception extends RuntimeException implements ClientExceptionInterface
{
}
