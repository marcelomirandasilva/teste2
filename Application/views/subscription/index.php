<?php
$title = 'Inscrições';
ob_start();
//session_start();
?>

<div class="container mx-auto p-4">


    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4 text-center">Inscrições</h1>

        <div class="container mx-auto p-4">
            <?php if ($_SESSION['role'] == 'Admin') : ?>
                <div class="text-right mb-4">
                    <a href="/event/create" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                        Adicionar Novo Evento
                    </a>
                </div>
            <?php endif; ?>

            <div class="container md:w-8/12 mt-5 mb-5">
                <table id="eventsTable"
                       class="min-w-full mt-4 bg-white border border-gray-200 table table-hover table-striped compact responsive display nowrap cellspacing=0 width=100%">
                    <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Evento</th>
                        <th class="py-2 px-4 border-b">Usuários Inscritos</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($events) && !empty($events)) : ?>
                        <?php foreach ($events as $event) : ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($event['no_event']); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <?php if (!empty($event['subscriptions'])) : ?>
                                        <ul>
                                            <?php foreach ($event['subscriptions'] as $subscription) : ?>
                                                <li><?php echo htmlspecialchars($subscription['no_user']); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else : ?>
                                        Nenhum usuário inscrito.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="2" class="py-2 px-4 border-b text-center">Nenhum evento encontrado</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <?php
    $content = ob_get_clean();
    include __DIR__ . '/../layouts/layout.php';
    ?>
</div>