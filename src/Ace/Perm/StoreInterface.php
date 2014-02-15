<?php namespace Ace\Perm;

use Ace\Perm\Perm;

/**
* @todo update to use the Perm class throughout
*/
interface StoreInterface
{
    /**
    * get the Perm instance for this Subject Object pair
    * @return Ace\Perm\Perm
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
    * Stores Perm's state
    * adds any added perm names
    * removes any removed perm names
    */
    public function update(Perm $perm);

    /**
    * Removes the Perm instance from the store 
    * ie. remove all perm names for a Subject Object pair
    */
    public function remove(Perm $perm);
}
