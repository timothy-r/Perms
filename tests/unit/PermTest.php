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

        $actual = $perm->allPerms();

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
    
    public function testCanRemovePermName()
    {
        $name = 'dominate';
        $perm = new Perm($this->subject, $this->object, [$name]);
        $actual = $perm->hasPerm($name);
        $this->assertTrue($actual);

        $perm->remove($name);

        $actual = $perm->hasPerm($name);
        $this->assertFalse($actual);
    }
}
