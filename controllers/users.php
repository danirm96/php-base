<?php

namespace Controllers;
use core\classes\BaseController;
use Models\User;

class Users extends BaseController {
    public $private = true;
    private $model;
    protected $model_name = 'User';
    
    public function __construct() {
        parent::__construct();
        $this->model = new User();
    }

}

?>