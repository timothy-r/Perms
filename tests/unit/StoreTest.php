<?php

use Ace\Perm\Store;
use Ace\Test\UnitTest;
use Ace\Test\PermMockTrait;

class StoreTest extends UnitTest
{
    use PermMockTrait;
    
    protected $subject = 'group:admins@accounts.com';
    protected $object = 'issue_33421@bugs.co.uk';

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
        $rows = [];
        foreach ($values as $value){
        $rows[]= [
            'subject' => $this->subject,
            'object' => $this->object,
            'value' => $value
       ];
       }

        $expected_sql = 
            "SELECT * FROM perm WHERE subject = ? AND object = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new Store($this->mock_db);

        $perm = $store->get($this->subject, $this->object);
        $this->assertInstanceOf('Ace\Perm\Perm', $perm);
        
        $expected = array_unique($values);
        $this->assertSame($expected, $perm->allPerms());
    }

    
    /**
    * @expectedException Ace\Perm\NotFoundException
    */
    public function testGetThrowsExceptionForMissingPair()
    {
        $rows = [];

        $expected_sql = 
            "SELECT * FROM perm WHERE subject = ? AND object = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new Store($this->mock_db);

        $perm = $store->get($this->subject, $this->object);
    }

    public function testAddAddsAPermObject()
    {
        $perm = 'write';

        $table = 'perm';
        $this->givenAMockDb();
        $this->whenDbExpectsInsert($table, $this->subject, $this->object, $perm);


        $store = new Store($this->mock_db);
        $result = $store->add($this->subject, $this->object, $perm);
        $this->assertTrue($result);
    }
    
    /*
    public function testRemoveRemovesAPerm()
    {
        $value = 'write';

        $table = 'perm';
        $this->givenAMockDb();
        $this->whenDbExpectsDelete($table, $this->subject, $this->object, $value);

        $this->givenAMockPerm([$value]);

        $store = new Store($this->mock_db);
        $result = $store->remove($this->mock_perm, $value);
        $this->assertTrue($result);

    }
*/

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

    protected function whenDbExpectsInsert($table, $subject, $object, $perm)
    {
        $this->mock_db->expects($this->once())
            ->method('insert')
            ->with($table, ['subject' => $subject, 'object' => $object, 'value' => $perm]);
    }

    protected function whenDbExpectsDelete($table, $subject, $object, $perm)
    {
        $this->mock_db->expects($this->once())
            ->method('delete')
            ->with($table, ['subject' => $subject, 'object' => $object, 'value' => $perm]);
    }

    protected function whenDbExpectsDeleteAll($table, $subject, $object)
    {
        $this->mock_db->expects($this->once())
            ->method('delete')
            ->with($table, ['subject' => $subject, 'object' => $object]);
    }
}
