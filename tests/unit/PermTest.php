<?php

use Ace\Perm\Perm;
use Ace\Test\UnitTest;

class PermTest extends UnitTest
{
    public function testOffsetGet()
    {
        $perm = new Perm(['read', 'write']);
        $actual = $perm['read'];
        $this->assertTrue($actual);
    }

    public function testOffsetGetReturnsNullForMissingItems()
    {
        $perm = new Perm(['read', 'write']);
        $actual = $perm['delete'];
        $this->assertNull($actual);
    }

    public function testOffsetExists()
    {
        $perm = new Perm(['read', 'write']);
        $actual = isset($perm['read']);
        $this->assertTrue($actual);
    }

    public function testOffsetExistsReturnsFalseForMissingKeys()
    { 
        $perm = new Perm(['read', 'write']);
        $actual = isset($perm['admin']);
        $this->assertFalse($actual);
    }

}
