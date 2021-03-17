-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 17 2021 г., 11:13
-- Версия сервера: 5.7.29
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `api_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tovars`
--

CREATE TABLE `tovars` (
  `ID_Tovar` int(11) NOT NULL,
  `Name_Tovar` varchar(100) NOT NULL,
  `Cathegory_Tovara` varchar(100) DEFAULT NULL,
  `Price_tovar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tovars`
--

INSERT INTO `tovars` (`ID_Tovar`, `Name_Tovar`, `Cathegory_Tovara`, `Price_tovar`) VALUES
(1, 'Книга Царевна лягушка', 'Книги', 30),
(2, 'Хлеб', 'Продукты', 20),
(3, 'Микрофон', 'Аксессуары', 50),
(5, 'Молоко', 'Продукты', 60),
(6, 'Кефир', 'Продукты', 70),
(7, 'Конфеты', 'Продукты', 40),
(8, 'Магнитофон', 'Аксессуары', 10);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `tovars`
--
ALTER TABLE `tovars`
  ADD UNIQUE KEY `id_tovar` (`ID_Tovar`),
  ADD KEY `Cathegory_Tovara` (`Cathegory_Tovara`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `tovars`
--
ALTER TABLE `tovars`
  MODIFY `ID_Tovar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
