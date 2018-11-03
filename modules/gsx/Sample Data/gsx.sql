--
-- Table structure for table `gsx`
--

CREATE TABLE IF NOT EXISTS `gsx` (
  `id` int(11) NOT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `warrantystatus` varchar(255) DEFAULT NULL,
  `coverageenddate` varchar(255) DEFAULT NULL,
  `coveragestartdate` varchar(255) DEFAULT NULL,
  `daysremaining` varchar(255) DEFAULT NULL,
  `estimatedpurchasedate` varchar(255) DEFAULT NULL,
  `purchasecountry` varchar(255) DEFAULT NULL,
  `registrationdate` varchar(255) DEFAULT NULL,
  `productdescription` varchar(255) DEFAULT NULL,
  `configdescription` varchar(255) DEFAULT NULL,
  `contractcoverageenddate` varchar(255) DEFAULT NULL,
  `contractcoveragestartdate` varchar(255) DEFAULT NULL,
  `contracttype` varchar(255) DEFAULT NULL,
  `laborcovered` varchar(255) DEFAULT NULL,
  `partcovered` varchar(255) DEFAULT NULL,
  `warrantyreferenceno` varchar(255) DEFAULT NULL,
  `isloaner` varchar(255) DEFAULT NULL,
  `warrantymod` varchar(255) DEFAULT NULL,
  `isvintage` varchar(255) DEFAULT NULL,
  `isobsolete` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=411 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gsx`
--

INSERT INTO `gsx` (`id`, `serial_number`, `warrantystatus`, `coverageenddate`, `coveragestartdate`, `daysremaining`, `estimatedpurchasedate`, `purchasecountry`, `registrationdate`, `productdescription`, `configdescription`, `contractcoverageenddate`, `contractcoveragestartdate`, `contracttype`, `laborcovered`, `partcovered`, `warrantyreferenceno`, `isloaner`, `warrantymod`, `isvintage`, `isobsolete`) VALUES
(311, 'W87351XXX1X', 'Obsolete', '2008-08-26', '2007-08-27', '0', '2007-08-27', '', '2007-08-27', 'iMac (24-inch, Mid 2007)', '', '', '', '', 'No', 'No', '', 'No', 'Expired', 'No', 'Yes'),
(320, 'W87351XXX2X', 'Obsolete', '2008-08-05', '2007-08-06', '0', '2007-08-06', '', '2007-08-06', 'iMac (20-inch, Mid 2007)', '', '', '', '', 'No', 'No', '', 'No', 'Expired', 'No', 'Yes'),
(256, 'C17GXXX1XXX1', 'Out Of Warranty (No Coverage)', '2014-08-31', '2011-09-01', '0', '2011-09-01', 'United States', '2011-09-01', 'MacBook Pro (13-inch, Early 2011)', 'MBP 13.3/2.3/2X2GB/320/SD', '2014-08-31', '2011-10-31', 'AppleCare Protection Plan', 'No', 'No', '', 'No', 'Expired', 'No', 'No'),
(1, 'D25L307XXXX1', 'AppleCare Protection Plan', '2016-07-16', '2013-07-17', '146', '2013-07-17', 'United States', '2013-07-17', 'iMac (27-inch, Late 2012)', 'IMAC,27,QC,CTO', '2016-07-16', '2013-07-17', 'AppleCare Protection Plan', 'Yes', 'Yes', 'ONSITE ORANGE (2ND BUS DAY)', 'No', 'Supported', 'No', 'No'),
(257, 'D25L307XXXX2', 'Out Of Warranty (No Coverage)', '2015-08-29', '2014-08-29', '0', '2014-08-29', 'United States', '2014-08-29', 'iMac (27-inch, Late 2013)', 'IMAC,27",3.2GHZ,CTO', '', '', '', 'No', 'No', '', 'No', 'Expired', 'No', 'No'),
(259, 'C17GXXX1XXX2', 'Apple Limited Warranty', '2016-07-01', '2015-07-02', '1227', '2015-07-02', 'United States', '2015-07-02', 'MacBook Air (13-inch, Early 2015)', 'MBAIR 13.3 CTO', '2019-07-01', '2015-09-30', 'AppleCare Protection Plan', 'Yes', 'Yes', '', 'No', 'No Applecare', 'No', 'No'),
(261, 'C17GXXX1XXX3', 'Apple Limited Warranty', '2016-07-01', '2015-07-02', '1227', '2015-07-02', 'United States', '2015-07-02', 'MacBook Air (13-inch, Early 2015)', 'MBAIR 13.3 CTO', '2019-07-01', '2015-09-30', 'AppleCare Protection Plan', 'Yes', 'Yes', '', 'No', 'No Applecare', 'No', 'No'),
(380, 'W87351XXX3X', 'Out Of Warranty (No Coverage)', '2012-11-24', '2009-11-25', '0', '2009-11-25', 'United States', '2009-11-25', '~VIN,MacBook Pro (15-inch, Mid 2009)', 'VIN,MBP 15.4/2.66/2X2GB/320-5400/GLSY', '2012-11-24', '2010-01-24', 'AppleCare Protection Plan', 'No', 'No', '', 'No', 'Expired', 'Yes', 'No'),
(381, 'W87351XXX4X', 'Out Of Warranty (No Coverage)', '2013-06-20', '2010-06-21', '0', '2010-06-21', 'United States', '2010-06-21', '~VIN,MacBook Pro (15-inch, Mid 2009)', 'VIN,MBP 15.4/2.66/2X2GB/320-5400/GLSY', '2013-06-20', '2010-08-20', 'AppleCare Protection Plan', 'No', 'No', '', 'No', 'Expired', 'Yes', 'No');

--
-- Indexes for table `gsx`
--
ALTER TABLE `gsx`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`);

