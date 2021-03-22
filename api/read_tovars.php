<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *"); // кем-либо может быть прочитан файл. (* - доступ открыт для всех сайтов).
header("Content-Type: application/json; charset=UTF-8"); // ожидаемый формат данных UTF-8.

// подключение базы данных и файл, содержащий объекты 
include_once __DIR__ . '..\db\database.php';
include_once __DIR__ .'..\functions\functions_tovars.php';

// соединение с базой данных 
$database = new Database();
$db = $database->getConnection();

// инициализируем объект 
$tovar = new Tovar($db);

// запрашиваем товары 
$tovars = $tovar->read_tovars();
$num = $tovars->rowCount();

// проверка, найдено ли больше 0 записей 
if ($num>0) {

    // массив товаров 
    $tovars_arr=array();
    $tovars_arr["db_tovars"]=array();

    // получение содержимого таблицы товаров
    while ($row = $tovars->fetch(PDO::FETCH_ASSOC)){ //PDO::FETCH_ASSOC: возвращает массив, индексированный именами столбцов результирующего набора
        
        extract($row); // извлекаем строку 

        $tovar_item = array(
            "ID_Tovar" => $ID_Tovar,
            "Name_Tovar" => $Name_Tovar,
            "Cathegory_Tovara" => $Cathegory_Tovara,
            "Price_tovar" => $Price_tovar
        );

        array_push($tovars_arr["db_tovars"], $tovar_item); //функция добавления нескольких записей таблицы товаров в конец массива
    }

    // устанавливаем код ответа - 200 OK 
    http_response_code(200);

    // выводим данные о товаре в формате JSON 
    echo json_encode($tovars_arr, JSON_UNESCAPED_UNICODE);
}

else {

    // установим код ответа - 404 Не найдено 
    http_response_code(404);

    // сообщаем пользователю, что товары не найдены 
    echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}
?>