<?php


namespace App\CommissionTask\Tests\Service\Transaction;


use App\CommissionTask\Service\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    private $transaction;

    protected function setUp()
    {
        $data              = ['2014-12-31', '4', 'private', 'withdraw', '1200.00', 'eur'];
        $this->transaction = new Transaction($data);
    }

    /**
     * Date value should be string
     */
    public function testDate()
    {
        $this->assertEquals('2014-12-31', $this->transaction->getDate());
    }

    /**
     * ID should be integer
     */
    public function testId()
    {
        $this->assertEquals(4, $this->transaction->getId());
    }

    /**
     * Type should be string
     */
    public function testType()
    {
        $this->assertEquals('private', $this->transaction->getType());
    }

    /**
     * Operation type should be string
     */
    public function testOperationType()
    {
        $this->assertEquals('withdraw', $this->transaction->getOperationType());
    }

    /**
     * Amount should be float
     */
    public function testAmount()
    {
        $this->assertEquals(1200.00, $this->transaction->getAmount());
    }

    /**
     * Currency should be string and Capital letter
     */
    public function testCurrency()
    {
        $this->assertEquals('EUR', $this->transaction->getCurrency());
    }
}