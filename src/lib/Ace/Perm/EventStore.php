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

    private function fetchPerms($subject = null, $object = null, $key = null)
    {
        $events = $this->runQuery($subject, $object, $key);

        if (!count($events)){
            throw new NotFoundException;
        }

        return $this->eventsToPerms($events);
    }

    private function eventsToPerms($results)
    {

    }

    private function runQuery($subject, $object, $key)
    {
        $sql = [];
        $options = [];
        $vars = ['subject', 'object', 'key'];
        foreach ($vars as $var) {
            if (!is_null($$var)){
                $sql []= "$var = ?";
                $options []= $$var;
            }
        }

        // order results by the time field so they can be iterated over in the order they happened
        // (lowest timestamps first)
        $sql_string = 'SELECT * FROM events WHERE ' . implode(' AND ', $sql) . ' ORDER BY time ASC';
        return $this->db->fetchAll($sql_string, $options);
    }
}
