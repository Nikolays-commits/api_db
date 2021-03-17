<!doctype html>
<html lang="ru">
<head>
  <title>Админ-панель</title>
</head>
<body>
  <?php
    //подключение к базе данных
    $server = 'localhost'; // Имя или адрес сервера
    $user = 'mysql'; // Имя пользователя БД
    $password = 'mysql'; // Пароль пользователя
    $db = 'api_db'; // Название БД
    $link = mysqli_connect($server, $user, $password, $db); // Подключение к серверу базы данных
	
    // Если соединение установить не удалось, то ошибка
    if (!$link) {
      echo 'Нет соединения с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }
	
	$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `tovars`"); // количество полученных строк
	$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
	
    //Если переменная передана
        if (isset($_POST["Name_Tovar"])){
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red'])) {
        $sql = mysqli_query($link, "UPDATE tovars SET `Name_Tovar` = '{$_POST['Name_Tovar']}',`Cathegory_Tovara` = '{$_POST['Cathegory_Tovara']}', 
		`Price_tovar` = '{$_POST['Price_tovar']}' WHERE `ID_Tovar`={$_GET['red']}");
      } 
	  else {
        //Иначе вставляем данные, подставляя их в запрос
        $sql = mysqli_query($link, "INSERT INTO `tovars` (`Name_Tovar`, `Cathegory_Tovara`, `Price_tovar`) VALUES 
		('{$_POST['Name_Tovar']}', '{$_POST['Cathegory_Tovara']}', '{$_POST['Price_tovar']}')");
      }

      //Если вставка прошла успешно
      if ($sql) {
		  $rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `tovars`"); // количество полученных строк
		  $count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
		  echo '<p>Успешно!</p>'; 
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }

    //Если передана переменная red, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red'])) {
      $sql = mysqli_query($link, "SELECT * FROM `tovars` WHERE `ID_Tovar`={$_GET['red']}");
      $tovars = mysqli_fetch_array($sql);
    }
  ?>
  
  <!--Форма заполнения полей-->
  <form action="" method="post">
  <p>Наименование товара: <input type="text" name="Name_Tovar" value="<?= isset($_GET['red']) ? $tovars['Name_Tovar'] : ''; ?>" placeholder="Наименование товара" required></p>
  <p>Категория товара: <input type="text" name="Cathegory_Tovara" value="<?= isset($_GET['red']) ? $tovars['Cathegory_Tovara'] : ''; ?>" placeholder="Категория товара" required></p>
  <p>Цена товара: <input type="number" name="Price_tovar" min="0" max="10000" value="<?= isset($_GET['red']) ? $tovars['Price_tovar'] : ''; ?>" placeholder="Цена товара" required></p>
  <p><input type="submit" value="Подтвердить"> <button><a href="index.php">Назад</a></button></p>
    </table>
  </form>
</body>
</html>