<?php namespace Ace\Perm;

use Ace\Perm\StoreInterface;
use Ace\Perm\Perm;
use Doctrine\DBAL\Connection;

class Store implements StoreInterface
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function get($subject, $object)
    {
        $sql = "SELECT * FROM perm WHERE subject_id = ? AND object_id = ?";
        $options = [$subject, $object];
        $results = $this->db->fetchAll($sql, $options);
        $perms = [];
        foreach($results as $result){
            // remove any duplicates
            $perms [$result['value']]= $result['value'];
        }
        $perm = new Perm($subject, $object, $perms);
        return $perm;
    }

    public function add($subject, $object, $value)
    {
        $table = 'perm';
        $options = ['subject_id' => $subject, 'object_id' => $object, 'value' => $value];
        $types = ['text', 'text', 'text', 'text', 'text'];
        $result = $this->db->insert($table, $options, $types);
        return true;
    }

    public function remove(Perm $perm, $value)
    {
        $table = 'perm';
        $subject = $perm->getSubject();
        $object = $perm->getObject();
        $options = ['subject_id' => $subject, 'object_id' => $object, 'value' => $value];
        $result = $this->db->delete($table, $options);
        return true;
    }
}
