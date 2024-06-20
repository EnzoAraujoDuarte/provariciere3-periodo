$(document).ready(function() {
    fetchTasks();
    fetchCategories();

    $('#task-form').on('submit', function(e) {
        e.preventDefault();
        const id = $('#task-id').val();
        const title = $('#task-title').val().trim();
        const description = $('#task-description').val().trim();
        const completed = false;
        const category_id = $('#task-category').val();

        if (title === '' || description === '') {
            showNotification('error', 'Título e Descrição são obrigatórios.');
            return;
        }

        if (id) {
            updateTask(id, title, description, completed, category_id);
        } else {
            createTask(title, description, completed, category_id);
        }

        $('#task-form')[0].reset();
    });

    $(document).on('click', '.edit-task', function() {
        const id = $(this).data('id');
        getTask(id);
    });

    $(document).on('click', '.delete-task', function() {
        const id = $(this).data('id');
        deleteTask(id);
    });

    function fetchTasks() {
        $.ajax({
            url: 'api/TaskApi.php',
            method: 'GET',
            success: function(data) {
                $('#task-list').empty();
                data.records.forEach(task => {
                    $('#task-list').append(`
                        <li data-id="${task.id}" class="${task.completed ? 'completed' : ''}">
                            <span>${task.title}</span>
                            <button class="edit-task" data-id="${task.id}">Editar</button>
                            <button class="delete-task" data-id="${task.id}">Deletar</button>
                        </li>
                    `);
                });
            },
            error: function(xhr, status, error) {
                showNotification('error', "Erro ao buscar tarefas: " + xhr.responseText);
            }
        });
    }

    function fetchCategories() {
        $.ajax({
            url: 'api/TaskApi.php?categories=1',
            method: 'GET',
            success: function(data) {
                $('#task-category').empty();
                data.records.forEach(category => {
                    $('#task-category').append(`<option value="${category.id}">${category.name}</option>`);
                });
            },
            error: function(xhr, status, error) {
                showNotification('error', "Erro ao buscar categorias: " + xhr.responseText);
            }
        });
    }

    function createTask(title, description, completed, category_id) {
        $.ajax({
            url: 'api/TaskApi.php',
            method: 'POST',
            data: JSON.stringify({ title, description, completed, category_id }),
            contentType: 'application/json',
            success: function() {
                fetchTasks();
                showNotification('success', 'Tarefa criada com sucesso.');
            },
            error: function(xhr, status, error) {
                showNotification('error', "Erro ao criar tarefa: " + xhr.responseText);
            }
        });
    }

    function updateTask(id, title, description, completed, category_id) {
        $.ajax({
            url: 'api/TaskApi.php',
            method: 'PUT',
            data: JSON.stringify({ id, title, description, completed, category_id }),
            contentType: 'application/json',
            success: function() {
                fetchTasks();
                showNotification('success', 'Tarefa atualizada com sucesso.');
            },
            error: function(xhr, status, error) {
                showNotification('error', "Erro ao atualizar tarefa: " + xhr.responseText);
            }
        });
    }

    function deleteTask(id) {
        $.ajax({
            url: 'api/TaskApi.php',
            method: 'DELETE',
            data: JSON.stringify({ id }),
            contentType: 'application/json',
            success: function() {
                fetchTasks();
                showNotification('success', 'Tarefa deletada com sucesso.');
            },
            error: function(xhr, status, error) {
                showNotification('error', "Erro ao deletar tarefa: " + xhr.responseText);
            }
        });
    }

    function getTask(id) {
        $.ajax({
            url: 'api/TaskApi.php',
            method: 'GET',
            success: function(data) {
                const task = data.records.find(task => task.id == id);
                $('#task-id').val(task.id);
                $('#task-title').val(task.title);
                $('#task-description').val(task.description);
                $('#task-category').val(task.category_id);
            },
            error: function(xhr, status, error) {
                showNotification('error', "Erro ao buscar tarefa: " + xhr.responseText);
            }
        });
    }

    function showNotification(type, message) {
        const notification = $('<div class="notification ' + type + '">' + message + '</div>');
        $('body').append(notification);
        setTimeout(function() {
            notification.fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }
});
