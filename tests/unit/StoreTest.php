<?php

use Ace\Perm\Store;
use Ace\Test\UnitTest;
use Ace\Test\PermMockTrait;

class StoreTest extends UnitTest
{
    use PermMockTrait;
    
    protected $mock_db;

    public function testGetReturnsAPermObject()
    {
        $object_id = '1s';
        $object_type = 'user';
        $subject_id = 'x';
        $subject_type = 'thread';
        $perm_value = 'read';

        $expected_sql = 
            "SELECT * FROM perm WHERE subject_id = ? AND subject_type = ? AND object_id = ? AND object_type = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $subject_id, $subject_type, $object_id, $object_type, $perm_value);
        
        $this->givenAMockSubject($subject_id, $subject_type);
        $this->givenAMockObject($object_id, $object_type);

        $store = new Store($this->mock_db);

        $perm = $store->get($this->mock_subject, $this->mock_object);
        $this->assertInstanceOf('Ace\Perm\Perm', $perm);

        $this->assertSame([$perm_value], $perm->allPerms());
    }

    public function testAddAddsAPermObject()
    {
        $object_id = '1s';
        $object_type = 'user';
        $subject_id = 'x';
        $subject_type = 'thread';
        $perm_value = ['write','admin'];

        $table = 'perm';
        $this->givenAMockDb();
        $this->whenDbExpects($table, $subject_id, $subject_type, $object_id, $object_type, implode(',', $perm_value));

        $this->givenAMockSubject($subject_id, $subject_type);
        $this->givenAMockObject($object_id, $object_type);

        $store = new Store($this->mock_db);
        $result = $store->add($this->mock_subject, $this->mock_object, $perm_value);
        $this->assertTrue($result);
    }

    protected function givenAMockDb()
    {
        $this->mock_db = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
    }

    protected function whenDbContains($expected_sql, $subject_id, $subject_type, $object_id, $object_type, $perm)
    {
        $this->mock_db->expects($this->once())
            ->method('fetchAssoc')
            ->with($expected_sql)
            ->will($this->returnValue(['subject_id' => $subject_id, 'subject_type' => $subject_type, 'object_id' => $object_id, 'object_type' => $object_type, 'value' => $perm]));
    }

    protected function whenDbExpects($table, $subject_id, $subject_type, $object_id, $object_type, $perm)
    {
        $this->mock_db->expects($this->once())
            ->method('insert')
            ->with($table, ['subject_id' => $subject_id, 'subject_type' => $subject_type, 'object_id' => $object_id, 'object_type' => $object_type, 'value' => $perm]);
    }
}
