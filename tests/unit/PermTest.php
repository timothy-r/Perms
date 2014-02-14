<?php

use Ace\Perm\Perm;
use Ace\Test\UnitTest;

class PermTest extends UnitTest
{
    protected $subject = 'user.103@accounts.com';
    protected $object = 'page.12166@content.net';

    public function testHasPermReturnsTrueForSetPerms()
    {
        $perm = new Perm($this->subject, $this->object, ['read', 'write']);
        $actual = $perm->hasPerm('read');
        $this->assertTrue($actual);
    }

    public function testHasPermReturnsFalseForUnsetPerms()
    {
        $perm = new Perm($this->subject, $this->object, ['read', 'write']);
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

}
