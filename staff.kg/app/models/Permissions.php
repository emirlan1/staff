<?php
namespace Staff\Models;

use MongoDB\BSON\Timestamp;
use Phalcon\Mvc\Model;

class Permissions extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $profilesId;

    /**
     *
     * @var string
     */
    public $resource;

    /**
     *
     * @var string
     */
    public $action;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'profilesId' => 'profilesId', 
            'resource' => 'resource', 
            'action' => 'action'
        );
    }

}
