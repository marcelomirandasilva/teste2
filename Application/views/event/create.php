<?php
$title = 'Eventos';
ob_start();
//session_start();

?>

<div class="container md:w-8/12 mt-5 mb-5">
    <h1 class="text-2xl font-bold mb-4 text-center">Adicionar Evento</h1>
    <form id="createEventForm" method="POST">
        <div class="form-group">
            <label for="no_event">Nome do Evento</label>
            <input type="text" class="form-control" id="no_event" name="no_event" required>
        </div>
        <button type="submit" class="btn btn-primary">Criar Evento</button>
    </form>
</div>


<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>

<script>
    $(document).ready(function () {
        $('#createEventForm').on('submit', function (event) {
            event.preventDefault();

            $.ajax({
                url: '/event/store',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    try {
                        const res = typeof response === 'string' ? JSON.parse(response) : response;

                        if (res.status === 'error') {
                            alert(res.message);
                        } else {
                            alert(res.message);
                            window.location.href = '/event/' + res.eventId;
                        }
                    } catch (e) {
                        console.error("Erro ao processar a resposta JSON:", e);
                        alert('Erro.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Erro na requisição AJAX:", xhr.responseText);
                    alert('Erro ao tentar salvar o Evento: ' + error);
                }
            });
        });
    });
</script>
