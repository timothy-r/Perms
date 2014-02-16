<?php namespace Ace\Perm;

use Ace\Perm\StoreInterface;
use Ace\Perm\Perm;
use Ace\Perm\NotFoundException;
use Doctrine\DBAL\Connection;

class Store implements StoreInterface
{
    private $db;
    
    private $table = 'perm';

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function get($subject, $object)
    {
        $sql = "SELECT * FROM perm WHERE subject = ? AND object = ?";
        $options = [$subject, $object];
        $results = $this->fetchAll($sql, $options);
        $perms = $this->resultsToPerms($results);
        return current($perms);
    }

    public function getAllForSubject($subject)
    {
        $sql = "SELECT * FROM perm WHERE subject = ?";
        $options = [$subject];
        $results = $this->fetchAll($sql, $options);
        return $this->resultsToPerms($results);
    }

    public function getAllForSubjectWithPerm($subject, $perm)
    {
        $sql = "SELECT * FROM perm WHERE subject = ? AND value = ?";
        $options = [$subject, $perm];
        $results = $this->fetchAll($sql, $options);
        return $this->resultsToPerms($results);
    }

    public function getAllForObject($object)
    {
        $sql = "SELECT * FROM perm WHERE object = ?";
        $options = [$object];
        $results = $this->fetchAll($sql, $options);
        return $this->resultsToPerms($results);
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

    private function fetchAll($sql, $options)
    {
        $results = $this->db->fetchAll($sql, $options);
        
        if (!count($results)){
            throw new NotFoundException;
        }
        return $results;
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
