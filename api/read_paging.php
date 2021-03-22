<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *"); // кем-либо может быть прочитан файл. (* - доступ открыт для всех сайтов).
header("Content-Type: application/json; charset=UTF-8"); // ожидаемый формат данных UTF-8.

// подключение файлов
include_once __DIR__ . '..\db\database.php';
include_once __DIR__ .'..\functions\utilities.php';
include_once __DIR__ .'..\functions\functions_tovars.php';

// показывать сообщения об ошибках 
ini_set('display_errors', 1);
error_reporting(E_ALL);

// URL домашней страницы 
$home_url="http://localhost/api";

// страница указана в параметре URL, страница по умолчанию одна 
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// установка количества записей на странице 
$records_per_page = 5;

// расчёт для запроса предела записей 
$from_record_num = ($records_per_page * $page) - $records_per_page;

// utilities 
$utilities = new Utilities();

// создание подключения 
$database = new Database();
$db = $database->getConnection();

// инициализация объекта 
$tovar = new Tovar($db);

// запрос товаров 
$stmt = $tovar->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount(); //количество записей

// если больше 0 записей 
if ($num>0) {

    // массив товаров 
    $tovars_arr=array();
    $tovars_arr["db_tovars"]=array();
    $tovars_arr["paging"]=array();

    // получаем содержимое нашей таблицы 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлечение строки 
        extract($row);

        $tovar_item=array(
            "ID_Tovar" => $ID_Tovar,
            "Name_Tovar" => $Name_Tovar,
            "Cathegory_Tovara" => $Cathegory_Tovara,
            "Price_tovar" => $Price_tovar
        );

        array_push($tovars_arr["db_tovars"], $tovar_item);
    }

    // подключим пагинацию 
    $total_rows=$tovar->count();
    $page_url="{$home_url}/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $tovars_arr["paging"]=$paging;

    // установим код ответа - 200 OK 
    http_response_code(200);

    //str_replace - замена слэша '\/' на слэш '/'
    echo str_replace('\/','/', json_encode($tovars_arr, JSON_UNESCAPED_UNICODE));// вывод в json-формате
} else {

    // код ответа - 404 Ничего не найдено 
    http_response_code(404);

    // сообщим пользователю, что товаров не существует 
    echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}
?>