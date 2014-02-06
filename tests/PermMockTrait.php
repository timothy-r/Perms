<?php 

trait PermMockTrait
{
    protected $mock_subject;
    protected $mock_object;

    protected function givenAMockSubject($id = 'x', $type = 'User')
    {
        $this->mock_subject = $this->getMock('Ace\Perm\SubjectInterface');
        $this->mock_subject->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($id));
        $this->mock_subject->expects($this->once())
            ->method('getType')
            ->will($this->returnValue($type));
    }

    protected function givenAMockObject($id = 'x', $type = 'User')
    {
        $this->mock_object = $this->getMock('Ace\Perm\ObjectInterface');
        $this->mock_object->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($id));
        $this->mock_object->expects($this->once())
            ->method('getType')
            ->will($this->returnValue($type));
    }

}
