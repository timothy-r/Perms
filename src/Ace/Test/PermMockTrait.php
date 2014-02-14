<?php namespace Ace\Test;

trait PermMockTrait
{
    protected $mock_subject;
    protected $mock_object;
    protected $mock_perm;

    protected function givenAMockSubject($id = 'x', $type = 'User')
    {
        $this->mock_subject = $this->getMock('Ace\Perm\SubjectInterface');
        $this->mock_subject->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        $this->mock_subject->expects($this->any())
            ->method('getType')
            ->will($this->returnValue($type));
    }

    protected function givenAMockObject($id = 'x', $type = 'User')
    {
        $this->mock_object = $this->getMock('Ace\Perm\ObjectInterface');
        $this->mock_object->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        $this->mock_object->expects($this->any())
            ->method('getType')
            ->will($this->returnValue($type));
    }

    protected function givenAMockPerm(array $perms = [])
    {
        $this->mock_perm = $this->getMock('Ace\Perm\Perm', ['getSubject', 'getObject'], [$this->subject, $this->object]);
        $this->mock_perm->expects($this->any())
            ->method('getSubject')
            ->will($this->returnValue($this->subject));
        $this->mock_perm->expects($this->any())
            ->method('getObject')
            ->will($this->returnValue($this->object));
    }
}
