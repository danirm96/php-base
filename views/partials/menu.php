<?php
global $menu, $user;
?>

<nav>
    <ul class="menu">
        <?php foreach ($menu as $title => $item): ?>
            <?php
            if ($item['private'] && !isset($user)) {
                continue;
            }
            if (isset($item['roles']) && !in_array('*', $item['roles']) && isset($user) && !in_array($user['role'], $item['roles'])) {
                continue;
            }
            ?>
            <li>
                <a href="<?php echo $item['url']; ?>">
                    <?php if (isset($item['icon'])): ?>
                        <ion-icon name="<?php echo $item['icon']; ?>"></ion-icon>
                    <?php endif; ?>
                    <?php echo $title; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>