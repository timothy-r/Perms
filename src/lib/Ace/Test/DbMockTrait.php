<?php namespace Ace\Test; 
/**
 * @author timrodger
 * Date: 17/05/15
 */
trait DbMockTrait
{
    protected $mock_db;

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