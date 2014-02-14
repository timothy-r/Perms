<?php

use Ace\Perm\Store;
use Ace\Test\UnitTest;
use Ace\Test\PermMockTrait;

class StoreTest extends UnitTest
{
    use PermMockTrait;
    
    protected $mock_db;

    public function getPermValues()
    {
        return [
            [['read']],
            [['read','write']],
            [['read','admin','read']],
        ];
    }

    /**
    * @dataProvider getPermValues
    */
    public function testGetReturnsAPermObject($values)
    {
        $object_id = '1s';
        $object_type = 'user';
        $subject_id = 'x';
        $subject_type = 'thread';
        
        $rows = [];
        foreach ($values as $value){
        $rows[]= [
            'subject_id' => $subject_id,
            'subject_type' => $subject_type,
            'object_id' => $object_id,
            'object_type' => $object_type,
            'value' => $value
       ];
       }

        $expected_sql = 
            "SELECT * FROM perm WHERE subject_id = ? AND subject_type = ? AND object_id = ? AND object_type = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $this->givenAMockSubject($subject_id, $subject_type);
        $this->givenAMockObject($object_id, $object_type);

        $store = new Store($this->mock_db);

        $perm = $store->get($this->mock_subject, $this->mock_object);
        $this->assertInstanceOf('Ace\Perm\Perm', $perm);
        
        $expected = array_unique($values);
        $this->assertSame($expected, $perm->allPerms());
    }

    public function testAddAddsAPermObject()
    {
        $object_id = '1s';
        $object_type = 'user';
        $subject_id = 'x';
        $subject_type = 'thread';
        $perm = 'write';

        $table = 'perm';
        $this->givenAMockDb();
        $this->whenDbExpects($table, $subject_id, $subject_type, $object_id, $object_type, $perm);

        $this->givenAMockSubject($subject_id, $subject_type);
        $this->givenAMockObject($object_id, $object_type);

        $store = new Store($this->mock_db);
        $result = $store->add($this->mock_subject, $this->mock_object, $perm);
        $this->assertTrue($result);
    }

    protected function givenAMockDb()
    {
        $this->mock_db = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
    }

    protected function whenDbContains($expected_sql, $rows)
    {
        $this->mock_db->expects($this->once())
            ->method('fetchAll')
            ->with($expected_sql)
            ->will($this->returnValue($rows));
    }

    protected function whenDbExpects($table, $subject_id, $subject_type, $object_id, $object_type, $perm)
    {
        $this->mock_db->expects($this->once())
            ->method('insert')
            ->with($table, ['subject_id' => $subject_id, 'subject_type' => $subject_type, 'object_id' => $object_id, 'object_type' => $object_type, 'value' => $perm]);
    }
}
