<?php

require_once 'config/menu.php';
global $user;

?>

<header class="header border-b border-gray-200">
    <div class="welcome">
        <span class="user-name font-bold">Hola, <?php echo htmlspecialchars($user['name']); ?></span>
    </div>
    <div class="user-menu">
        <?php if (isset($user)): ?>
            <a href="/logout" class="logout-button">Cerrar sesión</a>
        <?php else: ?>
            <a href="/login" class="login-button">Iniciar sesión</a>
        <?php endif; ?>
    </div>
</header>