<div class="template-general">
    <?php charge_partial('header'); ?>

    <div class="flex pt-[60px] min-h-screen">
        <div class="w-64 p-4 bg-white border-r border-gray-200 sidebar">
            <?php charge_partial('sidebar'); ?>
        </div>
        <div class="flex-1 p-4">
            <?php
            if (!empty($views)) {
                foreach ($views as $view) {

                    if (file_exists($view)) {
                        require_once $view;
                    } else {
                        echo "<h2>¡Ups! Parece que esta página no está disponible, puedes volver a <a href='" . BASE_URL . "'><span class='font-bold tomato'>la página de inicio</span></a>.</h2>";
                    }
                }
            } else {
                echo "<h2>¡Ups! Parece que esta página no está disponible, puedes volver a <a href='" . BASE_URL . "'><span class='font-bold tomato'>la página de inicio</span></a>.</h2>";
            }
            ?>
        </div>
    </div>
</div>