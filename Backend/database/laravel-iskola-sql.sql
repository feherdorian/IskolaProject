# Adatb�zis l�trehoz�s
CREATE DATABASE `laravel-iskola`
	CHARACTER SET utf8
	COLLATE utf8_hungarian_ci;

SELECT * from diaks;
select * from sports;
select * from osztalies;
select * from sportolas;


delete from diaks;
delete from osztalies;
delete from sports;


insert into osztalyok
  (id, osztalyNev)
  VALUES
  (1, '1a'), (2, '1b'), (3, '2a'), (4, '2b'), (5, '3a'), (6, '3b');

insert into sportok
  (id, sportNev)
  VALUES
  (1, 'Foci'), (2, 'Tenisz'), (3, 'Lovagl�s'), (4, 'Kos�rlabda'), (5, 'Tenisz'), (6, 'Sakk');

insert into diakok
  (id, osztalyId, nev, neme, szuletett, helyseg, osztondij, atlag)
  VALUES
  (1, 1, 'Rudi', 1, '2018-01-12', 'Szolnok', 4000, 3.5),
  (2, 1, 'P�ter', 1, '2018-03-02', 'Szolnok', 4000, 4.5),
  (3, 1, 'B�la', 1, '2017-06-24', 'Budapest', 4000, 3.7),
  (4, 2, 'Enik�', 0, '2016-05-17', 'Abony', 4000, 4.2),
  (5, 2, '�gnes', 0, '2018-04-22', 'Szolnok', 4000, 3.5),
  (6, 1, 'Anik�', 0, '2018-11-02', 'Szolnok', 4000, 3.1),
  (7, 2, 'Feri', 1, '2018-03-21', 'Abony', 4000, 3.5);

insert into sportolas
  (diakokId, sportokId)
  VALUES
  (2, 2), (2, 5), (4, 1), (6, 1), (6, 4), (6, 5);

call iskolaKeszit();



