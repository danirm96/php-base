<?php

namespace core\classes;

abstract class Auth {
    abstract public function canAccess($roles = []): bool;
}

?>