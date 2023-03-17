create database if not exists PARKINGMASTER;
use parkingmaster;

Create table if not exists CUSTOMER (
	Name	varchar(30) not null,
	PhoneNum char(10),
	Username varchar(25) not null,
	Password varchar(25) not null,
	UserType varchar(20) not null,
	Primary key (Username));

Create table if not exists RESERVATION (
	TrackNum smallint not null,
	Date date,
	totalfee decimal(9,2),
	CancelStatus smallint not null,
    	username varchar(25) not null,
    	eventname varchar(30) not null,
    	garagename varchar(30) not null,
	Primary key (TrackNum));

Create table if not exists GARAGE (
	Name varchar(30) not null,
	Address varchar(95),
	NumSpace smallint,
	garagefee decimal(9,2),
	Primary key (Name));

Create table if not exists VENUE (
	Name varchar(30) not null,
	Primary key (Name));

Create table if not exists EVENT (
	Name varchar(30) not null,
	StartDate date,
	EndDate date,
	eventcharge decimal(9,2),
    	VenueName varchar(30) not null,
	Primary key (Name),
    	Foreign key (VenueName) references VENUE(Name) on delete cascade);

Create table if not exists DISTANCE_TO (
	GarageName varchar(30) not null,
	VenueName varchar(30) not null,
	Distance smallint,
	Primary key (GarageName, VenueName),
	Foreign key (GarageName) references GARAGE(Name) on delete cascade,
	Foreign key (VenueName) references VENUE(Name) on delete cascade);

Create or replace sql security definer view reservationnum 
	as select garagename,
	date,
	count(*)as count 
    	from reservation 
    	where cancelstatus = 0 
    	group by date, garagename;
	