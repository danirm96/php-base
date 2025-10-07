<?php

if(!isset($model)) {
    die('Model not defined');
}

switch($type) {
    case 'create':
        $name = 'Crear ' . $model->label;
        $readonly = '';
        $disabled = '';
        break;
    case 'edit':
        $name = 'Editar ' . $model->label;
        $readonly = '';
        $disabled = '';
        break;
    case 'view':
        $name = 'Ver ' . $model->label;
        $readonly = 'readonly';
        $disabled = 'disabled';
        break;
    default:
        die('Type not defined');
}


?>

<div class="head-page flex justify-between items-center mb-4 align-center">
    <h1 class="text-3xl"><?php echo  $name; ?></h1>
    <a href="/users/list" class="d-block bg-black text-[16px] px-4 py-2 mb-4 inline-block btn">Volver a la Lista</a>
</div>

<?php

form(
    $data,
    $model,
    $type
);