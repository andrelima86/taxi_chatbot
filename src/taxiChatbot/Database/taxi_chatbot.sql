-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 17, 2017 at 01:05 PM
-- Server version: 5.6.33
-- PHP Version: 7.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `chatbot_taxi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_intents`
--

CREATE TABLE `tbl_intents` (
  `intent_id` int(11) UNSIGNED NOT NULL,
  `intent_name` varchar(50) NOT NULL,
  `intent_params` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_intents`
--

INSERT INTO `tbl_intents` (`intent_id`, `intent_name`, `intent_params`) VALUES
(1, 'greeting', '["contact", "local_search_query"]'),
(2, 'taxi_request', '["to", "from", "when", "number"]'),
(3, 'taxi_request_from', ''),
(4, 'taxi_request_confirm', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_intent_responses`
--

CREATE TABLE `tbl_intent_responses` (
  `intent_id` int(11) UNSIGNED NOT NULL,
  `response_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_messages`
--

CREATE TABLE `tbl_messages` (
  `message_id` int(11) UNSIGNED NOT NULL,
  `message_fb_id` varchar(50) NOT NULL,
  `message_text` text NOT NULL,
  `message_payload` text,
  `message_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_responses`
--

CREATE TABLE `tbl_responses` (
  `response_id` int(11) UNSIGNED NOT NULL,
  `response_name` varchar(50) NOT NULL,
  `response_template` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_responses`
--

INSERT INTO `tbl_responses` (`response_id`, `response_name`, `response_template`) VALUES
(1, 'greeting', 'Where can we take you today?'),
(2, 'greeting_contact', 'Hello (contact), where would you like to go?'),
(3, 'greeting_contact', 'Hey (contact) where to?'),
(4, 'greeting_contact', 'Where are we off to now (contact)'),
(5, 'greeting_contact', 'Hi (contact)'),
(6, 'greeting_contact', 'Hello (contact)'),
(7, 'greeting_contact', 'What\'s up (contact)?'),
(8, 'greeting', 'Hey, where to?'),
(9, 'taxi_request_to', 'Where can we pick you up, send me your location'),
(10, 'taxi_request_to', 'Where are you right now, send me your location'),
(11, 'taxi_request_to', 'Send me your location'),
(12, 'taxi_request_when', 'Where can Alfred take you today?');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sessions`
--

CREATE TABLE `tbl_sessions` (
  `session_id` varchar(50) NOT NULL,
  `seesion_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_data` varchar(20000) NOT NULL,
  `session_closed` enum('TRUE','FALSE') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_sessions`
--

INSERT INTO `tbl_sessions` (`session_id`, `seesion_created`, `session_data`, `session_closed`) VALUES
('12345', '2017-02-04 20:25:32', '[]', 'FALSE'),
('123456', '2017-02-04 20:27:30', '[]', 'FALSE'),
('1234567', '2017-02-04 20:33:56', '[]', 'FALSE'),
('12345678', '2017-02-04 20:39:12', '[]', 'FALSE'),
('Array', '2017-02-04 17:08:00', '[]', 'FALSE');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_session_messages`
--

CREATE TABLE `tbl_session_messages` (
  `session_id` varchar(50) NOT NULL,
  `message_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_fb_id` varchar(50) NOT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `user_address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_messages`
--

CREATE TABLE `tbl_user_messages` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `message_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_intents`
--
ALTER TABLE `tbl_intents`
  ADD PRIMARY KEY (`intent_id`);

--
-- Indexes for table `tbl_intent_responses`
--
ALTER TABLE `tbl_intent_responses`
  ADD PRIMARY KEY (`intent_id`,`response_id`),
  ADD KEY `response_id` (`response_id`);

--
-- Indexes for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `tbl_responses`
--
ALTER TABLE `tbl_responses`
  ADD PRIMARY KEY (`response_id`);

--
-- Indexes for table `tbl_sessions`
--
ALTER TABLE `tbl_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `tbl_session_messages`
--
ALTER TABLE `tbl_session_messages`
  ADD PRIMARY KEY (`session_id`,`message_id`),
  ADD KEY `message_id` (`message_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_user_messages`
--
ALTER TABLE `tbl_user_messages`
  ADD PRIMARY KEY (`user_id`,`message_id`),
  ADD KEY `message_id` (`message_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_intents`
--
ALTER TABLE `tbl_intents`
  MODIFY `intent_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  MODIFY `message_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_responses`
--
ALTER TABLE `tbl_responses`
  MODIFY `response_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_intent_responses`
--
ALTER TABLE `tbl_intent_responses`
  ADD CONSTRAINT `tbl_intent_responses_ibfk_1` FOREIGN KEY (`intent_id`) REFERENCES `tbl_intents` (`intent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_intent_responses_ibfk_2` FOREIGN KEY (`response_id`) REFERENCES `tbl_responses` (`response_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_session_messages`
--
ALTER TABLE `tbl_session_messages`
  ADD CONSTRAINT `tbl_session_messages_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `tbl_sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_session_messages_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `tbl_messages` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_user_messages`
--
ALTER TABLE `tbl_user_messages`
  ADD CONSTRAINT `tbl_user_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_user_messages_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `tbl_messages` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE;
