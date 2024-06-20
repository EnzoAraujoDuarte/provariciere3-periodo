<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../classes/Database.php';
include_once '../classes/Task.php';
include_once '../classes/Category.php';

$database = new Database();
$db = $database->getConnection();
$task = new Task($db);
$category = new Category($db);

$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if (isset($_GET['categories'])) {
            $stmt = $category->read();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $categories_arr = array();
                $categories_arr["records"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $category_item = array(
                        "id" => $id,
                        "name" => $name
                    );

                    array_push($categories_arr["records"], $category_item);
                }
                http_response_code(200);
                echo json_encode($categories_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No categories found."));
            }
        } else {
            $stmt = $task->read();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $tasks_arr = array();
                $tasks_arr["records"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $task_item = array(
                        "id" => $id,
                        "title" => $title,
                        "description" => $description,
                        "completed" => $completed,
                        "category_id" => $category_id
                    );

                    array_push($tasks_arr["records"], $task_item);
                }
                http_response_code(200);
                echo json_encode($tasks_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No tasks found."));
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $task->title = $data->title;
        $task->description = $data->description;
        $task->completed = $data->completed;
        $task->category_id = $data->category_id;

        if($task->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Task created."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create task."));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $task->id = $data->id;
        $task->title = $data->title;
        $task->description = $data->description;
        $task->completed = $data->completed;
        $task->category_id = $data->category_id;

        if($task->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Task updated."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update task."));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $task->id = $data->id;

        if($task->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Task deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete task."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}
?>
