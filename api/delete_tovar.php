<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *"); // кем-либо может быть прочитан файл. (* - доступ открыт для всех сайтов).
header("Content-Type: application/json; charset=UTF-8"); // ожидаемый формат данных UTF-8.
header("Access-Control-Allow-Methods: POST"); //метод POST используется во время запроса
header("Access-Control-Max-Age: 3600"); //кэширование предзапроса в 360 секундах
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); //указание списка HTTP заголовков, которые могут быть использованы в запросе.

// получаем соединение с базой данных 
include_once __DIR__ . '..\db\database.php';

// удаление объекта товара 
include_once __DIR__ .'..\functions\functions_tovars.php';

// получаем соединение с БД 
$database = new Database();
$db = $database->getConnection();

// подготовка объекта 
$tovar = new Tovar($db);

// получаем id товара 
$data = json_decode(file_get_contents("php://input"));

// установим ID_Tovar товара для удаления
$tovar->ID_Tovar = $data->ID_Tovar;

// удаление товара 
if ($tovar->delete()) {

    // код ответа - 200 ok 
    http_response_code(200);

    // сообщение пользователю 
    echo json_encode(array("message" => "Товар был удалён."), JSON_UNESCAPED_UNICODE);
}

// если не удается удалить товар 
else {

    // код ответа - 503 Сервис не доступен 
    http_response_code(503);

    // сообщим об этом пользователю 
    echo json_encode(array("message" => "Не удалось удалить товар."));
}
?>