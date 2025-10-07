-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`cursos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`cursos` (
  `id` INT NOT NULL,
  `nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nome_UNIQUE` (`nome` ASC)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`alunos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`alunos` (
  `id` INT NOT NULL,
  `nome` VARCHAR(45) NULL,
  `idade` VARCHAR(45) NULL,
  `curso_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_alunos_cursos_idx` (`curso_id` ASC),
  CONSTRAINT `fk_alunos_cursos`
    FOREIGN KEY (`curso_id`)
    REFERENCES `mydb`.`cursos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
