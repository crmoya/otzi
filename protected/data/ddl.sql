SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `contratos_ctipaume` DEFAULT CHARACTER SET utf8 ;
USE `contratos_ctipaume` ;

-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`usuarios`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user` VARCHAR(100) NOT NULL ,
  `nombre` VARCHAR(100) NOT NULL ,
  `email` VARCHAR(100) NOT NULL ,
  `clave` VARCHAR(45) NOT NULL ,
  `rol` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  UNIQUE INDEX `usuario_UNIQUE` (`user` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`estados_contratos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`estados_contratos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`contratos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`contratos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(200) NOT NULL ,
  `fecha_inicio` DATE NOT NULL ,
  `plazo` INT NULL ,
  `monto_inicial` INT NULL ,
  `modificaciones_monto` INT NULL ,
  `monto_actualizado` INT NULL ,
  `creador_id` INT NOT NULL ,
  `modificador_id` INT NOT NULL ,
  `observacion` TEXT NULL ,
  `estados_contratos_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) ,
  INDEX `fk_contratos_usuarios1_idx` (`creador_id` ASC) ,
  INDEX `fk_contratos_usuarios2_idx` (`modificador_id` ASC) ,
  INDEX `fk_contratos_estados_contratos1_idx` (`estados_contratos_id` ASC) ,
  CONSTRAINT `fk_contratos_usuarios1`
    FOREIGN KEY (`creador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_usuarios2`
    FOREIGN KEY (`modificador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_estados_contratos1`
    FOREIGN KEY (`estados_contratos_id` )
    REFERENCES `contratos_ctipaume`.`estados_contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`resoluciones`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`resoluciones` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `numero` INT NOT NULL ,
  `fecha_inicio` DATE NOT NULL ,
  `fecha_final` DATE NOT NULL ,
  `monto` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  `observacion` TEXT NULL ,
  `creador_id` INT NOT NULL ,
  `modificador_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `numero_UNIQUE` (`numero` ASC) ,
  INDEX `fk_resoluciones_contratos1_idx` (`contratos_id` ASC) ,
  INDEX `fk_resoluciones_usuarios1_idx` (`creador_id` ASC) ,
  INDEX `fk_resoluciones_usuarios2_idx` (`modificador_id` ASC) ,
  CONSTRAINT `fk_resoluciones_contratos1`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_resoluciones_usuarios1`
    FOREIGN KEY (`creador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_resoluciones_usuarios2`
    FOREIGN KEY (`modificador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`instituciones`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`instituciones` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`tipos_garantias`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`tipos_garantias` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`garantias`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`garantias` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `numero` INT NOT NULL ,
  `monto` INT NOT NULL ,
  `duracion_dias` INT NOT NULL ,
  `instituciones_id` INT NOT NULL ,
  `tipos_garantias_id` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `numero_UNIQUE` (`numero` ASC) ,
  INDEX `fk_garantias_instituciones_idx` (`instituciones_id` ASC) ,
  INDEX `fk_garantias_tipos_garantias1_idx` (`tipos_garantias_id` ASC) ,
  INDEX `fk_garantias_contratos1_idx` (`contratos_id` ASC) ,
  CONSTRAINT `fk_garantias_instituciones`
    FOREIGN KEY (`instituciones_id` )
    REFERENCES `contratos_ctipaume`.`instituciones` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_garantias_objetos_garantias1`
    FOREIGN KEY (`objetos_garantias_id` )
    REFERENCES `contratos_ctipaume`.`objetos_garantias` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_garantias_tipos_garantias1`
    FOREIGN KEY (`tipos_garantias_id` )
    REFERENCES `contratos_ctipaume`.`tipos_garantias` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_garantias_contratos1`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`objetos_garantias`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`objetos_garantias` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `descripcion` VARCHAR(200) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`garantias`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`garantias` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `numero` INT NOT NULL ,
  `monto` INT NOT NULL ,
  `duracion_dias` INT NOT NULL ,
  `instituciones_id` INT NOT NULL ,
  `tipos_garantias_id` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  `objetos_garantias_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `numero_UNIQUE` (`numero` ASC) ,
  INDEX `fk_garantias_instituciones_idx` (`instituciones_id` ASC) ,
  INDEX `fk_garantias_tipos_garantias1_idx` (`tipos_garantias_id` ASC) ,
  INDEX `fk_garantias_contratos1_idx` (`contratos_id` ASC) ,
  INDEX `fk_garantias_objetos_garantias1_idx` (`objetos_garantias_id` ASC) ,
  CONSTRAINT `fk_garantias_instituciones`
    FOREIGN KEY (`instituciones_id` )
    REFERENCES `contratos_ctipaume`.`instituciones` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_garantias_tipos_garantias1`
    FOREIGN KEY (`tipos_garantias_id` )
    REFERENCES `contratos_ctipaume`.`tipos_garantias` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_garantias_contratos1`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_garantias_objetos_garantias1`
    FOREIGN KEY (`objetos_garantias_id` )
    REFERENCES `contratos_ctipaume`.`objetos_garantias` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`flujos_reales`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`flujos_reales` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `produccion` INT NOT NULL ,
  `costo` INT NULL ,
  `reajuste` INT NULL ,
  `retencion` INT NULL ,
  `descuento` INT NULL ,
  `mes` INT NOT NULL ,
  `agno` INT NOT NULL ,
  `comentarios` VARCHAR(200) NULL ,
  `resoluciones_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_flujos_reales_resoluciones1_idx` (`resoluciones_id` ASC) ,
  CONSTRAINT `fk_flujos_reales_resoluciones1`
    FOREIGN KEY (`resoluciones_id` )
    REFERENCES `contratos_ctipaume`.`resoluciones` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;




-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`flujos_programados`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`flujos_programados` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `produccion` INT NOT NULL ,
  `costo` INT NULL ,
  `mes` INT NOT NULL ,
  `agno` INT NOT NULL ,
  `comentarios` VARCHAR(200) NULL ,
  `resoluciones_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_flujos_programados_resoluciones1_idx` (`resoluciones_id` ASC) ,
  CONSTRAINT `fk_flujos_programados_resoluciones1`
    FOREIGN KEY (`resoluciones_id` )
    REFERENCES `contratos_ctipaume`.`resoluciones` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`authitem`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`authitem` (
  `name` VARCHAR(64) NOT NULL ,
  `type` INT NOT NULL ,
  `description` TEXT NULL ,
  `bizrule` TEXT NULL ,
  `data` TEXT NULL ,
  PRIMARY KEY (`name`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`authitemchild`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`authitemchild` (
  `parent` VARCHAR(64) NOT NULL ,
  `child` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`parent`, `child`) ,
  INDEX `fk_authitemchild_authitem2_idx` (`child` ASC) ,
  CONSTRAINT `fk_authitemchild_authitem1`
    FOREIGN KEY (`parent` )
    REFERENCES `contratos_ctipaume`.`authitem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_authitemchild_authitem2`
    FOREIGN KEY (`child` )
    REFERENCES `contratos_ctipaume`.`authitem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`authassignment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`authassignment` (
  `itemname` VARCHAR(64) NOT NULL ,
  `userid` VARCHAR(64) NOT NULL ,
  `bizrule` TEXT NULL ,
  `data` TEXT NULL ,
  PRIMARY KEY (`itemname`, `userid`) ,
  CONSTRAINT `fk_authassignment_authitem1`
    FOREIGN KEY (`itemname` )
    REFERENCES `contratos_ctipaume`.`authitem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`usuarios_contratos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`usuarios_contratos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `usuarios_id` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_usuarios_has_contratos_contratos1_idx` (`contratos_id` ASC) ,
  INDEX `fk_usuarios_has_contratos_usuarios1_idx` (`usuarios_id` ASC) ,
  UNIQUE INDEX `usuarios_contratos_uq` (`usuarios_id` ASC, `contratos_id` ASC) ,
  CONSTRAINT `fk_usuarios_has_contratos_usuarios1`
    FOREIGN KEY (`usuarios_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_contratos_contratos1`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `contratos_ctipaume`.`contratos_de_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contratos_ctipaume`.`contratos_de_usuario` (`contratos_id` INT, `id` INT, `nombre` INT, `fecha_inicio` INT, `observacion` INT, `estados_contratos_id` INT, `estados_contratos_nombre` INT, `usuarios_id` INT);

-- -----------------------------------------------------
-- View `contratos_ctipaume`.`contratos_de_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contratos_ctipaume`.`contratos_de_usuario`;
USE `contratos_ctipaume`;
CREATE  OR REPLACE VIEW `contratos_de_usuario` AS
select 	c.id as contratos_id,uc.id,c.nombre,c.fecha_inicio,c.observacion,e.id as estados_contratos_id,e.nombre as estados_contratos_nombre,uc.usuarios_id
from 	contratos as c,usuarios_contratos as uc,estados_contratos as e
where	c.id = uc.contratos_id and
		e.id = c.estados_contratos_id;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;



























SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `contratos_ctipaume` DEFAULT CHARACTER SET utf8 ;
USE `contratos_ctipaume` ;

-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`usuarios`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user` VARCHAR(100) NOT NULL ,
  `nombre` VARCHAR(100) NOT NULL ,
  `email` VARCHAR(100) NOT NULL ,
  `clave` VARCHAR(45) NOT NULL ,
  `rol` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  UNIQUE INDEX `usuario_UNIQUE` (`user` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`estados_contratos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`estados_contratos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`contratos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`contratos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(200) NOT NULL ,
  `fecha_inicio` DATE NOT NULL ,
  `plazo` INT NULL ,
  `monto_inicial` INT NULL ,
  `modificaciones_monto` INT NULL ,
  `monto_actualizado` INT NULL ,
  `creador_id` INT NOT NULL ,
  `modificador_id` INT NOT NULL ,
  `observacion` TEXT NULL ,
  `estados_contratos_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) ,
  INDEX `fk_contratos_usuarios1_idx` (`creador_id` ASC) ,
  INDEX `fk_contratos_usuarios2_idx` (`modificador_id` ASC) ,
  INDEX `fk_contratos_estados_contratos1_idx` (`estados_contratos_id` ASC) ,
  CONSTRAINT `fk_contratos_usuarios1`
    FOREIGN KEY (`creador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_usuarios2`
    FOREIGN KEY (`modificador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_estados_contratos1`
    FOREIGN KEY (`estados_contratos_id` )
    REFERENCES `contratos_ctipaume`.`estados_contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`resoluciones`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`resoluciones` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `numero` INT NOT NULL ,
  `fecha_inicio` DATE NOT NULL ,
  `fecha_final` DATE NOT NULL ,
  `monto` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  `observacion` TEXT NULL ,
  `creador_id` INT NOT NULL ,
  `modificador_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `numero_UNIQUE` (`numero` ASC) ,
  INDEX `fk_resoluciones_contratos1_idx` (`contratos_id` ASC) ,
  INDEX `fk_resoluciones_usuarios1_idx` (`creador_id` ASC) ,
  INDEX `fk_resoluciones_usuarios2_idx` (`modificador_id` ASC) ,
  CONSTRAINT `fk_resoluciones_contratos1`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_resoluciones_usuarios1`
    FOREIGN KEY (`creador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_resoluciones_usuarios2`
    FOREIGN KEY (`modificador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`instituciones`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`instituciones` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`tipos_garantias`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`tipos_garantias` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`flujos_reales`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`flujos_reales` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `produccion` INT NOT NULL ,
  `costo` INT NULL ,
  `reajuste` INT NULL ,
  `retencion` INT NULL ,
  `descuento` INT NULL ,
  `mes` INT NOT NULL ,
  `agno` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  `comentarios` VARCHAR(200) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_flujos_contratos1_idx` (`contratos_id` ASC) ,
  CONSTRAINT `fk_flujos_contratos1`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`flujos_programados`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`flujos_programados` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `produccion` INT NOT NULL ,
  `costo` INT NULL ,
  `mes` INT NOT NULL ,
  `agno` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  `comentarios` VARCHAR(200) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_flujos_contratos1_idx` (`contratos_id` ASC) ,
  CONSTRAINT `fk_flujos_contratos10`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`authitem`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`authitem` (
  `name` VARCHAR(64) NOT NULL ,
  `type` INT NOT NULL ,
  `description` TEXT NULL ,
  `bizrule` TEXT NULL ,
  `data` TEXT NULL ,
  PRIMARY KEY (`name`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`authitemchild`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`authitemchild` (
  `parent` VARCHAR(64) NOT NULL ,
  `child` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`parent`, `child`) ,
  INDEX `fk_authitemchild_authitem2_idx` (`child` ASC) ,
  CONSTRAINT `fk_authitemchild_authitem1`
    FOREIGN KEY (`parent` )
    REFERENCES `contratos_ctipaume`.`authitem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_authitemchild_authitem2`
    FOREIGN KEY (`child` )
    REFERENCES `contratos_ctipaume`.`authitem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`authassignment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`authassignment` (
  `itemname` VARCHAR(64) NOT NULL ,
  `userid` VARCHAR(64) NOT NULL ,
  `bizrule` TEXT NULL ,
  `data` TEXT NULL ,
  PRIMARY KEY (`itemname`, `userid`) ,
  CONSTRAINT `fk_authassignment_authitem1`
    FOREIGN KEY (`itemname` )
    REFERENCES `contratos_ctipaume`.`authitem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`usuarios_contratos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`usuarios_contratos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `usuarios_id` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_usuarios_has_contratos_contratos1_idx` (`contratos_id` ASC) ,
  INDEX `fk_usuarios_has_contratos_usuarios1_idx` (`usuarios_id` ASC) ,
  UNIQUE INDEX `usuarios_contratos_uq` (`usuarios_id` ASC, `contratos_id` ASC) ,
  CONSTRAINT `fk_usuarios_has_contratos_usuarios1`
    FOREIGN KEY (`usuarios_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_contratos_contratos1`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `contratos_ctipaume`.`contratos_de_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contratos_ctipaume`.`contratos_de_usuario` (`contratos_id` INT, `id` INT, `nombre` INT, `fecha_inicio` INT, `observacion` INT, `estados_contratos_id` INT, `estados_contratos_nombre` INT, `usuarios_id` INT);

-- -----------------------------------------------------
-- View `contratos_ctipaume`.`contratos_de_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contratos_ctipaume`.`contratos_de_usuario`;
USE `contratos_ctipaume`;
CREATE  OR REPLACE VIEW `contratos_de_usuario` AS
select 	c.id as contratos_id,uc.id,c.nombre,c.fecha_inicio,c.observacion,e.id as estados_contratos_id,e.nombre as estados_contratos_nombre,uc.usuarios_id
from 	contratos as c,usuarios_contratos as uc,estados_contratos as e
where	c.id = uc.contratos_id and
		e.id = c.estados_contratos_id;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;



SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `contratos_ctipaume` DEFAULT CHARACTER SET utf8 ;
USE `contratos_ctipaume` ;

-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`usuarios`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user` VARCHAR(100) NOT NULL ,
  `nombre` VARCHAR(100) NOT NULL ,
  `email` VARCHAR(100) NOT NULL ,
  `clave` VARCHAR(45) NOT NULL ,
  `rol` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  UNIQUE INDEX `usuario_UNIQUE` (`user` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`estados_contratos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`estados_contratos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`contratos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`contratos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(200) NOT NULL ,
  `fecha_inicio` DATE NOT NULL ,
  `plazo` INT NULL ,
  `monto_inicial` INT NULL ,
  `modificaciones_monto` INT NULL ,
  `monto_actualizado` INT NULL ,
  `creador_id` INT NOT NULL ,
  `modificador_id` INT NOT NULL ,
  `observacion` TEXT NULL ,
  `estados_contratos_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) ,
  INDEX `fk_contratos_usuarios1_idx` (`creador_id` ASC) ,
  INDEX `fk_contratos_usuarios2_idx` (`modificador_id` ASC) ,
  INDEX `fk_contratos_estados_contratos1_idx` (`estados_contratos_id` ASC) ,
  CONSTRAINT `fk_contratos_usuarios1`
    FOREIGN KEY (`creador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_usuarios2`
    FOREIGN KEY (`modificador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_estados_contratos1`
    FOREIGN KEY (`estados_contratos_id` )
    REFERENCES `contratos_ctipaume`.`estados_contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`resoluciones`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`resoluciones` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `numero` INT NOT NULL ,
  `fecha_inicio` DATE NOT NULL ,
  `fecha_final` DATE NOT NULL ,
  `monto` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  `observacion` TEXT NULL ,
  `creador_id` INT NOT NULL ,
  `modificador_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `numero_UNIQUE` (`numero` ASC) ,
  INDEX `fk_resoluciones_contratos1_idx` (`contratos_id` ASC) ,
  INDEX `fk_resoluciones_usuarios1_idx` (`creador_id` ASC) ,
  INDEX `fk_resoluciones_usuarios2_idx` (`modificador_id` ASC) ,
  CONSTRAINT `fk_resoluciones_contratos1`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_resoluciones_usuarios1`
    FOREIGN KEY (`creador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_resoluciones_usuarios2`
    FOREIGN KEY (`modificador_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`instituciones`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`instituciones` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`tipos_garantias`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`tipos_garantias` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) )
ENGINE = InnoDB;




-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`flujos_reales`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`flujos_reales` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `produccion` INT NOT NULL ,
  `costo` INT NULL ,
  `reajuste` INT NULL ,
  `retencion` INT NULL ,
  `descuento` INT NULL ,
  `mes` INT NOT NULL ,
  `agno` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  `comentarios` VARCHAR(200) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_flujos_contratos1_idx` (`contratos_id` ASC) ,
  CONSTRAINT `fk_flujos_contratos1`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`flujos_programados`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`flujos_programados` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `produccion` INT NOT NULL ,
  `costo` INT NULL ,
  `mes` INT NOT NULL ,
  `agno` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  `comentarios` VARCHAR(200) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_flujos_contratos1_idx` (`contratos_id` ASC) ,
  CONSTRAINT `fk_flujos_contratos10`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`authitem`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`authitem` (
  `name` VARCHAR(64) NOT NULL ,
  `type` INT NOT NULL ,
  `description` TEXT NULL ,
  `bizrule` TEXT NULL ,
  `data` TEXT NULL ,
  PRIMARY KEY (`name`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`authitemchild`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`authitemchild` (
  `parent` VARCHAR(64) NOT NULL ,
  `child` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`parent`, `child`) ,
  INDEX `fk_authitemchild_authitem2_idx` (`child` ASC) ,
  CONSTRAINT `fk_authitemchild_authitem1`
    FOREIGN KEY (`parent` )
    REFERENCES `contratos_ctipaume`.`authitem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_authitemchild_authitem2`
    FOREIGN KEY (`child` )
    REFERENCES `contratos_ctipaume`.`authitem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`authassignment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`authassignment` (
  `itemname` VARCHAR(64) NOT NULL ,
  `userid` VARCHAR(64) NOT NULL ,
  `bizrule` TEXT NULL ,
  `data` TEXT NULL ,
  PRIMARY KEY (`itemname`, `userid`) ,
  CONSTRAINT `fk_authassignment_authitem1`
    FOREIGN KEY (`itemname` )
    REFERENCES `contratos_ctipaume`.`authitem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contratos_ctipaume`.`usuarios_contratos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contratos_ctipaume`.`usuarios_contratos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `usuarios_id` INT NOT NULL ,
  `contratos_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_usuarios_has_contratos_contratos1_idx` (`contratos_id` ASC) ,
  INDEX `fk_usuarios_has_contratos_usuarios1_idx` (`usuarios_id` ASC) ,
  UNIQUE INDEX `usuarios_contratos_uq` (`usuarios_id` ASC, `contratos_id` ASC) ,
  CONSTRAINT `fk_usuarios_has_contratos_usuarios1`
    FOREIGN KEY (`usuarios_id` )
    REFERENCES `contratos_ctipaume`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_contratos_contratos1`
    FOREIGN KEY (`contratos_id` )
    REFERENCES `contratos_ctipaume`.`contratos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `contratos_ctipaume`.`contratos_de_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contratos_ctipaume`.`contratos_de_usuario` (`contratos_id` INT, `id` INT, `nombre` INT, `fecha_inicio` INT, `observacion` INT, `estados_contratos_id` INT, `estados_contratos_nombre` INT, `usuarios_id` INT);

-- -----------------------------------------------------
-- View `contratos_ctipaume`.`contratos_de_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contratos_ctipaume`.`contratos_de_usuario`;
USE `contratos_ctipaume`;
CREATE  OR REPLACE VIEW `contratos_de_usuario` AS
select 	c.id as contratos_id,uc.id,c.nombre,c.fecha_inicio,c.observacion,e.id as estados_contratos_id,e.nombre as estados_contratos_nombre,uc.usuarios_id
from 	contratos as c,usuarios_contratos as uc,estados_contratos as e
where	c.id = uc.contratos_id and
		e.id = c.estados_contratos_id;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('administrador', 2, '', NULL, 'N;'),
('configureRoles', 0, 'configure app roles', NULL, 'N;'),
('operador', 2, '', NULL, 'N;');

INSERT INTO `authassignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('administrador', '1', NULL, 'N;');

INSERT INTO `authitemchild` (`parent`, `child`) VALUES
('administrador', 'configureRoles');

INSERT INTO `usuarios` (`id`, `user`, `nombre`, `email`, `clave`, `rol`) VALUES
(1, 'admin', 'Sr.Administrador', 'admin@mvs.cl', '40dc6c3b5c6595384395164908da32c18ae9dfc9', 'administrador');

INSERT INTO `estados_contratos` (`id`, `nombre`) VALUES
(3, 'CERRADO'),
(2, 'CON RESOLUCIÓN'),
(1, 'SIN RESOLUCIÓN');
