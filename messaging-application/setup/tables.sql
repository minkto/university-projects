-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 02, 2016 at 06:15 PM
-- Server version: 5.5.47-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db1315540`
--

-- --------------------------------------------------------

--
-- Table structure for table `empuk_contacts`
--

CREATE TABLE IF NOT EXISTS `empuk_contacts` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `u1` int(16) NOT NULL,
  `u2` int(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `empuk_email`
--

CREATE TABLE IF NOT EXISTS `empuk_email` (
  `emailID` int(16) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject` varchar(255) NOT NULL,
  `content` varchar(32767) NOT NULL,
  `draft` tinyint(1) NOT NULL,
  `flag` tinyint(1) NOT NULL,
  `tag` tinyint(1) NOT NULL,
  `markread` tinyint(1) NOT NULL,
  `sender` int(16) NOT NULL,
  `receiver` int(16) NOT NULL,
  PRIMARY KEY (`emailID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `empuk_email`
--

INSERT INTO `empuk_email` (`emailID`, `time`, `subject`, `content`, `draft`, `flag`, `tag`, `markread`, `sender`, `receiver`) VALUES
(1, '2016-04-14 21:22:54', 'Message', 'This is a message.', 0, 0, 0, 0, 1, 2),
(2, '2016-04-14 21:23:07', 'Another message', 'This is a message to a different recipient.', 0, 0, 0, 0, 1, 3),
(3, '2016-04-14 21:22:22', '', 'This is a message with no subject.', 0, 0, 0, 0, 1, 2),
(4, '2016-04-19 18:39:56', 'Re: Message', 'This is a response to a message.', 0, 0, 0, 0, 2, 1),
(5, '2016-04-19 18:41:35', 'Re: Re: Message', 'This is a response to a response to a message.\n\nThis is some next level inception stuff.\n\nBut actually, this is just a test to see if line breaks work.', 0, 0, 0, 0, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `empuk_users`
--

CREATE TABLE IF NOT EXISTS `empuk_users` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(127) NOT NULL,
  `forename` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `email` varchar(60) NOT NULL,
  `question` int(1) NOT NULL,
  `answer` varchar(127) NOT NULL,
  `role` varchar(10) NOT NULL,
  `paired` tinyint(1) NOT NULL,
  `online` tinyint(1) NOT NULL,
  `last_active` datetime NOT NULL,
  `attempts` int(1) NOT NULL,
  `time_locked` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `empuk_users`
--

INSERT INTO `empuk_users` (`id`, `username`, `password`, `forename`, `surname`, `email`, `question`, `answer`, `role`, `paired`, `online`, `last_active`, `attempts`, `time_locked`) VALUES
(1, 'Admin', 'e15bdb56ba53b912a3061bf11cd0f83a', 'The', 'Admin', 'admin@empuk.com', 0, '', 'Admin', 0, 1, '2016-05-02 18:06:49', 0, '0000-00-00 00:00:00'),
(2, 'User', '461aeadeaa2f6445bd1c4c5b7002aeda', 'Example', 'User', 'user@empuk.com', 0, '', 'User', 0, 0, '2016-05-01 18:59:28', 0, '2016-04-22 11:53:31'),
(3, 'Mentor', '14c3011c9c95df53366e7bd262db4a3a', 'Example', 'Mentor', 'mentor@empuk.com', 0, '', 'Mentor', 0, 0, '2016-05-02 15:46:44', 0, '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
