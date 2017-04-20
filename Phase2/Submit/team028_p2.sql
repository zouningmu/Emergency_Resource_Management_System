CREATE DATABASE IF NOT EXISTS emergency_resource;
USE emergency_resource;
CREATE TABLE USERS (
Username varchar(50) NOT NULL,
Name_s varchar(50) NOT NULL,
Password_s varchar(50) NOT NULL,
PRIMARY KEY (Username));

CREATE TABLE INDIVIDUAL (
Username varchar(50) NOT NULL,
Job_title varchar(50) NOT NULL,
Date_hired datetime NOT NULL,
PRIMARY KEY (Username),
FOREIGN KEY (Username)
REFERENCES USERS (Username));

CREATE TABLE COMPANY (
Username varchar(50) NOT NULL,
Headquarter varchar(50) NOT NULL,
PRIMARY KEY (Username),
FOREIGN KEY (Username)
REFERENCES USERS (Username));

CREATE TABLE GOV_AGENCY (
Username varchar(50) NOT NULL,
Jurisdiction varchar(50) NOT NULL,
PRIMARY KEY (Username),
FOREIGN KEY (Username)
REFERENCES USERS (Username));

CREATE TABLE MUNICIPALITY (
Username varchar(50) NOT NULL,
Population_size varchar(50) NOT NULL,
PRIMARY KEY (Username),
FOREIGN KEY (Username)
REFERENCES USERS (Username));

CREATE TABLE INCIDENT (
Username varchar(50) NOT NULL,
Inc_ID int NOT NULL,
Date_s datetime NOT NULL,
Description varchar(50) NOT NULL,
Longitude double NOT NULL,
Latitude double NOT NULL,
PRIMARY KEY (Username, Inc_ID),
FOREIGN KEY (Username)
REFERENCES USERS (Username));

CREATE TABLE RESOURCES (
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
FOREIGN KEY (Username)
REFERENCES USERS (Username),
FOREIGN KEY (Prim_ESF)
REFERENCES ESF (ID_description),
FOREIGN KEY (Cost_unit)
REFERENCES COST_UNIT (unit));

CREATE TABLE COST_UNIT (
Unit varchar(50) NOT NULL,
PRIMARY KEY (Unit));

INSERT INTO COST_UNIT (Unit) VALUES ('hour');
INSERT INTO COST_UNIT (Unit) VALUES ('day');
INSERT INTO COST_UNIT (Unit) VALUES ('week');

CREATE TABLE ESF (
ESF_ID_Desc varchar(100) NOT NULL,
PRIMARY KEY (ESF_ID_Desc));

INSERT INTO ESF (ESF_ID_Desc) VALUES ('1 – Transportation');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('2 – Communications');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('3 – Public Works & Engineering');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('4 – Firefighting');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('5 – Emergency Management');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('6 – Mass Care, Emergency Assistance, Housing, & Human Services');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('7 – Logistics Management & Resource Support');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('8 – Public Health & Medical Services');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('9 – Search & Rescue');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('10 – Oil & Hazardous Materials Response');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('11 – Agriculture & Natural Resources');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('12 – Energy');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('13 – Public Safety & Security');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('14 – Long-Term Community Recovery');
INSERT INTO ESF (ESF_ID_Desc) VALUES ('15 – External Affairs');

CREATE TABLE RES_ADDI_ESF (
Res_ID int NOT NULL,
ID_Desc varchar(100) NOT NULL,
PRIMARY KEY (Res_ID, ID_Desc),
FOREIGN KEY (Res_ID)
REFERENCES RESOURCES (Res_ID),
FOREIGN KEY (ID_Desc)
REFERENCES ESF (ESF_ID_Desc));

CREATE TABLE RES_CAP (
Res_ID int NOT NULL,
Res_capability varchar(50) NOT NULL,
PRIMARY KEY (Res_ID, Res_capability),
FOREIGN KEY (Res_ID)
REFERENCES RESOURCES (Res_ID));

CREATE TABLE REQUEST (
Inc_ID int NOT NULL,
Res_ID int NOT NULL,
Start_date datetime NULL,
Return_by datetime NULL,
PRIMARY KEY (Inc_ID, Res_ID),
FOREIGN KEY (Inc_ID)
REFERENCES INCIDENT (Inc_ID),
FOREIGN KEY (Res_ID)
REFERENCES RESOURCES (Res_ID));

CREATE TABLE REPAIRS (
	Res_ID int NOT NULL,
	Start_date datetime NOT NULL,
	Return_by datetime NOT NULL,
	PRIMARY KEY (Res_ID, Start_date),
	FOREIGN KEY (Res_ID)
		REFERENCES RESOURCES (Res_ID));