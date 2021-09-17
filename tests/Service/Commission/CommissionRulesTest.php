<?php


namespace Paysera\CommissionTask\Tests\Service\Commission;


use Paysera\CommissionTask\Service\Commission\CommissionRules;
use PHPUnit\Framework\TestCase;

class CommissionRulesTest extends TestCase
{
    private $rules;

    protected function setUp()
    {
        $this->rules = new CommissionRules();
    }

    /**
     *Test deposit charge
     */
    public function testDepositCharge()
    {
        $this->assertEquals(
            0.03,
            $this->rules->depositCharge()
        );
    }

    /**
     *Test private account withdraw charge
     */
    public function testPrivateWithdrawCharge()
    {
        $this->assertEquals(
            0.3,
            $this->rules->privateWithdrawCharge()
        );
    }

    /**
     *Test business account withdraw charge
     */
    public function testBusinessWithdrawCharge()
    {
        $this->assertEquals(
            0.5,
            $this->rules->businessWithdrawCharge()
        );
    }


    /**
     *Test weekly free of charge amount limit
     */
    public function testFreeWeekAmountLimit()
    {
        $this->assertEquals(
            1000,
            $this->rules->freeWeekAmountLimit()
        );
    }

    /**
     *Test weekly day count
     */
    public function testWeekDay()
    {
        $this->assertEquals(
            7,
            $this->rules->weekDay()
        );
    }

    /**
     *Test weekly free withdraw count
     */
    public function testFreeWithdrawLimit()
    {
        $this->assertEquals(
            3,
            $this->rules->freeWithdrawLimit()
        );
    }
}