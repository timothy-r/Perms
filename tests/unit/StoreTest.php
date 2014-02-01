<?php

use Ace\Perm\Store;
use Ace\Test\UnitTest;

class StoreTest extends UnitTest
{
    public function testGetReturnsAPermObject()
    {
        $subject = $this->getMock('Ace\Perm\SubjectInterface');
        $object = $this->getMock('Ace\Perm\ObjectInterface');
        $store = new Store;

        $perm = $store->get($subject, $object);
        $this->assertInstanceOf('Ace\Perm\Perm', $perm);
    }
}
