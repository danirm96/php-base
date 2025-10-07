<div class="head-page flex justify-between items-center mb-4 align-center">
    <h1 class="text-3xl">Lista de <?php echo $plural; ?></h1>
    <a href="/users/new" class="d-block bg-black text-[16px] px-4 py-2 mb-4 inline-block btn">Crear Usuario</a>  
</div>

<form method="GET" action="">
    <div class="mb-4 flex space-x-2 items-center ">
        <input type="text" name="search" placeholder="Buscar..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
            class="border border-gray-300 px-3 bg-white py-2 w-6/7 h-10">
        <button type="submit" class="bg-tomato text-white text-[16px] px-4 py-2 w-1/7 h-10">Buscar</button>
    </div>
</form>
<?php

table(
    $head,
    $rows,
    $current_page,
    $pages,
    $limit,
    $actions,
    $primary_key
);
