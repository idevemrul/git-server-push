-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2021 at 01:29 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dynamic_billing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `dbs_account_coo_sub_head`
--

CREATE TABLE `dbs_account_coo_sub_head` (
  `accsh_id` int(6) NOT NULL,
  `ach_id` int(6) NOT NULL,
  `acsh_id` int(6) NOT NULL,
  `accsh_title` varchar(255) NOT NULL,
  `accsh_by` int(11) NOT NULL,
  `accsh_date` date NOT NULL,
  `accsh_time` varchar(8) NOT NULL,
  `accsh_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_account_coo_sub_head`
--

INSERT INTO `dbs_account_coo_sub_head` (`accsh_id`, `ach_id`, `acsh_id`, `accsh_title`, `accsh_by`, `accsh_date`, `accsh_time`, `accsh_status`) VALUES
(1, 2, 8, 'Computer', 8, '2021-10-02', '04:27:32', 0),
(2, 1, 1, 'It Lab Solution', 8, '2021-10-02', '05:04:31', 0),
(3, 1, 1, 'It Lab Solution 2', 8, '2021-10-02', '05:05:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_account_head`
--

CREATE TABLE `dbs_account_head` (
  `ach_id` int(6) NOT NULL,
  `ach_title` varchar(255) NOT NULL,
  `ach_by` int(11) NOT NULL,
  `ach_date` date NOT NULL,
  `ach_time` varchar(8) NOT NULL,
  `ach_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_account_head`
--

INSERT INTO `dbs_account_head` (`ach_id`, `ach_title`, `ach_by`, `ach_date`, `ach_time`, `ach_status`) VALUES
(1, 'Capital', 8, '2021-10-02', '11:53:53', 0),
(2, 'Asset', 8, '2021-10-02', '11:54:03', 0),
(3, 'Liability', 8, '2021-10-02', '11:54:13', 0),
(4, 'Revenue', 8, '2021-10-02', '11:54:33', 0),
(5, 'Expenditure', 8, '2021-10-02', '11:54:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_account_sub_head`
--

CREATE TABLE `dbs_account_sub_head` (
  `acsh_id` int(6) NOT NULL,
  `ach_id` int(6) NOT NULL,
  `acsh_title` varchar(60) NOT NULL,
  `acsh_by` int(11) NOT NULL,
  `acsh_date` date NOT NULL,
  `acsh_time` varchar(8) NOT NULL,
  `acsh_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_account_sub_head`
--

INSERT INTO `dbs_account_sub_head` (`acsh_id`, `ach_id`, `acsh_title`, `acsh_by`, `acsh_date`, `acsh_time`, `acsh_status`) VALUES
(1, 1, 'Donaton', 8, '2021-10-02', '12:12:48', 0),
(2, 1, 'Investment', 8, '2021-10-02', '12:13:14', 0),
(3, 1, 'profit', 8, '2021-10-02', '12:13:35', 0),
(4, 2, 'Account Receivable', 8, '2021-10-02', '12:20:38', 0),
(5, 2, 'Inventory', 8, '2021-10-02', '12:21:26', 0),
(6, 2, 'Cash Payment', 8, '2021-10-02', '12:21:41', 0),
(7, 2, 'Bank Payment', 8, '2021-10-02', '12:21:49', 0),
(8, 2, 'Purchase', 8, '2021-10-02', '12:22:14', 0),
(9, 2, 'Sale Collection', 8, '2021-10-02', '12:22:25', 0),
(10, 2, 'Due Collection', 8, '2021-10-02', '12:22:34', 0),
(11, 2, 'Cash Collection', 8, '2021-10-02', '12:22:44', 0),
(12, 2, 'Sale Return Asset', 8, '2021-10-02', '12:22:57', 0),
(13, 3, 'Account Payable', 8, '2021-10-02', '12:23:17', 0),
(14, 4, 'Product Sale', 8, '2021-10-02', '12:23:50', 0),
(15, 4, 'Interest', 8, '2021-10-02', '12:24:01', 0),
(16, 4, 'Purchase Discount', 8, '2021-10-02', '12:24:11', 0),
(17, 4, 'Purchase return', 8, '2021-10-02', '12:24:21', 0),
(18, 5, 'Bonus', 8, '2021-10-02', '12:24:45', 0),
(19, 5, 'Commission Pay', 8, '2021-10-02', '12:25:37', 0),
(20, 5, 'Sale Discount', 8, '2021-10-02', '12:26:02', 0),
(21, 5, 'Charity', 8, '2021-10-02', '12:26:19', 0),
(22, 5, 'Entertainment', 8, '2021-10-02', '12:26:29', 0),
(23, 5, 'paid discount', 8, '2021-10-02', '12:26:37', 0),
(24, 5, 'Product purchase', 8, '2021-10-02', '12:26:45', 0),
(25, 5, 'Rental', 8, '2021-10-02', '12:26:53', 0),
(26, 5, 'Salary', 8, '2021-10-02', '12:27:01', 0),
(27, 5, 'Sale Return Payment', 8, '2021-10-02', '12:29:04', 0),
(28, 5, 'Stationary', 8, '2021-10-02', '12:29:25', 0),
(29, 5, 'Transportation', 8, '2021-10-02', '12:29:33', 0),
(30, 5, 'Utility', 8, '2021-10-02', '12:29:41', 0),
(31, 5, 'voucher pay', 8, '2021-10-02', '12:29:50', 0),
(32, 5, 'Profit Withdrawal', 8, '2021-10-02', '12:30:08', 0),
(33, 1, 'hadup', 8, '2021-10-02', '02:56:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_address`
--

CREATE TABLE `dbs_address` (
  `add_id` varchar(12) NOT NULL,
  `party_id` varchar(12) NOT NULL,
  `party_mem_id` varchar(12) NOT NULL,
  `add_house` varchar(50) NOT NULL,
  `add_road` varchar(50) NOT NULL,
  `add_vill_area` varchar(50) NOT NULL,
  `add_post` varchar(50) NOT NULL,
  `add_post_code` int(8) NOT NULL,
  `add_ps` varchar(50) NOT NULL,
  `add_district` varchar(50) NOT NULL,
  `add_country` varchar(50) NOT NULL,
  `add_by` varchar(12) NOT NULL,
  `add_date` date NOT NULL,
  `add_time` varchar(8) NOT NULL,
  `add_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dbs_bank_ac`
--

CREATE TABLE `dbs_bank_ac` (
  `bank_id` varchar(12) NOT NULL,
  `bank_Name` varchar(150) NOT NULL,
  `bank_branchNo` varchar(60) NOT NULL,
  `bank_swiptCode` varchar(20) NOT NULL,
  `bank_accountNo` varchar(30) NOT NULL,
  `bank_accountTitle` varchar(150) NOT NULL,
  `bank_accountType` varchar(60) NOT NULL,
  `bank_accountAuthor` varchar(60) NOT NULL,
  `bank_acOpenDate` date NOT NULL,
  `bank_by` varchar(12) NOT NULL,
  `bank_date` date NOT NULL,
  `bank_time` varchar(8) NOT NULL,
  `bank_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dbs_cash_ac`
--

CREATE TABLE `dbs_cash_ac` (
  `cash_id` varchar(6) NOT NULL,
  `cash_title` varchar(60) NOT NULL,
  `cash_department` varchar(60) NOT NULL,
  `cash_description` varchar(255) NOT NULL,
  `cash_by` varchar(12) NOT NULL,
  `cash_date` date NOT NULL,
  `cash_time` varchar(8) NOT NULL,
  `cash_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dbs_file`
--

CREATE TABLE `dbs_file` (
  `file_id` varchar(12) NOT NULL,
  `file_title` varchar(255) NOT NULL,
  `file_description` varchar(21000) NOT NULL,
  `file_by` int(11) NOT NULL,
  `file_date` date NOT NULL,
  `file_time` varchar(8) NOT NULL,
  `file_status` tinyint(2) NOT NULL COMMENT '13 for deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_file`
--

INSERT INTO `dbs_file` (`file_id`, `file_title`, `file_description`, `file_by`, `file_date`, `file_time`, `file_status`) VALUES
('FIL000000001', 'This is first file update 2', 'This is file description of first file. Update 2', 8, '2021-01-25', '01:13:00', 13),
('FIL000000002', 'This is first file Updating 22 update', 'This is file description of first file. Updated....222', 8, '2021-01-25', '01:13:31', 1),
('FIL000000003', 'File Number 2', 'This is description of file 2', 8, '2021-01-25', '01:16:03', 13),
('FIL000000004', 'fdf fsaf dsf asf', ' fs sfa fsd fdsf j     sfda', 8, '2021-01-25', '02:13:51', 13),
('FIL000000005', 'Hada dajfldfkj oifal fjlkf', 'kljhjakls kldsfjasl fldfjsaofi ]f ldjsf [of]pa sfpdfpofjdks', 8, '2021-01-25', '02:37:25', 13),
('FIL000000006', 'Hada dajfldfkj oifal fjlkf', 'kljhjakls kldsfjasl fldfjsaofi ]f ldjsf [of]pa sfpdfpofjdks', 8, '2021-01-25', '02:37:28', 13),
('FIL000000007', 'Hada dajfldfkj oifal fjlkf', 'kljhjakls kldsfjasl fldfjsaofi ]f ldjsf [of]pa sfpdfpofjdks', 8, '2021-01-25', '02:37:30', 13),
('FIL000000008', 'Hada dajfldfkj oifal fjlkf', 'kljhjakls kldsfjasl fldfjsaofi ]f ldjsf [of]pa sfpdfpofjdks', 8, '2021-01-25', '02:37:32', 13),
('FIL000000009', 'Hada dajfldfkj oifal fjlkf', 'kljhjakls kldsfjasl fldfjsaofi ]f ldjsf [of]pa sfpdfpofjdks', 8, '2021-01-25', '02:37:39', 0),
('FIL000000010', 'fdfdsfasfasf', 'fdfsdaf sfd ', 8, '2021-01-25', '02:40:37', 0),
('FIL000000011', 'fdfdfd', 'Limits for the VARCHAR varies depending on charset used. Using ASCII would use 1 byte per character. Meaning you could store 65,535 characters. Using utf8 will use 3 bytes per character resulting in character limit of 21,844. BUT if you are using the modern multibyte charset utf8mb4 which you should use! It supports emojis and other special characters. It will be using 4 bytes per character. This will limit the number of characters per table to 16,383. Note that other fields such as INT will also be counted to these limits. Limits for the VARCHAR varies depending on charset used. Using ASCII would use 1 byte per character. Meaning you could store 65,535 characters. Using utf8 will use 3 bytes per character resulting in character limit of 21,844. BUT if you are using the modern multibyte charset utf8mb4 which you should use! It supports emojis and other special characters. It will be using 4 bytes per character. This will limit the number of characters per table to 16,383. Note that oth', 8, '2021-01-25', '03:28:34', 13),
('FIL000000012', 'fdfdfdfdsfad', 'What is Lorem Ipsum?\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\nWhy do we use it?\r\nIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#039;Content here, content here&#039;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#039;lorem ipsum&#039; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).\r\n\r\n\r\nWhere does it come from?\r\nContrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.\r\n\r\nThe standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\r\n\r\nWhere can I get some?\r\nThere are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#039;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#039;t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.\r\n\r\nWhat is Lorem Ipsum?\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\nWhy do we use it?\r\nIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#039;Content here, content here&#039;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#039;lorem ipsum&#039; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).\r\n\r\n\r\nWhere does it come from?\r\nContrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.\r\n\r\nThe standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\r\n\r\nWhere can I get some?\r\nThere are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#039;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#039;t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', 8, '2021-01-25', '03:31:41', 0),
('FIL000000013', 'sdfsdf', 'sfasf sdfaf asdfasf', 8, '2021-01-25', '03:54:30', 0),
('FIL000000014', 'Hello  df', 'fasdf sdfa fd  safdf ', 8, '2021-01-25', '03:56:00', 0),
('FIL000000015', 'Hello  df asdfsdfa ', 'fasdf sdfa fd  safdf ', 8, '2021-01-25', '03:57:16', 0),
('FIL000000016', 'sdfaf sdfa fa sfadf ', 'sadf df sfdaf sdf saf sdf safd sdf fsf ', 8, '2021-01-25', '03:57:27', 0),
('FIL000000017', 'd asdf asfd fd', 'dfdffas', 8, '2021-01-25', '03:57:46', 0),
('FIL000000018', 'fawfasdf', 'fasdf fdasf dsfa ffa fsaf afaf', 8, '2021-01-25', '05:43:26', 0),
('FIL000000019', 'Kawsar file', 'This is  dummy file', 7, '2021-02-07', '11:32:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_file_party`
--

CREATE TABLE `dbs_file_party` (
  `file_party_id` int(11) NOT NULL,
  `file_id` varchar(12) NOT NULL,
  `party_mem_id` varchar(12) NOT NULL,
  `file_party_by` varchar(12) NOT NULL,
  `file_party_date` date NOT NULL,
  `file_party_time` varchar(8) NOT NULL,
  `file_party_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relational Table';

--
-- Dumping data for table `dbs_file_party`
--

INSERT INTO `dbs_file_party` (`file_party_id`, `file_id`, `party_mem_id`, `file_party_by`, `file_party_date`, `file_party_time`, `file_party_status`) VALUES
(1, 'FIL000000001', 'PME000000001', '8', '2021-01-28', '07:20:57', 13),
(2, 'FIL000000001', 'PME000000002', '7', '2021-01-30', '11:36:09', 13),
(3, 'FIL000000001', 'PME000000003', '7', '2021-01-30', '11:36:31', 13),
(4, 'FIL000000001', 'PME000000008', '7', '2021-01-30', '11:52:11', 0),
(6, 'FIL000000002', 'PME000000005', '7', '2021-01-30', '11:56:32', 0),
(7, 'FIL000000003', 'PME000000001', '7', '2021-01-30', '11:59:43', 0),
(8, 'FIL000000003', 'PME000000006', '7', '2021-01-30', '11:59:47', 0),
(9, 'FIL000000005', 'PME000000001', '7', '2021-01-30', '12:22:29', 0),
(12, 'FIL000000001', 'PME000000009', '', '0000-00-00', '', 13),
(13, 'FIL000000001', 'PME000000002', '8', '2021-01-31', '10:21:31', 13),
(14, 'FIL000000001', 'PME000000003', '8', '2021-01-31', '10:22:00', 13),
(15, 'FIL000000001', 'PME000000001', '8', '2021-02-01', '10:14:13', 0),
(16, 'FIL000000001', 'PME000000006', '8', '2021-02-01', '10:14:21', 13),
(17, 'FIL000000019', 'PME000000007', '7', '2021-02-07', '11:34:01', 0),
(18, 'FIL000000004', 'PME000000010', '7', '2021-02-07', '03:37:25', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_file_service`
--

CREATE TABLE `dbs_file_service` (
  `file_serv_id` int(11) NOT NULL,
  `file_id` varchar(12) NOT NULL,
  `serv_id` varchar(12) NOT NULL,
  `file_serv_by` int(11) NOT NULL,
  `file_serv_date` date NOT NULL,
  `file_serv_time` varchar(8) NOT NULL,
  `file_serv_status` tinyint(2) NOT NULL COMMENT '13 for delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_file_service`
--

INSERT INTO `dbs_file_service` (`file_serv_id`, `file_id`, `serv_id`, `file_serv_by`, `file_serv_date`, `file_serv_time`, `file_serv_status`) VALUES
(22690, 'FIL000000001', 'SER000000005', 8, '2021-02-04', '11:47:35', 13),
(22691, 'FIL000000001', 'SER000000001', 8, '2021-02-04', '12:00:43', 0),
(22692, 'FIL000000001', 'SER000000002', 8, '2021-02-04', '12:00:43', 0),
(22693, 'FIL000000001', 'SER000000005', 8, '2021-02-04', '12:00:43', 1),
(22694, 'FIL000000001', 'SER000000004', 8, '2021-02-04', '12:05:31', 1),
(22695, 'FIL000000001', 'SER000000005', 8, '2021-02-04', '12:05:31', 1),
(22696, 'FIL000000001', 'SER000000005', 8, '2021-02-04', '12:05:48', 13),
(22697, 'FIL000000001', 'SER000000004', 8, '2021-02-04', '12:09:21', 13),
(22698, 'FIL000000001', 'SER000000007', 8, '2021-02-04', '12:09:21', 1),
(22699, 'FIL000000001', 'SER000000004', 8, '2021-02-04', '12:17:45', 13),
(22700, 'FIL000000001', 'SER000000004', 8, '2021-02-04', '01:48:10', 0),
(22701, 'FIL000000001', 'SER000000001', 8, '2021-02-04', '01:48:10', 0),
(22702, 'FIL000000001', 'SER000000004', 8, '2021-02-04', '01:49:11', 0),
(22703, 'FIL000000001', 'SER000000001', 8, '2021-02-04', '01:49:11', 0),
(22704, 'FIL000000001', 'SER000000004', 8, '2021-02-04', '01:49:34', 0),
(22705, 'FIL000000001', 'SER000000001', 8, '2021-02-04', '01:49:34', 0),
(22706, 'FIL000000001', 'SER000000004', 8, '2021-02-04', '01:50:10', 0),
(22707, 'FIL000000001', 'SER000000001', 8, '2021-02-04', '01:50:10', 0),
(22708, 'FIL000000001', 'SER000000003', 8, '2021-02-04', '01:51:03', 0),
(22709, 'FIL000000001', 'SER000000006', 8, '2021-02-04', '01:51:03', 0),
(22710, 'FIL000000001', 'SER000000002', 8, '2021-02-04', '01:51:03', 0),
(22711, 'FIL000000001', 'SER000000002', 8, '2021-02-04', '02:19:46', 0),
(22712, 'FIL000000001', 'SER000000004', 8, '2021-02-04', '02:19:46', 0),
(22713, 'FIL000000001', 'SER000000006', 8, '2021-02-04', '02:19:46', 0),
(22714, 'FIL000000001', 'SER000000002', 8, '2021-02-04', '02:24:06', 0),
(22715, 'FIL000000001', 'SER000000005', 8, '2021-02-04', '02:24:06', 13),
(22716, 'FIL000000003', 'SER000000008', 8, '2021-02-06', '11:57:26', 0),
(22717, 'FIL000000003', 'SER000000008', 8, '2021-02-06', '11:58:12', 0),
(22718, 'FIL000000003', 'SER000000002', 8, '2021-02-06', '12:03:48', 0),
(22719, 'FIL000000003', 'SER000000005', 8, '2021-02-06', '12:03:48', 0),
(22720, 'FIL000000003', 'SER000000008', 8, '2021-02-06', '12:03:48', 0),
(22721, 'FIL000000003', 'SER000000002', 8, '2021-02-06', '12:04:08', 0),
(22722, 'FIL000000003', 'SER000000005', 8, '2021-02-06', '12:04:08', 0),
(22723, 'FIL000000003', 'SER000000008', 8, '2021-02-06', '12:04:08', 0),
(22724, 'FIL000000003', 'SER000000006', 8, '2021-02-06', '05:40:52', 0),
(22725, 'FIL000000003', 'SER000000001', 8, '2021-02-06', '05:41:54', 0),
(22726, 'FIL000000003', 'SER000000004', 8, '2021-02-06', '05:44:58', 0),
(22727, 'FIL000000003', 'SER000000002', 8, '2021-02-06', '05:46:16', 0),
(22728, 'FIL000000003', 'SER000000005', 8, '2021-02-06', '05:51:05', 0),
(22729, 'FIL000000005', 'SER000000006', 8, '2021-02-06', '06:10:36', 0),
(22730, 'FIL000000005', 'SER000000005', 8, '2021-02-06', '06:10:36', 0),
(22731, 'FIL000000005', 'SER000000001', 8, '2021-02-06', '06:11:31', 0),
(22732, 'FIL000000005', 'SER000000007', 8, '2021-02-06', '06:11:31', 0),
(22733, 'FIL000000001', 'SER000000004', 7, '2021-02-07', '10:30:32', 0),
(22734, 'FIL000000001', 'SER000000007', 7, '2021-02-07', '10:33:58', 0),
(22735, 'FIL000000001', 'SER000000003', 7, '2021-02-07', '11:13:04', 0),
(22736, 'FIL000000019', 'SER000000002', 7, '2021-02-07', '11:37:16', 0),
(22737, 'FIL000000019', 'SER000000005', 7, '2021-02-07', '11:37:16', 0),
(22738, 'FIL000000019', 'SER000000006', 7, '2021-02-07', '11:37:16', 0),
(22739, 'FIL000000019', 'SER000000002', 7, '2021-02-07', '11:40:18', 0),
(22740, 'FIL000000019', 'SER000000007', 7, '2021-02-07', '11:41:30', 0),
(22741, 'FIL000000003', 'SER000000004', 7, '2021-02-07', '01:22:31', 0),
(22742, 'FIL000000003', 'SER000000004', 7, '2021-02-07', '01:22:31', 13),
(22743, 'FIL000000003', 'SER000000002', 7, '2021-02-07', '01:22:31', 0),
(22744, 'FIL000000001', 'SER000000004', 7, '2021-02-07', '01:49:37', 0),
(22745, 'FIL000000001', 'SER000000005', 7, '2021-02-07', '01:54:05', 0),
(22746, 'FIL000000001', 'SER000000004', 7, '2021-02-07', '02:05:35', 0),
(22747, 'FIL000000001', 'SER000000006', 7, '2021-02-07', '02:05:35', 0),
(22748, 'FIL000000004', 'SER000000004', 7, '2021-02-07', '03:38:43', 0),
(22749, 'FIL000000004', 'SER000000004', 7, '2021-02-07', '04:38:11', 0),
(22750, 'FIL000000004', 'SER000000007', 7, '2021-02-07', '04:38:11', 0),
(22751, 'FIL000000004', 'SER000000004', 7, '2021-02-07', '05:25:34', 0),
(22752, 'FIL000000003', 'SER000000002', 8, '2021-02-11', '10:56:56', 0),
(22753, 'FIL000000003', 'SER000000004', 8, '2021-02-11', '10:56:56', 0),
(22754, 'FIL000000003', 'SER000000003', 8, '2021-02-11', '11:17:23', 0),
(22755, 'FIL000000003', 'SER000000003', 8, '2021-02-11', '11:18:43', 0),
(22756, 'FIL000000003', 'SER000000003', 8, '2021-02-11', '11:23:44', 0),
(22757, 'FIL000000003', 'SER000000003', 8, '2021-02-11', '11:24:57', 0),
(22758, 'FIL000000003', 'SER000000003', 8, '2021-02-11', '11:47:56', 0),
(22759, 'FIL000000003', 'SER000000003', 8, '2021-02-11', '11:49:11', 0),
(22760, 'FIL000000003', 'SER000000003', 8, '2021-02-11', '11:50:51', 0),
(22761, 'FIL000000003', 'SER000000003', 8, '2021-02-11', '11:51:27', 0),
(22762, 'FIL000000003', 'SER000000003', 8, '2021-02-11', '11:52:05', 0),
(22763, 'FIL000000002', 'SER000000002', 8, '2021-02-11', '11:53:40', 0),
(22764, 'FIL000000002', 'SER000000002', 8, '2021-02-11', '11:57:52', 0),
(22765, 'FIL000000002', 'SER000000007', 8, '2021-02-11', '11:57:52', 0),
(22766, 'FIL000000002', 'SER000000002', 8, '2021-02-11', '01:59:27', 0),
(22767, 'FIL000000002', 'SER000000004', 8, '2021-02-11', '01:59:27', 0),
(22768, 'FIL000000003', 'SER000000005', 8, '2021-02-11', '04:50:46', 0),
(22769, 'FIL000000001', 'SER000000004', 8, '2021-02-11', '05:15:27', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_invoice`
--

CREATE TABLE `dbs_invoice` (
  `inv_id` int(11) NOT NULL,
  `file_id` varchar(12) NOT NULL,
  `inv_amount` float NOT NULL,
  `inv_discount_amount` float NOT NULL,
  `inv_paid_amount` float NOT NULL,
  `inv_remarks` varchar(155) NOT NULL,
  `inv_by` int(11) NOT NULL,
  `inv_date` date NOT NULL,
  `inv_time` varchar(8) NOT NULL,
  `inv_status` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_invoice`
--

INSERT INTO `dbs_invoice` (`inv_id`, `file_id`, `inv_amount`, `inv_discount_amount`, `inv_paid_amount`, `inv_remarks`, `inv_by`, `inv_date`, `inv_time`, `inv_status`) VALUES
(6, 'FIL000000001', 6009.7, 300, 500, 'Comments 4', 8, '2021-02-04', '01:51:03', '0'),
(7, 'FIL000000001', 20001.7, 0, 0, '', 8, '2021-02-04', '02:19:46', '0'),
(8, 'FIL000000001', 31401, 1400, 30000, 'Comments for 2 item 31401.04', 8, '2021-02-04', '02:24:06', '0'),
(9, 'FIL000000003', 4500, 0, 4500, 'No comments', 8, '2021-02-06', '11:57:26', '0'),
(10, 'FIL000000003', 1500, 100, 1000, 'All field', 8, '2021-02-06', '11:58:12', '0'),
(11, 'FIL000000003', 6700.22, 0, 5000, 'No comments', 8, '2021-02-06', '12:03:48', '0'),
(12, 'FIL000000003', 6700.22, 0, 5000, 'No comments', 8, '2021-02-06', '12:04:08', '0'),
(13, 'FIL000000003', 1000.1, 100, 500, 'No comments', 8, '2021-02-06', '05:46:16', '0'),
(14, 'FIL000000003', 4200.12, 200.12, 3000, 'No comments', 8, '2021-02-06', '05:51:05', '0'),
(15, 'FIL000000005', 7200.22, 100, 7000, 'No comments', 8, '2021-02-06', '06:10:36', '0'),
(16, 'FIL000000005', 12609.9, 500, 10000, 'New file add', 8, '2021-02-06', '06:11:31', '0'),
(17, 'FIL000000001', 5000.45, 0, 0, '', 7, '2021-02-07', '10:30:32', '0'),
(18, 'FIL000000001', 7600.88, 0, 0, '', 7, '2021-02-07', '10:33:58', '0'),
(19, 'FIL000000001', 2009.5, 100, 50, 'no comments', 7, '2021-02-07', '11:13:04', '0'),
(20, 'FIL000000019', 25000.8, 2000, 20000, 'Comments', 7, '2021-02-07', '11:37:16', '0'),
(21, 'FIL000000019', 5000.5, 0, 3000, 'dsfasdf', 7, '2021-02-07', '11:40:18', '0'),
(22, 'FIL000000019', 7600.88, 5000, 4000, 'sdjfklsdj', 7, '2021-02-07', '11:41:30', '0'),
(23, 'FIL000000003', 15000.5, 500.55, 10000, 'jhghjg', 7, '2021-02-07', '01:22:31', '0'),
(24, 'FIL000000001', 5000.45, 10.5, 150, 'fasfsdf', 7, '2021-02-07', '01:49:37', '0'),
(25, 'FIL000000001', 4200.12, 50, 1200, 'fasfas', 7, '2021-02-07', '01:54:05', '0'),
(26, 'FIL000000001', 8000.55, 150, 500, 'dfasf', 7, '2021-02-07', '02:05:35', '0'),
(27, 'FIL000000004', 5000.45, 10, 500, 'dsfas', 7, '2021-02-07', '03:38:43', '0'),
(28, 'FIL000000004', 12601.3, 1000, 1500, 'cvcvzxzvc', 7, '2021-02-07', '04:38:11', '0'),
(29, 'FIL000000004', 5000.45, 0.44, 5000, 'dfa', 7, '2021-02-07', '05:25:34', '0'),
(30, 'FIL000000003', 6000.55, 100, 1000, 'fsdfa', 8, '2021-02-11', '10:56:56', '0'),
(31, 'FIL000000002', 8600.98, 120, 8000, 'No comments', 8, '2021-02-11', '11:57:52', '0'),
(32, 'FIL000000002', 6000.55, 100, 1000, 'No comments', 8, '2021-02-11', '01:59:27', '0'),
(33, 'FIL000000003', 33601, 1500, 15000, 'dfsd', 8, '2021-02-11', '04:50:46', '0'),
(34, 'FIL000000001', 5000.45, 0, 0, 'dfsd', 8, '2021-02-11', '05:15:27', '0'),
(35, 'FIL000000001', 0, 0, 444, '', 8, '2021-02-11', '05:19:06', '0'),
(36, 'FIL000000001', 0, 0, 0, '', 8, '2021-02-11', '05:36:30', '0');

-- --------------------------------------------------------

--
-- Table structure for table `dbs_invoice_details`
--

CREATE TABLE `dbs_invoice_details` (
  `inv_det_id` int(11) NOT NULL,
  `inv_id` int(11) NOT NULL,
  `serv_id` varchar(12) NOT NULL,
  `inv_det_quantity` float NOT NULL,
  `inv_det_price` float NOT NULL,
  `inv_det_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_invoice_details`
--

INSERT INTO `dbs_invoice_details` (`inv_det_id`, `inv_id`, `serv_id`, `inv_det_quantity`, `inv_det_price`, `inv_det_status`) VALUES
(1, 6, 'SER000000002', 1, 1000.1, 0),
(2, 6, 'SER000000004', 1, 5000.45, 0),
(3, 6, 'SER000000005', 1, 4200.12, 0),
(4, 6, 'SER000000007', 1, 7600.88, 0),
(5, 7, 'SER000000002', 2, 1000.1, 0),
(6, 7, 'SER000000004', 3, 5000.45, 0),
(7, 7, 'SER000000006', 1, 3000.1, 0),
(8, 8, 'SER000000002', 2, 1000.1, 0),
(9, 8, 'SER000000005', 7, 4200.12, 0),
(10, 9, 'SER000000008', 3, 1500, 0),
(11, 10, 'SER000000008', 1, 1500, 0),
(12, 11, 'SER000000002', 1, 1000.1, 0),
(13, 11, 'SER000000005', 1, 4200.12, 0),
(14, 11, 'SER000000008', 1, 1500, 0),
(15, 12, 'SER000000002', 1, 1000.1, 0),
(16, 12, 'SER000000005', 1, 4200.12, 0),
(17, 12, 'SER000000008', 1, 1500, 0),
(18, 13, 'SER000000002', 1, 1000.1, 0),
(19, 14, 'SER000000005', 1, 4200.12, 0),
(20, 15, 'SER000000006', 1, 3000.1, 0),
(21, 15, 'SER000000005', 1, 4200.12, 0),
(22, 16, 'SER000000001', 1, 5009, 0),
(23, 16, 'SER000000007', 1, 7600.88, 0),
(24, 17, 'SER000000004', 1, 5000.45, 0),
(25, 18, 'SER000000007', 1, 7600.88, 0),
(26, 19, 'SER000000003', 1, 2009.5, 0),
(27, 20, 'SER000000002', 2, 1000.1, 0),
(28, 20, 'SER000000005', 5, 4200.12, 0),
(29, 20, 'SER000000006', 1, 2000, 0),
(30, 21, 'SER000000002', 5, 1000.1, 0),
(31, 22, 'SER000000007', 1, 7600.88, 0),
(32, 23, 'SER000000004', 2, 4500, 0),
(33, 23, 'SER000000004', 1, 5000.45, 0),
(34, 23, 'SER000000002', 1, 1000.1, 0),
(35, 24, 'SER000000004', 1, 5000.45, 0),
(36, 25, 'SER000000005', 1, 4200.12, 0),
(37, 26, 'SER000000004', 1, 5000.45, 0),
(38, 26, 'SER000000006', 1, 3000.1, 0),
(39, 27, 'SER000000004', 1, 5000.45, 0),
(40, 28, 'SER000000004', 1, 5000.45, 0),
(41, 28, 'SER000000007', 1, 7600.88, 0),
(42, 29, 'SER000000004', 1, 5000.45, 0),
(43, 30, 'SER000000002', 1, 1000.1, 0),
(44, 30, 'SER000000004', 1, 5000.45, 0),
(45, 31, 'SER000000002', 1, 1000.1, 0),
(46, 31, 'SER000000007', 1, 7600.88, 0),
(47, 32, 'SER000000002', 1, 1000.1, 0),
(48, 32, 'SER000000004', 1, 5000.45, 0),
(49, 33, 'SER000000005', 8, 4200.12, 0),
(50, 34, 'SER000000004', 1, 5000.45, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_party`
--

CREATE TABLE `dbs_party` (
  `party_id` varchar(12) NOT NULL,
  `file_id` varchar(12) NOT NULL,
  `party_name` varchar(60) NOT NULL,
  `party_by` varchar(12) NOT NULL,
  `party_date` date NOT NULL,
  `party_time` varchar(8) NOT NULL,
  `party_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dbs_party_member`
--

CREATE TABLE `dbs_party_member` (
  `party_mem_id` varchar(12) NOT NULL,
  `party_mem_name` varchar(60) NOT NULL,
  `party_mem_designation` varchar(60) NOT NULL,
  `party_mem_email` varchar(60) NOT NULL,
  `party_mem_cell` varchar(16) NOT NULL,
  `party_mem_nid` varchar(20) NOT NULL,
  `party_mem_passport` varchar(20) NOT NULL,
  `party_mem_image` varchar(150) NOT NULL,
  `party_mem_note` varchar(555) NOT NULL,
  `party_mem_by` int(11) NOT NULL,
  `party_mem_date` date NOT NULL,
  `party_mem_time` varchar(8) NOT NULL,
  `party_mem_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_party_member`
--

INSERT INTO `dbs_party_member` (`party_mem_id`, `party_mem_name`, `party_mem_designation`, `party_mem_email`, `party_mem_cell`, `party_mem_nid`, `party_mem_passport`, `party_mem_image`, `party_mem_note`, `party_mem_by`, `party_mem_date`, `party_mem_time`, `party_mem_status`) VALUES
('PME000000001', 'Hamad', 'Director', 'hamad@gmail.com', '011010213', 'bn0132156465132', 'BN5646513216', 'http://[::1]/dbs/asset/img/party/download_2.jpg', 'No comments', 8, '2021-01-27', '09:35:12', 1),
('PME000000002', 'Ali', 'Director', 'ali@g.com', '021456413210', 'NOI35456465', 'bn665456546', 'http://[::1]/dbs/asset/img/party/download_(1)3.jpg', 'No comments 2', 8, '2021-01-27', '09:44:35', 1),
('PME000000003', 'faf', 'fasdfds', 'fasf@t.c', '42343', 'sfer543', 'wdewr432543', 'http://[::1]/dbs/asset/img/party/download_(1)4.jpg', 'dfaffdsa', 8, '2021-01-27', '10:12:49', 13),
('PME000000004', 'Kawsar', 'Managing Director', 'kawsar@g.com', '0156415231', '321564651321', 'BD354651', 'http://[::1]/dbs/asset/img/party/people4.png', 'This is testing file for data simulation.', 8, '2021-01-27', '10:55:33', 13),
('PME000000005', 'akas', 'Manager', 'akas@g.com', '23156465454', '3216546+54', 'BR321564', 'http://[::1]/dbs/asset/img/party/download_(1)5.jpg', 'fasfs ', 8, '2021-01-27', '10:59:34', 13),
('PME000000006', 'Mohammad Ali', 'Accountent', 'mali@gmail.com', '012315462', 'BF23156465135', 'BM564631231', 'http://[::1]/dbs/asset/img/party/download_(3)2.jpg', 'This is test add', 8, '2021-01-27', '04:17:06', 13),
('PME000000007', 'Haris ', 'Developer', 'haris@d.com', '00234654121', '0231542164', 'NF021134552', 'http://[::1]/dbs/asset/img/party/people5.png', 'This is test ........', 8, '2021-01-27', '04:18:12', 0),
('PME000000008', 'Rahim', 'Director', 'rahim@gmail.com', '0123135465', 'N3216546541541', 'BD21654', 'http://[::1]/dbs/asset/img/party/people6.png', 'This is from new form', 8, '2021-01-28', '03:20:24', 0),
('PME000000009', 'Jodu', 'Technician', 'a@g.com', '1235615', '654654654654', 'vd54564654', 'http://[::1]/dbs/asset/img/party/download_(3)3.jpg', 'Nu', 8, '2021-01-28', '03:25:52', 0),
('PME000000010', 'Suvo', 'Developer', 'dev@gmail.com', '654564654', 's564564', 'BD23145646', 'http://[::1]/dbs/asset/img/party/download_(3)4.jpg', 'Hello buddi', 8, '2021-01-28', '03:31:37', 0),
('PME000000011', 'dfa', 'fasdfds', 'fdsf@f.c', '5646546', '564654', '3212', 'http://[::1]/dbs/asset/img/party/download_(2)22.jpg', 'Hadi', 8, '2021-01-28', '03:32:24', 0),
('PME000000012', 'vcvd', 'dsfadfs', 'abaed@g.com', '65464', '014654+62313', 'bad46416', 'http://[::1]/dbs/asset/img/party/download_(3)5.jpg', 'fasdfasdfsdf asf as', 8, '2021-01-28', '05:01:43', 0),
('PME000000013', 'sdfasf', 'fasfdsf', 'fasfsf@t.c', '54984', '', '', 'http://[::1]/dbs/asset/img/party/download_(3)6.jpg', '', 8, '2021-01-28', '05:14:22', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_payment`
--

CREATE TABLE `dbs_payment` (
  `pay_id` int(11) NOT NULL,
  `file_id` varchar(12) NOT NULL,
  `pay_inv_amount` float NOT NULL,
  `pay_paid` float NOT NULL,
  `pay_balance` float NOT NULL,
  `pay_remarks` varchar(155) NOT NULL,
  `pay_by` int(11) NOT NULL,
  `pay_dateE` date NOT NULL,
  `pay_date` date NOT NULL,
  `pay_time` varchar(8) NOT NULL,
  `pay_status` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_payment`
--

INSERT INTO `dbs_payment` (`pay_id`, `file_id`, `pay_inv_amount`, `pay_paid`, `pay_balance`, `pay_remarks`, `pay_by`, `pay_dateE`, `pay_date`, `pay_time`, `pay_status`) VALUES
(1, 'FIL000000003', 1500, 1000, 500, 'First test', 8, '0000-00-00', '2021-02-11', '', ''),
(3, 'FIL000000003', 500, 200, 800, 'test', 8, '0000-00-00', '2021-02-11', '', ''),
(5, 'FIL000000002', 700, 200, 500, 'dfsdf', 8, '0000-00-00', '2021-02-11', '', ''),
(7, 'FIL000000003', 2009.5, 220, 2589.5, 'fdsfa', 8, '0000-00-00', '2021-02-11', '11:52:05', '0'),
(8, 'FIL000000002', 1000.1, 550, 950.1, 'fsfa', 8, '0000-00-00', '2021-02-11', '11:53:40', '0'),
(9, 'FIL000000002', 8600.98, 8120, 1431.08, 'No comments', 8, '0000-00-00', '2021-02-11', '11:57:52', '0'),
(12, 'FIL000000002', 0, 44, 1387.08, 'dfdsf', 8, '2021-02-11', '2021-02-11', '08:27:04', '0'),
(13, 'FIL000000002', 0, 500, 887.08, 'Double entry', 8, '2021-02-11', '2021-02-11', '08:57:54', '0'),
(14, 'FIL000000002', 6000.55, 1100, 5787.63, 'No comments', 8, '0000-00-00', '2021-02-11', '01:59:27', '0'),
(15, 'FIL000000002', 0, 500, 5287.63, 'drfdsf', 8, '2021-02-11', '2021-02-11', '09:01:29', '0'),
(16, 'FIL000000002', 0, 287, 5000.63, 'dfdsf', 8, '2021-02-11', '2021-02-11', '09:16:33', '0'),
(18, 'FIL000000003', 33601, 16500, 19690.5, 'dfsd', 8, '0000-00-00', '2021-02-11', '04:50:46', '0'),
(20, 'FIL000000003', 0, 10000, 9690.5, 'due 9690.50', 8, '2021-02-11', '2021-02-11', '11:58:36', '0'),
(21, 'FIL000000001', 0, 3000, -3000, 'Advance pay', 8, '2021-02-11', '2021-02-11', '12:13:53', '0'),
(22, 'FIL000000001', 0, 3500, -6500, 'dfsf', 8, '2021-02-11', '2021-02-11', '12:14:49', '0'),
(23, 'FIL000000001', 5000.45, 0, -1499.55, 'dfsd', 8, '0000-00-00', '2021-02-11', '05:15:27', '0'),
(24, 'FIL000000001', 0, 444, -1943.55, '', 8, '0000-00-00', '2021-02-11', '05:19:06', '0'),
(25, 'FIL000000001', 0, 0, -1943.55, '', 8, '0000-00-00', '2021-02-11', '05:36:30', '0');

-- --------------------------------------------------------

--
-- Table structure for table `dbs_sale_service`
--

CREATE TABLE `dbs_sale_service` (
  `serv_sale_id` varchar(12) NOT NULL,
  `file_id` varchar(12) NOT NULL,
  `serv_id` varchar(12) NOT NULL,
  `serv_sale_quantity` int(2) NOT NULL,
  `serv_sale_comments` varchar(255) NOT NULL,
  `serv_by` varchar(12) NOT NULL,
  `serv_date` date NOT NULL,
  `serv_time` varchar(8) NOT NULL,
  `serv_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dbs_service`
--

CREATE TABLE `dbs_service` (
  `serv_id` varchar(12) NOT NULL,
  `serv_title` varchar(255) NOT NULL,
  `serv_description` varchar(1000) NOT NULL,
  `serv_period` varchar(30) NOT NULL,
  `serv_rate` float NOT NULL,
  `serv_by` int(11) NOT NULL,
  `serv_date` date NOT NULL,
  `serv_time` varchar(8) NOT NULL,
  `serv_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_service`
--

INSERT INTO `dbs_service` (`serv_id`, `serv_title`, `serv_description`, `serv_period`, `serv_rate`, `serv_by`, `serv_date`, `serv_time`, `serv_status`) VALUES
('SER000000001', 'Service title 1 Update', 'details 19', '', 5009, 8, '2021-01-31', '11:13:26', 13),
('SER000000002', 'Service title 2', 'Service details 2', '', 1000.1, 8, '2021-01-31', '11:15:33', 1),
('SER000000003', 'Service title 3 Update', 'Service details 3 Update', '', 2009.5, 8, '2021-01-31', '11:18:58', 1),
('SER000000004', 'Service title 4', 'Service details 4', '', 5000.45, 8, '2021-01-31', '03:20:16', 13),
('SER000000005', 'Service title 5', 'Service description 5', '', 4200.12, 8, '2021-01-31', '03:21:29', 13),
('SER000000006', 'Service title 6', 'Service description 6', '', 3000.1, 8, '2021-01-31', '03:22:15', 13),
('SER000000007', 'Service title 7', 'Service details 7', '', 7600.88, 8, '2021-01-31', '03:22:56', 0),
('SER000000008', 'Service title 8', '1 Year Security service', '', 1500, 8, '2021-02-06', '11:56:45', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_transaction`
--

CREATE TABLE `dbs_transaction` (
  `tran_id` int(11) NOT NULL,
  `ach_id` varchar(6) NOT NULL,
  `acsh_id` varchar(6) NOT NULL,
  `accsh_id` varchar(7) NOT NULL,
  `file_id` varchar(12) NOT NULL,
  `tran_details` varchar(255) NOT NULL,
  `tran_mode` varchar(60) NOT NULL,
  `tran_reference` varchar(12) NOT NULL,
  `tran_dateE` date NOT NULL,
  `tran_dr` float NOT NULL,
  `tran_cr` float NOT NULL,
  `tran_by` varchar(12) NOT NULL,
  `tran_date` date NOT NULL,
  `tran_time` varchar(8) NOT NULL,
  `tran_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_transaction`
--

INSERT INTO `dbs_transaction` (`tran_id`, `ach_id`, `acsh_id`, `accsh_id`, `file_id`, `tran_details`, `tran_mode`, `tran_reference`, `tran_dateE`, `tran_dr`, `tran_cr`, `tran_by`, `tran_date`, `tran_time`, `tran_status`) VALUES
(18, 'Revenu', 'Sale', 'Service', 'FIL000000002', '', 'Cr', '8', '2021-02-06', 0, 9209.12, '8', '2021-02-06', '06:49:46', 0),
(19, 'Asset', 'Cash', 'Main Ca', 'FIL000000002', '', 'Dr', '8', '2021-02-06', 9209.12, 0, '8', '2021-02-06', '06:49:46', 0),
(20, 'Revenu', 'Sale', 'Service', 'FIL000000003', '', 'Cr', '9', '2021-02-06', 0, 4500, '8', '2021-02-06', '11:57:26', 0),
(21, 'Asset', 'Cash', 'Main Ca', 'FIL000000003', '', 'Dr', '9', '2021-02-06', 4500, 0, '8', '2021-02-06', '11:57:26', 0),
(22, 'Revenu', 'Sale', 'Service', 'FIL000000003', '', 'Cr', '10', '2021-02-06', 0, 1500, '8', '2021-02-06', '11:58:12', 0),
(23, 'Asset', 'Cash', 'Main Ca', 'FIL000000003', '', 'Dr', '10', '2021-02-06', 1000, 0, '8', '2021-02-06', '11:58:12', 0),
(24, 'Expend', 'Paid d', 'Sale du', 'FIL000000003', '', 'Dr', '10', '2021-02-06', 100, 0, '8', '2021-02-06', '11:58:12', 0),
(25, 'Asset', 'A/C re', 'Due cas', 'FIL000000003', '', 'Dr', '10', '2021-02-06', 400, 0, '8', '2021-02-06', '11:58:12', 0),
(26, 'Revenu', 'Sale', 'Service', 'FIL000000003', '', 'Cr', '11', '2021-02-06', 0, 6700.22, '8', '2021-02-06', '12:03:48', 0),
(27, 'Asset', 'Cash', 'Main Ca', 'FIL000000003', '', 'Dr', '11', '2021-02-06', 5000, 0, '8', '2021-02-06', '12:03:48', 0),
(28, 'Asset', 'A/C re', 'Due cas', 'FIL000000003', '', 'Dr', '11', '2021-02-06', 1700.22, 0, '8', '2021-02-06', '12:03:48', 0),
(29, 'Revenu', 'Sale', 'Service', 'FIL000000003', '', 'Cr', '12', '2021-02-06', 0, 6700.22, '8', '2021-02-06', '12:04:08', 0),
(30, 'Asset', 'Cash', 'Main Ca', 'FIL000000003', '', 'Dr', '12', '2021-02-06', 5000, 0, '8', '2021-02-06', '12:04:08', 0),
(31, 'Asset', 'A/C re', 'Due cas', 'FIL000000003', '', 'Dr', '12', '2021-02-06', 1700.22, 0, '8', '2021-02-06', '12:04:08', 0),
(32, 'Revenu', 'Sale', 'Service', 'FIL000000003', '', 'Cr', '13', '2021-02-06', 0, 1000.1, '8', '2021-02-06', '05:46:17', 0),
(33, 'Asset', 'Cash', 'Main Ca', 'FIL000000003', '', 'Dr', '13', '2021-02-06', 500, 0, '8', '2021-02-06', '05:46:17', 0),
(34, 'Expend', 'Paid d', 'Sale du', 'FIL000000003', '', 'Dr', '13', '2021-02-06', 100, 0, '8', '2021-02-06', '05:46:17', 0),
(35, 'Asset', 'A/C re', 'Due cas', 'FIL000000003', '', 'Dr', '13', '2021-02-06', 400.1, 0, '8', '2021-02-06', '05:46:17', 0),
(36, 'Revenu', 'Sale', 'Service', 'FIL000000003', '', 'Cr', '14', '2021-02-06', 0, 4200.12, '8', '2021-02-06', '05:51:05', 0),
(37, 'Asset', 'Cash', 'Main Ca', 'FIL000000003', '', 'Dr', '14', '2021-02-06', 3000, 0, '8', '2021-02-06', '05:51:05', 0),
(38, 'Expend', 'Paid d', 'Sale du', 'FIL000000003', '', 'Dr', '14', '2021-02-06', 200.12, 0, '8', '2021-02-06', '05:51:05', 0),
(39, 'Asset', 'A/C re', 'Due cas', 'FIL000000003', '', 'Dr', '14', '2021-02-06', 1000, 0, '8', '2021-02-06', '05:51:05', 0),
(40, 'Revenu', 'Sale', 'Service', 'FIL000000005', '', 'Cr', '15', '2021-02-06', 0, 7200.22, '8', '2021-02-06', '06:10:36', 0),
(41, 'Asset', 'Cash', 'Main Ca', 'FIL000000005', '', 'Dr', '15', '2021-02-06', 7000, 0, '8', '2021-02-06', '06:10:36', 0),
(42, 'Expend', 'Paid d', 'Sale du', 'FIL000000005', '', 'Dr', '15', '2021-02-06', 100, 0, '8', '2021-02-06', '06:10:36', 0),
(43, 'Asset', 'A/C re', 'Due cas', 'FIL000000005', '', 'Dr', '15', '2021-02-06', 100.22, 0, '8', '2021-02-06', '06:10:36', 0),
(44, 'Revenu', 'Sale', 'Service', 'FIL000000005', '', 'Cr', '16', '2021-02-06', 0, 12609.9, '8', '2021-02-06', '06:11:31', 0),
(45, 'Asset', 'Cash', 'Main Ca', 'FIL000000005', '', 'Dr', '16', '2021-02-06', 10000, 0, '8', '2021-02-06', '06:11:31', 0),
(46, 'Expend', 'Paid d', 'Sale du', 'FIL000000005', '', 'Dr', '16', '2021-02-06', 500, 0, '8', '2021-02-06', '06:11:31', 0),
(47, 'Asset', 'A/C re', 'Due cas', 'FIL000000005', '', 'Dr', '16', '2021-02-06', 2109.9, 0, '8', '2021-02-06', '06:11:31', 0),
(48, 'Revenu', 'Sale', 'Service', 'FIL000000001', '', 'Cr', '17', '2021-02-07', 0, 5000.45, '7', '2021-02-07', '10:30:32', 0),
(49, 'Asset', 'A/C re', 'Due cas', 'FIL000000001', '', 'Dr', '17', '2021-02-07', 5000.45, 0, '7', '2021-02-07', '10:30:32', 0),
(50, 'Revenu', 'Sale', 'Service', 'FIL000000001', '', 'Cr', '18', '2021-02-07', 0, 7600.88, '7', '2021-02-07', '10:33:58', 0),
(51, 'Asset', 'A/C re', 'Due cas', 'FIL000000001', '', 'Dr', '18', '2021-02-07', 7600.88, 0, '7', '2021-02-07', '10:33:58', 0),
(52, 'Revenu', 'Sale', 'Service', 'FIL000000001', '', 'Cr', '19', '2021-02-07', 0, 2009.5, '7', '2021-02-07', '11:13:05', 0),
(53, 'Asset', 'Cash', 'Main Ca', 'FIL000000001', '', 'Dr', '19', '2021-02-07', 50, 0, '7', '2021-02-07', '11:13:05', 0),
(54, 'Expend', 'Paid d', 'Sale du', 'FIL000000001', '', 'Dr', '19', '2021-02-07', 100, 0, '7', '2021-02-07', '11:13:05', 0),
(55, 'Asset', 'A/C re', 'Due cas', 'FIL000000001', '', 'Dr', '19', '2021-02-07', 1859.5, 0, '7', '2021-02-07', '11:13:05', 0),
(56, 'Revenu', 'Sale', 'Service', 'FIL000000019', '', 'Cr', '20', '2021-02-07', 0, 25000.8, '7', '2021-02-07', '11:37:16', 0),
(57, 'Asset', 'Cash', 'Main Ca', 'FIL000000019', '', 'Dr', '20', '2021-02-07', 20000, 0, '7', '2021-02-07', '11:37:16', 0),
(58, 'Expend', 'Paid d', 'Sale du', 'FIL000000019', '', 'Dr', '20', '2021-02-07', 2000, 0, '7', '2021-02-07', '11:37:16', 0),
(59, 'Asset', 'A/C re', 'Due cas', 'FIL000000019', '', 'Dr', '20', '2021-02-07', 3000.8, 0, '7', '2021-02-07', '11:37:16', 0),
(60, 'Revenu', 'Sale', 'Service', 'FIL000000019', '', 'Cr', '21', '2021-02-07', 0, 5000.5, '7', '2021-02-07', '11:40:18', 0),
(61, 'Asset', 'Cash', 'Main Ca', 'FIL000000019', '', 'Dr', '21', '2021-02-07', 3000, 0, '7', '2021-02-07', '11:40:18', 0),
(62, 'Asset', 'A/C re', 'Due cas', 'FIL000000019', '', 'Dr', '21', '2021-02-07', 2000.5, 0, '7', '2021-02-07', '11:40:18', 0),
(66, 'Revenu', 'Sale', 'Service', 'FIL000000003', '', 'Cr', '23', '2021-02-07', 0, 15000.5, '7', '2021-02-07', '01:22:31', 0),
(67, 'Asset', 'Cash', 'Main Ca', 'FIL000000003', '', 'Dr', '23', '2021-02-07', 10000, 0, '7', '2021-02-07', '01:22:31', 0),
(68, 'Expend', 'Paid d', 'Sale du', 'FIL000000003', '', 'Dr', '23', '2021-02-07', 500.5, 0, '7', '2021-02-07', '01:22:31', 0),
(69, 'Asset', 'A/C re', 'Due cas', 'FIL000000003', '', 'Dr', '23', '2021-02-07', 4500, 0, '7', '2021-02-07', '01:22:31', 0),
(70, 'Revenu', 'Sale', 'Service', 'FIL000000001', '', 'Cr', '24', '2021-02-07', 0, 5000.45, '7', '2021-02-07', '01:49:37', 0),
(71, 'Asset', 'Cash', 'Main Ca', 'FIL000000001', '', 'Dr', '24', '2021-02-07', 150, 0, '7', '2021-02-07', '01:49:37', 0),
(72, 'Expend', 'Paid d', 'Sale du', 'FIL000000001', '', 'Dr', '24', '2021-02-07', 10.5, 0, '7', '2021-02-07', '01:49:37', 0),
(73, 'Asset', 'A/C re', 'Due cas', 'FIL000000001', '', 'Dr', '24', '2021-02-07', 4839.95, 0, '7', '2021-02-07', '01:49:37', 0),
(74, 'Asset', 'Cash', 'Main Ca', 'FIL000000001', '', 'Dr', '25', '2021-02-07', 1200, 0, '7', '2021-02-07', '01:54:05', 0),
(75, 'Expend', 'Paid d', 'Sale du', 'FIL000000001', '', 'Dr', '25', '2021-02-07', 50, 0, '7', '2021-02-07', '01:54:05', 0),
(76, 'Asset', 'A/C re', 'Due cas', 'FIL000000001', '', 'Dr', '25', '2021-02-07', 2950.12, 0, '7', '2021-02-07', '01:54:05', 0),
(77, 'Revenu', 'Sale', 'Service', 'FIL000000001', '', 'Cr', '25', '2021-02-07', 0, 4200.12, '7', '2021-02-07', '01:54:05', 0),
(78, 'Asset', 'Cash', 'Main Ca', 'FIL000000001', '', 'Dr', '26', '2021-02-07', 500, 0, '7', '2021-02-07', '02:05:35', 0),
(79, 'Expend', 'Paid d', 'Sale du', 'FIL000000001', '', 'Dr', '26', '2021-02-07', 150, 0, '7', '2021-02-07', '02:05:35', 0),
(80, 'Asset', 'A/C re', 'Due cas', 'FIL000000001', '', 'Dr', '26', '2021-02-07', 7350.55, 0, '7', '2021-02-07', '02:05:35', 0),
(81, 'Revenu', 'Sale', 'Service', 'FIL000000001', '', 'Cr', '26', '2021-02-07', 0, 8000.55, '7', '2021-02-07', '02:05:35', 0),
(82, 'Asset', 'Cash', 'Main Ca', 'FIL000000004', '', 'Dr', '27', '2021-02-07', 490, 0, '7', '2021-02-07', '03:38:43', 0),
(83, 'Expend', 'Paid d', 'Sale du', 'FIL000000004', '', 'Dr', '27', '2021-02-07', 10, 0, '7', '2021-02-07', '03:38:43', 0),
(84, 'Asset', 'A/C re', 'Due cas', 'FIL000000004', '', 'Dr', '27', '2021-02-07', 4500.45, 0, '7', '2021-02-07', '03:38:43', 0),
(85, 'Revenu', 'Sale', 'Service', 'FIL000000004', '', 'Cr', '27', '2021-02-07', 0, 5000.45, '7', '2021-02-07', '03:38:43', 0),
(86, 'Asset', 'Cash', 'Main Ca', 'FIL000000004', '', 'Dr', '28', '2021-02-07', 1500, 0, '7', '2021-02-07', '04:38:11', 0),
(87, 'Expend', 'Paid d', 'Sale du', 'FIL000000004', '', 'Dr', '28', '2021-02-07', 1000, 0, '7', '2021-02-07', '04:38:11', 0),
(88, 'Asset', 'A/C re', 'Due cas', 'FIL000000004', '', 'Dr', '28', '2021-02-07', 10101.3, 0, '7', '2021-02-07', '04:38:11', 0),
(89, 'Revenu', 'Sale', 'Service', 'FIL000000004', '', 'Cr', '28', '2021-02-07', 0, 12601.3, '7', '2021-02-07', '04:38:11', 0),
(90, 'Asset', 'Cash', 'Main Ca', 'FIL000000004', '', 'Dr', '29', '2021-02-07', 5000, 0, '7', '2021-02-07', '05:25:34', 0),
(91, 'Expend', 'Paid d', 'Sale du', 'FIL000000004', '', 'Dr', '29', '2021-02-07', 0.44, 0, '7', '2021-02-07', '05:25:34', 0),
(92, 'Asset', 'A/C re', 'Due cas', 'FIL000000004', '', 'Dr', '29', '2021-02-07', 0.01, 0, '7', '2021-02-07', '05:25:34', 0),
(93, 'Revenu', 'Sale', 'Service', 'FIL000000004', '', 'Cr', '29', '2021-02-07', 0, 5000.45, '7', '2021-02-07', '05:25:34', 0),
(94, 'Asset', 'Cash', 'Main Ca', 'FIL000000003', '', 'Dr', '30', '2021-02-11', 1000, 0, '8', '2021-02-11', '10:56:56', 0),
(95, 'Expend', 'Paid d', 'Sale du', 'FIL000000003', '', 'Dr', '30', '2021-02-11', 100, 0, '8', '2021-02-11', '10:56:56', 0),
(96, 'Asset', 'A/C re', 'Due cas', 'FIL000000003', '', 'Dr', '30', '2021-02-11', 4900.55, 0, '8', '2021-02-11', '10:56:56', 0),
(97, 'Revenu', 'Sale', 'Service', 'FIL000000003', '', 'Cr', '30', '2021-02-11', 0, 6000.55, '8', '2021-02-11', '10:56:56', 0),
(98, 'Asset', 'Cash', 'Main Ca', 'FIL000000002', '', 'Dr', '31', '2021-02-11', 8000, 0, '8', '2021-02-11', '11:57:52', 0),
(99, 'Expend', 'Paid d', 'Sale du', 'FIL000000002', '', 'Dr', '31', '2021-02-11', 120, 0, '8', '2021-02-11', '11:57:52', 0),
(100, 'Asset', 'A/C re', 'Due cas', 'FIL000000002', '', 'Dr', '31', '2021-02-11', 480.98, 0, '8', '2021-02-11', '11:57:52', 0),
(101, 'Revenu', 'Sale', 'Service', 'FIL000000002', '', 'Cr', '31', '2021-02-11', 0, 8600.98, '8', '2021-02-11', '11:57:52', 0),
(102, 'Asset', 'Cash', 'Main Ca', 'FIL000000002', '', 'Dr', '12', '2021-02-11', 5, 0, '8', '2021-02-11', '08:54:20', 0),
(103, 'Asset', 'A/C re', 'Due cas', 'FIL000000002', '', 'Cr', '12', '2021-02-11', 5, 0, '8', '2021-02-11', '08:54:20', 0),
(104, 'Asset', 'Cash', 'Main Ca', 'FIL000000002', '', 'Dr', '12', '2021-02-11', 150, 0, '8', '2021-02-11', '08:56:14', 0),
(105, 'Asset', 'A/C re', 'Due cas', 'FIL000000002', '', 'Cr', '12', '2021-02-11', 0, 150, '8', '2021-02-11', '08:56:14', 0),
(106, 'Asset', 'Cash', 'Main Ca', 'FIL000000002', '', 'Dr', '32', '2021-02-11', 1000, 0, '8', '2021-02-11', '01:59:27', 0),
(107, 'Expend', 'Paid d', 'Sale du', 'FIL000000002', '', 'Dr', '32', '2021-02-11', 100, 0, '8', '2021-02-11', '01:59:27', 0),
(108, 'Asset', 'A/C re', 'Due cas', 'FIL000000002', '', 'Dr', '32', '2021-02-11', 4900.55, 0, '8', '2021-02-11', '01:59:27', 0),
(109, 'Revenu', 'Sale', 'Service', 'FIL000000002', '', 'Cr', '32', '2021-02-11', 0, 6000.55, '8', '2021-02-11', '01:59:27', 0),
(110, 'Asset', 'Cash', 'Main Ca', 'FIL000000003', '', 'Dr', '33', '2021-02-11', 15000, 0, '8', '2021-02-11', '04:50:46', 0),
(111, 'Expend', 'Paid d', 'Sale du', 'FIL000000003', '', 'Dr', '33', '2021-02-11', 1500, 0, '8', '2021-02-11', '04:50:46', 0),
(112, 'Asset', 'A/C re', 'Due cas', 'FIL000000003', '', 'Dr', '33', '2021-02-11', 17101, 0, '8', '2021-02-11', '04:50:46', 0),
(113, 'Revenu', 'Sale', 'Service', 'FIL000000003', '', 'Cr', '33', '2021-02-11', 0, 33601, '8', '2021-02-11', '04:50:46', 0),
(114, 'Asset', 'Cash', 'Main Ca', 'FIL000000003', '', 'Dr', '20', '2021-02-11', 10000, 0, '8', '2021-02-11', '11:58:36', 0),
(115, 'Asset', 'A/C re', 'Due cas', 'FIL000000003', '', 'Cr', '20', '2021-02-11', 0, 10000, '8', '2021-02-11', '11:58:36', 0),
(116, 'Asset', 'Cash', 'Main Ca', 'FIL000000001', '', 'Dr', '21', '2021-02-11', 3000, 0, '8', '2021-02-11', '12:13:53', 0),
(117, 'Asset', 'A/C re', 'Due cas', 'FIL000000001', '', 'Cr', '21', '2021-02-11', 0, 3000, '8', '2021-02-11', '12:13:53', 0),
(118, 'Asset', 'Cash', 'Main Ca', 'FIL000000001', '', 'Dr', '22', '2021-02-11', 3500, 0, '8', '2021-02-11', '12:14:49', 0),
(119, 'Asset', 'A/C re', 'Due cas', 'FIL000000001', '', 'Cr', '22', '2021-02-11', 0, 3500, '8', '2021-02-11', '12:14:49', 0),
(120, 'Asset', 'A/C re', 'Due cas', 'FIL000000001', '', 'Dr', '34', '2021-02-11', 5000.45, 0, '8', '2021-02-11', '05:15:27', 0),
(121, 'Revenu', 'Sale', 'Service', 'FIL000000001', '', 'Cr', '34', '2021-02-11', 0, 5000.45, '8', '2021-02-11', '05:15:27', 0),
(122, 'Asset', 'Cash', 'Main Ca', 'FIL000000001', '', 'Dr', '35', '2021-02-11', 444, 0, '8', '2021-02-11', '05:19:06', 0),
(123, 'Revenu', 'Sale', 'Service', 'FIL000000001', '', 'Cr', '35', '2021-02-11', 0, 0, '8', '2021-02-11', '05:19:06', 0),
(124, 'Revenu', 'Sale', 'Service', 'FIL000000001', '', 'Cr', '36', '2021-02-11', 0, 0, '8', '2021-02-11', '05:36:30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_user`
--

CREATE TABLE `dbs_user` (
  `user_id` varchar(12) NOT NULL,
  `user_name` varchar(60) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_cell` varchar(16) NOT NULL,
  `user_type` varchar(30) NOT NULL,
  `user_image` varchar(150) NOT NULL,
  `user_by` varchar(12) NOT NULL,
  `user_date` date NOT NULL,
  `user_time` varchar(8) NOT NULL,
  `user_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_user`
--

INSERT INTO `dbs_user` (`user_id`, `user_name`, `user_email`, `user_password`, `user_cell`, `user_type`, `user_image`, `user_by`, `user_date`, `user_time`, `user_status`) VALUES
('ADM000000001', 'Rahim', 'r@g.com', 'rrr', '3241513212', '', 'http://[::1]/dbs/asset/img/people5.png', '', '0000-00-00', '', 0),
('ADM000000002', 'emrul', 'emrul@g.com', 'eee', '3241513212', '', 'http://[::1]/dbs/asset/img/download42.jpg', '', '0000-00-00', '', 0),
('ADM000000003', 'emrul', 'emrul@g.com', 'eee', '3241513212', '', 'http://[::1]/dbs/asset/img/download43.jpg', '', '0000-00-00', '', 0),
('ADM000000004', 'Rakib', 'ra@g.com', 'aaa', '3241513212', '', 'http://[::1]/dbs/asset/img/jpg.png', '', '0000-00-00', '', 0),
('ADM000000005', 'Rakib', 'ra@g.com', 'aaa', '3241513212', '', 'http://[::1]/dbs/asset/img/jpg1.png', '', '0000-00-00', '', 0),
('ADM000000006', 'Rahib', 'aaa@g.com', 'aaa', '3241513212', '', 'http://[::1]/dbs/asset/img/jpg2.png', '', '0000-00-00', '', 0),
('ADM000000007', 'Rahib', 'aaa@g.com', 'aaa', '3241513212', '', 'http://[::1]/dbs/asset/img/jpg3.png', '', '0000-00-00', '', 0),
('ADM000000008', 'mohammad@gmail.com', 'emrul@g.com', 'aaa', '3241513212', '', 'http://[::1]/dbs/asset/img/jpg4.png', '', '0000-00-00', '', 0),
('ADM000000009', 'mohammad@gmail.com', 'a@g.com', 'aaa', '2123143423', '', 'http://[::1]/dbs/asset/img/download44.jpg', '', '0000-00-00', '', 0),
('ADM000000010', 'sdfas', 'fas', 'fasf', '3241513212', '', 'http://[::1]/dbs/asset/img/jpg5.png', '', '0000-00-00', '', 0),
('ADM000000011', 'mohammad@gmail.com', 'a@g.com', 'aaa', '3241513212423', '', 'http://[::1]/dbs/asset/img/people8.png', '', '0000-00-00', '', 0),
('ADM000000012', 'Rahib', 'emrul@g.com', 'sss', '3241513212', '', 'http://[::1]/dbs/asset/img/jpg7.png', '', '0000-00-00', '04:39:55', 0),
('ADM000000013', 'Rakib', 'a@g.com', 'aaa', '3241513212', '', 'http://[::1]/dbs/asset/img/people9.png', '', '0000-00-00', '04:40:42', 0),
('ADM000000014', 'Rahib', 'aaa@g.com', 'aaaa', '21231234324', '', 'http://[::1]/dbs/asset/img/jpg8.png', '', '0000-00-00', '04:42:12', 0),
('ADM000000015', 'mohammad@gmail.com', 'sfd', 'sfas', '23234324231', '', 'http://[::1]/dbs/asset/img/people12.png', '', '2021-01-21', '04:44:15', 0),
('ADM000000016', 'Rakib', 'a@g.com', 'adasds', '3241513212', 'New', 'http://[::1]/dbs/asset/img/jpg9.png', '', '2021-01-21', '04:47:30', 0),
('ADM000000017', 'asdfsad', 'a@g.com', 'adsfas', '3241513212', 'New', 'http://[::1]/dbs/asset/img/jpg10.png', '', '2021-01-21', '04:49:11', 0),
('ADM000000018', 'sfsf', 'emrul@g.com', 'aaa', '3241513212', 'New', 'http://[::1]/dbs/asset/img/people13.png', '', '2021-01-21', '05:20:04', 0),
('ADM000000019', 'mohammad@gmail.com', 'emrul@g.com', 'aaa', '3241513212', 'New', 'http://[::1]/dbs/asset/img/people14.png', '', '2021-01-21', '05:26:17', 0),
('ADM000000020', 'mohammad@gmail.com', 'sdfsd', 'aaaaaaaaaa', '3241513212', 'New', 'http://[::1]/dbs/asset/img/people15.png', '', '2021-01-21', '05:32:51', 0),
('ADM000000021', 'Rakib', 'a@g.com', 'aaa', '3241513212', 'New', 'http://[::1]/dbs/asset/img/people16.png', '', '2021-01-23', '10:56:05', 0),
('ADM000000022', 'Rakib', 'emrul@g.com', '000000', '3241513212', 'New', 'http://[::1]/dbs0/asset/img/jpg11.png', '', '2021-01-24', '11:47:17', 0),
('ADM000000023', 'ssssss', 's@gmail.com', 'ssssss', '3241513212423', 'New', 'http://[::1]/dbs0/asset/img/jpg12.png', '', '2021-01-24', '11:58:07', 0),
('ADM000000024', 'Rahib 21', 'rahib@gmail.com', 'rahib21', '324151321254', 'New', 'http://[::1]/dbs/asset/img/download_(2).jpg', '', '2021-01-31', '07:20:03', 0),
('ADM000000025', 'Rahib 2199', 'rahib99@gmail.com', 'rahib99', '3241513217', 'New', 'http://[::1]/dbs/asset/img/download_(1).jpg', '', '2021-01-31', '07:24:04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT 1,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `modified`) VALUES
(1, 'Emrul', '*', 'idevemrul@gmail.com', 1, 0, NULL, '1cc21ef7815f1aabf8ee2a8622184bc8', '2021-01-23 07:57:23', NULL, '79c9104ca38cde7e824be01d0900960c', '::1', '0000-00-00 00:00:00', '2021-01-23 07:53:32', '2021-01-23 07:02:31'),
(2, 'Emrul2', '*', 'idevemrul2@gmail.com', 0, 0, NULL, NULL, NULL, NULL, '9e89b59c411d0a58250d9660c2bc0845', '::1', '0000-00-00 00:00:00', '2021-01-23 08:16:39', '2021-01-23 07:16:39'),
(3, 'Emrul8', '*', 'idevemrul23@gmail.com', 0, 0, NULL, NULL, NULL, NULL, '380e1d4eb1a5a87f4d9923a2900aa2f3', '::1', '0000-00-00 00:00:00', '2021-01-23 08:47:11', '2021-01-23 07:47:11'),
(5, 'Emrul4', '$2a$08$MEsjNXzu.wIro5y91l5Qpu/sjmGqKQFf7oLvVEbxMYE7uiXgx37y2', 'idevemrul3@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '0000-00-00 00:00:00', '2021-01-24 04:39:29', '2021-01-24 03:39:29'),
(6, 'aaaaaa', '$2a$08$dxXmWvrDPSwjnr.c.uoOOubc1hVF7KN03K.s3m/p02wXWmXV./GAy', 'a@gmail.com', 1, 0, NULL, 'dbfc163284310fc87191b8bfa0066ed4', '2021-01-24 04:58:14', NULL, NULL, '::1', '0000-00-00 00:00:00', '2021-01-24 04:45:02', '2021-01-24 03:58:14'),
(7, 'kawsar', '$2a$08$oasuKxOqbiVSsUG/lQztOeVp1mFFYsKzp.NwJ0k3dVM1yrEFaWN8e', 'kawsar@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2021-02-07 05:14:39', '2021-01-24 07:00:53', '2021-02-07 04:14:39'),
(8, 'Hadup', '$2a$08$Qgr8tLhNlCot5eskjTPUJO5IVme2rKEsKjUy87mMTMzgXGZMl9/ou', 'hadup@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2021-02-11 04:52:20', '2021-01-24 07:56:14', '2021-02-11 03:52:20'),
(9, 'Emrul29', '$2a$08$L0/XU32JA4FDg40VXKRUseAHdwp3IEV/IMY.tGj7ED1p.Zp9On7ai', 'arrr@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '0000-00-00 00:00:00', '2021-01-31 14:25:03', '2021-01-31 13:25:03');

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

CREATE TABLE `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `country`, `website`) VALUES
(1, 5, NULL, NULL),
(2, 6, NULL, NULL),
(3, 7, NULL, NULL),
(4, 8, NULL, NULL),
(5, 9, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `dbs_account_coo_sub_head`
--
ALTER TABLE `dbs_account_coo_sub_head`
  ADD PRIMARY KEY (`accsh_id`),
  ADD KEY `ach_id` (`ach_id`),
  ADD KEY `acsh_id` (`acsh_id`),
  ADD KEY `accsh_by` (`accsh_by`);

--
-- Indexes for table `dbs_account_head`
--
ALTER TABLE `dbs_account_head`
  ADD PRIMARY KEY (`ach_id`),
  ADD KEY `ach_by` (`ach_by`);

--
-- Indexes for table `dbs_account_sub_head`
--
ALTER TABLE `dbs_account_sub_head`
  ADD PRIMARY KEY (`acsh_id`),
  ADD KEY `ach_id` (`ach_id`),
  ADD KEY `dbs_account_sub_head_ibfk_2` (`acsh_by`);

--
-- Indexes for table `dbs_address`
--
ALTER TABLE `dbs_address`
  ADD PRIMARY KEY (`add_id`),
  ADD KEY `party_id` (`party_id`),
  ADD KEY `party_mem_id` (`party_mem_id`),
  ADD KEY `add_by` (`add_by`);

--
-- Indexes for table `dbs_bank_ac`
--
ALTER TABLE `dbs_bank_ac`
  ADD PRIMARY KEY (`bank_id`),
  ADD KEY `bank_by` (`bank_by`);

--
-- Indexes for table `dbs_cash_ac`
--
ALTER TABLE `dbs_cash_ac`
  ADD PRIMARY KEY (`cash_id`),
  ADD KEY `cash_by` (`cash_by`);

--
-- Indexes for table `dbs_file`
--
ALTER TABLE `dbs_file`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `file_by` (`file_by`);

--
-- Indexes for table `dbs_file_party`
--
ALTER TABLE `dbs_file_party`
  ADD PRIMARY KEY (`file_party_id`);

--
-- Indexes for table `dbs_file_service`
--
ALTER TABLE `dbs_file_service`
  ADD PRIMARY KEY (`file_serv_id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `serv_id` (`serv_id`);

--
-- Indexes for table `dbs_invoice`
--
ALTER TABLE `dbs_invoice`
  ADD PRIMARY KEY (`inv_id`);

--
-- Indexes for table `dbs_invoice_details`
--
ALTER TABLE `dbs_invoice_details`
  ADD PRIMARY KEY (`inv_det_id`);

--
-- Indexes for table `dbs_party`
--
ALTER TABLE `dbs_party`
  ADD PRIMARY KEY (`party_id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `party_by` (`party_by`);

--
-- Indexes for table `dbs_party_member`
--
ALTER TABLE `dbs_party_member`
  ADD PRIMARY KEY (`party_mem_id`);

--
-- Indexes for table `dbs_payment`
--
ALTER TABLE `dbs_payment`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `dbs_sale_service`
--
ALTER TABLE `dbs_sale_service`
  ADD PRIMARY KEY (`serv_sale_id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `serv_id` (`serv_id`);

--
-- Indexes for table `dbs_service`
--
ALTER TABLE `dbs_service`
  ADD PRIMARY KEY (`serv_id`),
  ADD KEY `serv_by` (`serv_by`);

--
-- Indexes for table `dbs_transaction`
--
ALTER TABLE `dbs_transaction`
  ADD PRIMARY KEY (`tran_id`),
  ADD KEY `ach_id` (`ach_id`),
  ADD KEY `acsh_id` (`acsh_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Indexes for table `dbs_user`
--
ALTER TABLE `dbs_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_autologin`
--
ALTER TABLE `user_autologin`
  ADD PRIMARY KEY (`key_id`,`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbs_account_coo_sub_head`
--
ALTER TABLE `dbs_account_coo_sub_head`
  MODIFY `accsh_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dbs_account_head`
--
ALTER TABLE `dbs_account_head`
  MODIFY `ach_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dbs_account_sub_head`
--
ALTER TABLE `dbs_account_sub_head`
  MODIFY `acsh_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `dbs_file_party`
--
ALTER TABLE `dbs_file_party`
  MODIFY `file_party_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `dbs_file_service`
--
ALTER TABLE `dbs_file_service`
  MODIFY `file_serv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22770;

--
-- AUTO_INCREMENT for table `dbs_invoice`
--
ALTER TABLE `dbs_invoice`
  MODIFY `inv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `dbs_invoice_details`
--
ALTER TABLE `dbs_invoice_details`
  MODIFY `inv_det_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `dbs_payment`
--
ALTER TABLE `dbs_payment`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `dbs_transaction`
--
ALTER TABLE `dbs_transaction`
  MODIFY `tran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dbs_account_coo_sub_head`
--
ALTER TABLE `dbs_account_coo_sub_head`
  ADD CONSTRAINT `dbs_account_coo_sub_head_ibfk_1` FOREIGN KEY (`acsh_id`) REFERENCES `dbs_account_sub_head` (`acsh_id`),
  ADD CONSTRAINT `dbs_account_coo_sub_head_ibfk_2` FOREIGN KEY (`accsh_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `dbs_account_head`
--
ALTER TABLE `dbs_account_head`
  ADD CONSTRAINT `dbs_account_head_ibfk_1` FOREIGN KEY (`ach_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `dbs_account_sub_head`
--
ALTER TABLE `dbs_account_sub_head`
  ADD CONSTRAINT `dbs_account_sub_head_ibfk_1` FOREIGN KEY (`ach_id`) REFERENCES `dbs_account_head` (`ach_id`),
  ADD CONSTRAINT `dbs_account_sub_head_ibfk_2` FOREIGN KEY (`acsh_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `dbs_address`
--
ALTER TABLE `dbs_address`
  ADD CONSTRAINT `dbs_address_ibfk_1` FOREIGN KEY (`party_id`) REFERENCES `dbs_party` (`party_id`),
  ADD CONSTRAINT `dbs_address_ibfk_2` FOREIGN KEY (`party_mem_id`) REFERENCES `dbs_party_member` (`party_mem_id`),
  ADD CONSTRAINT `dbs_address_ibfk_3` FOREIGN KEY (`add_by`) REFERENCES `dbs_user` (`user_id`);

--
-- Constraints for table `dbs_bank_ac`
--
ALTER TABLE `dbs_bank_ac`
  ADD CONSTRAINT `dbs_bank_ac_ibfk_1` FOREIGN KEY (`bank_by`) REFERENCES `dbs_user` (`user_id`);

--
-- Constraints for table `dbs_cash_ac`
--
ALTER TABLE `dbs_cash_ac`
  ADD CONSTRAINT `dbs_cash_ac_ibfk_1` FOREIGN KEY (`cash_by`) REFERENCES `dbs_user` (`user_id`);

--
-- Constraints for table `dbs_file`
--
ALTER TABLE `dbs_file`
  ADD CONSTRAINT `dbs_file_ibfk_1` FOREIGN KEY (`file_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `dbs_file_service`
--
ALTER TABLE `dbs_file_service`
  ADD CONSTRAINT `dbs_file_service_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `dbs_file` (`file_id`),
  ADD CONSTRAINT `dbs_file_service_ibfk_2` FOREIGN KEY (`serv_id`) REFERENCES `dbs_service` (`serv_id`);

--
-- Constraints for table `dbs_party`
--
ALTER TABLE `dbs_party`
  ADD CONSTRAINT `dbs_party_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `dbs_party` (`party_id`),
  ADD CONSTRAINT `dbs_party_ibfk_2` FOREIGN KEY (`party_by`) REFERENCES `dbs_user` (`user_id`);

--
-- Constraints for table `dbs_sale_service`
--
ALTER TABLE `dbs_sale_service`
  ADD CONSTRAINT `dbs_sale_service_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `dbs_file` (`file_id`),
  ADD CONSTRAINT `dbs_sale_service_ibfk_2` FOREIGN KEY (`serv_id`) REFERENCES `dbs_service` (`serv_id`),
  ADD CONSTRAINT `dbs_sale_service_ibfk_3` FOREIGN KEY (`serv_id`) REFERENCES `dbs_user` (`user_id`);

--
-- Constraints for table `dbs_service`
--
ALTER TABLE `dbs_service`
  ADD CONSTRAINT `dbs_service_ibfk_1` FOREIGN KEY (`serv_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `dbs_transaction`
--
ALTER TABLE `dbs_transaction`
  ADD CONSTRAINT `dbs_transaction_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `dbs_file` (`file_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
