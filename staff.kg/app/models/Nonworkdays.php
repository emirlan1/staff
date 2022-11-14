<?php
namespace Staff\Models;

use MongoDB\BSON\Timestamp;
use Phalcon\Mvc\Model;
//use Phalcon\Db\Adapter\Pdo\Factory;
//use Phalcon\Di;
//use Phalcon\Mvc\Model\Manager as ModelsManager;

class Nonworkdays extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var string
     */
    public $name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('NonWorkDays');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'date' => 'date', 
            'name' => 'name',
            'to_repeat' => 'to_repeat'
        );
    }

}
