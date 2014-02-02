<?php

use Ace\Perm\Store;
use Ace\Test\UnitTest;

class StoreTest extends UnitTest
{

    public function testGetReturnsAPermObject()
    {
        $db = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
        $subject = $this->getMock('Ace\Perm\SubjectInterface');
        $object = $this->getMock('Ace\Perm\ObjectInterface');
        $store = new Store($db);

        $perm = $store->get($subject, $object);
        $this->assertInstanceOf('Ace\Perm\Perm', $perm);
    }
}
