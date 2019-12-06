-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Дек 06 2019 г., 23:44
-- Версия сервера: 10.3.16-MariaDB
-- Версия PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `auto1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `car`
--

CREATE TABLE `car` (
  `id_car` int(11) UNSIGNED NOT NULL,
  `mark` varchar(32) NOT NULL,
  `model` varchar(32) NOT NULL,
  `production_year` year(4) NOT NULL,
  `cost` bigint(32) NOT NULL,
  `mileage` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='object_car';

--
-- Дамп данных таблицы `car`
--

INSERT INTO `car` (`id_car`, `mark`, `model`, `production_year`, `cost`, `mileage`, `file_path`) VALUES
(37, 'Tesla', 'Model_3', 2019, 3000000, 70, 'user_file/toyotainf.jpg'),
(40, 'Lada', 'Granta', 2016, 300000, 57000, 'user_file/lada_(1).jpg'),
(50, 'VW', 'Polo', 2018, 800000, 345, 'user_file/polo1_(1).png'),
(62, 'Kia', 'Solaris', 2017, 3000000, 9, 'user_file/Solaris.png');

-- --------------------------------------------------------

--
-- Структура таблицы `relation`
--

CREATE TABLE `relation` (
  `id` int(11) NOT NULL,
  `id_salon` int(11) NOT NULL,
  `id_car` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `relation`
--

INSERT INTO `relation` (`id`, `id_salon`, `id_car`) VALUES
(9, 1, 37),
(12, 2, 40),
(23, 3, 50),
(34, 74, 62);

-- --------------------------------------------------------

--
-- Структура таблицы `salon`
--

CREATE TABLE `salon` (
  `id_salon` int(11) NOT NULL,
  `mark` varchar(32) NOT NULL,
  `number` varchar(16) NOT NULL,
  `email` varchar(64) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `salon`
--

INSERT INTO `salon` (`id_salon`, `mark`, `number`, `email`, `file_path`) VALUES
(1, 'Tesla', '+79044248020', 'email@gmail.como', 'user_file/renault.jpg'),
(2, 'Lada', '+79370815100', 'lada@ya.ru', 'user_file/lada.jpg'),
(3, 'VW', '+79370815122', 'alexul603@gmail.com', 'user_file/polo1.png'),
(74, 'Kia', '+79370815122', 'alexul603@gmail.com', 'user_file/kia.jpg');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id_car`),
  ADD UNIQUE KEY `id` (`id_car`);

--
-- Индексы таблицы `relation`
--
ALTER TABLE `relation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `salon`
--
ALTER TABLE `salon`
  ADD PRIMARY KEY (`id_salon`),
  ADD UNIQUE KEY `id_salon` (`id_salon`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `car`
--
ALTER TABLE `car`
  MODIFY `id_car` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT для таблицы `relation`
--
ALTER TABLE `relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `salon`
--
ALTER TABLE `salon`
  MODIFY `id_salon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
