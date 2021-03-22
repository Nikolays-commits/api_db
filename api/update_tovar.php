<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// получаем соединение с базой данных 
include_once __DIR__ . '..\db\database.php';

// изменение объекта товара 
include_once __DIR__ .'..\functions\functions_tovars.php';

// соединение с базой данных 
$database = new Database();
$db = $database->getConnection();

// подготовка объекта 
$tovar = new Tovar($db);

// получаем id товара для редактирования 
$data = json_decode(file_get_contents("php://input"));

// установим ID_Tovar свойства товара для редактирования 
$tovar->ID_Tovar = $data->ID_Tovar;

// установим значения свойств товара 
$tovar->Name_Tovar = $data->Name_Tovar;
$tovar->Cathegory_Tovara = $data->Cathegory_Tovara;
$tovar->Price_tovar = $data->Price_tovar;

// обновление товара 
if ($tovar->update()) {

    // установим код ответа - 200 ok 
    http_response_code(200);

    // сообщим пользователю 
    echo json_encode(array("message" => "Товар был обновлён."), JSON_UNESCAPED_UNICODE);
}

// если не удается обновить товар, сообщим пользователю 
else {

    // код ответа - 503 Сервис не доступен 
    http_response_code(503);

    // сообщение пользователю 
    echo json_encode(array("message" => "Невозможно обновить товар."), JSON_UNESCAPED_UNICODE);
}
?>