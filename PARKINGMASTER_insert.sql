insert into CUSTOMER values
("venadmin", "0000000000", "venadmin", "venadmin", "venadmin"),
("paradmin", "1111111111", "paradmin", "paradmin", "paradmin"),
("user user", NULL, "user", "password", "user"),
("user2 user2", "1234567890", "user2", "1234", "user"),
("user3 user3", "0987654321", "user3", "1234567", "user");

insert into RESERVATION values
(1, "2022-04-28", 75.00, 0, "user", "Event1", "Garage1"),
(2, "2022-04-24", 25.00, 1, "user2", "Event2", "Garage2"),
(3, "2022-04-02", 80.00, 0, "user3", "Event4", "Garage1"),
(4, "2022-04-20", 22.50, 0, "user", "Event3", "Garage2");

insert into GARAGE values
("Garage1", "123 road", 250, 70.00),
("Garage2", "456 street", 1000, 15.00);

insert into VENUE values
("Venue1"),
("Venue2");

insert into EVENT values
("Event1", "2022-04-28", NULL, 5.00, "Venue1"),
("Event2", "2022-04-22", "2022-04-24", 10.00, "Venue2"),
("Event3", "2022-04-20", NULL, 7.50, "Venue1"),
("Event4", "2022-04-01", "2022-04-03", 10.00, "Venue2");

insert into DISTANCE_TO values
("Garage1", "Venue1", 1),
("Garage2", "Venue2", 3),
("Garage1", "Venue2", 5),
("Garage2", "Venue1", 2);