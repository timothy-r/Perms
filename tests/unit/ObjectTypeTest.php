<?php

use Ace\Perm\ObjectType;
use Ace\Test\UnitTest;

class ObjectTypeTest extends UnitTest
{
    public function testGetIdentity()
    {
        $id = '1';
        $type = 'user';
        $subject = new ObjectType($id, $type);
        $actual = $subject->getId();
        $this->assertSame($id, $actual);
    }

    public function testGetType()
    {
        $id = '1';
        $type = 'user';
        $subject = new ObjectType($id, $type);
        $actual = $subject->getType();
        $this->assertSame($type, $actual);
    }
}
