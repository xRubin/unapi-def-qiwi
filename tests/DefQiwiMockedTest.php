<?php

use PHPUnit\Framework\TestCase;

use unapi\def\qiwi\Service;
use unapi\def\qiwi\Client;
use unapi\def\common\dto\PhoneDto;
use unapi\def\common\dto\OperatorInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class DefQiwiMockedTest extends TestCase
{
    protected function getService(HandlerStack $handler)
    {
        return new Service([
            'client' => new Client(['handler' => $handler]),
        ]);
    }

    public function testOperatorParser()
    {
        $service = $this->getService(
            HandlerStack::create(
                new MockHandler([
                    new Response(200, [], json_encode([
                        'code' => [
                            'value' => '0',
                            '_name' => 'NORMAL',
                        ],
                        'data' => null,
                        'message' => '2',
                        'messages' => null,
                    ])),
                ])
            )
        );

        /** @var OperatorInterface $operator */
        $operator = $service->detectOperator(new PhoneDto('9651238341'))->wait();

        $this->assertEquals('Билайн', $operator->getName());
    }
}