<?php
class Tovar { // класс для функций работы с таблицей БД tovars (товаров)

    // подключение к базе данных и таблице 'tovars' 
    private $connector;
    private $table_name = "tovars";

    // свойства объекта 
    public $ID_Tovar;
    public $Name_Tovar;
    public $Cathegory_Tovara;
    public $Price_tovar;

    // конструктор для соединения с базой данных 
    public function __construct($db){
        $this->connector = $db;
    }

// метод read_tovars() - чтение товаров 
function read_tovars(){

	// выбираем все записи 
	$query = "SELECT * FROM tovars";

    // подготовка запроса 
    $stmt = $this->connector->prepare($query);

    // выполняем запрос 
    $stmt->execute();

    return $stmt;
} 

// метод add_tovar - добавление товаров 
function add_tovar(){

    // запрос для вставки (создания) записей 
    $query = "INSERT INTO tovars SET Name_Tovar=:Name_Tovar, Cathegory_Tovara=:Cathegory_Tovara, Price_tovar=:Price_tovar";

    // подготовка запроса 
    $stmt = $this->connector->prepare($query);

    // очистка 
    $this->Name_Tovar=htmlspecialchars(strip_tags($this->Name_Tovar));
	$this->Cathegory_Tovara=htmlspecialchars(strip_tags($this->Cathegory_Tovara));
    $this->Price_tovar=htmlspecialchars(strip_tags($this->Price_tovar));

    // привязка значений 
    $stmt->bindParam(":Name_Tovar", $this->Name_Tovar);
	$stmt->bindParam(":Cathegory_Tovara", $this->Cathegory_Tovara);
    $stmt->bindParam(":Price_tovar", $this->Price_tovar);

    // выполняем запрос 
    if ($stmt->execute()) {
        return true;
    }

    return false;
}

// метод update() - обновление товара 
function update(){

    // запрос для обновления записи (товара) 
    $query = "UPDATE tovars SET Name_Tovar=:Name_Tovar, Cathegory_Tovara=:Cathegory_Tovara, Price_tovar=:Price_tovar WHERE ID_Tovar = :ID_Tovar";

    // подготовка запроса 
    $stmt = $this->connector->prepare($query);

    // очистка 
    $this->Name_Tovar=htmlspecialchars(strip_tags($this->Name_Tovar));
	$this->Cathegory_Tovara=htmlspecialchars(strip_tags($this->Cathegory_Tovara));
    $this->Price_tovar=htmlspecialchars(strip_tags($this->Price_tovar));
	$this->ID_Tovar=htmlspecialchars(strip_tags($this->ID_Tovar));

    // привязываем значения
    $stmt->bindParam(":Name_Tovar", $this->Name_Tovar);
	$stmt->bindParam(":Cathegory_Tovara", $this->Cathegory_Tovara);
    $stmt->bindParam(":Price_tovar", $this->Price_tovar);
    $stmt->bindParam(":ID_Tovar", $this->ID_Tovar);

    // выполняем запрос 
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// метод delete - удаление товара 
function delete(){

    // запрос для удаления записи (товара) 
    $query = "DELETE FROM tovars WHERE ID_Tovar = ?";

    // подготовка запроса 
    $stmt = $this->connector->prepare($query);

    // очистка 
    $this->ID_Tovar=htmlspecialchars(strip_tags($this->ID_Tovar));

    // привязываем ID_Tovar записи для удаления 
    $stmt->bindParam(1, $this->ID_Tovar);

    // выполняем запрос 
    if ($stmt->execute()) {
        return true;
    }

    return false;
}

// чтение товаров с пагинацией 
public function readPaging($from_record_num, $records_per_page){

    // выборка 
    $query = "SELECT * FROM `tovars` LIMIT ?, ?";

    // подготовка запроса 
    $stmt = $this->connector->prepare($query);

    // свяжем значения переменных 
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

    // выполнение запроса 
    $stmt->execute();

    // вернёт значения из базы данных 
    return $stmt;
}

// используется для пагинации товаров 
public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM tovars";
	// подготовка запроса 
    $stmt = $this->connector->prepare( $query );
	// выполнение запроса 
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['total_rows'];
}

// метод kolvo_cathegory_tovars() - количество товаров по категориям 
function kolvo_cathegory_tovars(){

	// выбираем все записи 
	$query = "SELECT `cathegory_tovara`, `name_tovar`, count(*) as `count_cathegory` FROM `tovars` GROUP BY `cathegory_tovara`, `name_tovar`";

    // подготовка запроса 
    $stmt = $this->connector->prepare($query);

    // выполняем запрос 
    $stmt->execute();

    return $stmt;
} 

}
?>