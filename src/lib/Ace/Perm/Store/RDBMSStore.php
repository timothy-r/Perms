<?php namespace Ace\Perm\Store;

use Ace\Perm\Store\StoreInterface;
use Ace\Perm\Perm;
use Ace\Perm\NotFoundException;
use Doctrine\DBAL\Connection;

/**
 * Class RDBMSStore
 * @package Ace\Perm\Store
 */
class RDBMSStore implements StoreInterface
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @var string
     */
    private $table = 'perm';

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function get($subject, $object)
    {
        $perms = $this->fetchPerms($subject, $object);
        return current($perms);
    }

    public function getAllForSubject($subject)
    {
        return $this->fetchPerms($subject);
    }

    public function getAllForSubjectWithPerm($subject, $perm)
    {
        return $this->fetchPerms($subject, null, $perm);
    }

    public function getAllForObject($object)
    {
        return $this->fetchPerms(null, $object, null);
    }

    public function getAllForObjectWithPerm($object, $perm)
    {
        return $this->fetchPerms(null, $object, $perm);
    }

    public function update(Perm $perm)
    {
        $types = ['text', 'text', 'text'];
        foreach ($perm->added() as $value){
            $options = ['subject' => $perm->subject(), 'object' => $perm->object(), 'value' => $value];
            $result = $this->db->insert($this->table, $options, $types);
        }

        foreach ($perm->removed() as $value){
            $options = ['subject' => $perm->subject(), 'object' => $perm->object(), 'value' => $value];
            $result = $this->db->delete($this->table, $options);
        }
        return true;
    }

    public function remove(Perm $perm)
    {
        $options = ['subject' => $perm->subject(), 'object' => $perm->object()];
        $result = $this->db->delete($this->table, $options);
        // @todo test result
        return true;
    }

    private function fetchPerms($subject = null, $object = null, $value = null)
    {
        $results = $this->runQuery($subject, $object, $value); 

        if (!count($results)){
            throw new NotFoundException;
        }
        return $this->resultsToPerms($results);
    }
    
    private function runQuery($subject, $object, $value)
    {
        $sql = [];
        $options = [];
        $vars = ['subject', 'object', 'value'];
        foreach ($vars as $var) {
            if (!is_null($$var)){
                $sql []= "$var = ?";
                $options []= $$var;
            }
        }

        $sql_string = 'SELECT * FROM perm WHERE ' . implode(' AND ', $sql);
        return $this->db->fetchAll($sql_string, $options);
    }

    private function resultsToPerms($results)
    {
        $perms = [];
        foreach ($results as $result) {
            if (!isset($perms[$result['subject']])){
                $perms[$result['subject']] = [];
            }
            if (!isset($perms[$result['subject']][$result['object']])){
                $perms[$result['subject']][$result['object']] = [];
            }
            $perms[$result['subject']][$result['object']][$result['value']] = $result['value'];
        }

        $perm_objects = [];
        foreach ($perms as $subject => $perm){
            foreach ($perm as $object => $values){
                $perm_objects []= new Perm($subject, $object, $values);
            }
        }
        return $perm_objects;
    }
}
