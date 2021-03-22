<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *"); // кем-либо может быть прочитан файл. (* - доступ открыт для всех сайтов).
header("Content-Type: application/json; charset=UTF-8"); // ожидаемый формат данных UTF-8.
header("Access-Control-Allow-Methods: POST"); //метод POST используется во время запроса
header("Access-Control-Max-Age: 3600"); //кэширование предзапроса в 360 секундах
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); //указание списка HTTP заголовков, которые могут быть использованы в запросе.

// получаем соединение с базой данных 
include_once __DIR__ . '..\db\database.php';

// создание объекта товара 
include_once __DIR__ .'..\functions\functions_tovars.php';

// соединение с базой данных 
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$tovar = new Tovar($db);
 
// получаем отправленные данные 
$data = json_decode(file_get_contents("php://input"));
 
// убеждаемся, что данные не пусты 
if (
    !empty($data->Name_Tovar) &&
    !empty($data->Cathegory_Tovara) &&
    !empty($data->Price_tovar)
) {

    // устанавливаем значения свойств товара 
    $tovar->Name_Tovar = $data->Name_Tovar;
    $tovar->Cathegory_Tovara = $data->Cathegory_Tovara;
    $tovar->Price_tovar = $data->Price_tovar;

    // создание товара 
    if($tovar->add_tovar()){

        // установим код ответа - 201 создано 
        http_response_code(201);

        // сообщим пользователю 
        echo json_encode(array("message" => "Товар был создан."), JSON_UNESCAPED_UNICODE);
    }

    // если не удается создать товар, сообщим пользователю 
    else {

        // установим код ответа - 503 сервис недоступен 
        http_response_code(503);

        // сообщим пользователю 
        echo json_encode(array("message" => "Невозможно создать товар."), JSON_UNESCAPED_UNICODE);
    }
}

// сообщим пользователю что данные неполные 
else {

    // установим код ответа - 400 неверный запрос 
    http_response_code(400);

    // сообщим пользователю 
    echo json_encode(array("message" => "Невозможно создать товар. Данные неполные."), JSON_UNESCAPED_UNICODE);
}
?>
