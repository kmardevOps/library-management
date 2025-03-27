-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 08:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `librarygh`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `isbn` char(13) NOT NULL,
  `title` varchar(80) NOT NULL,
  `author` varchar(80) NOT NULL,
  `category` varchar(80) NOT NULL,
  `copies` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`isbn`, `title`, `author`, `category`, `copies`) VALUES
('9782616052277', 'X-Men: God Loves, Man Kills', 'Chris', 'Comics', 30),
('9789996245442', 'When Breath Becomes Air', 'Paul Kalanithi', 'Medical', 7),
('9782616052277', 'X-Men: God Loves, Man Kills', 'Chris', 'Comics', 30),
('9789996245442', 'When Breath Becomes Air', 'Paul Kalanithi', 'Medical', 7),
('0102', 'HTML Baby edition', 'Hello word', 'Technology', 0);

-- --------------------------------------------------------

--
-- Table structure for table `book_issue_log`
--

CREATE TABLE `book_issue_log` (
  `issue_id` int(11) NOT NULL,
  `member` varchar(20) NOT NULL,
  `book_isbn` varchar(13) NOT NULL,
  `due_date` date NOT NULL,
  `last_reminded` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `book_issue_log`
--

INSERT INTO `book_issue_log` (`issue_id`, `member`, `book_isbn`, `due_date`, `last_reminded`) VALUES
(0, 'harry', '9782616052277', '0000-00-00', NULL),
(0, 'mark', '1234', '0000-00-00', NULL),
(0, 'mark', '123', '0000-00-00', NULL),
(0, 'hello', '9789996245442', '2025-04-09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

CREATE TABLE `librarian` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`id`, `username`, `password`) VALUES
(1, 'harry', '93c768d0152f72bc8d5e782c0b585acc35fb0442'),
(0, 'john_doe', 'securepassword123');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `username`, `password`, `name`, `email`) VALUES
(1, 'kram', '8cb2237d0679ca88db6464eac60da96345513964', 'mark lumerio', 'lumerio@gmail.com'),
(13, 'christine', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 'Christine', 'christine400eer@gmail.com'),
(14, 'hello', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'mark hello', 'hello@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `pending_book_requests`
--

CREATE TABLE `pending_book_requests` (
  `request_id` int(11) NOT NULL,
  `member` varchar(20) NOT NULL,
  `book_isbn` varchar(13) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_registrations`
--

CREATE TABLE `pending_registrations` (
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
