
DROP SCHEMA public CASCADE;
CREATE SCHEMA public;

CREATE TABLE asiakas (
	anro SERIAL,
	enimi VARCHAR(20) NOT NULL,
	snimi VARCHAR(40) NOT NULL,
	kaupunki VARCHAR(50) NOT NULL,
	postinumero INT NOT NULL,
	katuosoite VARCHAR(50) NOT NULL,
	PRIMARY KEY(anro)
);

CREATE TABLE tyosuoritus (
	tsnro SERIAL,
	tila VARCHAR(20) NOT NULL,
	kokonaissumma DECIMAL(10, 2) NOT NULL,
	kaupunki VARCHAR(50) NOT NULL,
	postinumero INT NOT NULL,
	katuosoite VARCHAR(50) NOT NULL,
	tyyppi VARCHAR(20) NOT NULL,
	linkki INT,
	anro INT NOT NULL,
	PRIMARY KEY(tsnro),
	FOREIGN KEY(linkki) REFERENCES tyosuoritus(tsnro),
	FOREIGN KEY(anro) REFERENCES asiakas(anro)
);

CREATE TABLE tarvike (
	trnro SERIAL,
	nimi VARCHAR (50) NOT NULL,
	myyntihinta DECIMAL NOT NULL,
	yksikko VARCHAR(10) NOT NULL,
	varastotilanne DECIMAL NOT NULL,
	sisaan_hinta DECIMAL NOT NULL,
	alennus INT NOT NULL,
	alv DECIMAL NOT NULL,
	tsnro INT,
	PRIMARY KEY(trnro)
);

CREATE TABLE kaytetyt_tarvikkeet (
  trnro INT,
  tsnro INT,
  nimi VARCHAR (50),
  maara INT,
  alennus DECIMAL NOT NULL,
  PRIMARY KEY (trnro, tsnro),
  FOREIGN KEY (trnro) REFERENCES tarvike,
  FOREIGN KEY (tsnro) REFERENCES tyosuoritus
);

CREATE TABLE lasku (
	lnro SERIAL,
	kokonaissumma DECIMAL NOT NULL,
	laskutuslisa DECIMAL NOT NULL,
	viivastyskorko DECIMAL,
	erapaiva DATE NOT NULL,
	maksupvm DATE,
	mones_muistutus INT NOT NULL,
	edellinen INT,
	tsnro INT,
	tarkistettu BOOLEAN,
	PRIMARY KEY(lnro),
	FOREIGN KEY(edellinen) REFERENCES lasku(lnro),
	FOREIGN KEY(tsnro) REFERENCES tyosuoritus(tsnro)
);

CREATE TABLE tyo (
	tynro SERIAL,
	nimi VARCHAR(50),
	hinta DECIMAL NOT NULL,
	alv DECIMAL NOT NULL,
	PRIMARY KEY(tynro)
);

CREATE TABLE tehdyt_tyot (
	tsnro INT,
	tynro INT,
	nimi VARCHAR(50),
	tunnit DECIMAL NOT NULL,
	alennus DECIMAL NOT NULL,
	PRIMARY KEY(tsnro, tynro),
	FOREIGN KEY(tsnro) REFERENCES tyosuoritus(tsnro),
	FOREIGN KEY(tynro) REFERENCES tyo(tynro)
);

CREATE OR REPLACE FUNCTION kokonaissummaan_muutettu_tyo() RETURNS TRIGGER AS $kokonaissummaan_muutettu_tyo$
DECLARE
oldTunnit integer DEFAULT 0;
BEGIN
IF (TG_OP = 'UPDATE') THEN
UPDATE tyosuoritus SET kokonaissumma = kokonaissumma - (OLD.tunnit * (SELECT hinta FROM tyo WHERE tynro = OLD.tynro) * ((100-OLD.alennus)/100))
WHERE tsnro=OLD.tsnro;
END IF;
UPDATE tyosuoritus SET kokonaissumma = kokonaissumma + (NEW.tunnit * (SELECT hinta FROM tyo WHERE tynro = NEW.tynro) * ((100-NEW.alennus)/100))
WHERE tsnro=NEW.tsnro;
RETURN NEW;
END;
$kokonaissummaan_muutettu_tyo$ LANGUAGE plpgsql;

CREATE TRIGGER tehty_tyo_muuttui
AFTER INSERT OR UPDATE ON tehdyt_tyot
FOR EACH ROW EXECUTE PROCEDURE kokonaissummaan_muutettu_tyo();

CREATE OR REPLACE FUNCTION kokonaissummaan_muutettu_tarvike() RETURNS TRIGGER AS $kokonaissummaan_muutettu_tarvike$
BEGIN
IF (TG_OP = 'UPDATE') THEN
UPDATE tyosuoritus SET kokonaissumma = kokonaissumma - (OLD.maara * (SELECT myyntihinta FROM tarvike WHERE trnro = OLD.trnro) * ((100-OLD.alennus)/100))
WHERE tsnro=OLD.tsnro;
END IF;
UPDATE tyosuoritus SET kokonaissumma = kokonaissumma + (NEW.maara * (SELECT myyntihinta FROM tarvike WHERE trnro = NEW.trnro) * ((100-NEW.alennus)/100))
WHERE tsnro=NEW.tsnro;
RETURN NEW;
END;
$kokonaissummaan_muutettu_tarvike$ LANGUAGE plpgsql;

CREATE TRIGGER kaytetty_tarvike_muuttui
AFTER INSERT OR UPDATE ON kaytetyt_tarvikkeet
FOR EACH ROW EXECUTE PROCEDURE kokonaissummaan_muutettu_tarvike();

CREATE OR REPLACE FUNCTION tarvikkeisiin_muutettu_tarvikemaara() RETURNS TRIGGER AS $tarvikkeisiin_muutettu_tarvikemaara$
BEGIN
IF (TG_OP = 'UPDATE') THEN
UPDATE tarvike SET varastotilanne = varastotilanne + OLD.maara
WHERE trnro=OLD.trnro;
END IF;
UPDATE tarvike SET varastotilanne = varastotilanne - NEW.maara
WHERE trnro=NEW.trnro;
RETURN NEW;
END;
$tarvikkeisiin_muutettu_tarvikemaara$ LANGUAGE plpgsql;

CREATE TRIGGER kaytetty_tarvike_maara_muuttui
AFTER INSERT OR UPDATE ON kaytetyt_tarvikkeet
FOR EACH ROW EXECUTE PROCEDURE tarvikkeisiin_muutettu_tarvikemaara();













































