<?php
class Database { //создание класса для подключения к БД

    // учетные данные базы данных (БД)
	// закрытые переменные, которые доступны из методов Database 
    private $host = "localhost";
    private $db_name = "api_db";
    private $username = "mysql";
    private $password = "mysql";
    public $connector; // общедоступная переменная свойства $connector для класса Database

    // функция соединения с БД 
    public function getConnection(){

        $this->connector = null; // изначально соединение с БД отсутствует. $this - псевдопеременная указывает на переменные свойства для класса Database
		//обработчик исключения try catch
        try { //попытка подключения к БД
            $this->connector = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password); // создание объекта PDO для доступа подключение к БД
            $this->connector->exec("set names utf8_general_ci"); //установка кодировки utf8_general_ci
        } catch(PDOException $exception){ // обработка исключения фатальной ошибки
            echo "Connection error: " . $exception->getMessage(); //ошибка соединения с БД
        }
        return $this->connector; // возврат результата соединения с БД
    } // конец функции
	
} // конец класса
?>