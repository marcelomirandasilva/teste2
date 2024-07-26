<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Aplicação'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 0.25rem;
            border: 1px solid #e5e7eb;
            color: #1f2937;
            background-color: #ffffff;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f3f4f6;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #3b82f6;
            color: #ffffff;
            border-color: #3b82f6;
        }
    </style>
</head>

<body class="bg-gray-100">
<header class="bg-blue-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Teste 2</h1>
        <nav>
            <?php if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado']) : ?>
                <a href="/home" class="text-white hover:underline">Home</a> |
                <a href="/users" class="text-white hover:underline">Usuários</a> |

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') : ?>
                    <a href="/events" class="text-white hover:underline">Eventos</a> |
                <?php else : ?>
                    <a href="/event/<?php echo htmlspecialchars($_SESSION['user_id']); ?>"
                       class="text-white hover:underline">Eventos</a> |
                <?php endif; ?>

                <a href="/subscriptions" class="text-white hover:underline">Inscrições</a> |
                <a href="/logout" class="text-white hover:underline">Logout</a>
            <?php else : ?>
                <!-- Não exibe nada se não estiver logado -->
            <?php endif; ?>
        </nav>
    </div>
</header>


<main class="container mx-auto p-4">
    <?php echo $content ?? ''; ?>
</main>

<!-- Scripts -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $('#usersTable, #eventsTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json',
        },
        responsive: true,
        stateSave: true,
    });
</script>
</body>

</html>