<?php

use Ace\Perm\Perm;
use Ace\Test\UnitTest;
require_once(__DIR__.'/../PermMockTrait.php');

class PermTest extends UnitTest
{
    use PermMockTrait;
    
    public function setUp()
    {
        parent::setUp();
        $this->givenAMockSubject();
        $this->givenAMockObject();
    }

    public function testHasPermReturnsTrueForSetPerms()
    {
        $perm = new Perm($this->mock_subject, $this->mock_object, ['read', 'write']);
        $actual = $perm->hasPerm('read');
        $this->assertTrue($actual);
    }

    public function testHasPermReturnsFalseForUnsetPerms()
    {
        $perm = new Perm($this->mock_subject, $this->mock_object, ['read', 'write']);
        $actual = $perm->hasPerm('delete');
        $this->assertFalse($actual);
    }

}
