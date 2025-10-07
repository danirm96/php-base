<main>
    <div class="flex items-center justify-center min-h-screen">
        <form action="/access" method="POST" class="bg-white p-8 rounded shadow-md w-full max-w-sm">
            <h1 class="text-2xl font-bold mb-6 text-center">Inicia sesión</h1>
            <div class="mb-6">
                <label for="email" class="block text-gray-700 mb-2">Correo electrónico</label>
                <input type="email" id="email" name="email" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo $email ?? ''; ?>" autofocus>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 mb-2">Contraseña</label>
                <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo $password ?? ''; ?>">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Iniciar sesión</button>
        </form>
    </div>
</main>