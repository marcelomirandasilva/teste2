<?php
$title = 'Usuários';
ob_start();
//session_start();
?>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Usuários</h1>

    <div class="container mx-auto p-4">
        <div class="text-right mb-4">
            <a href="/user/create" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                Adicionar Novo Usuário
            </a>
        </div>
        <div class="container md:w-8/12 mt-5 mb-5">

            <table id="usersTable"
                   class="min-w-full mt-4 bg-white border border-gray-200 table table-hover table-striped compact responsive display nowrap cellspacing=0 width=100%">
                <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Usuário</th>
                    <th class="py-2 px-4 border-b">Role</th>
                    <th class="py-2 px-4 border-b">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($users) && !empty($users)) : ?>
                    <?php foreach ($users as $user) : ?>
                        <tr>

                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($user['no_user']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($user['no_role']); ?></td>
                            <td class="py-2 px-4 border-b">
                                <a href="/user/edit/<?php echo htmlspecialchars($user['id']); ?>"
                                   class="text-blue-600 hover:underline">Editar</a>
                                <a href="/user/delete/<?php echo htmlspecialchars($user['id']); ?>"
                                   class="text-red-600 hover:underline">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="py-2 px-4 border-b text-center">Nenhum usuário encontrado</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    $content = ob_get_clean();
    include __DIR__ . '/../layouts/layout.php';
    ?>
</div>

