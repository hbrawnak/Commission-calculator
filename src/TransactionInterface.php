<?php


namespace Paysera\CommissionTask;


interface TransactionInterface
{
    public function getDate();

    public function getId();

    public function getType();

    public function getOperationType();

    public function getAmount();

    public function getCurrency();
}