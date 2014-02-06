<?php

use Ace\Perm\Store;
use Ace\Test\UnitTest;
require_once(__DIR__.'/../PermMockTrait.php');

class StoreTest extends UnitTest
{
    use PermMockTrait;

    public function testGetReturnsAPermObject()
    {
        $object_id = '1s';
        $object_type = 'user';
        $subject_id = 'x';
        $subject_type = 'thread';

        $expected_sql = 
            "SELECT * FROM perm WHERE object_id = ? AND object_type = ? AND subject_id = ? AND subject_type = ?";
        
        $db = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
        $db->expects($this->once())
            ->method('fetchAssoc')
            ->with($expected_sql)
            ->will($this->returnValue(['subject_id' => $subject_id, 'subject_type' => $subject_type, 'object_id' => $object_id, 'object_type' => $object_type, 'value' => 'read']));
        
        $this->givenAMockSubject($subject_id, $subject_type);
        $this->givenAMockObject($object_id, $object_type);

        $store = new Store($db);

        $perm = $store->get($this->mock_subject, $this->mock_object);
        $this->assertInstanceOf('Ace\Perm\Perm', $perm);
        $this->assertSame(['read'], $perm->allPerms());
    }
}
