<?php


charge_partial('head');

foreach($views as $view) {
    charge_view($view, $data, false, false);
}

charge_partial('footer');