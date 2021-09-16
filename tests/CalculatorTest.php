<?php

declare(strict_types=1);


namespace Paysera\CommissionTask\Tests;


use Paysera\CommissionTask\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp()
    {
        $this->calculator = new Calculator();
    }

    public function testInit()
    {
        $this->assertEquals(
            [0.60, 3.00],
            $this->calculator->init('input_test.csv')
        );

    }
}