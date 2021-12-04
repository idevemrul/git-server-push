-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2021 at 01:15 PM
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
(3, 1, 1, 'It Lab Solution 2', 8, '2021-10-02', '05:05:01', 0),
(4, 2, 6, 'Main cash', 8, '0000-00-00', '04:13:56', 0),
(5, 2, 4, 'Due cash', 8, '0000-00-00', '05:20:17', 0),
(6, 4, 14, 'Service sale', 8, '0000-00-00', '05:26:04', 0),
(7, 5, 20, 'Sale discount', 8, '0000-00-00', '05:31:43', 0);

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
(4, 'Revenue / Income', 8, '2021-10-02', '11:54:33', 0),
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
(6, 2, 'Cash', 8, '2021-10-02', '12:21:41', 0),
(7, 2, 'Bank', 8, '2021-10-02', '12:21:49', 0),
(8, 2, 'Purchase', 8, '2021-10-02', '12:22:14', 0),
(9, 2, 'Sale Collection', 8, '2021-10-02', '12:22:25', 0),
(10, 2, 'Due Collection', 8, '2021-10-02', '12:22:34', 0),
(11, 2, 'Cash Collection', 8, '2021-10-02', '12:22:44', 0),
(12, 2, 'Sale Return Asset', 8, '2021-10-02', '12:22:57', 0),
(13, 3, 'Account Payable', 8, '2021-10-02', '12:23:17', 0),
(14, 4, 'Sale', 8, '2021-10-02', '12:23:50', 0),
(15, 4, 'Interest', 8, '2021-10-02', '12:24:01', 0),
(16, 4, 'Purchase Discount', 8, '2021-10-02', '12:24:11', 0),
(17, 4, 'Purchase return', 8, '2021-10-02', '12:24:21', 0),
(18, 5, 'Bonus', 8, '2021-10-02', '12:24:45', 0),
(19, 5, 'Commission Pay', 8, '2021-10-02', '12:25:37', 0),
(20, 5, 'Discount', 8, '2021-10-02', '12:26:02', 0),
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
-- Table structure for table `dbs_expenditure`
--

CREATE TABLE `dbs_expenditure` (
  `exp_id` int(11) NOT NULL,
  `ach_id` int(11) NOT NULL,
  `acsh_id` int(11) NOT NULL,
  `accsh_id` int(11) NOT NULL,
  `party_mem_id` varchar(12) NOT NULL,
  `exp_reference` varchar(55) NOT NULL,
  `exp_vou_amount` float NOT NULL,
  `exp_pay_amount` float NOT NULL,
  `exp_details` varchar(255) NOT NULL,
  `exp_date` date NOT NULL,
  `exp_time` varchar(11) NOT NULL,
  `exp_by` int(11) NOT NULL,
  `exp_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dbs_expenditure`
--

INSERT INTO `dbs_expenditure` (`exp_id`, `ach_id`, `acsh_id`, `accsh_id`, `party_mem_id`, `exp_reference`, `exp_vou_amount`, `exp_pay_amount`, `exp_details`, `exp_date`, `exp_time`, `exp_by`, `exp_status`) VALUES
(1, 5, 21, 0, ' PME00000000', 'fdfd', 500, 250, 'dfsdf', '2021-02-17', '02:17:35', 8, 0),
(2, 2, 6, 0, ' PME00000000', 'fdfd', 55, 23, 'sdsd', '2021-02-17', '04:46:06', 8, 0),
(3, 5, 20, 7, ' PME00000000', 'fdfd', 55, 23, 'erewr wew', '2021-02-17', '05:47:33', 8, 0);

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
('FIL000000012', 'fdfdfdfdsfad', 'What is Lorem Ipsum?\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\nWhy do we use it?\r\nIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#039;Content here, content here&#039;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#039;lorem ipsum&#039; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).\r\n\r\n\r\nWhere does it come from?\r\nContrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.\r\n\r\nThe standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\r\n\r\nWhere can I get some?\r\nThere are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#039;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#039;t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.\r\n\r\nWhat is Lorem Ipsum?\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\nWhy do we use it?\r\nIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#039;Content here, content here&#039;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#039;lorem ipsum&#039; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).\r\n\r\n\r\nWhere does it come from?\r\nContrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.\r\n\r\nThe standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\r\n\r\nWhere can I get some?\r\nThere are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#039;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#039;t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', 8, '2021-01-25', '03:31:41', 13),
('FIL000000013', 'sdfsdf', 'sfasf sdfaf asdfasf', 8, '2021-01-25', '03:54:30', 0),
('FIL000000014', 'Hello  df', 'fasdf sdfa fd  safdf ', 8, '2021-01-25', '03:56:00', 0),
('FIL000000015', 'Hello  df asdfsdfa ', 'fasdf sdfa fd  safdf ', 8, '2021-01-25', '03:57:16', 0),
('FIL000000016', 'sdfaf sdfa fa sfadf ', 'sadf df sfdaf sdf saf sdf safd sdf fsf ', 8, '2021-01-25', '03:57:27', 0),
('FIL000000017', 'd asdf asfd fd', 'dfdffas', 8, '2021-01-25', '03:57:46', 0),
('FIL000000018', 'fawfasdf', 'fasdf fdasf dsfa ffa fsaf afaf', 8, '2021-01-25', '05:43:26', 0),
('FIL000000019', 'Kawsar file', 'This is  dummy file', 7, '2021-02-07', '11:32:52', 0),
('FIL000000020', 'Sample file', 'This is a sample file details.', 8, '2021-02-11', '06:35:44', 0),
('FIL000000021', 'Motor ', 'fsdafas', 8, '2021-02-13', '04:21:12', 0),
('FIL000000022', 'Parts', 'byk sale', 8, '2021-02-13', '04:32:51', 0),
('FIL000000023', 'cng', 'cng sale', 8, '2021-02-13', '04:45:34', 0),
('FIL000000024', 'jewellery', 'sale j..', 8, '2021-02-13', '05:03:25', 0);

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
(18, 'FIL000000004', 'PME000000010', '7', '2021-02-07', '03:37:25', 0),
(19, 'FIL000000020', 'PME000000004', '8', '2021-02-11', '06:37:16', 0),
(20, 'FIL000000020', 'PME000000010', '8', '2021-02-11', '06:37:26', 0),
(21, 'FIL000000021', 'PME000000014', '8', '2021-02-13', '04:21:36', 0),
(22, 'FIL000000024', 'PME000000015', '8', '2021-02-13', '05:06:52', 13),
(23, 'FIL000000022', 'PME000000005', '8', '2021-02-17', '01:36:03', 0),
(24, 'FIL000000010', 'PME000000004', '8', '2021-02-17', '02:29:21', 0),
(25, 'FIL000000010', 'PME000000008', '8', '2021-02-17', '02:29:29', 0),
(26, 'FIL000000022', 'PME000000012', '8', '2021-02-17', '04:23:04', 13),
(27, 'FIL000000024', 'PME000000008', '8', '2021-02-17', '04:23:55', 0),
(28, 'FIL000000024', 'PME000000005', '8', '2021-02-17', '04:24:55', 0),
(29, 'FIL000000024', 'PME000000012', '8', '2021-02-17', '04:26:25', 13),
(30, 'FIL000000024', 'PME000000014', '8', '2021-02-17', '04:27:37', 13),
(31, 'FIL000000024', 'PME000000009', '8', '2021-02-17', '04:28:58', 13),
(32, 'FIL000000024', 'PME000000003', '8', '2021-02-17', '04:29:39', 0),
(33, 'FIL000000024', 'PME000000011', '8', '2021-02-17', '04:31:16', 13),
(34, 'FIL000000022', 'PME000000009', '8', '2021-02-17', '04:51:20', 0);

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
(1, 'FIL000000024', 'SER000000003', 8, '2021-02-14', '09:59:45', 0),
(2, 'FIL000000024', 'SER000000008', 8, '2021-02-14', '10:01:07', 0),
(3, 'FIL000000024', 'SER000000001', 8, '2021-02-14', '01:26:50', 0),
(4, 'FIL000000024', 'SER000000004', 8, '2021-02-14', '01:59:32', 0),
(5, 'FIL000000024', 'SER000000007', 8, '2021-02-14', '02:02:08', 0),
(6, 'FIL000000024', 'SER000000003', 8, '2021-02-14', '02:05:54', 0),
(7, 'FIL000000024', 'SER000000003', 8, '2021-02-14', '02:06:40', 0),
(8, 'FIL000000024', 'SER000000003', 8, '2021-02-14', '02:07:38', 0),
(9, 'FIL000000022', 'SER000000003', 8, '2021-02-17', '01:36:23', 0),
(10, 'FIL000000010', 'SER000000005', 8, '2021-02-17', '02:43:11', 0),
(11, 'FIL000000010', 'SER000000005', 8, '2021-02-17', '04:20:55', 0),
(12, 'FIL000000010', 'SER000000007', 8, '2021-02-17', '04:20:55', 0),
(13, 'FIL000000024', 'SER000000007', 8, '2021-02-17', '04:33:28', 0),
(14, 'FIL000000022', 'SER000000003', 8, '2021-02-17', '04:39:33', 0),
(15, 'FIL000000022', 'SER000000005', 8, '2021-02-17', '04:39:33', 0),
(16, 'FIL000000022', 'SER000000005', 8, '2021-02-17', '04:51:39', 0),
(17, 'FIL000000022', 'SER000000006', 8, '2021-02-17', '04:51:39', 0),
(18, 'FIL000000020', 'SER000000004', 8, '2021-02-17', '05:37:52', 0);

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
(1, 'FIL000000010', 4200.12, 100, 500, 'lkjhklj', 8, '2021-02-17', '02:43:11', '0'),
(2, 'FIL000000010', 11801, 1520, 9000, 'no comments', 8, '2021-02-17', '04:20:55', '0'),
(3, 'FIL000000024', 7600.88, 150, 5000, 'kjhj  kjh khkj', 8, '2021-02-17', '04:33:28', '0'),
(4, 'FIL000000022', 14247.6, 565, 5565, 'kmhjkn', 8, '2021-02-17', '04:39:33', '0'),
(5, 'FIL000000022', 7200.22, 12, 45, 'jhjk', 8, '2021-02-17', '04:51:39', '0'),
(6, 'FIL000000020', 5000.45, 90, 4233, 'fds  d sda', 8, '2021-02-17', '05:37:52', '0');

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
(1, 1, 'SER000000005', 1, 4200.12, 0),
(2, 2, 'SER000000005', 1, 4200.12, 0),
(3, 2, 'SER000000007', 1, 7600.88, 0),
(4, 3, 'SER000000007', 1, 7600.88, 0),
(5, 4, 'SER000000003', 5, 2009.5, 0),
(6, 4, 'SER000000005', 1, 4200.12, 0),
(7, 5, 'SER000000005', 1, 4200.12, 0),
(8, 5, 'SER000000006', 1, 3000.1, 0),
(9, 6, 'SER000000004', 1, 5000.45, 0);

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
('PME000000013', 'sdfasf', 'fasfdsf', 'fasfsf@t.c', '54984', '', '', 'http://[::1]/dbs/asset/img/party/download_(3)6.jpg', '', 8, '2021-01-28', '05:14:22', 0),
('PME000000014', 'Pllob', 'Sales', 'pllob@g.com', '234156415', '014654+62313', '6565', 'http://[::1]/dbs/asset/img/party/jpg2.png', 'fsadf', 8, '2021-02-13', '04:20:33', 0),
('PME000000015', 'rohim', 'Sales', 'rohim@gmail.com', '21321562', '01465462313', '', 'http://[::1]/dbs/asset/img/party/jpg3.png', '', 8, '2021-02-13', '05:06:29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbs_payment`
--

CREATE TABLE `dbs_payment` (
  `pay_id` int(11) NOT NULL,
  `file_id` varchar(12) NOT NULL,
  `inv_id` int(11) NOT NULL,
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

INSERT INTO `dbs_payment` (`pay_id`, `file_id`, `inv_id`, `pay_inv_amount`, `pay_paid`, `pay_balance`, `pay_remarks`, `pay_by`, `pay_dateE`, `pay_date`, `pay_time`, `pay_status`) VALUES
(1, 'FIL000000010', 1, 4200.12, 600, 3600.12, 'lkjhklj', 8, '0000-00-00', '2021-02-17', '02:43:11', '0'),
(2, 'FIL000000010', 0, 0, 1500, 2100.12, 'No comments', 8, '2021-02-17', '2021-02-17', '10:53:00', '0'),
(3, 'FIL000000010', 2, 11801, 10520, 3381.12, 'no comments', 8, '0000-00-00', '2021-02-17', '04:20:55', '0'),
(4, 'FIL000000024', 3, 7600.88, 5150, 2450.88, 'kjhj  kjh khkj', 8, '0000-00-00', '2021-02-17', '04:33:28', '0'),
(5, 'FIL000000022', 4, 14247.6, 6130, 8117.62, 'kmhjkn', 8, '0000-00-00', '2021-02-17', '04:39:33', '0'),
(6, 'FIL000000022', 0, 0, 500, 7617.62, 'jgjhg', 8, '2021-02-17', '2021-02-17', '11:40:02', '0'),
(7, 'FIL000000022', 5, 7200.22, 57, 14760.8, 'jhjk', 8, '0000-00-00', '2021-02-17', '04:51:39', '0'),
(8, 'FIL000000020', 6, 5000.45, 4323, 677.45, 'fds  d sda', 8, '0000-00-00', '2021-02-17', '05:37:52', '0'),
(9, 'FIL000000024', 0, 0, 500, 1950.88, 'dfds', 8, '2021-02-17', '2021-02-17', '12:57:29', '0');

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
('SER000000001', 'Service title 1', 'details 19', '', 5009, 8, '2021-01-31', '11:13:26', 13),
('SER000000002', 'Service title 2', 'Service details 2', '', 1000.1, 8, '2021-01-31', '11:15:33', 1),
('SER000000003', 'Service title 3', 'Service details 3', '', 2009.5, 8, '2021-01-31', '11:18:58', 1),
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
(1, '2', '6', '4', 'FIL000000010', '', 'Dr', '1', '2021-02-17', 500, 0, '8', '2021-02-17', '02:43:11', 0),
(2, '5', '20', '7', 'FIL000000010', '', 'Dr', '1', '2021-02-17', 100, 0, '8', '2021-02-17', '02:43:11', 0),
(3, '2', '4', '5', 'FIL000000010', '', 'Dr', '1', '2021-02-17', 3600.12, 0, '8', '2021-02-17', '02:43:11', 0),
(4, '4', '14', '6', 'FIL000000010', '', 'Cr', '1', '2021-02-17', 0, 4200.12, '8', '2021-02-17', '02:43:11', 0),
(5, '2', '6', '4', 'FIL000000010', '', 'Dr', '2', '2021-02-17', 1500, 0, '8', '2021-02-17', '10:53:00', 0),
(6, '2', '4', '5', 'FIL000000010', '', 'Cr', '2', '2021-02-17', 0, 1500, '8', '2021-02-17', '10:53:00', 0),
(7, '2', '6', '4', 'FIL000000010', '', 'Dr', '3', '2021-02-17', 9000, 0, '8', '2021-02-17', '04:20:55', 0),
(8, '5', '20', '7', 'FIL000000010', '', 'Dr', '3', '2021-02-17', 1520, 0, '8', '2021-02-17', '04:20:55', 0),
(9, '2', '4', '5', 'FIL000000010', '', 'Dr', '3', '2021-02-17', 1281, 0, '8', '2021-02-17', '04:20:55', 0),
(10, '4', '14', '6', 'FIL000000010', '', 'Cr', '3', '2021-02-17', 0, 11801, '8', '2021-02-17', '04:20:55', 0),
(11, '2', '6', '4', 'FIL000000024', '', 'Dr', '4', '2021-02-17', 5000, 0, '8', '2021-02-17', '04:33:28', 0),
(12, '5', '20', '7', 'FIL000000024', '', 'Dr', '4', '2021-02-17', 150, 0, '8', '2021-02-17', '04:33:28', 0),
(13, '2', '4', '5', 'FIL000000024', '', 'Dr', '4', '2021-02-17', 2450.88, 0, '8', '2021-02-17', '04:33:28', 0),
(14, '4', '14', '6', 'FIL000000024', '', 'Cr', '4', '2021-02-17', 0, 7600.88, '8', '2021-02-17', '04:33:28', 0),
(15, '2', '6', '4', 'FIL000000022', '', 'Dr', '5', '2021-02-17', 5565, 0, '8', '2021-02-17', '04:39:33', 0),
(16, '5', '20', '7', 'FIL000000022', '', 'Dr', '5', '2021-02-17', 565, 0, '8', '2021-02-17', '04:39:33', 0),
(17, '2', '4', '5', 'FIL000000022', '', 'Dr', '5', '2021-02-17', 8117.62, 0, '8', '2021-02-17', '04:39:33', 0),
(18, '4', '14', '6', 'FIL000000022', '', 'Cr', '5', '2021-02-17', 0, 14247.6, '8', '2021-02-17', '04:39:33', 0),
(19, '2', '6', '4', 'FIL000000022', '', 'Dr', '6', '2021-02-17', 500, 0, '8', '2021-02-17', '11:40:02', 0),
(20, '2', '4', '5', 'FIL000000022', '', 'Cr', '6', '2021-02-17', 0, 500, '8', '2021-02-17', '11:40:02', 0),
(21, '2', '6', '4', 'FIL000000022', '', 'Dr', '7', '2021-02-17', 45, 0, '8', '2021-02-17', '04:51:39', 0),
(22, '5', '20', '7', 'FIL000000022', '', 'Dr', '7', '2021-02-17', 12, 0, '8', '2021-02-17', '04:51:39', 0),
(23, '2', '4', '5', 'FIL000000022', '', 'Dr', '7', '2021-02-17', 7143.22, 0, '8', '2021-02-17', '04:51:39', 0),
(24, '4', '14', '6', 'FIL000000022', '', 'Cr', '7', '2021-02-17', 0, 7200.22, '8', '2021-02-17', '04:51:39', 0),
(25, '2', '6', '4', 'FIL000000020', '', 'Dr', '8', '2021-02-17', 4233, 0, '8', '2021-02-17', '05:37:52', 0),
(26, '5', '20', '7', 'FIL000000020', '', 'Dr', '8', '2021-02-17', 90, 0, '8', '2021-02-17', '05:37:52', 0),
(27, '2', '4', '5', 'FIL000000020', '', 'Dr', '8', '2021-02-17', 677.45, 0, '8', '2021-02-17', '05:37:52', 0),
(28, '4', '14', '6', 'FIL000000020', '', 'Cr', '8', '2021-02-17', 0, 5000.45, '8', '2021-02-17', '05:37:52', 0),
(29, '2', '6', '4', 'FIL000000024', '', 'Dr', '9', '2021-02-17', 500, 0, '8', '2021-02-17', '12:57:29', 0),
(30, '2', '4', '5', 'FIL000000024', '', 'Cr', '9', '2021-02-17', 0, 500, '8', '2021-02-17', '12:57:29', 0);

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
-- Table structure for table `dbs_voucher`
--

CREATE TABLE `dbs_voucher` (
  `vou_id` int(11) NOT NULL,
  `party_mem_id` varchar(12) NOT NULL,
  `vou_amount` float NOT NULL,
  `vou_discount_amount` float NOT NULL,
  `vou_paid_amount` float NOT NULL,
  `vou_remarks` varchar(155) NOT NULL,
  `vou_by` int(11) NOT NULL,
  `vou_date` date NOT NULL,
  `vou_time` varchar(8) NOT NULL,
  `vou_status` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dbs_vou_payment`
--

CREATE TABLE `dbs_vou_payment` (
  `vou_pay_id` int(11) NOT NULL,
  `vou_party_mem_id` varchar(12) NOT NULL,
  `vou_id` int(11) NOT NULL,
  `vou_pay_amount` float NOT NULL,
  `vou_pay_paid` float NOT NULL,
  `vou_pay_balance` float NOT NULL,
  `vou_pay_remarks` varchar(155) NOT NULL,
  `vou_pay_by` int(11) NOT NULL,
  `vou_pay_dateE` date NOT NULL,
  `vou_pay_date` date NOT NULL,
  `vou_pay_time` varchar(8) NOT NULL,
  `vou_pay_status` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(8, 'Hadup', '$2a$08$Qgr8tLhNlCot5eskjTPUJO5IVme2rKEsKjUy87mMTMzgXGZMl9/ou', 'hadup@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2021-02-17 05:33:48', '2021-01-24 07:56:14', '2021-02-17 04:33:48'),
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
-- Indexes for table `dbs_expenditure`
--
ALTER TABLE `dbs_expenditure`
  ADD PRIMARY KEY (`exp_id`);

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
-- Indexes for table `dbs_voucher`
--
ALTER TABLE `dbs_voucher`
  ADD PRIMARY KEY (`vou_id`);

--
-- Indexes for table `dbs_vou_payment`
--
ALTER TABLE `dbs_vou_payment`
  ADD PRIMARY KEY (`vou_pay_id`);

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
  MODIFY `accsh_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT for table `dbs_expenditure`
--
ALTER TABLE `dbs_expenditure`
  MODIFY `exp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dbs_file_party`
--
ALTER TABLE `dbs_file_party`
  MODIFY `file_party_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `dbs_file_service`
--
ALTER TABLE `dbs_file_service`
  MODIFY `file_serv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `dbs_invoice`
--
ALTER TABLE `dbs_invoice`
  MODIFY `inv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dbs_invoice_details`
--
ALTER TABLE `dbs_invoice_details`
  MODIFY `inv_det_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `dbs_payment`
--
ALTER TABLE `dbs_payment`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `dbs_transaction`
--
ALTER TABLE `dbs_transaction`
  MODIFY `tran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `dbs_voucher`
--
ALTER TABLE `dbs_voucher`
  MODIFY `vou_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbs_vou_payment`
--
ALTER TABLE `dbs_vou_payment`
  MODIFY `vou_pay_id` int(11) NOT NULL AUTO_INCREMENT;

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
