<?php

use Ace\Perm\EventStore;
use Ace\Perm\Perm;
use Ace\Test\UnitTest;
use Ace\Test\PermMockTrait;
use Ace\Test\DbMockTrait;

class EventStoreTest extends UnitTest
{
    use DbMockTrait;

    public function getEventData()
    {
        return [
            [[
                ['user:1', 'article:abc', 'read', 1233, 'grant'],
                ['user:1', 'article:abc', 'read', 3346, 'revoke'],
                ['user:1', 'article:abc', 'write', 1111, 'grant']
            ]],
        ];
    }

    /**
     * @dataProvider getEventData
     * @param $values
     */
    public function testGetReturnsAPermObject($values)
    {
        $expected_sql =
            "SELECT * FROM events WHERE subject = ? AND object = ?";

        $this->givenAMockDb();
        $this->whenDbContains($expected_sql, $values);

        $store = new EventStore($this->mock_db);

        $perm = $store->get('user:1', 'article:abc');
        $this->assertInstanceOf('Ace\Perm\Perm', $perm);

    }
}