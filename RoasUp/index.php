<html>
<head>
    <title>API_Tovars</title>
</head>
<body>
    <?php
	$ourData = file_get_contents("api_db.json");
	$object=json_decode($ourData, JSON_UNESCAPED_UNICODE);
	//var_dump($object); //вывод информации из данных файла json
	switch (json_last_error()) {
		case JSON_ERROR_NONE:
		//echo 'Ошибок нет';
		break;
		case JSON_ERROR_DEPTH:
			echo 'Достигнута максимальная глубина стека';
		break;
		case JSON_ERROR_STATE_MISMATCH:
			echo 'Некорректные разряды или несоответствие режимов';
		break;
		case JSON_ERROR_CTRL_CHAR:
			echo 'Некорректный управляющий символ';
		break;
		case JSON_ERROR_SYNTAX:
			echo 'Синтаксическая ошибка, некорректный JSON';
		break;
		case JSON_ERROR_UTF8:
			echo 'Некорректные символы UTF-8, возможно неверно закодирован';
		break;
		default:
			echo 'Неизвестная ошибка';
		break;
		}
		
		//подключение к базе данных
        $server = 'localhost'; // Имя или адрес сервера
        $user = 'mysql'; // Имя пользователя БД
        $password = 'mysql'; // Пароль пользователя
        $db = 'api_db'; // Название БД
        $link = mysqli_connect($server, $user, $password, $db); // Подключение к серверу базы данных
		
        // Проверка на подключение
        if (!$link) { // Если проверку не прошло
		echo "Не удается подключиться к серверу базы данных!"; //то выводится надпись ошибки
		exit; //и заканчивается работа скрипта
        } //конец проверки
		//если значение страницы получено
        if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno']; //то присваивается
        } else {
            $pageno = 1;
        }

        $size_page = 5; //ограниченное количество записей БД на одной страцице
        $offset = ($pageno * $size_page) - $size_page; //смещение страницы с размером записей БД

        $pages_sql = "SELECT COUNT(*) FROM `tovars`";  //количество полученных строк
        $result = mysqli_query($link, $pages_sql);     //результат количества полученных строк
        $total_rows = mysqli_fetch_array($result)[0];  //количество записей в каждой строке БД
        $total_pages = ceil($total_rows / $size_page); //общее количество страниц
        $sql = "SELECT * FROM `tovars` LIMIT $offset, $size_page"; //получение товаров с ограниченным количеством
		
		//функция пагинации номеров страниц
		function Paginator($pageno, $total_pages){
			echo '</br>Страницы: ';
			foreach(range(1, $total_pages) as $page)
			echo ' '.'<a href="?pageno='.$page.'">'.$page.'</a>';
		}
		Paginator($pageno, $total_pages); //вызов функции
		
		//Удаление нужной записи
		if (isset($_GET['del'])) {
			$res_data = mysqli_query($link, "DELETE FROM `tovars` WHERE `ID_Tovar` = {$_GET['del']}");
			if ($res_data) {
				//echo "<p>Запись удалена.</p>";
				$result = mysqli_query($link, "SELECT COUNT(*) as count FROM `tovars`");     //результат количества полученных строк
				$total_rows = mysqli_fetch_array($result)[0];  //количество записей в каждой строке БД
				} else {
					echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
					}
				}
		
		if ($total_rows != 0){
			//Получаем данные
			$res_data = mysqli_query($link, $sql);
			//шапка таблицы
			print ("<table border=1 align=left width=100% cellpadding=5>
			<tr><td><font = arial black><b>Номер товара</b></td>
			<td><font = arial black><b>Наименование товара</td>
			<td><font = arial black><b>Категория товара</td>
			<td><font = arial black><b>Цена товара</td>
			<td><font = arial black><b>Изменение записи</td>
			<td><font = arial black><b>Удаление записи</td></tr>");
			
			while($row = mysqli_fetch_array($res_data)){ //пока есть записи БД с ограниченным количеством
				$ID_Tovar=$row['ID_Tovar'];
				$Name_Tovar=$row['Name_Tovar'];
				$Cathegory_Tovara=$row['Cathegory_Tovara'];
				$Price_tovar=$row['Price_tovar'];
				print ("<tr><td>$ID_Tovar</td>
				<td>$Name_Tovar</td>
				<td>$Cathegory_Tovara</td>
				<td>$Price_tovar</td>
				<td><a href='api_db_edit.php?red=$ID_Tovar'>Редактировать</a></td>
				<td><a href='?del=$ID_Tovar'>Удалить</a></td></tr>");
				$bd[]=array(
				'ID_Tovar' => $row["ID_Tovar"],
				'Name_Tovar' => $row["Name_Tovar"],
				'Cathegory_Tovara' => $row["Cathegory_Tovara"],
				'Price_tovar' => $row["Price_tovar"]
				);
			} //конец цикла
			print ("</table>");
			print ("<p><font = arial black><b>Количество записей: $total_rows</b></br>");
			
		}
		else {
			echo("Нет товаров");
			}
		$cathegory_kolvo = mysqli_query($link, "SELECT `cathegory_tovara`, count(*) FROM `tovars` GROUP BY `cathegory_tovara`");
		//вывод списка категорий товаров, с количеством наименований товара в каждой
		echo '</br>Количество товаров по категориям:</br>';
		while ($cathegory = mysqli_fetch_assoc($cathegory_kolvo)){  //пока есть записи БД 
		$Cathegory_Tovara=$cathegory['cathegory_tovara'];
		$Cathegory_count=$cathegory['count(*)'];
		echo '<li>'.$Cathegory_Tovara.' ('.$Cathegory_count.' шт.)</li>';
		}
        mysqli_close($link); //закрытие доступа к бд
    ?>
<form>
<p><button style="border-radius: 10 px;"><a href = "api_db_edit.php">Добавить новый товар</a></button></p>
</form>
<?php Paginator($pageno, $total_pages); //вызов функции 
file_put_contents('api_db.json', ''); //очистка файла перед записью
$encode = json_encode($bd, JSON_UNESCAPED_UNICODE); 
$fp=fopen("api_db.json", 'a+');//fopen — Открывает файл или URL
fwrite($fp, $encode);
fclose($fp);
?>
</body>
</html>