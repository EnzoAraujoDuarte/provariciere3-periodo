<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Tarefas</h1>
        <form id="task-form">
            <input type="hidden" id="task-id">
            <input type="text" id="task-title" placeholder="Título">
            <textarea id="task-description" placeholder="Descrição"></textarea>
            <select id="task-category">
                <!-- Opções de categorias serão preenchidas dinamicamente -->
            </select>
            <button type="submit">Salvar</button>
        </form>
        <ul id="task-list"></ul>
    </div>

    <div id="notification" title="Notification" style="display:none;">
        <p id="notification-message"></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
