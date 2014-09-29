-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 29, 2014 at 06:55 PM
-- Server version: 5.5.38
-- PHP Version: 5.4.4-14+deb7u14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `q_booker`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `limit` decimal(10,0) NOT NULL,
  `term` varchar(99) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE IF NOT EXISTS `details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `row_id` int(11) NOT NULL,
  `row_type` int(11) NOT NULL,
  `fname` varchar(99) NOT NULL,
  `lname` varchar(99) NOT NULL,
  `organization` varchar(99) NOT NULL,
  `vat` varchar(99) NOT NULL,
  `telephone` varchar(99) NOT NULL,
  `mobile` varchar(99) NOT NULL,
  `email_work` varchar(99) NOT NULL,
  `email_home` varchar(99) NOT NULL,
  `fax` varchar(99) NOT NULL,
  `skype` varchar(99) NOT NULL,
  `notes` varchar(99) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `details`
--

INSERT INTO `details` (`id`, `row_id`, `row_type`, `fname`, `lname`, `organization`, `vat`, `telephone`, `mobile`, `email_work`, `email_home`, `fax`, `skype`, `notes`, `created`) VALUES
(1, 1, 1, 'Tom', 'Sendze', 'Ecosys', 'DA11122313', '77771669', '', 'tom@ecosys.dk', '', '', '', '', '2014-09-29 13:26:03');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `row_id` int(11) NOT NULL,
  `username` varchar(99) NOT NULL,
  `password` varchar(99) NOT NULL,
  `email` varchar(99) NOT NULL,
  `role` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `fullname` varchar(99) NOT NULL,
  `code` varchar(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `row_id`, `username`, `password`, `email`, `role`, `level`, `fullname`, `code`, `created`) VALUES
(1, 1, 'kolor', 'efe6398127928f1b2e9ef3207fb82663', 'tom@ecosys.dk', 0, 3, '0', '0', '2014-09-29 13:26:03');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
