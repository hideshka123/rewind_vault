-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июн 30 2026 г., 19:23
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `movie_lovers_platform`
--

-- --------------------------------------------------------

--
-- Структура таблицы `actors`
--

CREATE TABLE `actors` (
  `id_actor` int(11) NOT NULL,
  `name_actor` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `actors`
--

INSERT INTO `actors` (`id_actor`, `name_actor`) VALUES
(1, 'Colin Farrell'),
(2, 'Brendan Gleeson'),
(3, 'Kerry Condon'),
(4, 'Miles Teller'),
(5, 'J.K. Simmons'),
(6, 'Marlon Brando'),
(7, 'Al Pacino'),
(8, 'Anthony Perkins'),
(9, 'Jake Gyllenhaal'),
(10, 'Martin Sheen'),
(11, 'Matthew Modine'),
(12, 'R. Lee Ermey'),
(13, 'Anatoly Solonitsyn'),
(14, 'Al Pacino'),
(15, 'James Caan'),
(16, 'Janet Leigh'),
(17, 'Cillian Murphy'),
(18, 'Casey Affleck'),
(19, 'Michelle Williams'),
(20, 'Isabelle Adjani'),
(21, 'James Woods'),
(22, 'Keir Dullea'),
(23, 'Max von Sydow'),
(24, 'Gunnar Björnstrand'),
(25, 'Frankie Corio'),
(26, 'Paul Mescal'),
(27, 'Lubna Azabal'),
(28, 'Mélissa Désormeaux-Poulin'),
(29, 'James McAvoy'),
(30, 'Debbie Harry'),
(31, 'Jamie Bell'),
(32, 'Craig Parkinson'),
(33, 'Eddie Marsan'),
(34, 'Imogen Poots'),
(35, 'Kyle Gallner'),
(36, 'Katie Aselton'),
(37, 'Mark Duplass'),
(38, 'Melissa Benoist'),
(39, 'Blake Jenner'),
(40, 'Anthony Mackie'),
(41, 'Christopher Eccleston'),
(42, 'Naomie Harris'),
(43, 'Celia Rowlson-Hall'),
(44, 'Gary Lockwood'),
(45, 'Bengt Ekerot'),
(46, 'Margit Carstensen'),
(47, 'Inga Ibsdotter Lilleaas'),
(48, 'Caroline Christl Long'),
(49, 'Jennifer Kincer'),
(50, 'Kathy Fields'),
(51, 'Jena Malone'),
(52, 'Mary McDonnell'),
(53, 'Marsha Hunt'),
(54, 'Kyle Chandler'),
(55, 'Maxim Gaudette'),
(56, 'Ivan Lapikov');

-- --------------------------------------------------------

--
-- Структура таблицы `collections`
--

CREATE TABLE `collections` (
  `id_collection` int(11) NOT NULL,
  `name_collection` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `cover_url` varchar(255) DEFAULT NULL,
  `is_thematic` tinyint(1) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `collections`
--

INSERT INTO `collections` (`id_collection`, `name_collection`, `description`, `cover_url`, `is_thematic`, `id_user`) VALUES
(1, 'Top of the Week', 'Best movies of the week handpicked by our editors', 'assets/images/collections/top1.png', 1, NULL),
(2, 'Top of the Week', 'Best movies of the week handpicked by our editors', 'assets/images/collections/top2.png', 1, NULL),
(3, 'Top of the Week', 'Best movies of the week handpicked by our editors', 'assets/images/collections/top3.png', 1, NULL),
(4, 'Top of the Week', 'Best movies of the week handpicked by our editors', 'assets/images/collections/top4.png', 1, NULL),
(5, 'Drama', 'Powerful dramatic films', 'assets/images/collections/drama.png', 1, NULL),
(6, 'Thriller', 'Edge-of-your-seat thrillers', 'assets/images/collections/thriller.png', 1, NULL),
(7, 'Classic', 'Timeless classic films', 'assets/images/collections/classic.png', 1, NULL),
(8, 'Comedy', 'Hilarious comedies', 'assets/images/collections/comedy.png', 1, NULL),
(10, '80s Cult Classics', 'Cult classics from the 1980s', 'assets/images/collections/user2.png', 0, 2),
(11, 'Timeless Masterpieces', 'Classics that never get old', 'assets/images/collections/user3.png', 0, 3),
(12, 'Late Night Thrillers', 'Thrillers for late night viewing', 'assets/images/collections/user4.png', 0, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `directors`
--

CREATE TABLE `directors` (
  `id_director` int(11) NOT NULL,
  `name_director` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `directors`
--

INSERT INTO `directors` (`id_director`, `name_director`) VALUES
(1, 'Martin McDonagh'),
(2, 'Damien Chazelle'),
(3, 'Francis Ford Coppola'),
(4, 'Alfred Hitchcock'),
(5, 'Richard Kelly'),
(6, 'Stanley Kubrick'),
(7, 'Andrei Tarkovsky'),
(8, 'Alejandro G. Iñárritu'),
(9, 'Denis Villeneuve'),
(10, 'Anton Corbijn'),
(11, 'Ryan Fleck'),
(12, 'Danny Boyle'),
(13, 'Robert Eggers'),
(14, 'Kenneth Lonergan'),
(15, 'Charlotte Wells'),
(16, 'Andrzej Żuławski'),
(17, 'Patrick Brice'),
(18, 'Jon S. Baird'),
(19, 'David Cronenberg'),
(20, 'Dalton Trumbo'),
(21, 'Ingmar Bergman'),
(22, 'Adam Rehmeier'),
(23, 'Joachim Trier'),
(24, 'Josh Safdie');

-- --------------------------------------------------------

--
-- Структура таблицы `forum_categories`
--

CREATE TABLE `forum_categories` (
  `id_category` int(11) NOT NULL,
  `name_category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `forum_categories`
--

INSERT INTO `forum_categories` (`id_category`, `name_category`) VALUES
(1, 'Film Discussions'),
(2, 'User Topics'),
(3, 'Director Spotlights');

-- --------------------------------------------------------

--
-- Структура таблицы `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id_post` int(11) NOT NULL,
  `id_topic` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `message` varchar(5000) DEFAULT NULL,
  `post_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `forum_posts`
--

INSERT INTO `forum_posts` (`id_post`, `id_topic`, `id_user`, `message`, `post_date`) VALUES
(1, 1, 2, 'I think Dune Part Two has absolutely stunning cinematography. The desert scenes are breathtaking!', '2026-06-10'),
(2, 2, 3, 'You should check out The Witch (2015) and Hereditary. Both are masterpieces that didnt get enough recognition.', '2026-06-11'),
(4, 4, 5, 'The soundtrack of Interstellar by Hans Zimmer is absolutely phenomenal. It elevates every scene!', '2026-06-13'),
(5, 5, 4, 'The opening scene of Touch of Evil is one of the most famous long takes in cinema history. Pure genius!', '2026-06-14'),
(9, 6, 2, 'Hitchcock is a master of suspense. The shower scene is iconic!', '2026-06-15'),
(10, 6, 3, 'Anthony Perkins gives one of the best performances in horror history.', '2026-06-15'),
(12, 7, 4, 'Jake Gyllenhaal was perfect for this role. The ending still confuses me.', '2026-06-15'),
(13, 8, 2, 'Kubrick shows the dehumanizing effects of war. R. Lee Ermey is unforgettable.', '2026-06-15'),
(14, 8, 5, 'The boot camp scenes are intense. One of the best war films ever made.', '2026-06-15'),
(16, 9, 3, '28 Days Later changed the zombie genre forever. Danny Boyle is a genius.', '2026-06-16'),
(18, 10, 4, 'The wedding scene at the beginning is a masterclass in storytelling.', '2026-06-16');

-- --------------------------------------------------------

--
-- Структура таблицы `forum_topics`
--

CREATE TABLE `forum_topics` (
  `id_topic` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `id_category` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_movie` int(11) DEFAULT NULL,
  `created_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `forum_topics`
--

INSERT INTO `forum_topics` (`id_topic`, `title`, `id_category`, `id_user`, `id_movie`, `created_date`) VALUES
(2, 'Underrated Horror Films', 2, 2, NULL, '2026-06-11'),
(3, 'Christopher Nolan vs Denis Villeneuve', 3, 3, NULL, '2026-06-12'),
(4, 'Favorite Movie Soundtracks', 2, 4, NULL, '2026-06-13'),
(5, 'The Art of Long Takes in Cinema', 1, 5, 6, '2026-06-14'),
(7, 'Discussion: Donnie Darko', 1, 2, 5, '2026-06-15'),
(8, 'Discussion: Full Metal Jacket', 1, 3, 7, '2026-06-15'),
(9, 'Best Horror Movies of All Time', 2, 4, NULL, '2026-06-16'),
(10, 'Discussion: The Godfather', 1, 5, 3, '2026-06-16');

-- --------------------------------------------------------

--
-- Структура таблицы `genres`
--

CREATE TABLE `genres` (
  `id_genre` int(11) NOT NULL,
  `name_genre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `genres`
--

INSERT INTO `genres` (`id_genre`, `name_genre`) VALUES
(1, 'Drama'),
(2, 'Crime'),
(3, 'Thriller'),
(4, 'Horror'),
(5, 'Sci-Fi'),
(6, 'Mystery'),
(7, 'War'),
(8, 'Action'),
(9, 'Adventure'),
(10, 'Biography'),
(11, 'Comedy'),
(12, 'Documentary'),
(13, 'Fantasy'),
(14, 'History'),
(15, 'Music'),
(16, 'Short');

-- --------------------------------------------------------

--
-- Структура таблицы `movies`
--

CREATE TABLE `movies` (
  `id_movie` int(11) NOT NULL,
  `id_genre` int(11) DEFAULT NULL,
  `id_director` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `description` text DEFAULT NULL,
  `poster_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `movies`
--

INSERT INTO `movies` (`id_movie`, `id_genre`, `id_director`, `title`, `year`, `duration`, `rating`, `description`, `poster_url`) VALUES
(1, 11, 1, 'The Banshees of Inisherin', 2022, 114, 7.7, 'Two lifelong friends find themselves at an impasse.', 'assets/images/placeholders/movie3.png'),
(2, 15, 2, 'Whiplash', 2014, 106, 8.5, 'A promising young drummer enrolls at a music conservatory.', 'assets/images/placeholders/movie9.png'),
(3, 2, 3, 'The Godfather', 1972, 175, 9.2, 'The aging patriarch of a crime dynasty.', 'assets/images/placeholders/movie20.png'),
(4, 4, 4, 'Psycho', 1960, 109, 8.5, 'A secretary checks into a remote motel.', 'assets/images/placeholders/movie24.png'),
(5, 5, 5, 'Donnie Darko', 2001, 113, 8.0, 'A troubled teenager plagued by visions.', 'assets/images/placeholders/movie15.png'),
(6, 7, 3, 'Apocalypse Now', 1979, 147, 8.4, 'A U.S. Army officer in Vietnam.', 'assets/images/placeholders/movie19.png'),
(7, 7, 6, 'Full Metal Jacket', 1987, 116, 8.3, 'A U.S. Marine in Vietnam.', 'assets/images/placeholders/movie16.png'),
(8, 14, 7, 'Andrei Rublev', 1966, 205, 8.1, 'The Russian iconographer.', 'assets/images/placeholders/movie23.png'),
(9, 1, 8, 'Birdman', 2014, 119, 7.7, 'A washed-up superhero actor.', 'assets/images/placeholders/movie7.png'),
(10, 11, 18, 'Filth', 2013, 97, 7.1, 'A bipolar junkie cop.', 'assets/images/placeholders/movie10.png'),
(11, 1, 9, 'Incendies', 2010, 131, 8.3, 'Twins journey to the Middle East.', 'assets/images/placeholders/movie11.png'),
(12, 10, 10, 'Control', 2007, 122, 7.6, 'A profile of Ian Curtis.', 'assets/images/placeholders/movie12.png'),
(13, 1, 11, 'Half Nelson', 2006, 106, 7.1, 'An inner-city teacher.', 'assets/images/placeholders/movie13.png'),
(14, 4, 12, '28 Days Later', 2002, 113, 7.5, 'A mysterious virus spreads.', 'assets/images/placeholders/movie14.png'),
(15, 13, 13, 'The Lighthouse', 2019, 109, 7.4, 'Two lighthouse keepers.', 'assets/images/placeholders/movie5.png'),
(16, 1, 14, 'Manchester by the Sea', 2016, 137, 7.8, 'A depressed uncle.', 'assets/images/placeholders/movie6.png'),
(17, 11, 22, 'Dinner in America', 2020, 106, 7.5, 'A punk rock frontman.', 'assets/images/placeholders/movie4.png'),
(18, 4, 16, 'Possession', 1981, 124, 7.3, 'Disturbing behavior.', 'assets/images/placeholders/movie18.png'),
(19, 4, 19, 'Videodrome', 1983, 87, 7.2, 'A dangerous broadcast.', 'assets/images/placeholders/movie17.png'),
(20, 1, 15, 'Aftersun', 2022, 102, 7.7, 'Sophie with her father.', 'assets/images/placeholders/movie2.png'),
(21, 1, 23, 'Sentimental Value', 2025, 120, 7.2, 'A personal story.', 'assets/images/placeholders/movie1.png'),
(22, 7, 20, 'Johnny Got His Gun', 1971, 111, 8.0, 'A young man wakes up.', 'assets/images/placeholders/movie21.png'),
(23, 5, 6, '2001: A Space Odyssey', 1968, 149, 8.3, 'Mankind on a quest.', 'assets/images/placeholders/movie22.png'),
(24, 14, 21, 'The Seventh Seal', 1957, 96, 8.1, 'A knight plays chess.', 'assets/images/placeholders/movie25.png'),
(25, 4, 17, 'Creep', 2014, 77, 6.3, 'A videographer accepts a job in a remote mountain town.', 'assets/images/placeholders/movie8.png');

-- --------------------------------------------------------

--
-- Структура таблицы `movie_actors`
--

CREATE TABLE `movie_actors` (
  `id_movie` int(11) NOT NULL,
  `id_actor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `movie_actors`
--

INSERT INTO `movie_actors` (`id_movie`, `id_actor`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(3, 6),
(3, 7),
(3, 14),
(4, 8),
(4, 16),
(5, 9),
(5, 51),
(6, 6),
(6, 10),
(7, 11),
(7, 12),
(8, 13),
(8, 56),
(9, 7),
(10, 29),
(10, 32),
(11, 27),
(11, 28),
(12, 5),
(13, 17),
(14, 17),
(14, 42),
(15, 1),
(16, 18),
(16, 19),
(17, 35),
(17, 38),
(18, 20),
(18, 46),
(19, 21),
(19, 30),
(20, 25),
(20, 26),
(21, 14),
(22, 11),
(23, 22),
(23, 44),
(24, 23),
(24, 24),
(24, 45);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id_review` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_movie` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_text` varchar(5000) DEFAULT NULL,
  `review_date` date DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `dislikes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id_review`, `id_user`, `id_movie`, `rating`, `review_text`, `review_date`, `likes`, `dislikes`) VALUES
(3, 7, 6, 5, 'ИМБА', '2026-06-17', 25, 18),
(4, 7, 9, 4, 'ТУПО Я', '2026-06-17', 7, 0),
(5, 6, 5, 2, 'не понял', '2026-06-17', 7, 0),
(6, 6, 10, 5, 'добро', '2026-06-17', 7, 43),
(7, 6, 12, 5, 'я как маленький йен кертис но мне 13 лет', '2026-06-17', 6, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `join_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `avatar_url`, `join_date`) VALUES
(2, 'Gallagher9', 'gallagher@mail.com', '$2y$10$hashedpassword2', 'assets/images/avatars/user2.png', '2026-04-15'),
(3, 'Barrett67', 'barrett@mail.com', '$2y$10$hashedpassword3', 'assets/images/avatars/user3.png', '2026-03-20'),
(4, 'casablancas228', 'casablanca@mail.com', '$2y$10$hashedpassword4', 'assets/images/avatars/user4.png', '2026-02-10'),
(5, 'liamg72', 'liam@mail.com', '$2y$10$hashedpassword5', 'assets/images/avatars/user5.png', '2026-01-05'),
(6, 'liam', 'isip_k.a.vilnikova@mpt.ru', '$2y$10$xAJUI/gXXPYhMzFUF.4B..WpFD5.ZyvaOM96P2K3Rcp5g5BZisj4.', 'uploads/avatars/user_6_1781917624.jpg', '2026-06-16'),
(7, 'noel', 'isip_k.a.vilnikova1@mpt.ru', '$2y$10$.AHixJQ4yhHtBsIX30J3CeyNOjsuNH9E3FGbHjAxiwtQ1Gx0vxYw.', 'uploads/avatars/user_7_1781725469.jpg', '2026-06-16'),
(8, 'kriss', 'hell1995ad@gmail.com', '$2y$10$zgpBHotgBdnE//wzwAcQC.jFLSZTMzBzUZwbW/0RjjleBjySRgSNW', 'uploads/avatars/user_8_1781725512.jpg', '2026-06-16'),
(10, 'krids', 'isip_k.a.vilnikov666@mpt.ru', '$2y$10$RnXmxGbgQubAKDR20rU1l.j2JgGjylrtgeVuMcB8AXRoBNiLK0FFe', NULL, '2026-06-20');

-- --------------------------------------------------------

--
-- Структура таблицы `user_movie_lists`
--

CREATE TABLE `user_movie_lists` (
  `id_list_item` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_movie` int(11) NOT NULL,
  `list_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `user_movie_lists`
--

INSERT INTO `user_movie_lists` (`id_list_item`, `id_user`, `id_movie`, `list_type`) VALUES
(3, 6, 10, 'watchlist'),
(9, 6, 3, 'favorites'),
(13, 6, 4, 'favorites'),
(14, 6, 8, 'favorites'),
(16, 6, 7, 'favorites'),
(17, 6, 22, 'watched'),
(18, 6, 2, 'watched'),
(21, 6, 16, 'watched'),
(22, 6, 25, 'watchlist'),
(23, 6, 19, 'watched'),
(26, 6, 13, 'watched'),
(27, 6, 15, 'watchlist'),
(28, 6, 21, 'watchlist'),
(29, 6, 4, 'watched');

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_latest_movies`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_latest_movies` (
`id_movie` int(11)
,`title` varchar(200)
,`year` int(11)
,`duration` int(11)
,`rating` decimal(2,1)
,`genre` varchar(50)
,`director` varchar(100)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_movies_by_actor`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_movies_by_actor` (
`id_movie` int(11)
,`title` varchar(200)
,`year` int(11)
,`rating` decimal(2,1)
,`actor` varchar(100)
,`poster_url` varchar(255)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_movies_by_director`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_movies_by_director` (
`id_movie` int(11)
,`title` varchar(200)
,`year` int(11)
,`duration` int(11)
,`rating` decimal(2,1)
,`director` varchar(100)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_movies_by_genre`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_movies_by_genre` (
`id_movie` int(11)
,`title` varchar(200)
,`year` int(11)
,`duration` int(11)
,`rating` decimal(2,1)
,`genre` varchar(50)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_movies_by_year`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_movies_by_year` (
`id_movie` int(11)
,`title` varchar(200)
,`year` int(11)
,`duration` int(11)
,`rating` decimal(2,1)
,`genre` varchar(50)
,`director` varchar(100)
);

-- --------------------------------------------------------

--
-- Структура для представления `v_latest_movies`
--
DROP TABLE IF EXISTS `v_latest_movies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_latest_movies`  AS SELECT `m`.`id_movie` AS `id_movie`, `m`.`title` AS `title`, `m`.`year` AS `year`, `m`.`duration` AS `duration`, `m`.`rating` AS `rating`, `g`.`name_genre` AS `genre`, `d`.`name_director` AS `director` FROM ((`movies` `m` left join `genres` `g` on(`m`.`id_genre` = `g`.`id_genre`)) left join `directors` `d` on(`m`.`id_director` = `d`.`id_director`)) ORDER BY `m`.`year` DESC, `m`.`id_movie` DESC ;

-- --------------------------------------------------------

--
-- Структура для представления `v_movies_by_actor`
--
DROP TABLE IF EXISTS `v_movies_by_actor`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_movies_by_actor`  AS SELECT `m`.`id_movie` AS `id_movie`, `m`.`title` AS `title`, `m`.`year` AS `year`, `m`.`rating` AS `rating`, `a`.`name_actor` AS `actor`, `m`.`poster_url` AS `poster_url` FROM ((`movies` `m` join `movie_actors` `ma` on(`m`.`id_movie` = `ma`.`id_movie`)) join `actors` `a` on(`ma`.`id_actor` = `a`.`id_actor`)) ;

-- --------------------------------------------------------

--
-- Структура для представления `v_movies_by_director`
--
DROP TABLE IF EXISTS `v_movies_by_director`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_movies_by_director`  AS SELECT `m`.`id_movie` AS `id_movie`, `m`.`title` AS `title`, `m`.`year` AS `year`, `m`.`duration` AS `duration`, `m`.`rating` AS `rating`, `d`.`name_director` AS `director` FROM (`movies` `m` join `directors` `d` on(`m`.`id_director` = `d`.`id_director`)) WHERE `d`.`name_director` = 'Francis Ford Coppola' ;

-- --------------------------------------------------------

--
-- Структура для представления `v_movies_by_genre`
--
DROP TABLE IF EXISTS `v_movies_by_genre`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_movies_by_genre`  AS SELECT `m`.`id_movie` AS `id_movie`, `m`.`title` AS `title`, `m`.`year` AS `year`, `m`.`duration` AS `duration`, `m`.`rating` AS `rating`, `g`.`name_genre` AS `genre` FROM (`movies` `m` join `genres` `g` on(`m`.`id_genre` = `g`.`id_genre`)) WHERE `g`.`name_genre` = 'Drama' ;

-- --------------------------------------------------------

--
-- Структура для представления `v_movies_by_year`
--
DROP TABLE IF EXISTS `v_movies_by_year`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_movies_by_year`  AS SELECT `m`.`id_movie` AS `id_movie`, `m`.`title` AS `title`, `m`.`year` AS `year`, `m`.`duration` AS `duration`, `m`.`rating` AS `rating`, `g`.`name_genre` AS `genre`, `d`.`name_director` AS `director` FROM ((`movies` `m` left join `genres` `g` on(`m`.`id_genre` = `g`.`id_genre`)) left join `directors` `d` on(`m`.`id_director` = `d`.`id_director`)) WHERE `m`.`year` = 2014 ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `actors`
--
ALTER TABLE `actors`
  ADD PRIMARY KEY (`id_actor`);

--
-- Индексы таблицы `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id_collection`),
  ADD KEY `id_user` (`id_user`);

--
-- Индексы таблицы `directors`
--
ALTER TABLE `directors`
  ADD PRIMARY KEY (`id_director`);

--
-- Индексы таблицы `forum_categories`
--
ALTER TABLE `forum_categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Индексы таблицы `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_topic` (`id_topic`),
  ADD KEY `id_user` (`id_user`);

--
-- Индексы таблицы `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`id_topic`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_movie` (`id_movie`);

--
-- Индексы таблицы `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id_genre`);

--
-- Индексы таблицы `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id_movie`),
  ADD KEY `id_genre` (`id_genre`),
  ADD KEY `id_director` (`id_director`);

--
-- Индексы таблицы `movie_actors`
--
ALTER TABLE `movie_actors`
  ADD PRIMARY KEY (`id_movie`,`id_actor`),
  ADD KEY `id_actor` (`id_actor`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_movie` (`id_movie`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `user_movie_lists`
--
ALTER TABLE `user_movie_lists`
  ADD PRIMARY KEY (`id_list_item`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_movie` (`id_movie`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `actors`
--
ALTER TABLE `actors`
  MODIFY `id_actor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT для таблицы `collections`
--
ALTER TABLE `collections`
  MODIFY `id_collection` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `directors`
--
ALTER TABLE `directors`
  MODIFY `id_director` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `forum_categories`
--
ALTER TABLE `forum_categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `id_topic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `genres`
--
ALTER TABLE `genres`
  MODIFY `id_genre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `movies`
--
ALTER TABLE `movies`
  MODIFY `id_movie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `user_movie_lists`
--
ALTER TABLE `user_movie_lists`
  MODIFY `id_list_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`id_topic`) REFERENCES `forum_topics` (`id_topic`),
  ADD CONSTRAINT `forum_posts_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD CONSTRAINT `forum_topics_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `forum_categories` (`id_category`),
  ADD CONSTRAINT `forum_topics_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `forum_topics_ibfk_3` FOREIGN KEY (`id_movie`) REFERENCES `movies` (`id_movie`);

--
-- Ограничения внешнего ключа таблицы `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`id_genre`) REFERENCES `genres` (`id_genre`),
  ADD CONSTRAINT `movies_ibfk_2` FOREIGN KEY (`id_director`) REFERENCES `directors` (`id_director`);

--
-- Ограничения внешнего ключа таблицы `movie_actors`
--
ALTER TABLE `movie_actors`
  ADD CONSTRAINT `movie_actors_ibfk_1` FOREIGN KEY (`id_movie`) REFERENCES `movies` (`id_movie`),
  ADD CONSTRAINT `movie_actors_ibfk_2` FOREIGN KEY (`id_actor`) REFERENCES `actors` (`id_actor`);

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_movie`) REFERENCES `movies` (`id_movie`);

--
-- Ограничения внешнего ключа таблицы `user_movie_lists`
--
ALTER TABLE `user_movie_lists`
  ADD CONSTRAINT `user_movie_lists_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `user_movie_lists_ibfk_2` FOREIGN KEY (`id_movie`) REFERENCES `movies` (`id_movie`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
