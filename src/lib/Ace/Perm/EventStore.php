<?php namespace Ace\Perm;

use Ace\Perm\StoreInterface;
use Ace\Perm\Perm;
use Ace\Perm\NotFoundException;
use Doctrine\DBAL\Connection;

/**
 * Class EventStore
 * Persists events
 */
class EventStore implements StoreInterface
{
    private $db;
    
    private $table = 'events';

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function get($subject, $object)
    {

    }

    public function getAllForSubject($subject)
    {
    }

    public function getAllForSubjectWithPerm($subject, $perm)
    {
    }

    public function getAllForObject($object)
    {
    }

    public function getAllForObjectWithPerm($object, $perm)
    {
    }

    public function update($data)
    {
    }

    public function remove($data)
    {
    }

    private function fetchPerms($subject = null, $object = null, $value = null)
    {
    }
    
    private function runQuery($subject, $object, $value)
    {
    }

    private function resultsToPerms($results)
    {
    }
}
