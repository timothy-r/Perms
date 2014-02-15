<?php namespace Ace\Test;

trait PermMockTrait
{
    protected $mock_subject;
    protected $mock_object;
    protected $mock_perm;

    protected function givenAMockPerm(array $perms = [])
    {
        $this->mock_perm = $this->getMock('Ace\Perm\Perm', ['subject', 'object', 'removed'], [$this->subject, $this->object]);
        $this->mock_perm->expects($this->any())
            ->method('subject')
            ->will($this->returnValue($this->subject));
        $this->mock_perm->expects($this->any())
            ->method('object')
            ->will($this->returnValue($this->object));
    }
}
