<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;

/**
 * Create a mocked HTTP Client
 * @param ResponseInterface[] $responses
 * @return Client
 */
function getFakeClient(array $responses)
{
    $mock = new MockHandler($responses);
    $handlerStack = HandlerStack::create($mock);
    return new Client(['handler' => $handlerStack]);
}
