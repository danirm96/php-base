<?php

namespace Controllers;
use core\classes\BaseController;

class Dashboard extends BaseController {
    public $private = true;

    public function index($params) {
        charge_layout('general', ['dashboard/index'], ['title' => 'Dashboard']);
    }

}

?>