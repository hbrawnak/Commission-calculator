<?php

namespace App\CommissionTask\Service\Transaction;

class Transaction implements TransactionInterface
{

    private $date;
    private $id;
    private $type;
    private $operationType;
    private $amount;
    private $currency;

    /**
     * Transaction constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->date          = $data[0];
        $this->id            = $data[1];
        $this->type          = $data[2];
        $this->operationType = $data[3];
        $this->amount        = $data[4];
        $this->currency      = $data[5];
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return (string)$this->date;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return (float)$this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return strtoupper($this->currency);
    }
}