<?php

namespace unapi\def\qiwi;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use unapi\def\common\dto\PhoneInterface;
use unapi\def\common\interfaces\DefServiceInterface;

class Service implements DefServiceInterface, LoggerAwareInterface
{
    /** @var Client */
    private $client;
    /** @var LoggerInterface */
    private $logger;
    /** @var ParserInterface */
    private $parser;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['client'])) {
            $this->client = new Client();
        } elseif ($config['client'] instanceof Client) {
            $this->client = $config['client'];
        } else {
            throw new \InvalidArgumentException('Client must be instance of Client');
        }

        if (!isset($config['logger'])) {
            $this->logger = new NullLogger();
        } elseif ($config['logger'] instanceof LoggerInterface) {
            $this->setLogger($config['logger']);
        } else {
            throw new \InvalidArgumentException('Logger must be instance of LoggerInterface');
        }

        if (!isset($config['parser'])) {
            $this->parser = new Parser();
        } else {
            if ($config['parser'] instanceof ParserInterface) {
                $this->parser = $config['parser'];
            } else {
                throw new \InvalidArgumentException('Parser must be instance of ParserInterface');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @param PhoneInterface $phone
     * @return PromiseInterface
     */
    public function detectOperator(PhoneInterface $phone): PromiseInterface
    {
        return $this->client->requestAsync('POST', '/mobile/detect.action', [
            'form_params' => [
                'phone' => $phone->getNumber('+7')
            ]
        ])->then(function (ResponseInterface $response) {
            return $this->parser->parseResponse($response);
        });
    }
}