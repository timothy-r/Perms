<?php

use Ace\Perm\Store;
use Ace\Test\UnitTest;

class StoreTest extends UnitTest
{

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

        $subject = $this->getMock('Ace\Perm\SubjectInterface');
        $subject->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($subject_id));
        $subject->expects($this->once())
            ->method('getType')
            ->will($this->returnValue($subject_type));

        $object = $this->getMock('Ace\Perm\ObjectInterface');
        $object->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($object_id));
        $object->expects($this->once())
            ->method('getType')
            ->will($this->returnValue($object_type));

        $store = new Store($db);

        $perm = $store->get($subject, $object);
        $this->assertInstanceOf('Ace\Perm\Perm', $perm);
    }
}
