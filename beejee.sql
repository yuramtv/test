-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 17 2020 г., 12:11
-- Версия сервера: 5.7.20
-- Версия PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `beejee`
--

-- --------------------------------------------------------

--
-- Структура таблицы `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sText` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `corrected` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `task`
--

INSERT INTO `task` (`id`, `name`, `email`, `sText`, `status`, `corrected`) VALUES
(1, 'Mikle Jordan', 'adc@mail.ru', 'Build responsive, mobile-first projects on the web with the world’s most popular front-end component library.\r\n\r\nBootstrap is an open source toolkit for developing with HTML, CSS, and JS. Quickly prototype your ideas or build your entire app with our Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful plugins built on jQuery.', 0, 0),
(2, 'Валерий Михайлович', 'qwerty@mail.ru', 'Все примеры построены на стандартном шаблоне Bootstrap\'а - см. выше. Так же обратите внимание на Кастомизация Bootstrap тля доп. информации по созданию Вашего уникального дизайна Bootstrap 3. 123', 0, 0),
(3, 'Николай', 'test@mail.ru', 'Начните работать с базового HTML шаблона, или сразу перейдите к примерам которые мы начали за Вас. Мы надеемся что взяв за оснеову один из наших примеров - путем его изменения Вы сможете содать дизайн и функциональность необходимую Вашему проекту.111', 1, 1),
(4, 'Антонина', 'dddd@wwwwwrr.ru', 'Шаги по отключению адаптивности в Bootstrap 3\r\n\r\n    Уберите тег &lt;meta&gt; описывающий поведение viewport, описанный в документации по CSS\r\n    Перепишите значение width класса .container всех уровней сетки, на необходимую Вашему проекту ширину, например: width: 970px !important;. Убедитесь, что эти правила прописаны после подключения CSS-файлов Bootstrap 3. Вы также можете убрать !important через медиа-запросы или через свое правило, типа: selector-fu.\r\n    При использовании навигационного бара, уберите элементы отвечающие за сворачивание или разворачивание панели.', 1, 0),
(5, 'компания D\'Logic', 'aaaaaaa@test.com', 'Не смотря на отключение адаптивности описанным способом выше, для совместимости с IE8 (Так как медиа-запросы все равно присутвуют в CSS-файлах) - Вам понадобится подключить файл Respond.js.\r\nЭто просто отключит &quot;мобильную версию&quot; в Bootstrap 3.', 0, 0),
(6, 'злой хакер &quot;# &lt;//*+&quot;', 'yura@test.com', 'злой хакер &quot;# &lt;//*+&quot;', 0, 0),
(7, 'Юрий', 'test@mail.ru', 'Классы .container и .row - Теперь всегда резиновые.\r\nИзображения больше не адаптивные по умолчанию. Используйте класс .img-responsive для адаптсции размера элемента &lt;img&gt;.', 0, 0),
(8, 'Yuriy', 'test@mail.ru', 'Bootstrap 3 поддерживает следующие браузеры в последней версии:\r\n\r\n    Chrome (Mac, Windows, iOS, и Android)\r\n    Safari (Mac и iOS only, так как версия под Windows больше не обновляется)\r\n    Firefox (Mac, Windows)\r\n    Internet Explorer\r\n    Opera (Mac, Windows)\r\n\r\nНеофициально, Bootstrap должен работать в Chromium для Linux и в Internet Explorer 7 версии, но тем не менее официальной поддержки этих браузеров - НЕТ', 0, 0),
(9, 'qwee', 'yura@mail.ru', 'sdfsdfsdfsdf', 1, 0),
(10, 'test', 'test@test.com', '&lt;script&gt;alert(‘test’);&lt;/script&gt;,', 0, 0),
(11, 'Юрий', 'mail@test.ru', 'Это последняя задача - зд здесь здесь  ровно 100 символов', 1, 0),
(12, 'test', 'test@test.ru', 'Для проверки XSS qqq уязвимости нужно создать задачу с тегами в описании задачи (добавить в поле описания задачи текст &lt;script&gt;alert(‘test’);&lt;/script&gt;', 1, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
