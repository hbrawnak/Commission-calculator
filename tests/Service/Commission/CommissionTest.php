<?php


namespace Paysera\CommissionTask\Tests\Service\Commission;

use Paysera\CommissionTask\Service\Commission\Commission;
use Paysera\CommissionTask\Service\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

class CommissionTest extends TestCase
{
    private $commission;

    protected function setUp()
    {
        $data             = ['2014-12-31', '4', 'private', 'withdraw', '1200.00', 'EUR'];
        $this->commission = new Commission(new Transaction($data));
    }

    public function testCommissionProcess()
    {
        $this->assertEquals(
            0.60,
            $this->commission->process()
        );
    }
}