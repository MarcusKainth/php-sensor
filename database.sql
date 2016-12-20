-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 20, 2016 at 05:40 PM
-- Server version: 5.7.13-0ubuntu0.16.04.2
-- PHP Version: 7.0.8-0ubuntu0.16.04.2

--
-- Table structure for table `AuthTokens`
--

CREATE TABLE `AuthTokens` (
  `AuthID` int(11) NOT NULL,
  `Selector` char(12) DEFAULT NULL,
  `Token` char(64) DEFAULT NULL,
  `UserID` tinyint(4) NOT NULL,
  `Expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Temps`
--

CREATE TABLE `Temps` (
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Temp1` float NOT NULL,
  `Temp2` float NOT NULL,
  `Average` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TempSettings`
--

CREATE TABLE `TempSettings` (
  `TempSettingsID` tinyint(4) NOT NULL,
  `Low` tinyint(4) NOT NULL,
  `High` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `UserID` tinyint(4) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` binary(60) DEFAULT NULL,
  `FirstName` varchar(30) DEFAULT NULL,
  `LastName` varchar(30) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AuthTokens`
--
ALTER TABLE `AuthTokens`
  ADD PRIMARY KEY (`AuthID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `TempSettings`
--
ALTER TABLE `TempSettings`
  ADD PRIMARY KEY (`TempSettingsID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `AuthTokens`
--
ALTER TABLE `AuthTokens`
  MODIFY `AuthID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `UserID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `AuthTokens`
--
ALTER TABLE `AuthTokens`
  ADD CONSTRAINT `AuthTokens_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`) ON DELETE CASCADE;
