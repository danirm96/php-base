<?php

namespace Controllers;
use core\classes\BaseController;
use Models\User;

class UsersController extends BaseController {
    private $model;
    protected $model_name = 'User';
    
    public function __construct() {
        parent::__construct();
        $this->model = new User();
    }

}