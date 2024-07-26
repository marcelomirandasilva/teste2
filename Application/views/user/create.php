<?php
$title = 'Usuários';
ob_start();
//session_start();

?>

<div class="container md:w-8/12 mt-5 mb-5">
    <h1 class="text-2xl font-bold mb-4 text-center">Adicionar Usuário</h1>
    <form action="/user/store" method="POST" class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <label for="no_user" class="block text-gray-700">Nome</label>
            <input type="text" name="no_user" id="no_user" class="border border-gray-300 p-2 w-full" required>
        </div>
        <!--<div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="border border-gray-300 p-2 w-full" required>
        </div>-->
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="border border-gray-300 p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label for="role_id" class="block text-gray-700">Role</label>
            <select name="role_id" id="role_id" class="border border-gray-300 p-2 w-full" required>
                <?php foreach ($roles as $role) : ?>
                    <option value=<?php echo $role['id'] ?>><?php echo htmlspecialchars($role['no_role']) ?></option>
                <?php endforeach; ?>


            </select>
        </div>
        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Criar
            </button>
        </div>
    </form>
</div>


<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
