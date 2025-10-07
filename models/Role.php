<?php
namespace Models;
use core\classes\BaseModel;

class Role extends BaseModel {

    private $name;
    private $description;

    public $table = 'roles';

    public function __construct() {
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function columns() {
        return
        array(
        'id' => array(
            'type' => 'int',
            'required' => true,
            'show_in_list' => true,
            'auto_increment' => true
        ),
        'name' => array(
            'type' => 'varchar',
            'max_length' => 50,
            'required' => true,
            'show_in_list' => true

        ),
         'description' => array(
            'type' => 'text',
            'required' => false,
            'show_in_list' => true

        ));
    }

    public function getRoles($where = '', $order = '', $limit = 10, $page = 1) {
        $model = new Role();
        return $model->all($where, $order, $limit, $page);
    }

}