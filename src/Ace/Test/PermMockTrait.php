<?php namespace Ace\Test;

trait PermMockTrait
{
    protected $mock_subject;
    protected $mock_object;
    protected $mock_perm;

    protected function givenAMockPerm(array $perms = [])
    {
        $this->mock_perm = $this->getMock('Ace\Perm\Perm', ['getSubject', 'getObject', 'removed'], [$this->subject, $this->object]);
        $this->mock_perm->expects($this->any())
            ->method('getSubject')
            ->will($this->returnValue($this->subject));
        $this->mock_perm->expects($this->any())
            ->method('getObject')
            ->will($this->returnValue($this->object));
    }
}
