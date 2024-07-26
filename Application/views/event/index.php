<?php
namespace Application\views\event;

require_once __DIR__ . '/../../models/Events.php';

use Application\models\Events;

$title = 'Eventos';
ob_start();

// Iniciar a sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Eventos</h1>
    <div class="container mx-auto p-4">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') : ?>
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
                    <th class="py-2 px-4 border-b">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($events) && !empty($events)) : ?>
                    <?php foreach ($events as $event) : ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($event['no_event']); ?></td>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') : ?>
                                <td class="py-2 px-4 border-b">
                                    <a href="/event/edit/<?php echo htmlspecialchars($event['id']); ?>"
                                       class="text-blue-600 hover:underline">Editar</a>
                                    <a href="/event/delete/<?php echo htmlspecialchars($event['id']); ?>"
                                       class="text-red-600 hover:underline">Excluir</a>
                                </td>
                            <?php else : ?>
                                <td class="py-2 px-4 border-b">
                                    <?php if ($event['is_subscribed'] == 1) : ?>
                                        <form action="/subscription/delete" method="POST" class="inline">
                                            <input type="hidden" name="event_id"
                                                   value="<?php echo htmlspecialchars($event['id']); ?>">
                                            <input type="hidden" name="user_id"
                                                   value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                                            <form class="inline">
                                                <button type="button"
                                                        class="text-red-600 hover:underline unsubscribe-link"
                                                        data-userid="<?php echo htmlspecialchars($_SESSION['user_id']); ?>"
                                                        data-eventid="<?php echo htmlspecialchars($event['id']); ?>"
                                                        style="background:none;border:none;color:inherit;text-decoration:underline;cursor:pointer;">
                                                    Cancelar Inscrição
                                                </button>
                                            </form>
                                        </form>
                                    <?php else : ?>
                                        <form action="/subscription/create" method="POST" class="inline">
                                            <input type="hidden" name="event_id"
                                                   value="<?php echo htmlspecialchars($event['id']); ?>">
                                            <input type="hidden" name="user_id"
                                                   value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                                            <form class="inline">
                                                <button type="button"
                                                        class="text-green-600 hover:underline subscribe-link"
                                                        data-userid="<?php echo htmlspecialchars($_SESSION['user_id']); ?>"
                                                        data-eventid="<?php echo htmlspecialchars($event['id']); ?>"
                                                        style="background:none;border:none;color:inherit;text-decoration:underline;cursor:pointer;">
                                                    Inscrever
                                                </button>
                                            </form>

                                        </form>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="py-2 px-4 border-b text-center">Nenhum evento encontrado</td>
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

<script>

    $(document).ready(function () {

        $('.subscribe-link').on('click', function (event) {
            event.preventDefault();
            var userId = $(this).data('userid');
            var eventId = $(this).data('eventid');
            if (confirm('Você deseja se inscrever neste evento?')) {
                subscribeToEvent(userId, eventId);
            }
        });

        $('.unsubscribe-link').on('click', function (event) {
            event.preventDefault();
            var userId = $(this).data('userid');
            var eventId = $(this).data('eventid');
            if (confirm('Você deseja cancelar sua inscrição neste evento?')) {
                unsubscribeFromEvent(userId, eventId);
            }
        });

        function subscribeToEvent(userId, eventId) {
            $.ajax({
                url: '/event/subscribe',
                type: 'POST',
                data: {
                    user_id: userId,
                    event_id: eventId
                },
                success: function (response) {
                    alert('Inscrição realizada com sucesso!');
                    location.reload();
                },
                error: function (response) {
                    alert('Erro ao realizar inscrição.');
                    console.log(response);
                }
            });
        }

        function unsubscribeFromEvent(userId, eventId) {
            $.ajax({
                url: '/event/unsubscribe',
                type: 'POST',
                data: {
                    user_id: userId,
                    event_id: eventId
                },
                success: function (response) {
                    alert('Inscrição cancelada com sucesso!');
                    location.reload();
                },
                error: function (response) {
                    alert('Erro ao cancelar inscrição.');
                    console.log(response);
                }
            });
        }
    });

</script>
