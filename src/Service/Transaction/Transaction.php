<?php

namespace Paysera\CommissionTask;

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
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}