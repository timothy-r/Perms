<?php

use Ace\Perm\Subject;
use Ace\Test\UnitTest;

class SubjectTest extends UnitTest
{
    public function testGetIdentity()
    {
        $id = '1';
        $type = 'user';
        $subject = new Subject($id, $type);
        $actual = $subject->getId();
        $this->assertSame($id, $actual);
    }

    public function testGetType()
    {
        $id = '1';
        $type = 'user';
        $subject = new Subject($id, $type);
        $actual = $subject->getType();
        $this->assertSame($type, $actual);
    }
}
