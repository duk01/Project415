-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 01:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `librarydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `published_date` varchar(20) DEFAULT NULL,
  `page_count` int(11) DEFAULT NULL,
  `categories` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `thumbnail_url` text DEFAULT NULL,
  `availability` enum('available','unavailable') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `google_id`, `title`, `author`, `publisher`, `published_date`, `page_count`, `categories`, `description`, `thumbnail_url`, `availability`) VALUES
(1, 'dSC2qIV5xNsC', 'Articulating Your UU Faith', 'Barbara Wells, Jaco B. ten Hove', NULL, NULL, NULL, NULL, NULL, NULL, 'available'),
(2, 'Jj25GlLKXSgC', 'To Err Is Human', 'Institute of Medicine, Committee on Quality of Health Care in America', NULL, NULL, NULL, NULL, NULL, NULL, 'available'),
(3, 'UeY-DwAAQBAJ', 'Wewe ni nani?', 'Dino Lingo', NULL, NULL, NULL, NULL, NULL, NULL, 'available'),
(4, 'L7PYAAAAMAAJ', 'Dictionary of Deities and Demons in the Bible (DDD)', 'K. van der Toorn, Bob Becking, Pieter Willem van der Horst', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable');

-- --------------------------------------------------------

--
-- Table structure for table `borrowedbooks`
--

CREATE TABLE `borrowedbooks` (
  `borrow_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowedbooks`
--

INSERT INTO `borrowedbooks` (`borrow_id`, `member_id`, `book_id`, `borrow_date`) VALUES
(4, 6, 4, '2025-04-23 19:11:44');

-- --------------------------------------------------------

--
-- Table structure for table `librarians`
--

CREATE TABLE `librarians` (
  `librarian_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `joined_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `librarians`
--

INSERT INTO `librarians` (`librarian_id`, `name`, `email`, `password`, `joined_date`) VALUES
(2, 'Alice Smith', 'alicesmith@montclair.edu', '6a11085ee35b74baad79c7ff922ac60593cb914888dd9d546975a23f9356226d', '2025-04-23 19:14:25'),
(4, 'John Doe', 'johndoe@montclair.edu', '13d53ab6c3ec446c5f5c8ad94528c03de0d0c37bce0e193d0ff2f4c9031a450f', '2025-04-23 20:06:54');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `membership_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `name`, `email`, `phone`, `address`, `password`, `membership_date`) VALUES
(1, 'Brandon Morones', 'brandonm@montclair.edu', '5551112233', '123 Elm St, Cityville', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', '2025-04-23 19:14:25'),
(2, 'Nayam Ahmed', 'nayama@montclair.edu', '5554446677', '456 Oak St, Townsville', '9878d344400c00f8bab1a4ba1a3488b3ace88aea983e3d94ba1c781e09ba32bb', '2025-04-23 19:14:25'),
(3, 'Kelly Du', 'kellyd@montclair.edu', '5558889999', '789 Pine St, Villagetown', '1e7724923bb42b54b5df6bf30e4bd8d9d4699788ea53483694d309f3d6cd5c0e', '2025-04-23 19:14:25'),
(4, 'Julia Eltarazy', 'juliae@montclair.edu', '5559876543', '789 Cedar Ave, Riverdale', 'daba7ba00826f471f9fe0489a7d16acdfa599d7870dfc8d26127d7c26bc35767', '2025-04-23 19:14:25'),
(5, 'Radlyn Hernandez', 'radlynh@montclair.edu', '5556543210', '159 Maple Rd, Lakeside', 'b8d5168e64a143c916dd8397f3e9058603c560d2fae3e4ea8f7ff15a450ef5a4', '2025-04-23 19:14:25'),
(6, 'Student', 'Student123@montclair.edu', NULL, NULL, '3a015ec69ae0b41ca3f8513273b2702d8a97a2939005c83698546c5fcb993192', '2025-04-23 23:11:19');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_id`, `member_id`, `question`, `answer`, `submitted_at`) VALUES
(7, 0, 5, 'How long can someone borrow a book?', '14 Days', '2025-04-23 18:48:32');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `librarian_id` int(11) DEFAULT NULL,
  `issue_date` date DEFAULT curdate(),
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `borrowStatus` enum('Issued','Returned','Overdue') DEFAULT 'Issued'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `book_id`, `member_id`, `librarian_id`, `issue_date`, `due_date`, `return_date`, `borrowStatus`) VALUES
(5, 1, 5, NULL, '2025-04-23', '2025-05-07', '2025-04-23', 'Returned'),
(6, 2, 5, NULL, '2025-04-23', '2025-05-07', '2025-04-23', 'Returned'),
(7, 3, 5, NULL, '2025-04-23', '2025-05-07', '2025-04-23', 'Returned'),
(8, 4, 6, NULL, '2025-04-23', '2025-05-07', NULL, 'Issued');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- Indexes for table `borrowedbooks`
--
ALTER TABLE `borrowedbooks`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `librarians`
--
ALTER TABLE `librarians`
  ADD PRIMARY KEY (`librarian_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `librarian_id` (`librarian_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `borrowedbooks`
--
ALTER TABLE `borrowedbooks`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `librarians`
--
ALTER TABLE `librarians`
  MODIFY `librarian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowedbooks`
--
ALTER TABLE `borrowedbooks`
  ADD CONSTRAINT `borrowedbooks_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowedbooks_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`librarian_id`) REFERENCES `librarians` (`librarian_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
