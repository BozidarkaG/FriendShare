-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema diplomski
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema diplomski
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `wwwmania_diplomskiBozidarka` DEFAULT CHARACTER SET utf8 ;
USE `wwwmania_diplomskiBozidarka` ;

-- -----------------------------------------------------
-- Table `wwwmania_diplomskiBozidarka`.`korisnik`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wwwmania_diplomskiBozidarka`.`korisnik` (
  `id_korisnik` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(45) NULL DEFAULT NULL,
  `prezime` VARCHAR(45) NULL DEFAULT NULL,
  `datum_rodjenja` DATE NULL DEFAULT NULL,
  `korisnicko_ime` VARCHAR(45) NULL DEFAULT NULL,
  `pol` VARCHAR(10) NULL DEFAULT NULL,
  `sifra` VARCHAR(100) NULL DEFAULT NULL,
  `slika_profila` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id_korisnik`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `wwwmania_diplomskiBozidarka`.`objava`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wwwmania_diplomskiBozidarka`.`objava` (
  `id_objave` INT NOT NULL AUTO_INCREMENT,
  `slika` VARCHAR(300) NULL DEFAULT NULL,
  `datum_objave` DATETIME NULL DEFAULT NULL,
  `korisnik_id_korisnik` INT NOT NULL,
  `tekst` VARCHAR(1000) NULL,
  PRIMARY KEY (`id_objave`),
  INDEX `fk_objava_korisnik_idx` (`korisnik_id_korisnik` ASC),
  CONSTRAINT `fk_objava_korisnik`
    FOREIGN KEY (`korisnik_id_korisnik`)
    REFERENCES `wwwmania_diplomskiBozidarka`.`korisnik` (`id_korisnik`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `wwwmania_diplomskiBozidarka`.`komentar`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wwwmania_diplomskiBozidarka`.`komentar` (
  `id_komentara` INT NOT NULL AUTO_INCREMENT,
  `vrijeme_postavljanja` DATETIME NULL DEFAULT NULL,
  `sadrzaj_komentara` VARCHAR(1024) NULL DEFAULT NULL,
  `objava_id_objave` INT NULL DEFAULT NULL,
  `komentar_id_komentara` INT NULL DEFAULT NULL,
  `korisnik_id_korisnik` INT NOT NULL,
  PRIMARY KEY (`id_komentara`),
  INDEX `fk_komentar_objava1_idx` (`objava_id_objave` ASC),
  INDEX `fk_komentar_komentar1_idx` (`komentar_id_komentara` ASC),
  INDEX `fk_komentar_korisnik1_idx` (`korisnik_id_korisnik` ASC),
  CONSTRAINT `fk_komentar_objava1`
    FOREIGN KEY (`objava_id_objave`)
    REFERENCES `wwwmania_diplomskiBozidarka`.`objava` (`id_objave`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_komentar_komentar1`
    FOREIGN KEY (`komentar_id_komentara`)
    REFERENCES `wwwmania_diplomskiBozidarka`.`komentar` (`id_komentara`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_komentar_korisnik1`
    FOREIGN KEY (`korisnik_id_korisnik`)
    REFERENCES `wwwmania_diplomskiBozidarka`.`korisnik` (`id_korisnik`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `wwwmania_diplomskiBozidarka`.`poruke`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wwwmania_diplomskiBozidarka`.`poruke` (
  `id_poruke` INT NOT NULL AUTO_INCREMENT,
  `sadrzaj_poruke` VARCHAR(1024) NULL DEFAULT NULL,
  `posiljaoc` INT NOT NULL,
  `primaoc` INT NOT NULL,
  `vrijeme` DATETIME NULL,
  `procitano` VARCHAR(45) NULL,
  PRIMARY KEY (`id_poruke`),
  INDEX `fk_poruke_korisnik1_idx` (`posiljaoc` ASC),
  INDEX `fk_poruke_korisnik2_idx` (`primaoc` ASC),
  CONSTRAINT `fk_poruke_korisnik1`
    FOREIGN KEY (`posiljaoc`)
    REFERENCES `wwwmania_diplomskiBozidarka`.`korisnik` (`id_korisnik`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_poruke_korisnik2`
    FOREIGN KEY (`primaoc`)
    REFERENCES `wwwmania_diplomskiBozidarka`.`korisnik` (`id_korisnik`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `wwwmania_diplomskiBozidarka`.`zahtjev`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wwwmania_diplomskiBozidarka`.`zahtjev` (
  `idzahtjev` INT NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(45) NULL,
  `korisnik_id_poslao` INT NOT NULL,
  `korisnik_id_prima` INT NOT NULL,
  PRIMARY KEY (`idzahtjev`),
  INDEX `fk_zahtjev_korisnik1_idx` (`korisnik_id_poslao` ASC),
  INDEX `fk_zahtjev_korisnik2_idx` (`korisnik_id_prima` ASC),
  CONSTRAINT `fk_zahtjev_korisnik1`
    FOREIGN KEY (`korisnik_id_poslao`)
    REFERENCES `wwwmania_diplomskiBozidarka`.`korisnik` (`id_korisnik`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_zahtjev_korisnik2`
    FOREIGN KEY (`korisnik_id_prima`)
    REFERENCES `wwwmania_diplomskiBozidarka`.`korisnik` (`id_korisnik`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
