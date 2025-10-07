<?php

$roles = (new Role())->getRoles();

if(isset($type)) {
    
    switch ($type) {
        case 'edit':
            $readonly = '';
            $disabled = '';
            $name = 'Editar Usuario';
            break;
        case 'view':
            $readonly = 'readonly';
            $disabled = 'disabled';
            $name = 'Usuario';
            break;
        default:
            $readonly = '';
            $disabled = '';
            $name = 'Crear Usuario';
            $type = 'create';
            break;
    }
} else {
    $readonly = '';
    $disabled = '';
    $name = 'Crear Usuario';
    $type = 'create';
}

?>

<div class="head-page flex justify-between items-center mb-4 align-center">
    <h1 class="text-3xl"><?php echo  $name; ?></h1>
    <a href="/users/list" class="d-block bg-black text-[16px] px-4 py-2 mb-4 inline-block btn">Volver a la Lista</a>
</div>

<form method="POST" action="/users/new" class="w-full bg-white p-6 rounded shadow flex flex-row flex-wrap" type="<?php echo  $type ?>">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id'] ?? ''); ?>">
    <div class="w-1/2 p-2">
        <label for="name" class="block text-gray-700 font-bold mb-2">Nombre:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required <?php echo  $readonly . " " . $disabled; ?> class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>
    <div class="w-1/2 p-2">
        <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required <?php echo  $readonly . " " . $disabled; ?> class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>
    <div class="w-1/2 p-2 <?php echo  isset($type) && $type === 'view' ? 'hidden' : ''; ?>">
        <label for="password" class="block text-gray-700 font-bold mb-2">Contraseña:</label>
        <input type="password" id="password" name="password" <?php echo  isset($type) && $type === 'edit' ? '' : 'required'; ?> <?php echo  $readonly . " " . $disabled; ?> class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>
    <div class="w-1/2 p-2 <?php echo  isset($type) && $type === 'view' ? 'hidden' : ''; ?>">
        <label for="repPassword" class="block text-gray-700 font-bold mb-2">Repetir Contraseña:</label>
        <input type="password" id="repPassword" name="repPassword" <?php echo  isset($type) && $type === 'edit' ? '' : 'required'; ?> <?php echo  $readonly . " " . $disabled; ?> class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>
    <div class="w-1/2 p-2">
        <label for="role" class="block text-gray-700 font-bold mb-2">Rol:</label>
        <select id="role" name="role" required class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300" <?php echo  $disabled; ?>>
            <?php foreach ($roles as $role): ?>
                <option value="<?php echo $role['id']; ?>" <?php echo (isset($user['role']) && $user['role'] == $role['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($role['description']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="w-1/2 p-2">
        <label for="status" class="block text-gray-700 font-bold mb-2">Estado:</label>
        <select id="status" name="status" required class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300" <?php echo  $disabled; ?>>
            <option value="0" <?php echo (isset($user['status']) && $user['status'] == 0) ? 'selected' : ''; ?>>Activo</option>
            <option value="1" <?php echo (isset($user['status']) && $user['status'] == 1) ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </div>
    <div class="w-full p-2">
        <button type="submit" class="bg-tomato text-white text-[16px] px-4 py-2 rounded hover:bg-red-600"><?php echo  isset($type) && $type === 'edit' ? 'Guardar Usuario' : 'Crear Usuario'; ?></button>
    </div>
</form>

<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        const form = document.querySelector('form').getAttribute('type');
        if (form === 'edit') {
            return; // Skip password check on edit
        }
        const password = document.getElementById('password').value;
        const repPassword = document.getElementById('repPassword').value;
        if (password !== repPassword) {
            event.preventDefault();
            const $form = document.querySelector('form');
            const innerHTML = `<div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">Las contraseñas no coinciden.</span>
            </div>`;
            $form.insertAdjacentHTML('afterbegin', innerHTML);
        }
    });
</script>