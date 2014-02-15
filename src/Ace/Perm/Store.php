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

        $perms = [];
        foreach($results as $result){
            // remove any duplicates
            $perms [$result['value']]= $result['value'];
        }
        $perm = new Perm($subject, $object, $perms);
        return $perm;
    }

    public function getAllForSubject($subject)
    {
        $sql = "SELECT * FROM perm WHERE subject = ?";
        $options = [$subject];
        $results = $this->fetchAll($sql, $options);

        // construct multiple Perm class instances, 1 per unique object in 
        $perms = [];
        foreach ($results as $result) {
            if (!isset($perms[$result['object']])){
                $perms[$result['object']] = [];
            }
            $perms[$result['object']][$result['value']] = $result['value'];
        }

        $perm_objects = [];
        foreach ($perms as $object => $perm){
            $perm_objects []= new Perm($subject, $object, $perm);
        }
        return $perm_objects;
    }

    public function getAllForSubjectWithPerm($subject, $perm)
    {

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
}
