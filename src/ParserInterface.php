<?php

namespace unapi\def\qiwi;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

interface ParserInterface
{
    /**
     * @param ResponseInterface $response
     * @return PromiseInterface
     */
    public function parseResponse(ResponseInterface $response): PromiseInterface;
}