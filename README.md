[![Build Status](https://travis-ci.org/xRubin/unapi-def-qiwi.svg?branch=master)](https://travis-ci.org/xRubin/unapi-def-qiwi)
# Unapi DEF Qiwi
Определение мобильного оператора по номеру телефона через [Qiwi](https://developer.qiwi.com/ru/qiwi-wallet-personal/#cell)

Являтся частью библиотеки [Unapi](https://github.com/xRubin/unapi)

### Определение мобильного оператора по номеру телефона
```php
use unapi\def\common\dto\OperatorInterface;
use unapi\def\common\dto\PhoneDto;
use unapi\def\qiwi\Service;

/** @var OperatorInterface $operator */
$operator = (new Service())->detectOperator(new PhoneDto('9651238341'))->wait();
$this->assertEquals('Билайн', $operator->getName());
```
