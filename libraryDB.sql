-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 03:26 PM
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
(4, 'L7PYAAAAMAAJ', 'Dictionary of Deities and Demons in the Bible (DDD)', 'K. van der Toorn, Bob Becking, Pieter Willem van der Horst', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(5, 'Q6Q4EAAAQBAJ', 'The New Father: A Dad\'s Guide to the First Year (Third Edition) (The New Father)', 'Armin A. Brott', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(6, 'sGaRPVkQRooC', 'Love You More', 'Lisa Gardner', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(7, '2TdTAQAAQBAJ', 'Portable Play in Everyday Life: The Nintendo DS', 'Samuel Tobin', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(8, 'rtw7n6XUpaEC', 'The ADA Companion Guide', 'Marcela A. Rhoads', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(9, 'Dc7RAgAAQBAJ', 'Children and Youth with Autism Spectrum Disorder (ASD)', 'James K. Luiselli Ph.D.', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(10, 'qnEsAAAAYAAJ', 'Augustino Da Siena', 'Augustino', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(11, 's5eqBAAAQBAJ', 'Understanding Differences and Disorders of Sex Development (DSD)', 'O. Hiort, S.F. Ahmed', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(12, 'FdeAEAAAQBAJ', 'Seafarers With Designated Security Duties (SDSD)', 'RAJ. Susilo Hadi Wibowo, Dwi Prasetyo, Fuad Ardani Rahman', NULL, NULL, NULL, NULL, NULL, NULL, 'available'),
(13, 'l-KjBgAAQBAJ', 'FF By Jonathan Hickman Vol. 4', 'Jonathan Hickman', NULL, NULL, NULL, NULL, NULL, NULL, 'available'),
(14, 'DGeCuAEACAAJ', 'Echoes of Mt. Erer', 'Teninet Wendrad', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(15, 'jJJ-ngEACAAJ', 'Just So Stories', 'Rudyard Kipling', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(16, '4OgIC6DruJsC', 'The Musical World of J.J. Johnson', 'Joshua Berrett, Louis G. Bourgois', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(17, 'HwVnAgAAQBAJ', 'ASDA Magic', 'David Smith', NULL, NULL, NULL, NULL, NULL, NULL, 'available'),
(18, 'S7-LvAHiry4C', 'Dad is Fat', 'Jim Gaffigan', NULL, NULL, NULL, NULL, NULL, NULL, 'available'),
(19, 'exGyDgAAQBAJ', 'Fibroblast Growth Factors: Biology And Clinical Application - Fgf Biology And Therapeutics', 'Michael Simons', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(20, '-4HMEAAAQBAJ', 'The New Father: A Dad\'s Guide to the First Year (Fourth Edition) (The New Father)', 'Armin A. Brott', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(21, 'BHgvqtaSBzUC', 'The Ki Process', 'Scott Shaw', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(22, 'tlAWV41QFBwC', 'Der Anu-Adad-tempel in Assur', 'Walter Andrae', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(23, 'N3TGDgAAQBAJ', 'As Brave As You', 'Jason Reynolds', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(24, 'xN5djcTFz24C', 'the df routines user\'s guide', 'alfred b. cocanower', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(25, 'u2HyzQEACAAJ', 'O Pioneers! - Publishing People Series', 'Willa Cather', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(26, 'hPsTEAAAQBAJ', 'Daily Reflections', 'Alcoholics Anonymous World Services, Inc.', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(27, 'BdphDwAAQBAJ', 'What Is the Weather?', 'Ethan Lewis', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(28, 'QO8zDwAAQBAJ', 'Dad By My Side', 'Soosh', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(29, 'u-BQnAEACAAJ', 'The Da Vinci Code', 'Dan Brown', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(30, 'dAlRob5EWL8C', '‏דרך ה׳', 'R\' Moshe C. Luzzatto', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(31, '8REoAQAAIAAJ', 'Underground', 'Mark Rudd', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(32, 'jH-vvgEACAAJ', 'Two Treatises on Civil Government', 'John Locke, Robert Filmer', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(33, 'rzXdGpkDa7YC', 'Have Blue and the F-117A', 'David C. Aronstein, Albert C. Piccirillo', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(34, 'k82OXPxJsj8C', 'The Aztecs', 'David Carrasco', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(35, 'VUVuwgEACAAJ', 'Leonardo Da Vinci', 'Sigmund Freud', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(36, 'JHenBAAAQBAJ', 'Wor(l)d Religions', 'Daniel Deleanu', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(37, 'y31CvgAACAAJ', 'LEONARDO DA VINCI ARTIST THINK', 'Euge Ne 1845-1902 Mu Ntz', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(38, 'YwpfewAACAAJ', 'K-ON!, Vol. 3', 'kakifly', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable'),
(39, '3ZszkAEACAAJ', 'The Odyssey, Ed. with References [&C. ] by H. Hayman', '. Homerus', NULL, NULL, NULL, NULL, NULL, NULL, 'unavailable');

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
(49, 5, 29, '2025-04-25 08:29:49'),
(50, 5, 35, '2025-04-25 08:30:27'),
(51, 5, 35, '2025-04-25 08:30:41'),
(52, 5, 36, '2025-04-25 08:35:49'),
(53, 5, 10, '2025-04-25 08:36:40'),
(54, 5, 37, '2025-04-25 09:04:48'),
(55, 5, 36, '2025-04-25 09:09:17'),
(56, 5, 38, '2025-04-25 09:09:33'),
(57, 5, 39, '2025-04-25 09:13:14'),
(58, 8, 29, '2025-04-25 09:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `fine_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `fine_amount` decimal(10,2) NOT NULL DEFAULT 30.00,
  `paid_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fines`
--

INSERT INTO `fines` (`fine_id`, `member_id`, `book_id`, `fine_amount`, `paid_status`, `created_at`) VALUES
(33, 5, 35, 30.00, 'paid', '2025-04-25 12:30:41'),
(34, 5, 36, 30.00, 'paid', '2025-04-25 12:36:04'),
(35, 5, 36, 30.00, 'paid', '2025-04-25 12:37:02'),
(36, 5, 36, 30.00, 'paid', '2025-04-25 12:38:04'),
(37, 5, 36, 30.00, 'paid', '2025-04-25 13:04:03'),
(38, 5, 36, 30.00, 'paid', '2025-04-25 13:05:20'),
(39, 5, 36, 30.00, 'paid', '2025-04-25 13:05:26'),
(40, 5, 36, 30.00, 'paid', '2025-04-25 13:08:53'),
(41, 5, 36, 30.00, 'paid', '2025-04-25 13:09:54'),
(42, 5, 39, 30.00, 'paid', '2025-04-25 13:13:28'),
(43, 5, 39, 30.00, 'paid', '2025-04-25 13:13:35'),
(44, 5, 39, 30.00, 'paid', '2025-04-25 13:19:31'),
(45, 5, 39, 30.00, 'paid', '2025-04-25 13:19:33');

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
  `membership_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `redhawk_balance` decimal(10,2) DEFAULT 100.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `name`, `email`, `phone`, `address`, `password`, `membership_date`, `redhawk_balance`) VALUES
(1, 'Brandon Morones', 'brandonm@montclair.edu', '5551112233', '123 Elm St, Cityville', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', '2025-04-23 19:14:25', 100.00),
(2, 'Nayam Ahmed', 'nayama@montclair.edu', '5554446677', '456 Oak St, Townsville', '9878d344400c00f8bab1a4ba1a3488b3ace88aea983e3d94ba1c781e09ba32bb', '2025-04-23 19:14:25', 100.00),
(3, 'Kelly Du', 'kellyd@montclair.edu', '5558889999', '789 Pine St, Villagetown', '1e7724923bb42b54b5df6bf30e4bd8d9d4699788ea53483694d309f3d6cd5c0e', '2025-04-23 19:14:25', 100.00),
(4, 'Julia Eltarazy', 'juliae@montclair.edu', '5559876543', '789 Cedar Ave, Riverdale', 'daba7ba00826f471f9fe0489a7d16acdfa599d7870dfc8d26127d7c26bc35767', '2025-04-23 19:14:25', 100.00),
(5, 'Radlyn Hernandez', 'radlynh@montclair.edu', '5556543210', '159 Maple Rd, Lakeside', 'b8d5168e64a143c916dd8397f3e9058603c560d2fae3e4ea8f7ff15a450ef5a4', '2025-04-23 19:14:25', 100.00),
(6, 'Student', 'Student123@montclair.edu', NULL, NULL, '3a015ec69ae0b41ca3f8513273b2702d8a97a2939005c83698546c5fcb993192', '2025-04-23 23:11:19', 100.00),
(7, 'billy joe', 'billy@montclair.edu', NULL, NULL, '05ae4d77852d7795c8ea0999fa8110181174db49072ddb026ef9bc3844cf6867', '2025-04-24 21:53:14', 100.00),
(8, 'DU', 'DU@montclair.edu', NULL, NULL, '08fa299aecc0c034e037033e3b0bbfaef26b78c742f16cf88ac3194502d6c394', '2025-04-25 13:22:18', 100.00);

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
(7, 0, 5, 'How long can someone borrow a book?', '14 Days', '2025-04-23 18:48:32'),
(8, 0, 5, 'How can I borrow a book?', 'By clicking Borrow/Return ', '2025-04-24 17:51:07');

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
(54, 29, 5, NULL, '2025-04-25', '2025-05-09', '2025-04-25', 'Returned'),
(55, 35, 5, NULL, '2025-04-25', '2025-05-09', '2025-04-25', 'Returned'),
(56, 35, 5, NULL, '2025-04-25', '2025-05-09', '2025-04-25', 'Returned'),
(57, 36, 5, NULL, '2025-04-25', '2025-05-09', '2025-04-25', 'Returned'),
(58, 10, 5, NULL, '2025-04-25', '2025-05-09', '2025-04-25', 'Returned'),
(59, 37, 5, NULL, '2025-04-25', '2025-05-09', '2025-04-25', 'Returned'),
(60, 36, 5, NULL, '2025-04-25', '2025-05-09', '2025-04-25', 'Returned'),
(61, 38, 5, NULL, '2025-04-25', '2025-05-09', '2025-04-25', 'Returned'),
(62, 39, 5, NULL, '2025-04-25', '2025-05-09', '2025-04-25', 'Returned'),
(63, 29, 8, NULL, '2025-04-25', '2025-05-09', NULL, 'Issued');

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
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`fine_id`),
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
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `borrowedbooks`
--
ALTER TABLE `borrowedbooks`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `fine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `librarians`
--
ALTER TABLE `librarians`
  MODIFY `librarian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

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
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
  ADD CONSTRAINT `fines_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

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
