<?php namespace Ace\Perm;

use Ace\Perm\Perm;

/**
 * The interface to get Perm instances by certain criteria
*/
interface StoreInterface
{
    /**
    * get the Perm instance for this Subject Object pair
    * @return \Ace\Perm\Perm
    */
    public function get($subject, $object);
    
    /**
    * Get all Perm instances for this Subject
    * @return array of Ace\Perm\Perm instances
    */
    public function getAllForSubject($subject);

    /**
    * Get all Perm instances for this Subject which contain perm name
    * @return array of Ace\Perm\Perm instances
    */
    public function getAllForSubjectWithPerm($subject, $perm);
    
    /**
    * Get all Perm instances for this Object
    * @return array of Ace\Perm\Perm instances
    */
    public function getAllForObject($object);

    /**
    * Get all Perm instances for this Object which contain perm name
    * @return array of Ace\Perm\Perm instances
    */
    public function getAllForObjectWithPerm($object, $perm);

    /**
    * Updates store of Perm data
     *
    * adds any added perm names
    * removes any removed perm names
    */
    public function update($data);

    /**
    * Removes the Perm instance from the store 
    * ie. remove all perm names for a Subject Object pair
    */
    public function remove($data);
}
