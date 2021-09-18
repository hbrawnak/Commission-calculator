<?php


namespace App\CommissionTask\Tests\Service\Cache;


use App\CommissionTask\Service\Cache\Cache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    /**
     * Test set and get value
     */
    public function testSetAndGetValue()
    {
        Cache::set('hello', 'world');

        $this->assertEquals('world', Cache::get('hello'));
    }

    /*
     * Test delete value from cache
     * */
    public function testDeleteValue()
    {
        Cache::delete('hello');

        $this->expectOutputString(Cache::get('hello'));
    }
}