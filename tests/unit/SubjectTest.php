<?php

use Ace\Perm\Subject;
use Ace\Test\UnitTest;

class SubjectTest extends UnitTest
{
    public function testIdentity()
    {
        $id = '1';
        $subject = new Subject($id);
        $actual = $subject->getId();
        $this->assertSame($id, $actual);
    }
}
