<?php

namespace Controllers;
use core\classes\BaseController;

class DashboardController extends BaseController {

    public function index($params) {
        charge_layout('general', ['dashboard/index'], ['title' => 'Dashboard']);
    }

}
