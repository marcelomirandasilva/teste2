<?php
$title = 'Usuários';
ob_start();
//session_start();

?>

<div class="container md:w-8/12 mt-5 mb-5">
    <h1 class="text-2xl font-bold mb-4 text-center">Edição de Usuário</h1>
    <form action="/event/update" method="POST" class="bg-white p-6 rounded shadow-md">
        <input type="hidden" name="id" id="id" value=<?php echo $event['id'] ?>/>
        <div class="mb-4">
            <label for="no_event" class="block text-gray-700">Nome</label>
            <input type="text" name="no_event" id="no_event" class="border border-gray-300 p-2 w-full"
                   value=<?php echo htmlspecialchars($event['no_event']) ?>  required/>
        </div>


        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Salvar
            </button>
        </div>
    </form>
</div>


<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
