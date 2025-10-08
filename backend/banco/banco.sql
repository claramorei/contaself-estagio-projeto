-- Desliga checagens temporariamente para evitar erros de FK e UNIQUE
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Cria schema
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8;
USE `mydb`;

-- -----------------------------------------------------
-- Tabela cursos
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`cursos`;
CREATE TABLE `mydb`.`cursos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nome_UNIQUE` (`nome`)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabela alunos
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`alunos`;
CREATE TABLE `mydb`.`alunos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `idade` INT NOT NULL,
  `curso_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_alunos_cursos_idx` (`curso_id`),
  CONSTRAINT `fk_alunos_cursos`
    FOREIGN KEY (`curso_id`)
    REFERENCES `mydb`.`cursos` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Restaura configurações anteriores
-- -----------------------------------------------------
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

