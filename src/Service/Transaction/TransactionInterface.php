<?php


namespace Paysera\CommissionTask\Service\Transaction;


interface TransactionInterface
{
    /**
     * @return mixed
     */
    public function getDate();

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getType();

    /**
     * @return mixed
     */
    public function getOperationType();

    /**
     * @return mixed
     */
    public function getAmount();

    /**
     * @return mixed
     */
    public function getCurrency();
}