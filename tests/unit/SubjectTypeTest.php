<?php

use Ace\Perm\SubjectType;
use Ace\Test\UnitTest;

class SubjectTypeTest extends UnitTest
{
    public function testGetIdentity()
    {
        $id = '1';
        $type = 'user';
        $subject = new SubjectType($id, $type);
        $actual = $subject->getId();
        $this->assertSame($id, $actual);
    }

    public function testGetType()
    {
        $id = '1';
        $type = 'user';
        $subject = new SubjectType($id, $type);
        $actual = $subject->getType();
        $this->assertSame($type, $actual);
    }
}
