SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS emergency_resource;
USE emergency_resource;

-- USERS -------------------------------------------------------------------

DROP TABLE IF EXISTS USERS;
CREATE TABLE IF NOT EXISTS USERS(
Username varchar(50) NOT NULL,
Name_s varchar(50) NOT NULL,
Password_s varchar(50) NOT NULL,
PRIMARY KEY (Username)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`Username`, `Name_s`, `Password_s`) VALUES
('Bubble', 'Bubble Wang', '123321'),
('AWhite1', 'Arnold White', 'qwerty'),
('Frank', 'Frank Wu', '123123'),
('HClinton12', 'Hillary Clinton', 'qazedc'),
('JDoe123', 'John Doe', '123456'),
('Raina', 'Raina Zou', '121212');
INSERT INTO USERS VALUES('admin', 'Johnny Admin', 'admin123');
INSERT INTO USERS VALUES('dschrute', 'Dwight Schrute', 'dwight123');
INSERT INTO USERS VALUES('gbluth', 'George Bluth', 'george123');
INSERT INTO USERS VALUES('jhalpert', 'Jim Halpert', 'jim123');
INSERT INTO USERS VALUES('lfunke', 'Lindsey Funke', 'lindsey123');
INSERT INTO USERS VALUES('michael', 'Michael Bluth', 'michael123');
INSERT INTO USERS VALUES('pam', 'Pam Halpert', 'pam123');
INSERT INTO USERS VALUES('rocky', 'Rocky Dunlap', 'rocky123');
INSERT INTO USERS VALUES('CNN', 'CNN', 'CNN123');
INSERT INTO USERS VALUES('ABC', 'ABC', 'ABC123');
INSERT INTO USERS VALUES('Google', 'Google Inc.', 'google123');
INSERT INTO USERS VALUES('Facebook', 'Facebook Inc.', 'fb123');
INSERT INTO USERS VALUES('fbi', 'FBI', 'fbi123');
INSERT INTO USERS VALUES('cia', 'CIA', 'cia123');
INSERT INTO USERS VALUES('doe', 'DOE', 'doe123');
INSERT INTO USERS VALUES('dod', 'DOD', 'dod123');
INSERT INTO USERS VALUES('nyc', 'New York City', 'nyc123');
INSERT INTO USERS VALUES('chicago', 'Chicago', 'chicago123');
INSERT INTO USERS VALUES('austin', 'Austin', 'austin123');
INSERT INTO USERS VALUES('miami', 'Miami', 'miami123');

-- INDIVIDUAL ---------------------------------------------------------------

DROP TABLE IF EXISTS INDIVIDUAL;
CREATE TABLE IF NOT EXISTS INDIVIDUAL(
Username varchar(50) NOT NULL,
Job_title varchar(50) NOT NULL,
Date_hired datetime NOT NULL,
PRIMARY KEY (Username),
FOREIGN KEY (Username)
REFERENCES USERS (Username))ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `individual` (`Username`, `Job_title`, `Date_hired`) VALUES
('Bubble', 'Teacher', '2015-10-01'),
('Frank', 'Engineer', '2013-10-01'),
('Raina', 'Scientist', '2010-03-01');
INSERT INTO INDIVIDUAL VALUES('pam', 'Driver', '2015-01-08 00:00:00');
INSERT INTO INDIVIDUAL VALUES('michael', 'Doctor', '2012-05-09 00:00:00');
INSERT INTO INDIVIDUAL VALUES('lfunke', 'Engineer', '2008-12-08 00:00:00');
INSERT INTO INDIVIDUAL VALUES('jhalpert', 'Professor', '2011-02-24 00:00:00');
INSERT INTO INDIVIDUAL VALUES('AWhite1', 'Mayor', '2014-05-12 00:00:00');

-- COMPANY ------------------------------------------------------------------

DROP TABLE IF EXISTS COMPANY;
CREATE TABLE IF NOT EXISTS COMPANY(
Username varchar(50) NOT NULL,
Headquarter varchar(50) NOT NULL,
PRIMARY KEY (Username),
FOREIGN KEY (Username)
REFERENCES USERS (Username))ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `company` (`Username`, `Headquarter`) VALUES
('HClinton12', 'Houston');
INSERT INTO COMPANY VALUES('CNN', 'Atlanta');
INSERT INTO COMPANY VALUES('ABC', 'New York');
INSERT INTO COMPANY VALUES('Google', 'San Francisco');
INSERT INTO COMPANY VALUES('Facebook', 'Menlo Park');

-- GOV AGENCY ---------------------------------------------------------------

DROP TABLE IF EXISTS GOV_AGENCY;
CREATE TABLE IF NOT EXISTS GOV_AGENCY(
Username varchar(50) NOT NULL,
Jurisdiction varchar(50) NOT NULL,
PRIMARY KEY (Username),
FOREIGN KEY (Username)
REFERENCES USERS (Username))ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO GOV_AGENCY VALUES('fbi', 'federal');
INSERT INTO GOV_AGENCY VALUES('cia', 'global');
INSERT INTO GOV_AGENCY VALUES('doe', 'energy');
INSERT INTO GOV_AGENCY VALUES('dod', 'defence');

-- MUNICIPALITY -------------------------------------------------------------

DROP TABLE IF EXISTS MUNICIPALITY;
CREATE TABLE IF NOT EXISTS MUNICIPALITY (
Username varchar(50) NOT NULL,
Population_size varchar(50) NOT NULL,
PRIMARY KEY (Username),
FOREIGN KEY (Username)
REFERENCES USERS (Username))ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO MUNICIPALITY VALUES('nyc', '8000000');
INSERT INTO MUNICIPALITY VALUES('chicago', '6000000');
INSERT INTO MUNICIPALITY VALUES('austin', '3652000');
INSERT INTO MUNICIPALITY VALUES('miami', '1250000');

-- INCIDENT -----------------------------------------------------------------

DROP TABLE IF EXISTS INCIDENT;
CREATE TABLE IF NOT EXISTS INCIDENT (
Username varchar(50) NOT NULL,
Inc_ID int NOT NULL,
Date_s date NOT NULL,
Description varchar(50) NOT NULL,
Latitude double NOT NULL,
Longitude double NOT NULL,
PRIMARY KEY (Username, Inc_ID),
KEY Inc_ID (Inc_ID))
ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `incident` (`Username`, `Inc_ID`, `Date_s`, `Description`, `Latitude`, `Longitude`) VALUES
('Bubble', 3, '2016-11-13', 'Landslide', 32.22, -98.12),
('AWhite1', 1, '2016-11-11', 'Flash Floods in County', 31.2, -95.35),
('AWhite1', 4, '2016-11-15', 'Midtown power outage', 33.21, -97.56),
('Frank', 2, '2016-11-12', 'Heavy snow', 30.72, -96.02),
('cia', 5, '2016-11-09', 'Car accident', 33.24, -97.68),
('doe', 6, '2016-11-03', 'Bettery explosion', 33.521, -97.786),
('Google', 7, '2016-11-11', 'Protester riot', 33.261, -97.836),
('Facebook', 8, '2016-11-12', 'Information leakage', 33.281, -97.256),
('austin', 9, '2016-11-13', 'fighting on street', 33.241, -97.636),
('miami', 10, '2016-11-13', 'noise', 33.341, -97.346),
('chicago', 11, '2016-11-13', 'Murder at house', 33.421, -97.496),
('AWhite1', 12, '2016-11-12', 'Emergency patient', 31.225, -95.315),
('AWhite1', 13, '2016-11-10', 'Power ourage in downtown', 33.321, -97.546),
('AWhite1', 14, '2016-11-06', 'Fire of laundry room', 31.227, -95.365),
('AWhite1', 15, '2016-11-09', 'Car accident in Main Street', 33.223, -97.276);

-- COST_UNIT List ------------------------------------------------------------

DROP TABLE IF EXISTS COST_UNIT ;
CREATE TABLE IF NOT EXISTS COST_UNIT (
Unit varchar(50) NOT NULL,
PRIMARY KEY (Unit))ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO COST_UNIT (Unit) VALUES ('each');
INSERT INTO COST_UNIT (Unit) VALUES ('hour');
INSERT INTO COST_UNIT (Unit) VALUES ('day');
INSERT INTO COST_UNIT (Unit) VALUES ('week');

-- ESF List ------------------------------------------------------------------

DROP TABLE IF EXISTS ESF;
CREATE TABLE IF NOT EXISTS ESF (
ESF_ID_Desc varchar(100) NOT NULL,
PRIMARY KEY (ESF_ID_Desc))ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO ESF (ESF_ID_Desc) VALUES ('01 – Transportation');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('02 – Communications');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('03 – Public Works & Engineering');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('04 – Firefighting');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('05 – Emergency Management');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('06 – Mass Care, Emergency Assistance, Housing, & Human Services');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('07 – Logistics Management & Resource Support');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('08 – Public Health & Medical Services');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('09 – Search & Rescue');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('10 – Oil & Hazardous Materials Response');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('11 – Agriculture & Natural Resources');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('12 – Energy');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('13 – Public Safety & Security');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('14 – Long-Term Community Recovery');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('15 – External Affairs');

-- Additional ESF ------------------------------------------------------------

DROP TABLE IF EXISTS RES_ADDI_ESF  ;
CREATE TABLE IF NOT EXISTS RES_ADDI_ESF(
Res_ID int NOT NULL,
ID_Desc varchar(100) NOT NULL,
PRIMARY KEY (Res_ID, ID_Desc))ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO RES_ADDI_ESF VALUES ('3','15 – External Affairs');
INSERT INTO RES_ADDI_ESF VALUES ('1','13 – Public Safety & Security');
INSERT INTO RES_ADDI_ESF VALUES ('6','05 – Emergency Management');
INSERT INTO RES_ADDI_ESF VALUES ('7','11 – Agriculture & Natural Resources');

-- Rescouce Capacities -------------------------------------------------------

DROP TABLE IF EXISTS RES_CAP  ;
CREATE TABLE IF NOT EXISTS RES_CAP(
Res_ID int NOT NULL,
Res_capability varchar(50) NOT NULL,
PRIMARY KEY (Res_ID, Res_capability))ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO RES_CAP VALUES (1,'walking in the snow');
INSERT INTO RES_CAP VALUES (5,'float on water');
INSERT INTO RES_CAP VALUES (7,'transport heavy stuff');
INSERT INTO RES_CAP VALUES (6,'transfer hurt people');

-- Requests ------------------------------------------------------------------

DROP TABLE IF EXISTS REQUEST;
CREATE TABLE IF NOT EXISTS REQUEST (
Inc_ID int NOT NULL,
Res_ID int NOT NULL,
Start_date date NULL,
Return_by date NULL,
PRIMARY KEY (Inc_ID, Res_ID))ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `request` (`Inc_ID`, `Res_ID`, `Start_date`, `Return_by`) VALUES
(1, 4, '2016-11-11', '2016-11-13'),
(2, 6, '2016-11-01', '2016-11-03'),
(3, 1, '2016-11-06', '2016-12-19'),
(4, 2, '2016-11-07', '2016-12-03'),
(5, 7, '2016-11-11', '2016-11-22'),
(11, 4, '2016-11-07', '2016-12-13'),
(14, 9, '2016-11-09', '2016-12-18'),
(1, 22, '2016-11-19', '2016-12-18'),
(15, 5, '2016-11-12', '2016-11-20');

-- Repairs ------------------------------------------------------------------

DROP TABLE IF EXISTS REPAIRS;
CREATE TABLE IF NOT EXISTS REPAIRS(
Res_ID int NOT NULL,
Start_date date NOT NULL,
Return_by date NOT NULL,
PRIMARY KEY (Res_ID, Start_date))ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `repairs` (`Res_ID`, `Start_date`, `Return_by`) VALUES
(8, '2016-11-10', '2016-11-13'),
(2, '2016-11-05', '2016-11-20'),
(13, '2016-11-20', '2016-11-29'),
(9, '2016-11-09', '2016-11-10'),
(17, '2016-11-11', '2016-12-05'),
(16, '2016-11-07', '2016-12-08'),
(24, '2016-11-17', '2016-12-08'),
(6, '2016-11-15', '2016-12-06');

-- Resources-----------------------------------------------------------------

DROP TABLE IF EXISTS RESOURCES;
CREATE TABLE IF NOT EXISTS RESOURCES(
Username varchar(50) NOT NULL,
Res_ID int NOT NULL,
Res_name varchar(50) NOT NULL,
Prim_ESF varchar(100) NOT NULL,
Model varchar(50) NULL,
Home_lat double NOT NULL,
Home_long double NOT NULL,
Status_s varchar(50) NULL,
Price int NOT NULL,
Cost_unit varchar(50) NULL,
PRIMARY KEY (Username, Res_ID),
KEY Res_ID (Res_ID))
ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `resources` (`Username`, `Res_ID`, `Res_name`, `Prim_ESF`, `Model`, `Home_lat`, `Home_long`, `Status_s`, `Price`, `Cost_unit`) VALUES
('Bubble', 1, 'Snow Ploughs', '06 – Mass Care, Emergency Assistance, Housing, & Human Services', '2016 new', 33.15, -98.32, 'IN REPAIR', 1, 'hour'),
('AWhite1', 2, 'All Terrain Vehicle', '01 – Transportation', '2015 Hummer', 30.61, -96.32, 'IN USE', 3, 'day'),
('Frank', 4, 'Ambulance', '02 – Communications', 'Honda', 32.22, -95.32, 'IN USE', 5, 'day'),
('Raina', 5, 'Lift Jackets', '06 – Mass Care, Emergency Assistance, Housing, & Human Services', 'Ford', 33.281, -97.256, 'AVAILABLE', 2, 'day'),
('doe', 6, 'Ambulance','03 – Public Works & Engineering', 'BMW', 32.242, -95.362, 'AVAILABLE', 5, 'hour'),
('cia', 7, 'Truck', '02 – Communications', 'Toyota', 32.36, -95.53, 'AVAILABLE', 3, 'day'),
('chicago', 8, 'Washing mechine', '05 – Emergency Management', 'Benz', 32.265, -95.342, 'AVAILABLE', 4, 'hour'),
('miami', 9, 'Drill machine', '02 – Communications', 'Lexus', 32.297, -95.382, 'AVAILABLE', 1, 'day'),
('cia', 10, 'Bettery', '12 – Energy', 'Chevy', 32.234, -95.362, 'IN USE', 3, 'week'),
('dod', 11, 'Fire Distinguisher', '10 – Oil & Hazardous Materials Response', 'GM', 32.275, -95.347, 'IN USE', 7, 'day'),
('doe', 12, 'Police car','13 – Public Safety & Security', 'Subaru', 32.270, -95.346, 'IN USE', 6, 'week'),
('AWhite1', 13, 'Screwdriver', '06 – Mass Care, Emergency Assistance, Housing, & Human Services', '2012', 33.481, -97.556, 'IN USE', 10, 'day'),
('AWhite1', 14, 'Power Sander', '12 – Energy', '2012', 33.45, -97.53, 'AVAILABLE', 3, 'week'),
('AWhite1', 15, 'Hand Drill', '09 – Search & Rescue', '2012', 33.76, -9729, 'IN USE', 5, 'hour'),
('AWhite1', 16, 'Wrench', '15 – External Affairs', '2012', 33.75, -97.12, 'IN REPAIR', 10, 'week'),
('AWhite1', 17, 'Power Saw', '03 – Public Works & Engineering', '2012', 33.735, -97.124, 'IN USE', 10, 'hour'),
('Frank', 18, 'Ambulance', '08 – Public Health & Medical Services', 'Honda', 32.22, -95.32, 'AVAILABLE', 5, 'day'),
('Frank', 19, 'Ambulance', '08 – Public Health & Medical Services', 'TOYOTA', 31.23, -96.34, 'AVAILABLE', 5, 'day'),
('Frank', 20, 'Ambulance', '08 – Public Health & Medical Services', 'SUBARU', 32.32, -95.32, 'AVAILABLE', 5, 'day'),
('Frank', 21, 'Ambulance', '08 – Public Health & Medical Services', 'GM', 32.12, -95.42, 'AVAILABLE', 5, 'day'),
('Frank', 22, 'Ambulance', '08 – Public Health & Medical Services', 'FORD', 32.42, -95.38, 'IN USE', 5, 'day'),
('Frank', 23, 'Ambulance', '08 – Public Health & Medical Services', 'FORD', 33.03, -95.50, 'AVAILABLE', 5, 'day'),
('Frank', 24, 'Ambulance', '08 – Public Health & Medical Services', 'Honda', 32.92, -95.43, 'IN REPAIR', 5, 'day'),
('Frank', 25, 'Ambulance', '08 – Public Health & Medical Services', 'Honda', 32.29, -95.28, 'AVAILABLE', 5, 'day'),
('AWhite1', 26, 'Ambulance', '08 – Public Health & Medical Services', 'BMW', 32.239, -95.258, 'AVAILABLE', 10, 'day');

--
-- Constraints for table RESOURCES
--
ALTER TABLE RESOURCES
  ADD CONSTRAINT RESOURCES_ibfk_1 FOREIGN KEY (Username) REFERENCES USERS (Username),
  ADD CONSTRAINT RESOURCES_ibfk_2 FOREIGN KEY (Prim_ESF) REFERENCES ESF (ESF_ID_Desc),
  ADD CONSTRAINT RESOURCES_ibfk_3 FOREIGN KEY (Cost_unit) REFERENCES COST_UNIT (unit);

--
-- Constraints for table REPAIRS
--
ALTER TABLE REPAIRS
  ADD CONSTRAINT REPAIRS_ibfk_1 FOREIGN KEY (Res_ID) REFERENCES RESOURCES (Res_ID);

--
-- Constraints for table REQUEST
--
ALTER TABLE REQUEST
  ADD CONSTRAINT REQUEST_ibfk_1 FOREIGN KEY (Inc_ID) REFERENCES INCIDENT (Inc_ID),
  ADD CONSTRAINT REQUEST_ibfk_2 FOREIGN KEY (Res_ID) REFERENCES RESOURCES (Res_ID);

--
-- Constraints for table RES_CAP
--
ALTER TABLE RES_CAP
  ADD CONSTRAINT RES_CAP_ibfk_1 FOREIGN KEY (Res_ID) REFERENCES RESOURCES (Res_ID);

--
-- Constraints for table RES_ADDI_ESF
--
ALTER TABLE RES_ADDI_ESF
  ADD CONSTRAINT RES_ADDI_ESF_ibfk_1 FOREIGN KEY (Res_ID) REFERENCES RESOURCES (Res_ID),
  ADD CONSTRAINT RES_ADDI_ESF_ibfk_2 FOREIGN KEY (ID_Desc) REFERENCES ESF (ESF_ID_Desc);

--
-- Constraints for table INCIDENT
--
ALTER TABLE INCIDENT
  ADD CONSTRAINT INCIDENT_ibfk_1 FOREIGN KEY (Username) REFERENCES USERS (Username);

