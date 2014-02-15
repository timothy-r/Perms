<?php

use Ace\Perm\Perm;
use Ace\Test\UnitTest;

class PermTest extends UnitTest
{
    protected $subject = 'user.103@accounts.com';
    protected $object = 'page.12166@content.net';

    public function testHasPermReturnsTrueForSetPerms()
    {
        $perms = ['read', 'write'];
        $perm = new Perm($this->subject, $this->object, $perms);

        $actual = $perm->hasPerm('read');

        $this->assertTrue($actual);
    }

    public function testHasPermReturnsFalseForUnsetPerms()
    {
        $perms = ['read', 'write'];
        $perm = new Perm($this->subject, $this->object, $perms);

        $actual = $perm->hasPerm('delete');

        $this->assertFalse($actual);
    }

    public function testAllPerms()
    {
        $perms = ['read', 'write'];
        $perm = new Perm($this->subject, $this->object, $perms);

        $actual = $perm->all();

        $this->assertSame($perms, $actual);
    }

    public function testCanAddPermName()
    {
        $name = 'dominate';
        $perm = new Perm($this->subject, $this->object, []);

        $perm->add($name);

        $actual = $perm->hasPerm($name);
        $this->assertTrue($actual);
    }
    
    public function testCanRemovePermNameThatExists()
    {
        $name = 'dominate';
        $perm = new Perm($this->subject, $this->object, [$name]);
        $actual = $perm->hasPerm($name);
        $this->assertTrue($actual);

        $perm->remove($name);

        $actual = $perm->hasPerm($name);
        $this->assertFalse($actual);
    }
    
    public function testCanRemovePermNameWhichDoesntExist()
    {
        $name = 'dominate';
        $perm = new Perm($this->subject, $this->object);

        $perm->remove($name);

        $actual = $perm->hasPerm($name);
        $this->assertFalse($actual);
    }

    public function testCanGetAddedPerms()
    {
        $name = 'master';
        $perm = new Perm($this->subject, $this->object, []);
        $perm->add($name);
        
        $actual = $perm->added();

        $this->assertSame([$name], $actual);
    }

    public function testCanGetRemovedPerms()
    {
        $name = 'master';
        $perm = new Perm($this->subject, $this->object, [$name]);
        $perm->remove($name);
        
        $actual = $perm->removed();

        $this->assertSame([$name], $actual);
    }

    public function testAnAddedPermIsNotRemoved()
    {
        $name = 'master';
        $perm = new Perm($this->subject, $this->object, [$name]);
        $perm->remove($name);
        $perm->add($name);

        $this->assertTrue(!in_array($name, $perm->removed()));
        $this->assertTrue(in_array($name, $perm->added()));
    }

    public function testARemovedPermIsNotAdded()
    {
        $name = 'master';
        $perm = new Perm($this->subject, $this->object);
        $perm->add($name);
        $perm->remove($name);

        $this->assertTrue(in_array($name, $perm->removed()));
        $this->assertTrue(!in_array($name, $perm->added()));
    }
}
