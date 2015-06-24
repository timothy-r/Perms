<?php

use Ace\Perm\Store\RDBMSStore;
use Ace\Perm\Perm;
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
        
        $store = new RDBMSStore($this->mock_db);

        $perm = $store->get($this->subject, $this->object);
        $this->assertInstanceOf('Ace\Perm\Perm', $perm);
        
        $expected = array_unique($values);
        $this->assertSame($expected, $perm->all());
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
        
        $store = new RDBMSStore($this->mock_db);

        $perm = $store->get($this->subject, $this->object);
    }
    
    /**
    * @expectedException Ace\Perm\NotFoundException
    */
    public function testGetForSubjectThrowsExceptionForMissingSubject()
    {
        $rows = [];

        $expected_sql = 
            "SELECT * FROM perm WHERE subject = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForSubject($this->subject);
    }

    /**
    * @dataProvider getPermValues
    */
    public function testGetForSubjectReturnsMultiplePerms($values)
    {
        $rows = [];
        foreach ($values as $value){
            $rows[]= [
                'subject' => $this->subject,
                'object' => $this->object . '-' . rand(1, 100000),
                'value' => $value
            ];
        }
        $expected_sql = 
            "SELECT * FROM perm WHERE subject = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForSubject($this->subject);
        $this->assertSame(count($values), count($perms));
        foreach($perms as $perm){
            $this->assertInstanceOf('Ace\Perm\Perm', $perm);
        }
    }

    /**
    * @dataProvider getPermValues
    */
    public function testGetForSubjectReturnsSinglePerm($values)
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
            "SELECT * FROM perm WHERE subject = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForSubject($this->subject);
        $this->assertSame(1, count($perms));
        $perm = current($perms);
        $this->assertInstanceOf('Ace\Perm\Perm', $perm);
        $this->assertSame(array_unique($values), $perm->all());
    }

    /**
    * @expectedException Ace\Perm\NotFoundException
    */
    public function testGetAllForSubjectWithPermThrowsExceptionForMissingSubject()
    {
        $perm = 'admin';
        $rows = [];

        $expected_sql = 
            "SELECT * FROM perm WHERE subject = ? AND value = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForSubjectWithPerm($this->subject, $perm);
    }

    public function testGetAllForSubjectWithPermReturnsSinglePerm()
    {
        $value = 'admin';
        $rows = [];
        $rows[] = ['object' => $this->object, 'subject' => $this->subject, 'value' => $value];

        $expected_sql = 
            "SELECT * FROM perm WHERE subject = ? AND value = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForSubjectWithPerm($this->subject, $value);
        $this->assertSame(1, count($perms));
        $perm = current($perms);
        $this->assertTrue($perm->has($value));
    }

    public function testGetAllForSubjectWithPermReturnsMultiplePerms()
    {
        $value = 'admin';
        $rows = [];
        $rows[] = ['object' => $this->object, 'subject' => $this->subject, 'value' => $value];
        // intentionally return the same data in two rows to test for de-duplication
        $rows[] = ['object' => $this->object, 'subject' => $this->subject, 'value' => $value];
        $rows[] = ['object' => $this->object . '-' . rand(1,100000), 'subject' => $this->subject, 'value' => $value];

        $expected_sql = 
            "SELECT * FROM perm WHERE subject = ? AND value = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForSubjectWithPerm($this->subject, $value);
        $this->assertSame(2, count($perms));
        foreach($perms as $perm){
            $this->assertTrue($perm->has($value));
        }
    }

    /**
    * @expectedException Ace\Perm\NotFoundException
    */
    public function testGetForObjectThrowsExceptionForMissingSubject()
    {
        $rows = [];

        $expected_sql = 
            "SELECT * FROM perm WHERE object = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForObject($this->object);
    }

    /**
    * @dataProvider getPermValues
    */
    public function testGetForObjectReturnsMultiplePerms($values)
    {
        $rows = [];
        foreach ($values as $value){
            $rows[]= [
                'subject' => $this->subject . '-' . rand(1,100000),
                'object' => $this->object, 
                'value' => $value
            ];
        }
        $expected_sql = 
            "SELECT * FROM perm WHERE object = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForObject($this->object);
        $this->assertSame(count($values), count($perms));
        foreach($perms as $perm){
            $this->assertInstanceOf('Ace\Perm\Perm', $perm);
        }
    }

    /**
    * @expectedException Ace\Perm\NotFoundException
    */
    public function testGetAllForObjectWithPermThrowsExceptionForMissingObject()
    {
        $perm = 'admin';
        $rows = [];

        $expected_sql = 
            "SELECT * FROM perm WHERE object = ? AND value = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForObjectWithPerm($this->object, $perm);
    }

    public function testGetAllForObjectWithPermReturnsSinglePerm()
    {
        $value = 'admin';
        $rows = [];
        $rows[] = ['object' => $this->object, 'subject' => $this->subject, 'value' => $value];

        $expected_sql = 
            "SELECT * FROM perm WHERE object = ? AND value = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForObjectWithPerm($this->object, $value);
        $this->assertSame(1, count($perms));
        $perm = current($perms);
        $this->assertTrue($perm->has($value));
    }

    public function testGetAllForObjectWithPermReturnsMultiplePerms()
    {
        $value = 'admin';
        $rows = [];
        $rows[] = ['object' => $this->object, 'subject' => $this->subject, 'value' => $value];
        // intentionally return the same data in two rows to test for de-duplication
        $rows[] = ['object' => $this->object, 'subject' => $this->subject, 'value' => $value];
        $rows[] = ['object' => $this->object, 'subject' => $this->subject . '-' .rand(1,100000), 'value' => $value];

        $expected_sql = 
            "SELECT * FROM perm WHERE object = ? AND value = ?";
        
        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $rows);
        
        $store = new RDBMSStore($this->mock_db);

        $perms = $store->getAllForObjectWithPerm($this->object, $value);
        $this->assertSame(2, count($perms));
        foreach($perms as $perm){
            $this->assertTrue($perm->has($value));
        }
    }

    public function testUpdateRemovesAPerm()
    {
        $value = 'write';

        $table = 'perm';
        $this->givenAMockDb();
        $this->whenDbExpectsDelete($table, $this->subject, $this->object, $value);

        $this->givenAMockPerm([$value]);
        $this->mock_perm->expects($this->any())
            ->method('removed')
            ->will($this->returnValue([$value]));

        $store = new RDBMSStore($this->mock_db);
        $result = $store->update($this->mock_perm);
        $this->assertTrue($result);
    }
    
    public function testUpdateAddsAPermObject()
    {
        $value = 'read';
        $table = 'perm';
        $this->givenAMockDb();
        $this->whenDbExpectsInsert($table, $this->subject, $this->object, $value);

        $store = new RDBMSStore($this->mock_db);
        $perm = new Perm($this->subject, $this->object);
        $perm->add($value);

        $result = $store->update($perm);

        $this->assertTrue($result);
    }
    
    public function testNoUpdatesWithoutAddsToAPermObject()
    {
        $this->givenAMockDb();
        $this->whenDbDoesNotExpectInsert();

        $store = new RDBMSStore($this->mock_db);
        $perm = new Perm($this->subject, $this->object);

        $result = $store->update($perm);

        $this->assertTrue($result);
    }

    public function testUpdateDeletesRemovedPerms()
    {
        $value = 'read';
        $table = 'perm';
        $this->givenAMockDb();
        $this->whenDbDoesNotExpectInsert();
        $this->whenDbExpectsDelete($table, $this->subject, $this->object, $value);

        $store = new RDBMSStore($this->mock_db);
        $perm = new Perm($this->subject, $this->object, [$value]);
        $perm->remove($value);

        $result = $store->update($perm);

        $this->assertTrue($result);
    }

    public function testUpdateDoesNotAddPreExistingNames()
    {
        $value = 'read';
        $table = 'perm';
        $this->givenAMockDb();
        $this->whenDbExpectsInsert($table, $this->subject, $this->object, $value);

        $store = new RDBMSStore($this->mock_db);
        $perm = new Perm($this->subject, $this->object, ['other']);
        $perm->add($value);

        $result = $store->update($perm);

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

    protected function whenDbExpectsInsert($table, $subject, $object, $perm)
    {
        $this->mock_db->expects($this->once())
            ->method('insert')
            ->with($table, ['subject' => $subject, 'object' => $object, 'value' => $perm]);
    }

    protected function whenDbDoesNotExpectInsert()
    {
        $this->mock_db->expects($this->never())
            ->method('insert');
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
