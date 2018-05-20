<?php

use PHPUnit\Framework\TestCase;

use unapi\def\qiwi\Service;
use unapi\def\common\dto\PhoneDto;
use unapi\def\common\dto\OperatorInterface;

class DefQiwiFunctionalTest extends TestCase
{
    public function testOperatorParser()
    {
        /** @var OperatorInterface $operator */
        $operator = (new Service())->detectOperator(new PhoneDto('9651238341'))->wait();

        $this->assertEquals('Билайн', $operator->getName());
    }
}