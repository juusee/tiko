INSERT INTO tarvike (nimi, myyntihinta, yksikko, varastotilanne, sisaan_hinta, alv, alennus)
VALUES ('jatkojohto', 10, 'kpl', 10, 5, 24, 0);

INSERT INTO tarvike (nimi, myyntihinta, yksikko, varastotilanne, sisaan_hinta, alv, alennus)
VALUES ('pistorasia', 5, 'kpl', 20, 3, 24, 0);

INSERT INTO tarvike (nimi, myyntihinta, yksikko, varastotilanne, sisaan_hinta, alv, alennus)
VALUES ('opaskirja', 10, 'kpl', 20, 5, 10, 0);

INSERT INTO tyo (nimi, hinta, alv)
VALUES ('suunnittelu', 55, 24);

INSERT INTO tyo (nimi, hinta, alv)
VALUES ('tyo', 45, 24);

INSERT INTO tyo (nimi, hinta, alv)
VALUES ('aputyo', 35, 24);

INSERT INTO asiakas (enimi, snimi, kaupunki, postinumero, katuosoite)
VALUES ('Jussi', 'Rinta-Jaskari', 'Tampere', 33100, 'Itsenäisyydenkatu 1');

INSERT INTO tyosuoritus (tila, kokonaissumma, kaupunki, postinumero, katuosoite, tyyppi, anro)
VALUES ('tuntityo_kesken', 0, 'Tampere', 33100, 'Testikatu 1', 'kerrostalo', 1);

INSERT INTO tehdyt_tyot (tynro, tsnro, nimi, tunnit, alennus)
VALUES (1, 1, 'suunnittelu', 4, 0);

INSERT INTO tehdyt_tyot (tynro, tsnro, nimi, tunnit, alennus)
VALUES (2, 1, 'tyo', 8, 0);

INSERT INTO tehdyt_tyot (tynro, tsnro, nimi, tunnit, alennus)
VALUES (3, 1, 'aputyo', 8, 0);

INSERT INTO kaytetyt_tarvikkeet (trnro, tsnro, nimi, maara, alennus)
VALUES (1, 1, 'jatkojohto', 2, 0);

INSERT INTO kaytetyt_tarvikkeet (trnro, tsnro, nimi, maara, alennus)
VALUES (2, 1, 'pistorasia', 3, 0);

INSERT INTO kaytetyt_tarvikkeet (trnro, tsnro, nimi, maara, alennus)
VALUES (3, 1, 'opaskirja', 1, 0);

INSERT INTO tyosuoritus (tila, kokonaissumma, kaupunki, postinumero, katuosoite, tyyppi, anro)
VALUES ('tuntityo_kesken', 0, 'Tampere', 33100, 'Testikatu 2', 'omakotitalo', 1);

INSERT INTO tehdyt_tyot (tynro, tsnro, nimi, tunnit, alennus)
VALUES (2, 2, 'tyo', 16, 0);

INSERT INTO tehdyt_tyot (tynro, tsnro, nimi, tunnit, alennus)
VALUES (3, 2, 'aputyo', 16, 0);







INSERT INTO kaytetyt_tarvikkeet (trnro, tsnro, nimi, maara, alennus)
VALUES (2, 2, 'pistorasia', 4, 0);





