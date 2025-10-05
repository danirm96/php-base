<?php

namespace Controllers;
use Config\Controller;

class Dashboard extends Controller {
    public $private = true;

    public function index($params) {
        charge_view('dashboard/index', ['title' => 'Dashboard'], true);
    }

}

?>