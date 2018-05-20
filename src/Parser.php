<?php

namespace unapi\def\qiwi;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\ResponseInterface;
use unapi\def\common\dto\OperatorDto;
use unapi\def\common\dto\OperatorInterface;

use function GuzzleHttp\json_decode;

class Parser implements ParserInterface
{
    private $responseClass = OperatorDto::class;

    /**
     * @param array $config Service configuration settings.
     */
    public function __construct(array $config = [])
    {
        if (isset($config['responseClass'])) {
            if ($config['responseClass'] instanceof OperatorInterface) {
                $this->responseClass = $config['responseClass'];
            } else {
                throw new \InvalidArgumentException('ResponseClass must implement OperatorInterface');
            }
        }
    }

    /**
     * @param string $message
     * @return PromiseInterface
     */
    protected function decodeMessage(string $message): PromiseInterface
    {
        $fulfilledPromise = function (string $name, string $mnc) {
            return new FulfilledPromise($this->responseClass::toDto([
                'name' => $name,
                'mnc' => $mnc,
            ]));
        };

        switch ($message) {
            case '1':
                return $fulfilledPromise('МТС', '25001');
            case '2':
                return $fulfilledPromise('Билайн', '25099');
            case '3':
                return $fulfilledPromise('МегаФон', '25002');
            case '42':
                return $fulfilledPromise('Tele2', '25020');
            default:
                return new RejectedPromise('Unknown operator ' . $message);
        }
    }

    /**
     * @param ResponseInterface $response
     * @return PromiseInterface
     */
    public function parseResponse(ResponseInterface $response): PromiseInterface
    {
        $result = json_decode($response->getBody()->getContents());

        if ('2' === $result->code->value)
            return new RejectedPromise('Невозможно определить оператора');

        if ('0' === $result->code->value) {
            return $this->decodeMessage($result->message);
        }

        return new RejectedPromise('Unknown Error');
    }
}