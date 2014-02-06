<?php

use Ace\Perm\Store;
use Ace\Test\UnitTest;
require_once(__DIR__.'/../PermMockTrait.php');

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

    public function testSetAddsAPermObject()
    {
        $object_id = '1s';
        $object_type = 'user';
        $subject_id = 'x';
        $subject_type = 'thread';
        $perm_value = 'write,admin';

        $expected_sql = 'INSERT INTO perm (subject_id, subject_type, object_id, object_type, value) values(?,?,?,?,?)';
        $this->givenAMockDb();
        $this->whenDbExpects($expected_sql, $subject_id, $subject_type, $object_id, $object_type, $perm_value);
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

    protected function whenDbExpects($expected_sql, $subject_id, $subject_type, $object_id, $object_type, $perm)
    {
        $this->mock_db->expects($this->once())
            ->method('')
            ->with($expected_sql, $this->returnValue(['subject_id' => $subject_id, 'subject_type' => $subject_type, 'object_id' => $object_id, 'object_type' => $object_type, 'value' => $perm]));
    }
}
