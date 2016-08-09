-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2016 at 11:00 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cfs_dblogin`
--

-- --------------------------------------------------------

--
-- Table structure for table `cfs`
--

CREATE TABLE `cfs` (
  `id` varchar(100) NOT NULL,
  `msg` text CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `msg_edit` text CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `status` text NOT NULL,
  `admin` text CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `time_send` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cfs`
--

INSERT INTO `cfs` (`id`, `msg`, `msg_edit`, `status`, `admin`, `time_send`, `time_edit`) VALUES
('01be10959b4', 'xin chào mọi người,\nĐây là cfs tiếng Việt có dấu ^^', 'xin chào mọi người,\nĐây là cfs tiếng Việt có dấu ^^', '', '-Vinh-', '2016-07-31 06:40:53', '2016-08-01 20:13:52'),
('236c689b702', 'Chúng mình sẽ sớm duyệt và đăng confession của bạn trên fanpage Hutech Confession \nChúng mình sẽ sớm duyệt và đăng confession của bạn trên fanpage Hutech Confession \n', 'Chúng mình sẽ sớm duyệt và đăng confession của bạn trên fanpage Hutech Confession \nChúng mình sẽ sớm duyệt và đăng confession của bạn trên fanpage Hutech Confession \n', 'post', '-Vinh-', '2016-08-01 07:07:58', '2016-08-02 06:24:09'),
('6d5d92632ea', 'Trong cuộc sống ai cũng gặp nhiều niềm vui, đôi khi cũng có chút buồn. Có thể bạn đã có người yêu hoặc đơn giản là đang tận hưởng cuộc sống độc thân hiện tại. Bạn vô tình bắt gặp 1 câu chuyện nào đó trong cuộc sống của mình hay chính mình là nhân vật trong câu chuyện đó.', 'Trong cuộc sống ai cũng gặp nhiều niềm vui, đôi khi cũng có chút buồn. Có thể bạn đã có người yêu hoặc đơn giản là đang tận hưởng cuộc sống độc thân hiện tại. Bạn vô tình bắt gặp 1 câu chuyện nào đó trong cuộc sống của mình hay chính mình là nhân vật trong câu chuyện đó.', 'post', '-Vinh-', '2016-08-01 06:04:11', '2016-08-02 06:25:15'),
('73e5726f171', 'addslashes thử hai ký tự khó nhằn đó là '' và " thử xme như thế nào, nãy thất bại :''(', 'addslashes thử hai ký tự khó nhằn đó là '' và " thử xme như thế nào, nãy thất bại :''(', 'post', '-Vinh-', '2016-07-31 09:08:41', '2016-08-02 06:30:34'),
('776a689e70e', 'test cfs mới đang được gửi vào hệ thống cfs hutech', '', '', '', '2016-08-03 11:09:07', '2016-08-03 11:09:07'),
('aa609187505', 'cfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_del sad sad asd ád', 'cfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_delcfs_ss_del cfs_ss_del sad sad asd ád', 'post', '-Vinh-', '2016-08-01 06:33:30', '2016-08-02 06:19:57'),
('ae9a977eee2', 'Gửi thử 1 cfs có dấu , và '' và " và & thử xem thế nào có edit đc k, đủ 10 ký tự nha ^^vd', 'Gửi thử 1 cfs có dấu , và '' và " và & thử xem thế nào có edit đc k, đủ 10 ký tự nha ^^vd', 'post', '-Vinh-', '2016-07-31 15:20:21', '2016-08-02 06:15:28'),
('b6d55305fe3', 'sadas sadas sadas sadas sadas sadas sadas sadas sadas sadas sadas', 'sadas sadas sadas sadas sadas sadas sadas sadas sadas sadas sadas', '', '-Vinh-', '2016-07-31 09:04:52', '2016-08-01 20:18:42'),
('c6ec155ccb4', 'Confession để giúp bạn nói những điều thầm kín mà bạn không dám chia sẻ trực tiếp, vì vui lòng không dùng confession để lăng mạ, sỉ nhục, đả kích người khác, không viết confession lúc bực bội, hãy dùng những ngôn từ lịch sự khi viết confession.\n', 'Confession để giúp bạn nói những điều thầm kín mà bạn không dám chia sẻ trực tiếp, vì vui lòng không dùng confession để lăng mạ, sỉ nhục, đả kích người khác, không viết confession lúc bực bội, hãy dùng những ngôn từ lịch sự khi viết confession.\n', 'post', '-Vinh-', '2016-08-01 05:59:08', '2016-08-02 15:34:40'),
('dd8391571a0', 'Confession để giúp bạn nói những điều thầm kín mà bạn không dám chia sẻ trực tiếp, vì vui lòng không dùng confession để lăng mạ, sỉ nhục, đả kích người khác, không viết confession lúc bực bội, hãy dùng những ngôn từ lịch sự khi viết confession.\n', 'Confession để giúp bạn nói những điều thầm kín mà bạn không dám chia sẻ trực tiếp, vì vui lòng không dùng confession để lăng mạ, sỉ nhục, đả kích người khác, không viết confession lúc bực bội, hãy dùng những ngôn từ lịch sự khi viết confession.\n', 'post', '-Vinh-', '2016-08-01 06:30:44', '2016-08-02 06:15:28'),
('ede53608aa1', 'Xin chào, đây là hệ thống duyệt cfs mình vừa build nhân dịp anh Tuấn có up 1 trang chia sẻ nội dung ^^', 'Xin chào, đây là hệ thống duyệt cfs mình vừa build nhân dịp anh Tuấn có up 1 trang chia sẻ nội dung ^^', 'post', '-Vinh-', '2016-08-02 06:42:41', '2016-08-02 06:45:10'),
('fa619134f6e', 'đây là cfs mới ab c đây là cfs mới ab c đây là cfs mới ab c đây là cfs mới ab c đây là cfs mới ab c đây là cfs mới ab c ', 'đây là cfs mới ab c đây là cfs mới ab c đây là cfs mới ab c đây là cfs mới ab c đây là cfs mới ab c đây là cfs mới ab c ', 'post', '-Vinh-', '2016-08-01 12:39:20', '2016-08-02 06:36:46'),
('fb964379939', 'Đây là cfs mới nó sẽ tự load vào trong kia mà ko cần f5 page :3 ajax còn cùi :))', 'Đây là cfs mới nó sẽ tự load vào trong kia mà ko cần f5 page :3 ajax còn cùi :))', 'post', '-Vinh-', '2016-08-02 06:44:33', '2016-08-02 06:45:10'),
('fcdb7523a55', ' cuộc sống ai cũng gặp nhiều niềm vui, đôi khi cũng có chút buồn. Có thể bạn đã có người yêu hoặc đơn giản là đang tận hưởng cuộc sống độc thân hiện tại. Bạn vô tình bắt gặp 1 câu chuyện nào đó trong cuộc sống của mình hay chính mình là nhân vật trong câu chuyện đó.\nĐừng mãi giữ trong lòng! Hãy chia sẻ với chúng t', ' cuộc sống ai cũng gặp nhiều niềm vui, đôi khi cũng có chút buồn. Có thể bạn đã có người yêu hoặc đơn giản là đang tận hưởng cuộc sống độc thân hiện tại. Bạn vô tình bắt gặp 1 câu chuyện nào đó trong cuộc sống của mình hay chính mình là nhân vật trong câu chuyện đó.\nĐừng mãi giữ trong lòng! Hãy chia sẻ với chúng t', 'post', '-Vinh-', '2016-08-01 05:54:54', '2016-08-02 06:37:15');

-- --------------------------------------------------------

--
-- Table structure for table `dblogin`
--

CREATE TABLE `dblogin` (
  `acc` varchar(100) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `nick` text CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `type` text NOT NULL,
  `block` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dblogin`
--

INSERT INTO `dblogin` (`acc`, `pass`, `name`, `nick`, `type`, `block`) VALUES
('1665900893731569', '6c695844d2aab4f929cb1ed3c0915670', 'Pham Ngoc Vinh', '-Vinh-', 'admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `info` varchar(100) NOT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`info`, `data`) VALUES
('cfs_cfs', '1'),
('cfs_login', '1'),
('cfs_number', '1'),
('cfs_page', ''),
('cfs_prefix', '#CFS_'),
('cfs_token', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cfs`
--
ALTER TABLE `cfs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dblogin`
--
ALTER TABLE `dblogin`
  ADD PRIMARY KEY (`acc`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`info`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
