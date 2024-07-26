<h1 class="text-2xl font-bold mb-6 text-center">Home</h1>

<?php

use Application\models\UserSession;


$content = ob_get_clean();
$title = "HOME";
require __DIR__ . '/../layouts/layout.php';

//var_dump($_SESSION['usuario_logado']);


?>
