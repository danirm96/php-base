<?php

use Config\Model;

class Role extends Model {

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
        return array('name' => array(
            'type' => 'varchar',
            'max_length' => 50,
            'required' => true,
            'show_in_list' => true

        ),
         'description' => array(
            'type' => 'text',
            'required' => false,
            'show_in_list' => false

        ));
    }

}