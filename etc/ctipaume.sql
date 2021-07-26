SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `ctipaume`;
CREATE SCHEMA IF NOT EXISTS `ctipaume` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ;
USE `ctipaume` ;

-- -----------------------------------------------------
-- Table `ctipaume`.`camionPropio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`camionPropio` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL ,
  `codigo` VARCHAR(45) NOT NULL ,
  `capacidad` DECIMAL(12,2) NOT NULL ,
  `pesoOVolumen` CHAR(1) NOT NULL ,
  `consumoPromedio` DECIMAL(12,2) NOT NULL ,
  `coeficienteDeTrato` DECIMAL(12,2) NOT NULL ,
  `produccionMinima` DECIMAL(12,2) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`chofer`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`chofer` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(200) NOT NULL ,
  `rut` VARCHAR(15) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`rCamionPropio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`rCamionPropio` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fecha` DATE NOT NULL ,
  `reporte` INT NOT NULL ,
  `observaciones` TEXT NULL ,
  `camionPropio_id` INT NOT NULL ,
  `chofer_id` INT NOT NULL ,
  `kmInicial` DECIMAL(12,2) NULL ,
  `kmFinal` DECIMAL(12,2) NULL ,
  `kmGps` DECIMAL(12,2) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_rCamionPropio_camionPropio1` (`camionPropio_id` ASC) ,
  INDEX `fk_rCamionPropio_chofer1` (`chofer_id` ASC) ,
  UNIQUE INDEX `reporte_UNIQUE` (`reporte` ASC) ,
  CONSTRAINT `fk_rCamionPropio_camionPropio1`
    FOREIGN KEY (`camionPropio_id` )
    REFERENCES `ctipaume`.`camionPropio` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rCamionPropio_chofer1`
    FOREIGN KEY (`chofer_id` )
    REFERENCES `ctipaume`.`chofer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`faena`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`faena` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(200) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`origen`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`origen` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`destino`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`destino` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`origendestino_faena`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`origenDestino_faena` (
  `origen_id` INT NOT NULL ,
  `destino_id` INT NOT NULL ,
  `faena_id` INT NOT NULL ,
  `pu` DECIMAL(12,2) NOT NULL ,
  `id` INT NOT NULL AUTO_INCREMENT ,
  `kmRecorridos` DECIMAL(12,2) NOT NULL DEFAULT 0 ,
  INDEX `fk_origendestino_faena_origen1` (`origen_id` ASC) ,
  INDEX `fk_origendestino_faena_destino1` (`destino_id` ASC) ,
  INDEX `fk_origendestino_faena_faena1` (`faena_id` ASC) ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `unico` (`faena_id` ASC, `destino_id` ASC, `origen_id` ASC) ,
  CONSTRAINT `fk_origendestino_faena_origen1`
    FOREIGN KEY (`origen_id` )
    REFERENCES `ctipaume`.`origen` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_origendestino_faena_destino1`
    FOREIGN KEY (`destino_id` )
    REFERENCES `ctipaume`.`destino` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_origendestino_faena_faena1`
    FOREIGN KEY (`faena_id` )
    REFERENCES `ctipaume`.`faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`viajeCamionPropio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`viajeCamionPropio` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nVueltas` INT NOT NULL ,
  `totalTransportado` DECIMAL(12,2) NOT NULL ,
  `total` DECIMAL(12,2) NOT NULL ,
  `kmRecorridos` DECIMAL(12,2) NOT NULL DEFAULT 0 ,
  `rCamionPropio_id` INT NOT NULL ,
  `faena_id` INT NOT NULL ,
  `origendestino_faena_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_viajesCamionPropio_rCamionPropio1` (`rCamionPropio_id` ASC) ,
  INDEX `fk_viajesCamionPropio_faena1` (`faena_id` ASC) ,
  INDEX `fk_viajeCamionPropio_origendestino_faena1` (`origendestino_faena_id` ASC) ,
  CONSTRAINT `fk_viajesCamionPropio_rCamionPropio1`
    FOREIGN KEY (`rCamionPropio_id` )
    REFERENCES `ctipaume`.`rCamionPropio` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_viajesCamionPropio_faena1`
    FOREIGN KEY (`faena_id` )
    REFERENCES `ctipaume`.`faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_viajeCamionPropio_origendestino_faena1`
    FOREIGN KEY (`origendestino_faena_id` )
    REFERENCES `ctipaume`.`origendestino_faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`tipoCombustible`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`tipoCombustible` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`supervisorCombustible`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`supervisorCombustible` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `rut` VARCHAR(15) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`cargaCombCamionPropio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`cargaCombCamionPropio` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `petroleoLts` DECIMAL(12,2) NOT NULL ,
  `kmCarguio` DECIMAL(12,2) NOT NULL ,
  `guia` VARCHAR(45) NULL ,
  `factura` VARCHAR(45) NULL ,
  `precioUnitario` INT NOT NULL ,
  `valorTotal` DECIMAL(12,2) NOT NULL ,
  `faena_id` INT NOT NULL ,
  `tipoCombustible_id` INT NOT NULL ,
  `supervisorCombustible_id` INT NOT NULL ,
  `rCamionPropio_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cargaCombPropio_faena1` (`faena_id` ASC) ,
  INDEX `fk_cargaCombPropio_tipoCombustible1` (`tipoCombustible_id` ASC) ,
  INDEX `fk_cargaCombPropio_supervisorCombustible1` (`supervisorCombustible_id` ASC) ,
  INDEX `fk_cargaCombCamionPropio_rCamionPropio1` (`rCamionPropio_id` ASC) ,
  CONSTRAINT `fk_cargaCombPropio_faena1`
    FOREIGN KEY (`faena_id` )
    REFERENCES `ctipaume`.`faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombPropio_tipoCombustible1`
    FOREIGN KEY (`tipoCombustible_id` )
    REFERENCES `ctipaume`.`tipoCombustible` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombPropio_supervisorCombustible1`
    FOREIGN KEY (`supervisorCombustible_id` )
    REFERENCES `ctipaume`.`supervisorCombustible` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombCamionPropio_rCamionPropio1`
    FOREIGN KEY (`rCamionPropio_id` )
    REFERENCES `ctipaume`.`rCamionPropio` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`compraRepuestoCamionPropio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`compraRepuestoCamionPropio` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `repuesto` VARCHAR(200) NOT NULL ,
  `montoNeto` INT NOT NULL ,
  `guia` VARCHAR(45) NULL ,
  `factura` VARCHAR(45) NULL ,
  `cantidad` INT NOT NULL DEFAULT 1 ,
  `unidad` CHAR(1) NOT NULL DEFAULT 'U' ,
  `rCamionPropio_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_compraRepuestoCamionPropio_rCamionPropio1` (`rCamionPropio_id` ASC) ,
  CONSTRAINT `fk_compraRepuestoCamionPropio_rCamionPropio1`
    FOREIGN KEY (`rCamionPropio_id` )
    REFERENCES `ctipaume`.`rCamionPropio` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`camionArrendado`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`camionArrendado` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL ,
  `capacidad` DECIMAL(12,2) NOT NULL ,
  `pesoOVolumen` CHAR(1) NOT NULL ,
  `consumoPromedio` DECIMAL(12,2) NOT NULL ,
  `coeficienteDeTrato` DECIMAL(12,2) NOT NULL ,
  `produccionMinima` DECIMAL(12,2) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`rCamionArrendado`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`rCamionArrendado` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fecha` DATE NOT NULL ,
  `reporte` INT NOT NULL ,
  `observaciones` TEXT NULL ,
  `ordenCompra` VARCHAR(45) NOT NULL ,
  `camionArrendado_id` INT NOT NULL ,
  `chofer_id` INT NOT NULL ,
  `kmInicial` DECIMAL(12,2) NULL ,
  `kmFinal` DECIMAL(12,2) NULL ,
  `kmGps` DECIMAL(12,2) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_rCamionArrendado_camionArrendado1` (`camionArrendado_id` ASC) ,
  INDEX `fk_rCamionArrendado_chofer1` (`chofer_id` ASC) ,
  UNIQUE INDEX `reporte_UNIQUE` (`reporte` ASC) ,
  CONSTRAINT `fk_rCamionArrendado_camionArrendado1`
    FOREIGN KEY (`camionArrendado_id` )
    REFERENCES `ctipaume`.`camionArrendado` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rCamionArrendado_chofer1`
    FOREIGN KEY (`chofer_id` )
    REFERENCES `ctipaume`.`chofer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`viajeCamionArrendado`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`viajeCamionArrendado` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nVueltas` INT NOT NULL ,
  `totalTransportado` DECIMAL(12,2) NOT NULL ,
  `total` DECIMAL(12,2) NOT NULL ,
  `kmRecorridos` DECIMAL(12,2) NOT NULL DEFAULT 0 ,
  `faena_id` INT NOT NULL ,
  `rCamionArrendado_id` INT NOT NULL ,
  `origendestino_faena_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_viajesCamionPropio_faena1` (`faena_id` ASC) ,
  INDEX `fk_viajeArrendado_rCamionArrendado1` (`rCamionArrendado_id` ASC) ,
  INDEX `fk_viajeCamionArrendado_origendestino_faena1` (`origendestino_faena_id` ASC) ,
  CONSTRAINT `fk_viajesCamionPropio_faena10`
    FOREIGN KEY (`faena_id` )
    REFERENCES `ctipaume`.`faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_viajeArrendado_rCamionArrendado1`
    FOREIGN KEY (`rCamionArrendado_id` )
    REFERENCES `ctipaume`.`rCamionArrendado` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_viajeCamionArrendado_origendestino_faena1`
    FOREIGN KEY (`origendestino_faena_id` )
    REFERENCES `ctipaume`.`origendestino_faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`cargaCombCamionArrendado`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`cargaCombCamionArrendado` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `petroleoLts` DECIMAL(12,2) NOT NULL ,
  `kmCarguio` DECIMAL(12,2) NOT NULL ,
  `guia` VARCHAR(45) NULL ,
  `factura` VARCHAR(45) NULL ,
  `precioUnitario` INT NOT NULL ,
  `valorTotal` DECIMAL(12,2) NOT NULL ,
  `faena_id` INT NOT NULL ,
  `tipoCombustible_id` INT NOT NULL ,
  `supervisorCombustible_id` INT NOT NULL ,
  `rCamionArrendado_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cargaCombPropio_faena1` (`faena_id` ASC) ,
  INDEX `fk_cargaCombPropio_tipoCombustible1` (`tipoCombustible_id` ASC) ,
  INDEX `fk_cargaCombPropio_supervisorCombustible1` (`supervisorCombustible_id` ASC) ,
  INDEX `fk_cargaCombCamionArrendado_rCamionArrendado1` (`rCamionArrendado_id` ASC) ,
  CONSTRAINT `fk_cargaCombPropio_faena10`
    FOREIGN KEY (`faena_id` )
    REFERENCES `ctipaume`.`faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombPropio_tipoCombustible10`
    FOREIGN KEY (`tipoCombustible_id` )
    REFERENCES `ctipaume`.`tipoCombustible` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombPropio_supervisorCombustible10`
    FOREIGN KEY (`supervisorCombustible_id` )
    REFERENCES `ctipaume`.`supervisorCombustible` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombCamionArrendado_rCamionArrendado1`
    FOREIGN KEY (`rCamionArrendado_id` )
    REFERENCES `ctipaume`.`rCamionArrendado` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`compraRepuestoCamionArrendado`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`compraRepuestoCamionArrendado` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `repuesto` VARCHAR(200) NOT NULL ,
  `montoNeto` INT NOT NULL ,
  `guia` VARCHAR(45) NULL ,
  `factura` VARCHAR(45) NULL ,
  `cantidad` INT NOT NULL DEFAULT 1 ,
  `unidad` CHAR(1) NOT NULL DEFAULT 'U' ,
  `rCamionArrendado_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_compraRepuestoCamionArrendado_rCamionArrendado1` (`rCamionArrendado_id` ASC) ,
  CONSTRAINT `fk_compraRepuestoCamionArrendado_rCamionArrendado1`
    FOREIGN KEY (`rCamionArrendado_id` )
    REFERENCES `ctipaume`.`rCamionArrendado` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`equipoPropio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`equipoPropio` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL ,
  `codigo` VARCHAR(45) NOT NULL ,
  `precioUnitario` INT NOT NULL ,
  `horasMin` DECIMAL(12,2) NULL ,
  `consumoEsperado` DECIMAL(12,2) NOT NULL ,
  `valorHora` INT NOT NULL ,
  `coeficienteDeTrato` DECIMAL(12,2) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`operador`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`operador` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(200) NOT NULL ,
  `rut` VARCHAR(15) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`rEquipoPropio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`rEquipoPropio` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fecha` DATE NOT NULL ,
  `reporte` INT NOT NULL ,
  `observaciones` TEXT NULL ,
  `equipoPropio_id` INT NOT NULL ,
  `hInicial` DECIMAL(12,2) NULL ,
  `hFinal` DECIMAL(12,2) NULL ,
  `horas` DECIMAL(12,2) NOT NULL ,
  `faena_id` INT NOT NULL ,
  `operador_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_rEquipoPropio_equipoPropio1` (`equipoPropio_id` ASC) ,
  INDEX `fk_rEquipoPropio_faena1` (`faena_id` ASC) ,
  INDEX `fk_rEquipoPropio_operador1` (`operador_id` ASC) ,
  UNIQUE INDEX `reporte_UNIQUE` (`reporte` ASC) ,
  CONSTRAINT `fk_rEquipoPropio_equipoPropio1`
    FOREIGN KEY (`equipoPropio_id` )
    REFERENCES `ctipaume`.`equipoPropio` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rEquipoPropio_faena1`
    FOREIGN KEY (`faena_id` )
    REFERENCES `ctipaume`.`faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rEquipoPropio_operador1`
    FOREIGN KEY (`operador_id` )
    REFERENCES `ctipaume`.`operador` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`compraRepuestoEquipoPropio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`compraRepuestoEquipoPropio` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `repuesto` VARCHAR(200) NOT NULL ,
  `montoNeto` INT NOT NULL ,
  `guia` VARCHAR(45) NULL ,
  `factura` VARCHAR(45) NULL ,
  `cantidad` INT NOT NULL DEFAULT 1 ,
  `unidad` CHAR(1) NOT NULL DEFAULT 'U' ,
  `rEquipoPropio_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_compraRepuestoEquipoPropio_rEquipoPropio1` (`rEquipoPropio_id` ASC) ,
  CONSTRAINT `fk_compraRepuestoEquipoPropio_rEquipoPropio1`
    FOREIGN KEY (`rEquipoPropio_id` )
    REFERENCES `ctipaume`.`rEquipoPropio` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`cargaCombEquipoPropio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`cargaCombEquipoPropio` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `petroleoLts` DECIMAL(12,2) NOT NULL ,
  `hCarguio` DECIMAL(12,2) NOT NULL ,
  `guia` VARCHAR(45) NULL ,
  `factura` VARCHAR(45) NULL ,
  `precioUnitario` INT NOT NULL ,
  `valorTotal` DECIMAL(12,2) NOT NULL ,
  `faena_id` INT NOT NULL ,
  `tipoCombustible_id` INT NOT NULL ,
  `supervisorCombustible_id` INT NOT NULL ,
  `rEquipoPropio_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cargaCombPropio_faena1` (`faena_id` ASC) ,
  INDEX `fk_cargaCombPropio_tipoCombustible1` (`tipoCombustible_id` ASC) ,
  INDEX `fk_cargaCombPropio_supervisorCombustible1` (`supervisorCombustible_id` ASC) ,
  INDEX `fk_cargaCombEquipoPropio_rEquipoPropio1` (`rEquipoPropio_id` ASC) ,
  CONSTRAINT `fk_cargaCombPropio_faena11`
    FOREIGN KEY (`faena_id` )
    REFERENCES `ctipaume`.`faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombPropio_tipoCombustible11`
    FOREIGN KEY (`tipoCombustible_id` )
    REFERENCES `ctipaume`.`tipoCombustible` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombPropio_supervisorCombustible11`
    FOREIGN KEY (`supervisorCombustible_id` )
    REFERENCES `ctipaume`.`supervisorCombustible` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombEquipoPropio_rEquipoPropio1`
    FOREIGN KEY (`rEquipoPropio_id` )
    REFERENCES `ctipaume`.`rEquipoPropio` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`propietario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`propietario` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(200) NOT NULL ,
  `rut` VARCHAR(15) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`equipoArrendado`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`equipoArrendado` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL ,
  `precioUnitario` INT NOT NULL ,
  `horasMin` DECIMAL(12,2) NULL ,
  `valorHora` INT NOT NULL ,
  `consumoEsperado` DECIMAL(12,2) NOT NULL ,
  `propietario_id` INT NOT NULL ,
  `coeficienteDeTrato` DECIMAL(12,2) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_equipoArrendado_propietario1` (`propietario_id` ASC) ,
  CONSTRAINT `fk_equipoArrendado_propietario1`
    FOREIGN KEY (`propietario_id` )
    REFERENCES `ctipaume`.`propietario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`rEquipoArrendado`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`rEquipoArrendado` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fecha` DATE NOT NULL ,
  `reporte` INT NOT NULL ,
  `ordenCompra` VARCHAR(45) NOT NULL ,
  `observaciones` TEXT NULL ,
  `hInicial` DECIMAL(12,2) NULL ,
  `hFinal` DECIMAL(12,2) NULL ,
  `horas` DECIMAL(12,2) NOT NULL ,
  `faena_id` INT NOT NULL ,
  `equipoArrendado_id` INT NOT NULL ,
  `operador_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_rEquipoPropio_faena1` (`faena_id` ASC) ,
  INDEX `fk_rEquipoArrendado_equipoArrendado1` (`equipoArrendado_id` ASC) ,
  INDEX `fk_rEquipoArrendado_operador1` (`operador_id` ASC) ,
  UNIQUE INDEX `reporte_UNIQUE` (`reporte` ASC) ,
  CONSTRAINT `fk_rEquipoPropio_faena10`
    FOREIGN KEY (`faena_id` )
    REFERENCES `ctipaume`.`faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rEquipoArrendado_equipoArrendado1`
    FOREIGN KEY (`equipoArrendado_id` )
    REFERENCES `ctipaume`.`equipoArrendado` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rEquipoArrendado_operador1`
    FOREIGN KEY (`operador_id` )
    REFERENCES `ctipaume`.`operador` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`compraRepuestoEquipoArrendado`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`compraRepuestoEquipoArrendado` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `repuesto` VARCHAR(200) NOT NULL ,
  `montoNeto` INT NOT NULL ,
  `guia` VARCHAR(45) NULL ,
  `factura` VARCHAR(45) NULL ,
  `cantidad` INT NOT NULL DEFAULT 1 ,
  `unidad` CHAR(1) NOT NULL DEFAULT 'U' ,
  `rEquipoArrendado_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_compraRepuestoEquipoArrendado_rEquipoArrendado1` (`rEquipoArrendado_id` ASC) ,
  CONSTRAINT `fk_compraRepuestoEquipoArrendado_rEquipoArrendado1`
    FOREIGN KEY (`rEquipoArrendado_id` )
    REFERENCES `ctipaume`.`rEquipoArrendado` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`cargaCombEquipoArrendado`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`cargaCombEquipoArrendado` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `petroleoLts` DECIMAL(12,2) NOT NULL ,
  `hCarguio` DECIMAL(12,2) NOT NULL ,
  `guia` VARCHAR(45) NULL ,
  `factura` VARCHAR(45) NULL ,
  `precioUnitario` INT NOT NULL ,
  `valorTotal` DECIMAL(12,2) NOT NULL ,
  `faena_id` INT NOT NULL ,
  `tipoCombustible_id` INT NOT NULL ,
  `supervisorCombustible_id` INT NOT NULL ,
  `rEquipoArrendado_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cargaCombPropio_faena1` (`faena_id` ASC) ,
  INDEX `fk_cargaCombPropio_tipoCombustible1` (`tipoCombustible_id` ASC) ,
  INDEX `fk_cargaCombPropio_supervisorCombustible1` (`supervisorCombustible_id` ASC) ,
  INDEX `fk_cargaCombEquipoArrendado_rEquipoArrendado1` (`rEquipoArrendado_id` ASC) ,
  CONSTRAINT `fk_cargaCombPropio_faena110`
    FOREIGN KEY (`faena_id` )
    REFERENCES `ctipaume`.`faena` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombPropio_tipoCombustible110`
    FOREIGN KEY (`tipoCombustible_id` )
    REFERENCES `ctipaume`.`tipoCombustible` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombPropio_supervisorCombustible110`
    FOREIGN KEY (`supervisorCombustible_id` )
    REFERENCES `ctipaume`.`supervisorCombustible` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargaCombEquipoArrendado_rEquipoArrendado1`
    FOREIGN KEY (`rEquipoArrendado_id` )
    REFERENCES `ctipaume`.`rEquipoArrendado` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeProduccionCamiones`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeProduccionCamiones` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `camion` VARCHAR(150) NULL ,
  `chofer` VARCHAR(220) NULL ,
  `centroGestion` VARCHAR(50) NULL ,
  `totalTransportado` DECIMAL(12,2) NULL ,
  `produccion` DECIMAL(12,2) NULL ,
  `produccionReal` DECIMAL(12,2) NULL ,
  `diferencia` DECIMAL(12,2) NULL ,
  `totalCobro` DECIMAL(12,2) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeProduccionMaquinaria`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeProduccionMaquinaria` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `maquina` VARCHAR(150) NULL ,
  `operador` VARCHAR(220) NULL ,
  `centroGestion` VARCHAR(50) NULL ,
  `pu` DECIMAL(12,2) NULL ,
  `horas` DECIMAL(12,2) NULL ,
  `produccion` DECIMAL(12,2) NULL ,
  `horasMin` DECIMAL(12,2) NULL ,
  `produccionMin` DECIMAL(12,2) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeGastoCombustible`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeGastoCombustible` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `maquina` VARCHAR(150) NULL ,
  `operador` VARCHAR(220) NULL ,
  `centroGestion` VARCHAR(50) NULL ,
  `consumoLts` DECIMAL(12,2) NULL ,
  `consumoPesos` DECIMAL(12,2) NULL ,
  `fInicio` DATE NULL ,
  `fFin` DATE NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeGastoRepuesto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeGastoRepuesto` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `maquina` VARCHAR(150) NULL ,
  `operador` VARCHAR(220) NULL ,
  `consumoPesos` DECIMAL(12,2) NULL ,
  `fInicio` DATE NULL ,
  `fFin` DATE NULL ,
  `propiosOArrendados` VARCHAR(45) NULL ,
  `maquina_id` INT NULL ,
  `operador_id` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeConsumoMaquinaria`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeConsumoMaquinaria` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `maquina` VARCHAR(150) NULL ,
  `operador` VARCHAR(220) NULL ,
  `consumo` DECIMAL(12,2) NULL ,
  `consumoEsperado` DECIMAL(12,2) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeConsumoCamion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeConsumoCamion` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `maquina` VARCHAR(150) NULL ,
  `operador` VARCHAR(220) NULL ,
  `consumoReal` DECIMAL(12,2) NULL ,
  `consumoGps` DECIMAL(12,2) NULL ,
  `consumoSugerido` DECIMAL(12,2) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeChofer`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeChofer` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `chofer` VARCHAR(220) NULL ,
  `camion` VARCHAR(150) NULL ,
  `produccionDia` DECIMAL(12,2) NULL ,
  `produccionMinima` DECIMAL(12,2) NULL ,
  `coeficienteCombustible` DECIMAL(12,2) NULL ,
  `gastoCombustible` DECIMAL(12,2) NULL ,
  `diferencia` DECIMAL(12,2) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeOperario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeOperario` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `maquina` VARCHAR(150) NULL ,
  `consumoPromedio` DECIMAL(12,2) NULL ,
  `horas` DECIMAL(12,2) NULL ,
  `valorHora` INT NULL ,
  `total` DECIMAL(12,2) NULL ,
  `operario` VARCHAR(220) NULL ,
  `coeficiente` DECIMAL(12,2) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeResultados`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeResultados` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `maquina` VARCHAR(150) NULL ,
  `operador` VARCHAR(220) NULL ,
  `centroGestion` VARCHAR(50) NULL ,
  `produccion` DECIMAL(12,2) NULL ,
  `combustible` DECIMAL(12,2) NULL ,
  `repuesto` DECIMAL(12,2) NULL ,
  `resultado` DECIMAL(12,2) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`usuario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`usuario` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `nombre` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `clave` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `rol` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `user` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `user` (`user` ASC) ,
  UNIQUE INDEX `email` (`email` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;


-- -----------------------------------------------------
-- Table `ctipaume`.`Authitem`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`AuthItem` (
  `name` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `type` INT(11) NOT NULL ,
  `description` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `bizrule` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `data` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  PRIMARY KEY (`name`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;


-- -----------------------------------------------------
-- Table `ctipaume`.`authitemchild`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`AuthItemChild` (
  `parent` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `child` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  PRIMARY KEY (`parent`, `child`) ,
  INDEX `child` (`child` ASC) ,
  CONSTRAINT `authitemchild_ibfk_1`
    FOREIGN KEY (`parent` )
    REFERENCES `ctipaume`.`authitem` (`name` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `authitemchild_ibfk_2`
    FOREIGN KEY (`child` )
    REFERENCES `ctipaume`.`authitem` (`name` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;


-- -----------------------------------------------------
-- Table `ctipaume`.`authassignment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`AuthAssignment` (
  `itemname` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `userid` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `bizrule` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `data` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  PRIMARY KEY (`itemname`, `userid`) ,
  CONSTRAINT `authassignment_ibfk_1`
    FOREIGN KEY (`itemname` )
    REFERENCES `ctipaume`.`authitem` (`name` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeDetalleGastoRepuesto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeDetalleGastoRepuesto` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fecha` DATE NULL ,
  `reporte` INT NULL ,
  `operario` VARCHAR(220) NULL ,
  `maquina` VARCHAR(220) NULL ,
  `repuesto` VARCHAR(200) NULL ,
  `montoNeto` INT NULL ,
  `guia` VARCHAR(45) NULL ,
  `factura` VARCHAR(45) NULL ,
  `cantidad` VARCHAR(20) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ctipaume`.`informeDetalleGastoCombustible`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ctipaume`.`informeDetalleGastoCombustible` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fecha` DATE NULL ,
  `reporte` INT NULL ,
  `operario` VARCHAR(220) NULL ,
  `maquina` VARCHAR(220) NULL ,
  `petroleoLts` DECIMAL(12,2) NULL ,
  `kmCarguio` DECIMAL(12,2) NULL ,
  `guia` VARCHAR(45) NULL ,
  `factura` VARCHAR(45) NULL ,
  `precioUnitario` INT NULL ,
  `valorTotal` DECIMAL(12,2) NULL ,
  `faena` VARCHAR(45) NULL ,
  `tipoCombustible` VARCHAR(45) NULL ,
  `supervisorCombustible` VARCHAR(220) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
