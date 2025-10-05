<?php 
extract($data);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <script src="<?php echo BASE_URL; ?>public/js/main.js" defer></script>

    <?php
    if(isset($css) && !empty($css)) {
        foreach($css as $cssFile) {
            echo '<link rel="stylesheet" href="' . BASE_URL . 'public/css/' . $cssFile . '">';
        }
    }

    if(isset($js) && !empty($js)) {
        foreach($js as $jsFile) {
            echo '<script src="' . BASE_URL . 'public/js/' . $jsFile . '" defer></script>';
        }
    }
    ?>
    <div id="notices">
        <?php if(!empty($error)) {
            echo '<div class="notice error">' . $error . '</div>';
        } ?>
    </div>
</head>
<body>
    