<?php


namespace App\CommissionTask\Tests\Service\Reader;


use App\CommissionTask\Service\Reader\CSVReader;
use PHPUnit\Framework\TestCase;

class CSVReaderTest extends TestCase
{
    public function testDataSource()
    {
        $arr    = [
            ['2014-12-31', '4', 'private', 'withdraw', '1200.00', 'EUR'],
            ['2015-01-01', '4', 'private', 'withdraw', '1000.00', 'EUR']
        ];
        $reader = new CSVReader('input_test.csv');
        $this->assertEquals($arr, $reader->dataArray());
    }
}