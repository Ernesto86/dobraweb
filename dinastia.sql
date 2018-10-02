# SQL Manager 2010 for MySQL 4.5.0.9
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : dinastia


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;

SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE `dinastia`
    CHARACTER SET 'utf8'
    COLLATE 'utf8_general_ci';

USE `dinastia`;

#
# Structure for the `tbl_pais` table : 
#

CREATE TABLE `tbl_pais` (
  `pai_id` int(11) NOT NULL,
  `pai_desc` varchar(30) DEFAULT '',
  `pai_estado` char(1) DEFAULT 'A',
  `pai_user_crea` varchar(20) DEFAULT NULL,
  `pai_fech_crea` datetime DEFAULT NULL,
  `pai_user_mod` varchar(20) DEFAULT NULL,
  `pai_fech_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`pai_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `tbl_provincia` table : 
#

CREATE TABLE `tbl_provincia` (
  `prv_id` int(11) NOT NULL,
  `pai_id` int(11) NOT NULL,
  `prv_desc` varchar(30) DEFAULT '',
  `prv_estado` char(1) DEFAULT 'A',
  `prv_user_crea` varchar(20) DEFAULT NULL,
  `prv_fech_crea` datetime DEFAULT NULL,
  `prv_user_mod` varchar(20) DEFAULT NULL,
  `prv_fech_mod` datetime NOT NULL,
  PRIMARY KEY (`prv_id`,`pai_id`),
  KEY `Ref419` (`pai_id`),
  CONSTRAINT `Reftbl_pais19` FOREIGN KEY (`pai_id`) REFERENCES `tbl_pais` (`pai_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `tbl_ciudad` table : 
#

CREATE TABLE `tbl_ciudad` (
  `ciu_id` int(11) NOT NULL,
  `prv_id` int(11) NOT NULL,
  `pai_id` int(11) NOT NULL,
  `ciu_desc` varchar(30) DEFAULT '',
  `ciu_estado` char(1) DEFAULT 'A',
  `ciu_user_crea` varchar(20) DEFAULT NULL,
  `ciu_fech_crea` datetime DEFAULT NULL,
  `ciu_user_mod` varchar(20) DEFAULT NULL,
  `ciu_fech_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`ciu_id`,`prv_id`,`pai_id`),
  KEY `Ref620` (`prv_id`,`pai_id`),
  CONSTRAINT `Reftbl_provincia20` FOREIGN KEY (`prv_id`, `pai_id`) REFERENCES `tbl_provincia` (`prv_id`, `pai_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `tbl_modulo` table : 
#

CREATE TABLE `tbl_modulo` (
  `mod_id` int(11) NOT NULL,
  `mod_desc` varchar(35) NOT NULL DEFAULT '',
  `mod_orden` int(11) NOT NULL,
  `mod_estado` char(1) DEFAULT 'A',
  `mod_user_crea` varchar(20) DEFAULT NULL,
  `mod_fech_crea` datetime DEFAULT NULL,
  `mod_user_mod` varchar(20) DEFAULT NULL,
  `mod_fech_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `tbl_menu` table : 
#

CREATE TABLE `tbl_menu` (
  `men_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `men_desc` varchar(35) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `men_archivo` varchar(35) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `men_carga` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT 'pw_mantenimiento.php',
  `men_orden` int(11) DEFAULT NULL,
  `men_estado` char(1) CHARACTER SET utf8 DEFAULT 'A',
  `men_user_crea` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `men_fech_crea` datetime DEFAULT NULL,
  `men_user_mod` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `men_fech_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`men_id`),
  KEY `Ref312` (`mod_id`),
  CONSTRAINT `Reftbl_modulo12` FOREIGN KEY (`mod_id`) REFERENCES `tbl_modulo` (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Structure for the `tbl_sucursal` table : 
#

CREATE TABLE `tbl_sucursal` (
  `suc_id` int(11) NOT NULL,
  `suc_codigo` char(10) NOT NULL,
  `suc_nombre` varchar(40) DEFAULT '',
  `suc_estado` char(1) DEFAULT NULL,
  `suc_user_crea` varchar(20) DEFAULT NULL,
  `suc_fech_crea` datetime DEFAULT NULL,
  `suc_user_mod` varchar(20) DEFAULT NULL,
  `suc_fech_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`suc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `tbl_personal` table : 
#

CREATE TABLE `tbl_personal` (
  `per_id` int(11) NOT NULL,
  `per_codigo` char(10) NOT NULL DEFAULT '',
  `per_tipo_doc` varchar(20) DEFAULT NULL,
  `per_num_doc` char(15) NOT NULL,
  `per_tratamiento` char(5) DEFAULT NULL,
  `per_apellido` varchar(30) NOT NULL,
  `per_nombre` varchar(30) NOT NULL,
  `per_dir_img` text,
  `per_sexo` char(1) NOT NULL DEFAULT 'M',
  `per_est_civil` char(1) DEFAULT 'S',
  `per_direccion` varchar(50) DEFAULT NULL,
  `ciu_id` int(11) NOT NULL,
  `prv_id` int(11) NOT NULL,
  `pai_id` int(11) NOT NULL,
  `per_telefono` char(12) DEFAULT NULL,
  `per_movil` char(10) DEFAULT NULL,
  `per_email` varchar(50) DEFAULT NULL,
  `per_fech_nace` date DEFAULT NULL,
  `per_observ` varchar(100) DEFAULT NULL,
  `per_estado` char(1) DEFAULT 'A',
  `suc_id` int(11) NOT NULL,
  `per_user_crea` varchar(20) DEFAULT NULL,
  `per_fech_crea` datetime DEFAULT NULL,
  `per_user_mod` varchar(20) DEFAULT NULL,
  `per_fech_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`per_id`),
  KEY `Ref121` (`ciu_id`,`prv_id`,`pai_id`),
  KEY `Ref1122` (`suc_id`),
  CONSTRAINT `Reftbl_ciudad21` FOREIGN KEY (`ciu_id`, `prv_id`, `pai_id`) REFERENCES `tbl_ciudad` (`ciu_id`, `prv_id`, `pai_id`),
  CONSTRAINT `Reftbl_sucursal22` FOREIGN KEY (`suc_id`) REFERENCES `tbl_sucursal` (`suc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `tbl_tipo_usuario` table : 
#

CREATE TABLE `tbl_tipo_usuario` (
  `tip_user_id` int(11) NOT NULL,
  `tip_user_desc` varchar(35) NOT NULL DEFAULT '',
  `tip_user_estado` char(1) DEFAULT 'A',
  `tip_user_crea` varchar(20) DEFAULT NULL,
  `tip_fech_crea` datetime DEFAULT NULL,
  `tip_user_mod` varchar(20) DEFAULT NULL,
  `tip_fech_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`tip_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `tbl_transaccion` table : 
#

CREATE TABLE `tbl_transaccion` (
  `tran_id` int(11) NOT NULL,
  `tip_user_id` int(11) NOT NULL,
  `men_id` int(11) NOT NULL,
  `tran_ingresar` int(1) NOT NULL DEFAULT '0',
  `tran_agregar` int(1) NOT NULL DEFAULT '0',
  `tran_modificar` int(1) NOT NULL DEFAULT '0',
  `tran_eliminar` int(1) NOT NULL DEFAULT '0',
  `tran_estado` char(1) DEFAULT 'A',
  `tran_user_crea` varchar(20) DEFAULT NULL,
  `tran_fech_crea` datetime DEFAULT NULL,
  `tran_user_mod` varchar(20) DEFAULT NULL,
  `tran_fech_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`tran_id`,`tip_user_id`),
  KEY `Ref813` (`tip_user_id`),
  KEY `Ref214` (`men_id`),
  CONSTRAINT `Reftbl_menu14` FOREIGN KEY (`men_id`) REFERENCES `tbl_menu` (`men_id`),
  CONSTRAINT `Reftbl_tipo_usuario13` FOREIGN KEY (`tip_user_id`) REFERENCES `tbl_tipo_usuario` (`tip_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `tbl_usuario` table : 
#

CREATE TABLE `tbl_usuario` (
  `usu_id` int(11) NOT NULL,
  `tip_user_id` int(11) NOT NULL,
  `per_id` int(11) NOT NULL,
  `usu_cuenta` varchar(30) NOT NULL DEFAULT '',
  `usu_clave` varchar(100) NOT NULL DEFAULT '',
  `grp_id` char(10) DEFAULT '',
  `bod_id` char(10) DEFAULT '',
  `usu_estado` char(1) DEFAULT 'A',
  `usu_crea` varchar(20) DEFAULT NULL,
  `usu_fech_crea` datetime DEFAULT NULL,
  `usu_mod` varchar(20) DEFAULT NULL,
  `usu_fech_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`usu_id`),
  UNIQUE KEY `usu_cuenta` (`usu_cuenta`),
  UNIQUE KEY `usu_cuenta_2` (`usu_cuenta`),
  KEY `Ref815` (`tip_user_id`),
  KEY `Ref516` (`per_id`),
  CONSTRAINT `Reftbl_personal16` FOREIGN KEY (`per_id`) REFERENCES `tbl_personal` (`per_id`),
  CONSTRAINT `Reftbl_tipo_usuario15` FOREIGN KEY (`tip_user_id`) REFERENCES `tbl_tipo_usuario` (`tip_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Definition for the `MAN_PRC_CIUDAD` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `MAN_PRC_CIUDAD`(
        IN OPC CHAR(2),
        IN ID INTEGER(11),
        IN IDPROVINCIA INTEGER(11),
        IN IDPAIS INTEGER(11),
        IN DESCRIP VARCHAR(30),
        IN ESTADO CHAR(1),
        IN USU VARCHAR(20)
    )
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN

  CASE OPC 
 
    WHEN 'I' THEN
      
      SET ID =(SELECT IFNULL(MAX(ciu_id)+1,1) FROM tbl_ciudad LIMIT 1);
      
      INSERT INTO tbl_ciudad
      (ciu_id,
       prv_id,
       pai_id,
       ciu_desc,
       ciu_estado,
       ciu_user_crea,
       ciu_fech_crea,
       ciu_user_mod,
       ciu_fech_mod
       )VALUE(ID,
              IDPROVINCIA,
              IDPAIS,
              DESCRIP,
              ESTADO,
              USU,
              NOW(),
              USU,
              NOW());      
      
    WHEN 'M' THEN
     
        UPDATE tbl_ciudad SET
        prv_id =IDPROVINCIA,
        pai_id =IDPAIS, 
        ciu_desc =DESCRIP, 
        ciu_estado=ESTADO,
        ciu_user_mod=USU,
        ciu_fech_mod=NOW()
        WHERE ciu_id = ID;
    
    
    WHEN 'EL' THEN
        
        UPDATE tbl_ciudad SET
        ciu_estado='I',
        ciu_user_mod=USU,
        ciu_fech_mod=NOW()
        WHERE ciu_id = ID; 
      
  END CASE;    

END;

#
# Definition for the `MAN_PRC_PAIS` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `MAN_PRC_PAIS`(
        IN OPC CHAR(2),
        IN ID INTEGER(11),
        IN DESCRIP VARCHAR(30),
        IN ESTADO CHAR(1),
        IN USU VARCHAR(20)
    )
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
  
  CASE OPC 
 
     WHEN 'I' THEN
      
       SET ID =(SELECT IFNULL(MAX(pai_id)+1,1) FROM tbl_pais LIMIT 1);
       INSERT INTO tbl_pais
       (pai_id,
        pai_desc,
        pai_estado,
        pai_user_crea,
        pai_fech_crea,
        pai_user_mod,
        pai_fech_mod
        )VALUE(ID,
               DESCRIP,
               ESTADO,
               USU,
               NOW(),
               USU,
               NOW()); 
       
     
     WHEN 'M' THEN
     
          UPDATE tbl_pais SET 
          pai_desc    = DESCRIP, 
          pai_estado  = ESTADO,
          pai_user_mod= USU,
          pai_fech_mod=NOW()
          WHERE pai_id=ID;
     
     
     WHEN 'EL' THEN
           
          UPDATE tbl_pais SET 
          pai_estado  = 'I',
          pai_user_mod= USU,
          pai_fech_mod=NOW()
          WHERE pai_id=ID;      
       
  END CASE;     
  
END;

#
# Definition for the `MAN_PRC_PROVINCIA` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `MAN_PRC_PROVINCIA`(
        IN OPC CHAR(1),
        IN ID INTEGER(11),
        IN IDPAIS INTEGER(11),
        IN DESCRIP VARCHAR(30),
        IN ESTADO CHAR(1),
        IN USU VARCHAR(20)
    )
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
   CASE OPC 
 
     WHEN 'I' THEN
      
      SET ID =(SELECT IFNULL(MAX(prv_id)+1,1) FROM tbl_provincia LIMIT 1);
      
      INSERT INTO tbl_provincia
      (prv_id,
       pai_id,
       prv_desc,
       prv_estado,
       prv_user_crea,
       prv_fech_crea,
       prv_user_mod,
       prv_fech_mod
       )VALUE(ID,
              IDPAIS,
              DESCRIP,
              ESTADO,
              USU,
              NOW(),
              USU,
              NOW());      
       
     WHEN 'M' THEN  
     
        UPDATE tbl_provincia SET
         pai_id =IDPAIS, 
         prv_desc =DESCRIP, 
         prv_estado=ESTADO,
         prv_user_mod=USU,
         prv_fech_mod=NOW()
         WHERE prv_id = ID;
     
     
     WHEN 'EL' THEN
     
         UPDATE tbl_provincia SET
         prv_estado= 'I',
         prv_user_mod=USU,
         prv_fech_mod=NOW()
         WHERE prv_id = ID;
       
       
   END CASE;    
END;

#
# Definition for the `SEG_PRC_MANT_MENU` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `SEG_PRC_MANT_MENU`(
        IN OPC CHAR(2),
        IN ID INTEGER(11),
        IN IDMODULO INTEGER(11),
        IN DESCRIP VARCHAR(35),
        IN ARCHIVO VARCHAR(50),
        IN CARGA VARCHAR(50),
        IN ORDEN INTEGER(3),
        IN ESTADO CHAR(1),
        IN USU VARCHAR(20)
    )
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
  
  CASE OPC 
 
    WHEN 'I' THEN
      
      SET ID =(SELECT IFNULL(MAX(men_id)+1,1) FROM tbl_menu LIMIT 1);
      INSERT INTO tbl_menu
      ( men_id,
        mod_id,
        men_desc,
        men_orden,
        men_estado,
        men_user_crea,
        men_fech_crea,
        men_user_mod,
        men_fech_mod
       )VALUE(ID,
              IDMODULO,
              DESCRIP,
              ORDEN,
              ESTADO,
              USU,
              NOW(),
              USU,
              NOW());
      
      
    WHEN 'M' THEN
    
          UPDATE tbl_menu SET 
          mod_id   = IDMODULO,	
          men_desc = DESCRIP, 
          men_orden= ORDEN,
          men_estado   = ESTADO,
          men_user_mod = USU,
          men_fech_mod = NOW()
          WHERE men_id = ID;
    
    WHEN 'EL' THEN  
          
          UPDATE tbl_menu SET 
          men_estado   = 'I',
          men_user_mod = USU,
          men_fech_mod = NOW()
          WHERE men_id = ID;
      
  END CASE;    
   
END;

#
# Definition for the `SEG_PRC_MANT_MODULO` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `SEG_PRC_MANT_MODULO`(
        IN OPC CHAR(2),
        IN ID INTEGER(11),
        IN DESCRIP VARCHAR(35),
        IN ORDEN INTEGER(3),
        IN ESTADO CHAR(1),
        IN USU VARCHAR(20)
    )
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
  CASE OPC 
 
    WHEN 'I' THEN
      
       SET ID =(SELECT IFNULL(MAX(mod_id)+1,1) FROM tbl_modulo LIMIT 1);
       INSERT INTO tbl_modulo
       (mod_id,
        mod_desc,
        mod_orden,
        mod_estado,
        mod_user_crea,
        mod_fech_crea,
        mod_user_mod,
        mod_fech_mod
        )VALUE(ID,
               DESCRIP,
               ORDEN,
               ESTADO,
               USU,
               NOW(),
               USU,
               NOW());	
    
    
    WHEN 'M' THEN
        
        UPDATE tbl_modulo SET 
        mod_desc  = DESCRIP, 
		mod_orden = ORDEN,
        mod_estado= ESTADO,
        mod_user_mod = USU,
        mod_fech_mod = NOW()
        WHERE mod_id = ID;
    
    WHEN 'EL' THEN
         
        UPDATE tbl_modulo SET 
        mod_estado   = 'I',
        mod_user_mod = USU,
        mod_fech_mod = NOW()
        WHERE mod_id = ID;     
    
    
  
  END CASE;  
 
 
 
END;

#
# Definition for the `SEG_PRC_MANT_TIPO_USUARIO` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `SEG_PRC_MANT_TIPO_USUARIO`(
        IN OPC CHAR(2),
        IN ID INTEGER(11),
        IN DESCRIP VARCHAR(35),
        IN ESTADO CHAR(1),
        IN USU VARCHAR(20)
    )
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
 
 CASE OPC 
 
    WHEN 'I' THEN
      
      SET ID =(SELECT IFNULL(MAX(tip_user_id)+1,1) FROM tbl_tipo_usuario LIMIT 1);
      
      INSERT INTO tbl_tipo_usuario
      (tip_user_id,
       tip_user_desc,
       tip_user_estado,
       tip_user_crea,
       tip_fech_crea,
       tip_user_mod,
       tip_fech_mod
       )VALUE(ID,
              DESCRIP,
              ESTADO,
              USU,
              NOW(),
              USU,
              NOW());
      
      
    WHEN 'M' THEN
        
        UPDATE tbl_tipo_usuario SET
        tip_user_desc  = DESCRIP, 
		tip_user_estado= ESTADO,
        tip_user_mod   = USU,
        tip_fech_mod   = NOW()
        WHERE tip_user_id = ID;
    
    
    WHEN 'EL' THEN
    
        UPDATE tbl_tipo_usuario SET
     	tip_user_estado= 'I',
        tip_user_mod   = USU,
        tip_fech_mod   = NOW()
        WHERE tip_user_id = ID;
    
 END CASE;   
    
END;

#
# Definition for the `SEG_PRC_MANT_TRANSACCION` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `SEG_PRC_MANT_TRANSACCION`(
        IN ID INTEGER(11),
        IN IDTIPO_USER INTEGER(11),
        IN IDMODULO INTEGER(11),
        IN IDMENU INTEGER(11),
        IN INGRESAR INTEGER(1),
        IN AGREGAR INTEGER(1),
        IN MODIFICAR INTEGER(1),
        IN ELIMINAR INTEGER(1),
        IN ESTADO CHAR(1),
        IN USU VARCHAR(20)
    )
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN

 IF EXISTS(SELECT @ID:=tran_id FROM tbl_tipo_usuario 
           INNER JOIN tbl_transaccion USING(tip_user_id)
           INNER JOIN tbl_menu USING(men_id)INNER JOIN tbl_modulo USING(mod_id)
           WHERE tip_user_id =IDTIPO_USER AND men_id = IDMENU AND mod_id = IDMODULO 
           GROUP BY tran_id LIMIT 1) THEN
        
       UPDATE tbl_transaccion SET 
	   tran_ingresar  = INGRESAR,
	   tran_agregar   = AGREGAR,
	   tran_modificar = MODIFICAR,
	   tran_eliminar  = ELIMINAR,
	   tran_estado    = ESTADO,
	   tran_user_mod  = USU,
	   tran_fech_mod  = NOW()
	   WHERE tran_id  = @ID AND tip_user_id = IDTIPO_USER;   
       
 ELSE
 
      SET ID =(SELECT IFNULL(MAX(tran_id)+1,1) FROM tbl_transaccion LIMIT 1);
      
      INSERT INTO tbl_transaccion
	  (tran_id,
       tip_user_id,
       men_id,
       tran_ingresar,
       tran_agregar,
       tran_modificar,
	   tran_eliminar,
       tran_estado,
       tran_user_crea,
       tran_fech_crea,
       tran_user_mod,
       tran_fech_mod)
	   VALUE(ID,
             IDTIPO_USER,
             IDMENU,
             INGRESAR,
             AGREGAR,
             MODIFICAR,
             ELIMINAR,
             ESTADO,
             USU,
             NOW(),
             USU,
             NOW());                   
       
  END IF;

END;

#
# Definition for the `SEG_PRC_MANT_USUARIO` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `SEG_PRC_MANT_USUARIO`(
        IN OPC CHAR(2),
        IN ID INTEGER(11),
        IN IDTIPO_USU INTEGER(11),
        IN IDPERS INTEGER(11),
        IN CUENTA VARCHAR(30),
        IN PASSW VARCHAR(40),
        IN ESTADO CHAR(1),
        IN USU VARCHAR(20)
    )
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN

CASE OPC 
 
 WHEN 'I' THEN
      
    SET ID =(SELECT IFNULL(MAX(usu_id)+1,1) FROM tbl_usuario LIMIT 1);
    INSERT INTO tbl_usuario
    ( usu_id,
      tip_user_id,
      per_id,
      usu_cuenta,
      usu_clave,
      usu_estado,
      usu_crea,
      usu_fech_crea,
      usu_mod,
      usu_fech_mod
      )VALUE(ID,
      	     IDTIPO_USU,
             IDPERS,
             CUENTA,
             PASSW,
             ESTADO,
             USU,
             Now(),
             USU,NOW());    
    
  
 
 WHEN 'M' THEN
 
      UPDATE tbl_usuario SET 
      tip_user_id = IDTIPO_USU,
      per_id      = IDPERS,	
      usu_cuenta  = CUENTA,
      usu_clave   = PASSW,
      usu_estado  = ESTADO,
      usu_mod     = USU,
      usu_fech_mod= Now()
      WHERE usu_id = ID;
 
 
 
 WHEN 'EL' THEN
 
      UPDATE tbl_usuario SET 
      usu_estado  = 'I',
      usu_mod     = USU,
      usu_fech_mod= Now()
      WHERE tip_user_id != 1 AND usu_id = ID;
      
    
END CASE;

END;

#
# Definition for the `SEG_PRC_SELECT_USUARIO` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `SEG_PRC_SELECT_USUARIO`(
        IN ID INTEGER(11),
        IN USU VARCHAR(20)
    )
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN

END;

#
# Definition for the `SEG_PRC_USUARIO_ACCESO` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `SEG_PRC_USUARIO_ACCESO`(
        IN USU VARCHAR(30),
        IN PASSW VARCHAR(40)
    )
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
SELECT 
 U.usu_id id,
 U.tip_user_id idtip,
 TU.tip_user_desc tipuser,
 U.per_id idpers,
 P.per_dir_img img,
 U.grp_id caj
FROM tbl_usuario U INNER JOIN tbl_tipo_usuario TU ON(U.tip_user_id = TU.tip_user_id)
     INNER JOIN tbl_transaccion TR ON(TU.tip_user_id = TR.tip_user_id AND tran_ingresar=1)
     INNER JOIN tbl_personal P ON (U.per_id = P.per_id) 
WHERE usu_estado = 'A' AND usu_cuenta = USU AND usu_clave = PASSW
LIMIT 1;
END;

#
# Data for the `tbl_pais` table  (LIMIT 0,500)
#

INSERT INTO `tbl_pais` (`pai_id`, `pai_desc`, `pai_estado`, `pai_user_crea`, `pai_fech_crea`, `pai_user_mod`, `pai_fech_mod`) VALUES 
  (1,'Ecuador','A','admin','2013-04-03 18:43:00','admin','2013-03-22 15:57:43'),
  (2,'Estados Unidos','A','admin','2013-04-03 18:38:12','admin','2013-04-03 18:38:12'),
  (3,'Venezuela','A','admin','2013-04-03 18:38:18','admin','2013-04-03 18:38:18'),
  (4,'Brazil','A','admin','2013-04-03 18:38:28','admin','2013-04-03 18:38:28'),
  (5,'Colombia','A','admin','2013-04-03 18:38:33','admin','2013-04-03 18:38:33'),
  (6,'Espa?a','A','admin','2013-04-03 18:38:39','admin','2013-04-03 18:38:39'),
  (7,'Guatemala','A','admin','2013-04-03 18:38:47','admin','2013-04-03 18:38:47'),
  (8,'Honduras','A','admin','2013-04-03 18:38:57','admin','2013-04-03 18:38:57'),
  (9,'M?xico','A','admin','2013-04-03 18:39:05','admin','2013-04-03 18:39:05'),
  (10,'Chile','A','admin','2013-04-03 18:39:12','admin','2013-04-03 18:39:12'),
  (11,'Canad?','A','admin','2013-04-03 18:39:20','admin','2013-04-03 18:39:20'),
  (12,'Italia','A','admin','2013-04-03 18:39:38','admin','2013-04-03 18:39:38'),
  (13,'Argentina','A','admin','2013-04-03 18:39:47','Admin','2013-07-31 07:30:55'),
  (14,'Uruguay','A','Admin','2013-07-31 07:31:06','Admin','2013-07-31 07:31:06');
COMMIT;

#
# Data for the `tbl_provincia` table  (LIMIT 0,500)
#

INSERT INTO `tbl_provincia` (`prv_id`, `pai_id`, `prv_desc`, `prv_estado`, `prv_user_crea`, `prv_fech_crea`, `prv_user_mod`, `prv_fech_mod`) VALUES 
  (1,1,'Guayas','A','admin','2013-03-19 12:49:57','admin','2013-03-22 15:57:49'),
  (2,1,'Machala','A','admin','2013-04-03 18:40:14','admin','2013-04-03 18:40:14'),
  (3,1,'El Oro','A','admin','2013-04-03 18:40:25','admin','2013-04-03 18:40:25'),
  (4,1,'Babahoyo','A','admin','2013-04-03 18:40:38','Admin','2013-07-31 07:47:13'),
  (5,1,'NI','A','admin','2013-04-03 18:40:46','admin','2013-04-03 18:40:46'),
  (6,1,'Ca?ar','A','admin','2013-04-03 18:40:58','admin','2013-04-03 18:40:58'),
  (7,1,'Esmeraldas','A','admin','2013-04-03 18:41:11','admin','2013-04-03 18:41:11'),
  (8,1,'Manab?','A','admin','2013-04-03 18:41:22','admin','2013-04-03 18:41:22'),
  (9,1,'Sucumbios','A','admin','2013-04-03 18:41:38','admin','2013-04-03 18:41:38'),
  (10,6,'M?laga','A','admin','2013-04-03 18:42:13','admin','2013-04-03 18:42:13'),
  (11,6,'Murcia','A','admin','2013-04-03 18:42:20','admin','2013-04-03 18:42:20');
COMMIT;

#
# Data for the `tbl_ciudad` table  (LIMIT 0,500)
#

INSERT INTO `tbl_ciudad` (`ciu_id`, `prv_id`, `pai_id`, `ciu_desc`, `ciu_estado`, `ciu_user_crea`, `ciu_fech_crea`, `ciu_user_mod`, `ciu_fech_mod`) VALUES 
  (1,1,1,'Guayaquil','A','admin','2013-04-03 18:43:00','admin','2013-03-22 15:58:11'),
  (2,1,1,'Milagro','A','admin','2013-04-03 18:43:00','admin','2013-04-03 18:43:00'),
  (3,1,1,'Daule','A','admin','2013-04-03 18:43:13','admin','2013-04-03 18:43:13'),
  (4,1,1,'Dur?n','A','admin','2013-04-03 18:43:26','admin','2013-04-03 18:43:26'),
  (5,6,1,'Azogues','A','admin','2013-04-03 18:43:53','admin','2013-04-03 18:44:03'),
  (6,11,6,'Cieza','A','admin','2013-04-03 18:44:27','Admin','2013-07-31 08:16:46'),
  (7,10,6,'Librilla','A','admin','2013-04-03 18:44:50','admin','2013-04-03 18:44:50'),
  (8,10,6,'Alameda','A','admin','2013-04-03 18:45:03','admin','2013-04-03 18:45:03'),
  (9,10,6,'Chilches','A','admin','2013-04-03 18:45:20','admin','2013-04-03 18:45:20');
COMMIT;

#
# Data for the `tbl_modulo` table  (LIMIT 0,500)
#

INSERT INTO `tbl_modulo` (`mod_id`, `mod_desc`, `mod_orden`, `mod_estado`, `mod_user_crea`, `mod_fech_crea`, `mod_user_mod`, `mod_fech_mod`) VALUES 
  (1,'Seguridad',8,'A',NULL,NULL,'Administrador','2013-08-11 23:15:43'),
  (2,'Mantenimiento',9,'A','Admin','2013-07-30 17:52:09','Administrador','2013-08-11 23:15:35'),
  (3,'Inventario',6,'A','Admin','2013-07-30 17:52:34','Administrador','2013-08-11 23:15:59'),
  (4,'Ventas',5,'A','Admin','2013-07-30 17:52:47','Administrador','2013-08-11 23:16:18'),
  (5,'Informes',10,'A','Admin','2013-07-30 17:53:13','Administrador','2013-08-11 23:15:27'),
  (6,'Catalogos',7,'A','Admin','2013-07-30 18:00:19','Administrador','2013-08-11 23:15:51'),
  (7,'Cuent. por Cobrar Cliente',1,'A','Administrador','2013-08-11 23:16:52','Administrador','2013-08-11 23:17:30'),
  (8,'Configurar',11,'A','Administrador','2013-09-19 08:56:48','Administrador','2013-09-19 08:56:48');
COMMIT;

#
# Data for the `tbl_menu` table  (LIMIT 0,500)
#

INSERT INTO `tbl_menu` (`men_id`, `mod_id`, `men_desc`, `men_archivo`, `men_carga`, `men_orden`, `men_estado`, `men_user_crea`, `men_fech_crea`, `men_user_mod`, `men_fech_mod`) VALUES 
  (1,1,'Acceso','acceso','pages/pw_acceso_form.php',5,'A','admin','2012-07-29 14:36:11','admin','2012-09-15 13:03:30'),
  (2,1,'M?dulos','modulo','pages/pw_mantenimiento.php',1,'A','admin','2012-07-29 14:36:11','Admin','2013-07-30 14:48:36'),
  (3,1,'Men?s','menu','pages/pw_mantenimiento.php',2,'A','admin','2012-07-29 14:36:11','Admin','2013-07-30 14:48:38'),
  (4,1,'Tipo de Usuario','tipo_usuario','pages/pw_mantenimiento.php',3,'A','admin','2012-07-29 14:36:11','admin','2012-09-15 13:03:18'),
  (5,1,'Usuario','usuario','pages/pw_mantenimiento.php',4,'A','admin','2012-07-29 14:36:11','admin','2012-09-15 13:03:41'),
  (6,2,'Pais','pais','pages/pw_mantenimiento.php',1,'A','Admin','2013-07-30 17:53:34','Admin','2013-07-30 17:53:34'),
  (7,2,'Provincia','provincia','pages/pw_mantenimiento.php',2,'A','Admin','2013-07-30 17:53:46','Admin','2013-07-30 17:53:46'),
  (8,2,'Ciudad','ciudad','pages/pw_mantenimiento.php',3,'A','Admin','2013-07-30 17:53:59','Admin','2013-07-30 17:53:59'),
  (9,4,'Informe Ventas','','pages/pw_ven_informe_factura.php',1,'A','Administrador','2013-08-11 22:58:48','Administrador','2013-08-11 22:58:48'),
  (10,4,'Informe Devoluciones','','pages/pw_ven_informe_devolucion.php',2,'A','Administrador','2013-08-11 22:59:11','Administrador','2013-08-11 22:59:11'),
  (11,4,'Informe Comisi?n Pendiente','','pages/pw_ven_informe_pendiente.php',3,'A','Administrador','2013-08-11 22:59:38','Administrador','2013-08-11 22:59:38'),
  (12,4,'Informe Venta Pagada','','pages/pw_ven_informe_venta_pagada.php',4,'A','Administrador','2013-08-11 23:00:49','Administrador','2013-08-11 23:00:49'),
  (13,4,'Informe Consolid. Vendedor','','pages/pw_ven_informe_venta_consolidado.php',5,'A','Administrador','2013-08-11 23:01:26','Administrador','2013-08-11 23:01:26'),
  (14,6,'Personal','personal','pages/pw_mantenimiento.php',1,'A','Administrador','2013-08-11 23:09:08','Administrador','2013-08-11 23:09:08'),
  (15,7,'Estado de Cuenta','','pages/pw_cli_informe_estado_cuenta_cliente.php',1,'A','Administrador','2013-08-11 23:17:55','Administrador','2013-08-11 23:17:55'),
  (16,7,'Informe de Saldos','','pages/pw_cli_informe_saldo.php',2,'A','Administrador','2013-08-11 23:19:22','Administrador','2013-08-11 23:19:22'),
  (17,7,'Cartera Vencida','','pages/pw_cli_informe_cartera_vencida.php',4,'A','Administrador','2013-08-11 23:24:30','Administrador','2013-09-30 16:38:24'),
  (18,3,'Ingreso de Premios','','pages/pw_inv_form_premios.php',1,'A','Administrador','2013-08-11 23:26:40','Administrador','2013-08-11 23:26:40'),
  (19,3,'Informe de Premios','','pages/pw_ven_informe_nota_venta_premios.php',2,'A','Administrador','2013-08-11 23:27:04','Administrador','2013-08-11 23:27:04'),
  (20,7,'Comprobante Ingreso Dinero','','pages/pw_form_trans_comp_ingreso_dinero.php',5,'A','Administrador','2013-08-11 15:36:07','Administrador','2013-09-30 16:38:19'),
  (21,7,'Informe Arqueo de Caja','','pages/pw_cli_informe_arqueo_caja.php',6,'A','Administrador','2013-08-11 15:36:45','Administrador','2013-09-30 16:38:13'),
  (22,5,'Call Center','','pages/pw_cli_informe_call_center.php',1,'A','Administrador','2013-08-20 12:56:16','Administrador','2013-08-20 12:56:16'),
  (23,4,'Factura de Ventas','','pages/pw_ven_form_factura_venta.php',6,'A','Administrador','2013-08-21 11:00:33','Administrador','2013-08-21 11:00:33'),
  (24,7,'Nota de Credito(Producto)','','pages/pw_cli_tran_form_nota_credito_producto.php',7,'A','Administrador','2013-09-02 09:44:05','Administrador','2013-09-30 16:38:07'),
  (25,4,'Porcentaje de Ventas','','pages/pw_ven_informe_factura_porcentaje.php',7,'A','Administrador','2013-09-03 13:24:07','Administrador','2013-09-03 13:24:07'),
  (26,8,'Fecha','','pages/pw_fecha_predeterminada.php',1,'A','Administrador','2013-09-19 08:57:21','Administrador','2013-09-19 08:57:21'),
  (27,4,'Devolucion de Venta','','pages/pw_ven_tran_form_devolucion_venta.php',8,'A','Administrador','2013-09-19 15:54:36','Administrador','2013-09-19 15:58:59'),
  (28,7,'Informe Saldo Cartera','','pages/pw_cli_informe_saldo_cartera.php',3,'A','Administrador','2013-09-30 16:35:00','Administrador','2013-09-30 16:38:30'),
  (29,7,'Nota de Debito','','pages/pw_cli_tran_form_nota_debito.php',8,'A','Administrador','2013-10-03 14:18:48','Administrador','2013-10-03 14:18:48');
COMMIT;

#
# Data for the `tbl_sucursal` table  (LIMIT 0,500)
#

INSERT INTO `tbl_sucursal` (`suc_id`, `suc_codigo`, `suc_nombre`, `suc_estado`, `suc_user_crea`, `suc_fech_crea`, `suc_user_mod`, `suc_fech_mod`) VALUES 
  (1,'ML-01','Milagro Central','A','Admin','2013-04-03 18:43:00','Admin','2013-04-03 18:43:00');
COMMIT;

#
# Data for the `tbl_personal` table  (LIMIT 0,500)
#

INSERT INTO `tbl_personal` (`per_id`, `per_codigo`, `per_tipo_doc`, `per_num_doc`, `per_tratamiento`, `per_apellido`, `per_nombre`, `per_dir_img`, `per_sexo`, `per_est_civil`, `per_direccion`, `ciu_id`, `prv_id`, `pai_id`, `per_telefono`, `per_movil`, `per_email`, `per_fech_nace`, `per_observ`, `per_estado`, `suc_id`, `per_user_crea`, `per_fech_crea`, `per_user_mod`, `per_fech_mod`) VALUES 
  (1,'EMP-001','CI','0926400615','Ing.','Guam?n Uzhca','Ernesto',NULL,'M','S',NULL,1,1,1,'0000000','000000000','Nestor_02ma@gmail.com',NULL,NULL,'A',1,'admin','2013-04-03 18:43:00','admin','2013-04-03 18:43:00'),
  (2,'','','','','','FRANKLIN','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (3,'','','','','','MINDIOLAZA','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (4,'','','','','','OFICINA','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (6,'','','','','','VICTOR','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (7,'','','','','','LCRUZ','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (8,'','','','','','MARVIN','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (9,'','','','','','MARTIN','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (10,'','','','','','MISAEL','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (11,'','','','','','WACHO','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (12,'','','','','','ES-01','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (13,'','','','','','ES-02','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (14,'','','','','','ES-03','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (15,'','','','','','ES-04','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (16,'','','','','','ES-05','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (18,'','','','','','XAVIER','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (19,'','','','','','PVJ-001','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (20,'','','','','','PVJ-002','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (21,'','','','','','PVJ-003','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (22,'','','','','','PVJ-004','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (23,'','','','','','PVJ-005','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (24,'','','','','','DURANML','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (25,'','','','','','Q-001','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (26,'','','','','','Q-002','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (27,'','','','','','Q-003','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (28,'','','','','','Q-004','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (29,'','','','','','MHL-001','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (30,'','','','','','MHL-002','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (31,'','','','','','MHL-003','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (32,'','','','','','MHL-004','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (33,'','','','','','JONATHAN','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (34,'','','','','','DURAN','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (35,'','','','','','GYE','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (36,'','','','','','70A','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (37,'','','','','','70B','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (38,'','','','','','NSUR','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (39,'','','','','','Z80','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (40,'','','','','','PACIFICO','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (41,'','','','','','PERLA','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (42,'','','','','','SURM','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (43,'','','','','','Z90','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (44,'','','','','','GOLFO','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (45,'','','','','','GUAYAS','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (46,'','','','','','SUR','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (47,'','','','','','YOYO','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (48,'','','','','','Z70','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (49,'','','','','','OFICINAGYE','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (50,'','','','','','MHLOF','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (51,'','','','','','PVJOF','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (52,'','','','','','Q2OF','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (53,'','','','','','ESOF','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (54,'','','','','','GYEOF1','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (55,'','','','','','GYEOF2','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (56,'','','','','','LENNIN','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (57,'','','','','','GUASMO','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (58,'','','','','','GUASMOS','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (59,'','','','','','ML-001','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (60,'','','','','','ML-002','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (61,'','','','','','ML-003','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (62,'','','','','','ML-004','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (63,'','','','','','ML-005','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (64,'','','','','','ML-006','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (65,'','','','','','ML-007','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (66,'','','','','','ML-008','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (67,'','','','','','ML-009','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (68,'','','','','','LB-001','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (69,'','','','','','LB-002','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (70,'','','','','','PL-001','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (71,'','','','','','CARTERA','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (72,'','','','','','QUIL2','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (73,'','','','','','GYEOF3','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (74,'','','','','','LBOF','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (75,'','','','','','PLOF','','M','S','',1,1,1,'','','','2013-08-14','','A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (76,'',NULL,'',NULL,' ','CALLCENTER',NULL,'M','S',NULL,1,1,1,NULL,NULL,NULL,'2013-08-14',NULL,'A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (77,'',NULL,'',NULL,'Rodriguez','Henry',NULL,'M','S',NULL,1,1,1,NULL,NULL,NULL,'2013-08-14',NULL,'A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (78,'',NULL,'',NULL,'Plaza','Leonor',NULL,'F','S',NULL,1,1,1,NULL,NULL,NULL,'2013-08-14',NULL,'A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (79,'',NULL,'',NULL,'Gonzalez','stefan',NULL,'F','S',NULL,1,1,1,NULL,NULL,NULL,'2013-08-14',NULL,'A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (80,'',NULL,'',NULL,'Blandin','Josue',NULL,'M','S',NULL,1,1,1,NULL,NULL,NULL,'2013-08-14',NULL,'A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (81,'',NULL,'',NULL,'DAULE','DAULE',NULL,'M','S',NULL,1,1,1,NULL,NULL,NULL,'2013-08-14',NULL,'A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11'),
  (82,'',NULL,'',NULL,'Q2-005','Q2-005',NULL,'M','S',NULL,1,1,1,NULL,NULL,NULL,'2013-08-14',NULL,'A',1,'Admin','2013-08-14 11:47:11','Admin','2013-08-14 11:47:11');
COMMIT;

#
# Data for the `tbl_tipo_usuario` table  (LIMIT 0,500)
#

INSERT INTO `tbl_tipo_usuario` (`tip_user_id`, `tip_user_desc`, `tip_user_estado`, `tip_user_crea`, `tip_fech_crea`, `tip_user_mod`, `tip_fech_mod`) VALUES 
  (1,'Administrador','A','admin','2013-04-03 18:43:00','admin','2013-04-03 18:43:00'),
  (2,'Gestion Cartera','A','Administrador','2013-08-14 11:36:56','Administrador','2013-08-14 11:37:13'),
  (3,'Gestion Premios','A','Administrador','2013-08-14 11:38:50','Administrador','2013-08-14 11:38:50'),
  (4,'Gestion Ventas','A','Administrador','2013-08-14 11:39:07','Administrador','2013-08-14 11:39:07'),
  (5,'Operador','A','ADMINISTRADOR','2013-08-14 12:38:19','ADMINISTRADOR','2013-08-14 12:38:26'),
  (6,'CallCenter','A','Administrador','2013-08-20 11:20:46','Administrador','2013-08-20 11:20:46'),
  (7,'SemiAdministrador','A','ADMINISTRADOR','2013-08-21 13:30:06','ADMINISTRADOR','2013-08-21 13:30:06'),
  (8,'Cord. Cobranza','A','Administrador','2013-08-25 09:12:59','Administrador','2013-08-25 09:12:59'),
  (9,'Sup. Inventario','A','ADMINISTRADOR','2013-09-01 10:32:13','ADMINISTRADOR','2013-09-01 10:32:13'),
  (10,'Super Admin','A','Administrador','2013-09-06 11:00:46','Administrador','2013-09-06 11:00:46');
COMMIT;

#
# Data for the `tbl_transaccion` table  (LIMIT 0,500)
#

INSERT INTO `tbl_transaccion` (`tran_id`, `tip_user_id`, `men_id`, `tran_ingresar`, `tran_agregar`, `tran_modificar`, `tran_eliminar`, `tran_estado`, `tran_user_crea`, `tran_fech_crea`, `tran_user_mod`, `tran_fech_mod`) VALUES 
  (1,1,1,1,1,1,1,'A','Admin','2013-07-29 21:11:55','Administrador','2013-08-11 22:51:49'),
  (2,1,5,1,1,1,1,'A','Administrador','2013-08-11 22:51:49','Administrador','2013-08-11 22:51:49'),
  (3,1,3,1,1,1,1,'A','Administrador','2013-08-11 22:51:49','Administrador','2013-08-11 22:51:49'),
  (4,1,4,1,1,1,1,'A','Administrador','2013-08-11 22:51:49','Administrador','2013-08-11 22:51:49'),
  (5,1,2,1,1,1,1,'A','Administrador','2013-08-11 22:51:50','Administrador','2013-08-11 22:51:50'),
  (6,1,9,1,1,1,1,'A','Administrador','2013-08-11 22:59:53','Administrador','2013-09-19 15:54:59'),
  (7,1,11,1,1,1,1,'A','Administrador','2013-08-11 22:59:53','Administrador','2013-09-19 15:54:59'),
  (8,1,10,1,1,1,1,'A','Administrador','2013-08-11 22:59:53','Administrador','2013-09-19 15:54:59'),
  (9,1,13,1,1,1,1,'A','Administrador','2013-08-11 23:01:40','Administrador','2013-09-19 15:54:59'),
  (10,1,12,1,1,1,1,'A','Administrador','2013-08-11 23:01:40','Administrador','2013-09-19 15:54:59'),
  (11,1,6,1,1,1,1,'A','Administrador','2013-08-11 23:05:26','Administrador','2013-08-11 23:05:26'),
  (12,1,8,1,1,1,1,'A','Administrador','2013-08-11 23:05:26','Administrador','2013-08-11 23:05:26'),
  (13,1,7,1,1,1,1,'A','Administrador','2013-08-11 23:05:26','Administrador','2013-08-11 23:05:26'),
  (14,1,14,1,1,1,1,'A','Administrador','2013-08-11 23:09:19','Administrador','2013-08-11 23:09:19'),
  (15,1,15,1,1,1,1,'A','Administrador','2013-08-11 23:18:06','Administrador','2013-10-03 14:19:10'),
  (16,1,16,1,1,1,1,'A','Administrador','2013-08-11 23:19:34','Administrador','2013-10-03 14:19:10'),
  (17,1,17,1,1,1,1,'A','Administrador','2013-08-11 23:24:44','Administrador','2013-10-03 14:19:11'),
  (18,1,19,1,1,1,1,'A','Administrador','2013-08-11 23:27:17','Administrador','2013-08-11 23:27:17'),
  (19,1,18,1,1,1,1,'A','Administrador','2013-08-11 23:27:18','Administrador','2013-08-11 23:27:18'),
  (20,1,21,1,1,1,1,'A','Administrador','2013-08-11 15:36:59','Administrador','2013-10-03 14:19:11'),
  (21,1,20,1,1,1,1,'A','Administrador','2013-08-11 15:36:59','Administrador','2013-10-03 14:19:11'),
  (22,4,9,1,1,1,1,'A','Administrador','2013-08-14 11:53:06','Administrador','2013-08-14 11:53:06'),
  (23,4,10,1,1,1,1,'A','Administrador','2013-08-14 11:53:06','Administrador','2013-08-14 11:53:06'),
  (24,4,13,1,1,1,1,'A','Administrador','2013-08-14 11:53:06','Administrador','2013-08-14 11:53:06'),
  (25,4,12,1,1,1,1,'A','Administrador','2013-08-14 11:53:07','Administrador','2013-08-14 11:53:07'),
  (26,4,11,1,1,1,1,'A','Administrador','2013-08-14 11:53:07','Administrador','2013-08-14 11:53:07'),
  (27,4,15,1,1,1,1,'A','ADMINISTRADOR','2013-08-14 12:05:21','Administrador','2013-09-30 16:36:26'),
  (28,4,16,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 12:05:21','Administrador','2013-09-30 16:36:26'),
  (29,4,17,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 12:05:21','Administrador','2013-09-30 16:36:26'),
  (30,4,20,0,0,0,0,'A','ADMINISTRADOR','2013-08-14 12:05:21','Administrador','2013-09-30 16:36:26'),
  (31,4,21,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 12:05:21','Administrador','2013-09-30 16:36:26'),
  (32,2,16,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 14:39:45','Administrador','2013-09-30 16:36:11'),
  (33,2,17,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 14:39:45','Administrador','2013-09-30 16:36:10'),
  (34,2,15,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 14:39:45','Administrador','2013-09-30 16:36:11'),
  (35,2,20,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 14:39:46','Administrador','2013-09-30 16:36:10'),
  (36,2,21,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 14:39:46','Administrador','2013-09-30 16:36:11'),
  (37,3,15,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 15:01:59','Administrador','2013-09-30 16:36:19'),
  (38,3,16,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 15:01:59','Administrador','2013-09-30 16:36:19'),
  (39,3,17,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 15:01:59','Administrador','2013-09-30 16:36:19'),
  (40,3,21,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 15:01:59','Administrador','2013-09-30 16:36:19'),
  (41,3,20,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 15:01:59','Administrador','2013-09-30 16:36:19'),
  (42,3,18,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 15:02:08','ADMINISTRADOR','2013-08-14 15:02:08'),
  (43,3,19,1,0,0,0,'A','ADMINISTRADOR','2013-08-14 15:02:08','ADMINISTRADOR','2013-08-14 15:02:08'),
  (44,6,15,1,0,0,0,'A','Administrador','2013-08-20 12:54:40','Administrador','2013-09-30 16:35:52'),
  (45,6,17,1,0,0,0,'A','Administrador','2013-08-20 12:54:40','Administrador','2013-09-30 16:35:52'),
  (46,6,16,1,0,0,0,'A','Administrador','2013-08-20 12:54:40','Administrador','2013-09-30 16:35:52'),
  (47,6,21,1,0,0,0,'A','Administrador','2013-08-20 12:54:40','Administrador','2013-09-30 16:35:52'),
  (48,6,20,0,0,0,0,'A','Administrador','2013-08-20 12:54:40','Administrador','2013-09-30 16:35:52'),
  (49,6,19,1,0,0,0,'A','Administrador','2013-08-20 12:54:48','ADMINISTRADOR','2013-08-21 13:36:14'),
  (50,6,18,0,0,0,0,'A','Administrador','2013-08-20 12:54:48','ADMINISTRADOR','2013-08-21 13:36:14'),
  (51,6,22,1,0,0,0,'A','Administrador','2013-08-20 12:56:28','Administrador','2013-08-20 12:56:28'),
  (52,7,16,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:21','Administrador','2013-09-30 16:36:40'),
  (53,7,17,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:21','Administrador','2013-09-30 16:36:40'),
  (54,7,15,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:21','Administrador','2013-09-30 16:36:40'),
  (55,7,20,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:21','Administrador','2013-09-30 16:36:40'),
  (56,7,21,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:21','Administrador','2013-09-30 16:36:41'),
  (57,7,10,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:27','Administrador','2013-09-30 12:28:52'),
  (58,7,9,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:27','Administrador','2013-09-30 12:28:52'),
  (59,7,11,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:27','Administrador','2013-09-30 12:28:52'),
  (60,7,12,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:27','Administrador','2013-09-30 12:28:52'),
  (61,7,13,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:27','Administrador','2013-09-30 12:28:52'),
  (62,7,18,0,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:33','ADMINISTRADOR','2013-08-21 13:30:36'),
  (63,7,19,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:34','ADMINISTRADOR','2013-08-21 13:30:36'),
  (64,7,14,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:30:43','ADMINISTRADOR','2013-08-21 13:30:43'),
  (65,7,2,0,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:31:11','ADMINISTRADOR','2013-08-21 13:31:11'),
  (66,7,3,0,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:31:11','ADMINISTRADOR','2013-08-21 13:31:11'),
  (67,7,4,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:31:11','ADMINISTRADOR','2013-08-21 13:31:11'),
  (68,7,5,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:31:11','ADMINISTRADOR','2013-08-21 13:31:11'),
  (69,7,1,0,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:31:11','ADMINISTRADOR','2013-08-21 13:31:11'),
  (70,7,22,1,0,0,0,'A','ADMINISTRADOR','2013-08-21 13:31:20','ADMINISTRADOR','2013-08-21 13:31:20'),
  (71,8,15,1,0,0,0,'A','Administrador','2013-08-25 09:14:05','Administrador','2013-09-30 16:36:04'),
  (72,8,21,1,0,0,0,'A','Administrador','2013-08-25 09:14:05','Administrador','2013-09-30 16:36:05'),
  (73,8,16,1,0,0,0,'A','Administrador','2013-08-25 09:14:05','Administrador','2013-09-30 16:36:05'),
  (74,8,20,0,0,0,0,'A','Administrador','2013-08-25 09:14:05','Administrador','2013-09-30 16:36:05'),
  (75,8,17,1,0,0,0,'A','Administrador','2013-08-25 09:14:05','Administrador','2013-09-30 16:36:05'),
  (76,8,18,0,0,0,0,'A','Administrador','2013-08-25 09:14:14','Administrador','2013-08-25 09:14:14'),
  (77,8,19,1,0,0,0,'A','Administrador','2013-08-25 09:14:14','Administrador','2013-08-25 09:14:14'),
  (78,1,23,1,1,1,1,'A','Administrador','2013-08-21 11:01:20','Administrador','2013-09-19 15:54:59'),
  (79,2,18,1,0,0,0,'A','Administrador','2013-08-26 12:00:59','Administrador','2013-08-20 13:12:19'),
  (80,2,19,1,0,0,0,'A','Administrador','2013-08-26 12:00:59','Administrador','2013-08-20 13:12:19'),
  (81,9,15,1,0,0,0,'A','ADMINISTRADOR','2013-09-01 10:36:47','Administrador','2013-09-30 16:36:48'),
  (82,9,17,1,0,0,0,'A','ADMINISTRADOR','2013-09-01 10:36:48','Administrador','2013-09-30 16:36:48'),
  (83,9,20,0,0,0,0,'A','ADMINISTRADOR','2013-09-01 10:36:48','Administrador','2013-09-30 16:36:48'),
  (84,9,16,1,0,0,0,'A','ADMINISTRADOR','2013-09-01 10:36:48','Administrador','2013-09-30 16:36:48'),
  (85,9,21,1,0,0,0,'A','ADMINISTRADOR','2013-09-01 10:36:48','Administrador','2013-09-30 16:36:49'),
  (86,9,18,1,0,0,0,'A','ADMINISTRADOR','2013-09-01 10:36:59','ADMINISTRADOR','2013-09-01 10:36:59'),
  (87,9,19,1,0,0,0,'A','ADMINISTRADOR','2013-09-01 10:36:59','ADMINISTRADOR','2013-09-01 10:36:59'),
  (88,9,22,1,0,0,0,'A','ADMINISTRADOR','2013-09-01 10:37:12','ADMINISTRADOR','2013-09-01 10:37:12'),
  (89,1,24,1,1,1,1,'A','Administrador','2013-09-02 09:44:22','Administrador','2013-10-03 14:19:11'),
  (90,4,24,0,0,0,0,'A','Administrador','2013-09-02 10:28:11','Administrador','2013-09-30 16:36:26'),
  (91,1,25,1,1,1,1,'A','Administrador','2013-09-03 13:24:24','Administrador','2013-09-19 15:55:00'),
  (92,7,23,1,0,0,0,'A','Administrador','2013-09-03 16:09:23','Administrador','2013-09-30 12:28:52'),
  (93,7,25,1,0,0,0,'A','Administrador','2013-09-03 16:09:23','Administrador','2013-09-30 12:28:52'),
  (94,10,24,1,1,0,0,'A','Administrador','2013-09-06 11:01:42','Administrador','2013-09-30 16:36:54'),
  (95,10,17,1,1,0,0,'A','Administrador','2013-09-06 11:01:42','Administrador','2013-09-30 16:36:54'),
  (96,10,15,1,1,0,0,'A','Administrador','2013-09-06 11:01:42','Administrador','2013-09-30 16:36:54'),
  (97,10,20,1,1,0,0,'A','Administrador','2013-09-06 11:01:42','Administrador','2013-09-30 16:36:54'),
  (98,10,21,1,1,0,0,'A','Administrador','2013-09-06 11:01:42','Administrador','2013-09-30 16:36:54'),
  (99,10,16,1,1,0,0,'A','Administrador','2013-09-06 11:01:42','Administrador','2013-09-30 16:36:54'),
  (100,10,9,1,1,0,0,'A','Administrador','2013-09-06 11:01:48','Administrador','2013-09-06 11:01:52'),
  (101,10,10,1,1,0,0,'A','Administrador','2013-09-06 11:01:48','Administrador','2013-09-06 11:01:52'),
  (102,10,11,1,1,0,0,'A','Administrador','2013-09-06 11:01:48','Administrador','2013-09-06 11:01:52'),
  (103,10,12,1,1,0,0,'A','Administrador','2013-09-06 11:01:48','Administrador','2013-09-06 11:01:52'),
  (104,10,13,1,1,0,0,'A','Administrador','2013-09-06 11:01:48','Administrador','2013-09-06 11:01:52'),
  (105,10,23,1,1,0,0,'A','Administrador','2013-09-06 11:01:48','Administrador','2013-09-06 11:01:52'),
  (106,10,25,1,1,0,0,'A','Administrador','2013-09-06 11:01:48','Administrador','2013-09-06 11:01:52'),
  (107,10,18,1,1,0,0,'A','Administrador','2013-09-06 11:02:01','Administrador','2013-09-06 11:02:01'),
  (108,10,19,1,1,0,0,'A','Administrador','2013-09-06 11:02:01','Administrador','2013-09-06 11:02:01'),
  (109,10,14,1,1,0,0,'A','Administrador','2013-09-06 11:02:06','Administrador','2013-09-06 11:02:06'),
  (110,10,2,1,1,0,0,'A','Administrador','2013-09-06 11:02:12','Administrador','2013-09-06 11:02:12'),
  (111,10,3,1,1,0,0,'A','Administrador','2013-09-06 11:02:12','Administrador','2013-09-06 11:02:12'),
  (112,10,4,1,1,0,0,'A','Administrador','2013-09-06 11:02:12','Administrador','2013-09-06 11:02:12'),
  (113,10,5,1,1,0,0,'A','Administrador','2013-09-06 11:02:12','Administrador','2013-09-06 11:02:12'),
  (114,10,1,1,1,0,0,'A','Administrador','2013-09-06 11:02:12','Administrador','2013-09-06 11:02:12'),
  (115,10,6,1,1,0,0,'A','Administrador','2013-09-06 11:02:18','Administrador','2013-09-06 11:02:18'),
  (116,10,7,1,1,0,0,'A','Administrador','2013-09-06 11:02:18','Administrador','2013-09-06 11:02:18'),
  (117,10,8,1,1,0,0,'A','Administrador','2013-09-06 11:02:18','Administrador','2013-09-06 11:02:18'),
  (118,10,22,1,1,0,0,'A','Administrador','2013-09-06 11:02:24','Administrador','2013-09-06 11:02:24'),
  (119,2,24,1,0,0,0,'A','Administrador','2013-09-18 16:48:23','Administrador','2013-09-30 16:36:11'),
  (120,2,10,0,0,0,0,'A','Administrador','2013-09-18 16:49:50','Administrador','2013-09-29 15:56:28'),
  (121,2,11,0,0,0,0,'A','Administrador','2013-09-18 16:49:50','Administrador','2013-09-29 15:56:29'),
  (122,2,9,0,0,0,0,'A','Administrador','2013-09-18 16:49:50','Administrador','2013-09-29 15:56:28'),
  (123,2,12,0,0,0,0,'A','Administrador','2013-09-18 16:49:50','Administrador','2013-09-29 15:56:28'),
  (124,2,13,0,0,0,0,'A','Administrador','2013-09-18 16:49:50','Administrador','2013-09-29 15:56:29'),
  (125,2,23,1,0,0,0,'A','Administrador','2013-09-18 16:49:50','Administrador','2013-09-29 15:56:29'),
  (126,2,25,0,0,0,0,'A','Administrador','2013-09-18 16:49:50','Administrador','2013-09-29 15:56:29'),
  (127,3,24,1,0,0,0,'A','Administrador','2013-09-19 08:49:37','Administrador','2013-09-30 16:36:19'),
  (128,3,9,0,0,0,0,'A','Administrador','2013-09-19 08:49:43','ADMINISTRADOR','2013-09-29 16:00:45'),
  (129,3,10,0,0,0,0,'A','Administrador','2013-09-19 08:49:43','ADMINISTRADOR','2013-09-29 16:00:45'),
  (130,3,11,0,0,0,0,'A','Administrador','2013-09-19 08:49:43','ADMINISTRADOR','2013-09-29 16:00:45'),
  (131,3,12,0,0,0,0,'A','Administrador','2013-09-19 08:49:43','ADMINISTRADOR','2013-09-29 16:00:45'),
  (132,3,13,0,0,0,0,'A','Administrador','2013-09-19 08:49:43','ADMINISTRADOR','2013-09-29 16:00:45'),
  (133,3,23,1,0,0,0,'A','Administrador','2013-09-19 08:49:43','ADMINISTRADOR','2013-09-29 16:00:45'),
  (134,3,25,0,0,0,0,'A','Administrador','2013-09-19 08:49:43','ADMINISTRADOR','2013-09-29 16:00:46'),
  (135,1,26,1,0,0,0,'A','Administrador','2013-09-19 08:57:31','Administrador','2013-09-19 08:57:31'),
  (136,8,26,1,0,0,0,'A','Administrador','2013-09-19 08:57:42','Administrador','2013-09-19 08:57:42'),
  (137,2,26,1,0,0,0,'A','Administrador','2013-09-19 08:57:50','Administrador','2013-09-19 08:57:50'),
  (138,3,26,1,0,0,0,'A','Administrador','2013-09-19 08:57:56','Administrador','2013-09-19 08:57:56'),
  (139,4,26,1,0,0,0,'A','Administrador','2013-09-19 08:58:00','Administrador','2013-09-22 08:23:18'),
  (140,7,26,1,0,0,0,'A','Administrador','2013-09-19 08:58:09','Administrador','2013-09-19 08:58:09'),
  (141,10,26,1,0,0,0,'A','Administrador','2013-09-19 08:58:15','Administrador','2013-09-19 08:58:15'),
  (142,1,27,1,1,1,1,'A','Administrador','2013-09-19 15:55:00','Administrador','2013-09-19 15:55:00'),
  (143,4,18,0,0,0,0,'A','Administrador','2013-09-22 08:23:04','Administrador','2013-09-22 08:23:04'),
  (144,4,19,1,0,0,0,'A','Administrador','2013-09-22 08:23:05','Administrador','2013-09-22 08:23:05'),
  (145,2,27,1,0,0,0,'A','Administrador','2013-09-29 15:56:29','Administrador','2013-09-29 15:56:29'),
  (146,3,27,1,0,0,0,'A','ADMINISTRADOR','2013-09-29 16:00:46','ADMINISTRADOR','2013-09-29 16:00:46'),
  (147,7,24,1,0,0,0,'A','Administrador','2013-09-30 12:28:46','Administrador','2013-09-30 16:36:41'),
  (148,7,27,1,0,0,0,'A','Administrador','2013-09-30 12:28:52','Administrador','2013-09-30 12:28:52'),
  (149,1,28,1,0,0,0,'A','Administrador','2013-09-30 16:35:35','Administrador','2013-10-03 14:19:11'),
  (150,6,24,0,0,0,0,'A','Administrador','2013-09-30 16:35:52','Administrador','2013-09-30 16:35:52'),
  (151,6,28,1,0,0,0,'A','Administrador','2013-09-30 16:35:52','Administrador','2013-09-30 16:35:52'),
  (152,8,24,0,0,0,0,'A','Administrador','2013-09-30 16:36:05','Administrador','2013-09-30 16:36:05'),
  (153,8,28,1,0,0,0,'A','Administrador','2013-09-30 16:36:05','Administrador','2013-09-30 16:36:05'),
  (154,2,28,1,0,0,0,'A','Administrador','2013-09-30 16:36:11','Administrador','2013-09-30 16:36:11'),
  (155,3,28,1,0,0,0,'A','Administrador','2013-09-30 16:36:19','Administrador','2013-09-30 16:36:19'),
  (156,4,28,1,0,0,0,'A','Administrador','2013-09-30 16:36:26','Administrador','2013-09-30 16:36:26'),
  (157,7,28,1,0,0,0,'A','Administrador','2013-09-30 16:36:41','Administrador','2013-09-30 16:36:41'),
  (158,9,24,0,0,0,0,'A','Administrador','2013-09-30 16:36:48','Administrador','2013-09-30 16:36:48'),
  (159,9,28,1,0,0,0,'A','Administrador','2013-09-30 16:36:49','Administrador','2013-09-30 16:36:49'),
  (160,10,28,1,0,0,0,'A','Administrador','2013-09-30 16:36:54','Administrador','2013-09-30 16:36:54'),
  (161,1,29,1,1,1,1,'A','Administrador','2013-10-03 14:19:11','Administrador','2013-10-03 14:19:11'),
  (162,10,29,1,0,0,0,'A','E.guaman.u','2018-09-05 21:55:14','E.guaman.u','2018-09-05 21:55:14'),
  (163,10,27,1,0,0,0,'A','E.guaman.u','2018-09-05 21:55:20','E.guaman.u','2018-09-05 21:55:20');
COMMIT;

#
# Data for the `tbl_usuario` table  (LIMIT 0,500)
#

INSERT INTO `tbl_usuario` (`usu_id`, `tip_user_id`, `per_id`, `usu_cuenta`, `usu_clave`, `grp_id`, `bod_id`, `usu_estado`, `usu_crea`, `usu_fech_crea`, `usu_mod`, `usu_fech_mod`) VALUES 
  (1,1,1,'admin','261719e080bd3f69a181fe2868b08a7986f37249a775c0b26dd38fd930bcf239',NULL,'','A','admin','2013-04-03 18:43:00','Administrador','2013-09-06 10:54:43'),
  (2,4,56,'LENNIN','sLWvwbzF',NULL,'','A','Administrador','2013-08-14 11:52:07','ADMINISTRADOR','2013-08-14 12:00:27'),
  (3,4,18,'XAVIER','vLG3vLjJ',NULL,'','A','Administrador','2013-08-14 11:52:37','Administrador','2013-08-14 11:52:37'),
  (5,2,12,'ES-01','qcOOo6Q=','0000000011','','A','ADMINISTRADOR','2013-08-14 12:01:59','ADMINISTRADOR','2013-08-14 12:01:59'),
  (6,2,13,'ES-02','qcOOo6U=','0000000011','','A','ADMINISTRADOR','2013-08-14 12:16:42','ADMINISTRADOR','2013-08-14 12:16:42'),
  (7,2,14,'ES-03','qcOOo6Y=','0000000011','','A','ADMINISTRADOR','2013-08-14 12:17:07','ADMINISTRADOR','2013-08-14 12:17:07'),
  (8,2,15,'ES-04','qcOOo6c=','0000000011','','A','ADMINISTRADOR','2013-08-14 12:17:50','ADMINISTRADOR','2013-08-14 12:17:50'),
  (9,2,19,'PVJ-001','tMaroKOnoA==','0000000013','0000000022','A','ADMINISTRADOR','2013-08-14 12:23:00','ADMINISTRADOR','2013-08-14 12:23:00'),
  (10,2,20,'PVJ-002','tMaroKOnoQ==','0000000013','0000000022','A','ADMINISTRADOR','2013-08-14 12:23:24','ADMINISTRADOR','2013-08-14 12:23:24'),
  (11,2,21,'PVJ-003','tMaroKOnog==','0000000013','0000000022','A','ADMINISTRADOR','2013-08-14 12:23:44','ADMINISTRADOR','2013-08-14 12:23:44'),
  (12,2,22,'PVJ-004','tMaroKOnow==','0000000013','0000000022','A','ADMINISTRADOR','2013-08-14 12:24:23','ADMINISTRADOR','2013-08-14 12:24:23'),
  (13,2,23,'PVJ-005','tMaroKOnpA==','0000000013','0000000022','A','ADMINISTRADOR','2013-08-14 12:24:55','ADMINISTRADOR','2013-08-14 12:24:55'),
  (14,2,24,'DURANML','qMWztMHEuw==','0000000024','','A','ADMINISTRADOR','2013-08-14 12:26:21','ADMINISTRADOR','2013-08-14 12:26:21'),
  (15,2,25,'Q-001','tZ2Ro6Q=','0000000006','','A','ADMINISTRADOR','2013-08-14 12:27:01','ADMINISTRADOR','2013-08-14 12:27:01'),
  (16,2,26,'Q-002','tZ2Ro6U=','0000000006','','A','ADMINISTRADOR','2013-08-14 12:27:19','ADMINISTRADOR','2013-08-14 12:27:19'),
  (17,2,27,'Q-003','tZ2Ro6Y=','0000000006','','A','ADMINISTRADOR','2013-08-14 12:27:51','ADMINISTRADOR','2013-08-14 12:27:51'),
  (18,2,28,'Q-004','tZ2Ro6c=','0000000006','','A','ADMINISTRADOR','2013-08-14 12:30:51','ADMINISTRADOR','2013-08-14 12:30:51'),
  (19,2,29,'MHL-001','sbitoKOnoA==','0000000010','0000000025','A','ADMINISTRADOR','2013-08-14 12:31:52','ADMINISTRADOR','2013-08-14 12:31:52'),
  (20,2,30,'MHL-002','sbitoKOnoQ==','0000000010','0000000025','A','ADMINISTRADOR','2013-08-14 12:36:34','ADMINISTRADOR','2013-08-14 12:36:34'),
  (21,2,31,'MHL-003','sbitoKOnog==','0000000010','0000000025','A','ADMINISTRADOR','2013-08-14 12:37:05','ADMINISTRADOR','2013-08-14 12:37:05'),
  (22,2,32,'MHL-004','sbitoKOnow==','0000000010','0000000025','A','ADMINISTRADOR','2013-08-14 12:37:35','ADMINISTRADOR','2013-08-14 12:37:35'),
  (23,2,34,'DURAN','qMWztME=','0000000024','','A','ADMINISTRADOR','2013-08-14 12:39:10','ADMINISTRADOR','2013-08-14 12:39:10'),
  (24,2,35,'GYE','q8mm','0000000024','','A','ADMINISTRADOR','2013-08-14 12:39:35','ADMINISTRADOR','2013-08-14 12:39:35'),
  (25,2,36,'70A','m6Ci','0000000024','','A','ADMINISTRADOR','2013-08-14 12:40:12','ADMINISTRADOR','2013-08-14 12:40:12'),
  (26,2,37,'70B','m6Cj','0000000024','','A','ADMINISTRADOR','2013-08-14 12:40:38','ADMINISTRADOR','2013-08-14 12:40:38'),
  (27,2,38,'NSUR','ssO2xQ==','0000000024','','A','ADMINISTRADOR','2013-08-14 12:41:25','ADMINISTRADOR','2013-08-14 12:41:25'),
  (28,2,39,'Z80','vqiR','0000000024','','A','ADMINISTRADOR','2013-08-14 12:42:58','ADMINISTRADOR','2013-08-14 12:42:58'),
  (29,2,40,'PACIFICO','tLGkvLnAssE=','0000000024','','A','ADMINISTRADOR','2013-08-14 12:43:20','ADMINISTRADOR','2013-08-14 12:43:20'),
  (30,2,41,'PERLA','tLWzv7Q=','0000000024','','A','ADMINISTRADOR','2013-08-14 12:43:39','ADMINISTRADOR','2013-08-14 12:43:39'),
  (31,2,42,'SURM','t8WzwA==','0000000024','','A','ADMINISTRADOR','2013-08-14 12:44:01','ADMINISTRADOR','2013-08-14 12:44:01'),
  (32,2,43,'Z90','vqmR','0000000024','','A','ADMINISTRADOR','2013-08-14 12:44:19','ADMINISTRADOR','2013-08-14 12:44:19'),
  (33,2,44,'GOLFO','q7+tucI=','0000000024','','A','ADMINISTRADOR','2013-08-14 12:44:46','ADMINISTRADOR','2013-08-14 12:44:46'),
  (34,2,45,'GUAYAS','q8WizLTK','0000000024','','A','ADMINISTRADOR','2013-08-14 12:45:08','ADMINISTRADOR','2013-08-14 12:45:08'),
  (35,2,46,'SUR','t8Wz','0000000024','','A','ADMINISTRADOR','2013-08-14 12:45:52','ADMINISTRADOR','2013-08-14 12:45:52'),
  (36,2,47,'YOYO','vb+6wg==','0000000024','','A','ADMINISTRADOR','2013-08-14 12:47:18','ADMINISTRADOR','2013-08-14 12:47:18'),
  (37,2,48,'Z70','vqeR','0000000024','','A','ADMINISTRADOR','2013-08-14 12:50:07','ADMINISTRADOR','2013-08-14 12:50:07'),
  (38,2,49,'OFICINAGYE','s7aqtrzFsLm9tQ==','0000000024','','A','ADMINISTRADOR','2013-08-14 12:50:41','ADMINISTRADOR','2013-08-14 12:50:41'),
  (39,3,4,'OFICINA','s7aqtrzFsA==','0000000004','0000000027','A','ADMINISTRADOR','2013-08-14 12:56:09','ADMINISTRADOR','2013-08-14 12:56:09'),
  (40,3,50,'MHLOF','sbitwrk=','0000000010','0000000025','A','ADMINISTRADOR','2013-08-14 12:57:00','ADMINISTRADOR','2013-08-14 12:57:00'),
  (41,3,51,'PVJOF','tMarwrk=','0000000013','0000000022','A','ADMINISTRADOR','2013-08-14 12:57:37','ADMINISTRADOR','2013-08-14 12:57:37'),
  (42,3,52,'Q2OF','taKwuQ==','0000000006','0000000021','A','ADMINISTRADOR','2013-08-14 12:58:12','ADMINISTRADOR','2013-08-14 12:58:12'),
  (43,3,53,'ESOF','qcOwuQ==','0000000011','0000000020','A','ADMINISTRADOR','2013-08-14 12:58:44','ADMINISTRADOR','2013-08-14 12:58:44'),
  (44,3,54,'GYEOF1','q8mmwrmo',NULL,'0000000004','A','ADMINISTRADOR','2013-08-14 13:00:45','ADMINISTRADOR','2013-08-14 13:00:45'),
  (45,3,55,'GYEOF2','q8mmwrmp',NULL,'0000000004','A','ADMINISTRADOR','2013-08-14 13:01:25','ADMINISTRADOR','2013-08-14 13:01:25'),
  (46,2,57,'GUASMO','q8WixsDG','0000000024','','A','ADMINISTRADOR','2013-08-14 13:02:11','ADMINISTRADOR','2013-08-14 13:02:11'),
  (47,2,58,'GUASMOS','q8WixsDGwg==','0000000024','','A','ADMINISTRADOR','2013-08-14 13:31:19','ADMINISTRADOR','2013-08-14 13:31:19'),
  (48,2,59,'ML-001','sbyOo6Oo','0000000004','','A','ADMINISTRADOR','2013-08-14 13:31:49','ADMINISTRADOR','2013-08-14 13:31:49'),
  (49,2,60,'ML-002','sbyOo6Op','0000000004','','A','ADMINISTRADOR','2013-08-14 13:32:07','ADMINISTRADOR','2013-08-14 13:32:07'),
  (50,2,61,'ML-003','sbyOo6Oq','0000000004','','A','ADMINISTRADOR','2013-08-14 13:32:22','ADMINISTRADOR','2013-08-14 13:32:22'),
  (51,2,62,'ML-004','sbyOo6Or','0000000004','','A','ADMINISTRADOR','2013-08-14 13:32:36','ADMINISTRADOR','2013-08-14 13:32:36'),
  (52,2,63,'ML-005','sbyOo6Os','0000000004','','A','ADMINISTRADOR','2013-08-14 13:33:01','ADMINISTRADOR','2013-08-14 13:33:01'),
  (53,2,64,'ML-006','sbyOo6Ot','0000000004','','A','ADMINISTRADOR','2013-08-14 13:33:28','ADMINISTRADOR','2013-08-14 13:33:28'),
  (54,2,65,'ML-007','sbyOo6Ou','0000000004','','A','ADMINISTRADOR','2013-08-14 13:33:48','ADMINISTRADOR','2013-08-14 13:33:48'),
  (55,2,66,'ML-008','sbyOo6Ov','0000000004','','A','ADMINISTRADOR','2013-08-14 13:34:07','ADMINISTRADOR','2013-08-14 13:34:07'),
  (56,2,67,'ML-009','sbyOo6Ow','0000000004','','A','ADMINISTRADOR','2013-08-14 13:34:29','ADMINISTRADOR','2013-08-14 13:34:29'),
  (57,2,68,'LB-001','sLKOo6Oo','0000000012','0000000019','A','ADMINISTRADOR','2013-08-14 13:35:51','ADMINISTRADOR','2013-08-14 13:35:51'),
  (58,2,69,'LB-002','sLKOo6Op','0000000012','0000000019','A','ADMINISTRADOR','2013-08-14 13:36:19','ADMINISTRADOR','2013-08-14 13:36:19'),
  (59,2,70,'PL-001','tLyOo6Oo','0000000022','','A','ADMINISTRADOR','2013-08-14 13:37:03','ADMINISTRADOR','2013-08-14 13:37:03'),
  (60,3,72,'QUIL2','tcWqv6U=','0000000024','','A','ADMINISTRADOR','2013-08-14 13:37:32','ADMINISTRADOR','2013-08-14 13:37:32'),
  (61,3,73,'GYEOF3','q8mmwrmq',NULL,'0000000004','A','ADMINISTRADOR','2013-08-14 13:37:58','ADMINISTRADOR','2013-08-14 13:37:58'),
  (62,3,74,'LBOF','sLKwuQ==','0000000012','0000000019','A','ADMINISTRADOR','2013-08-14 13:38:26','ADMINISTRADOR','2013-08-14 13:38:26'),
  (63,3,75,'PLOF','tLywuQ==','0000000022','0000000024','A','ADMINISTRADOR','2013-08-14 13:38:52','ADMINISTRADOR','2013-08-14 13:38:52'),
  (64,6,76,'CALLCENTER','p7Gtv7a8vcapwg==','','','A','Administrador','2013-08-20 11:24:00','ADMINISTRADOR','2013-08-21 13:36:30'),
  (65,7,2,'FRANKLIN','qsKiwb7DuMA=','','','A','ADMINISTRADOR','2013-08-21 13:31:55','ADMINISTRADOR','2013-08-21 13:31:55'),
  (66,8,77,'h.rodriguez','1t/F5dze5Nfe','','','A','Administrador','2013-08-25 09:13:45','Administrador','2013-08-25 09:13:45'),
  (67,2,8,'MARVIN','sbGzybzF','','','A','Administrador','2013-08-26 12:00:42','Administrador','2013-08-26 12:00:42'),
  (68,4,9,'MARTIN','sbGzx7zF','','','A','Administrador','2013-08-27 11:03:27','Administrador','2013-09-22 08:22:26'),
  (69,9,78,'leonor','0NXQ4eLp','','','A','ADMINISTRADOR','2013-09-01 10:36:31','ADMINISTRADOR','2013-09-01 10:36:31'),
  (70,2,79,'estefa','yePV2NnY','','','A','Administrador','2013-09-01 16:22:37','Administrador','2013-09-01 16:24:36'),
  (71,4,80,'josue','zt/U6Ng=','','','A','Administrador','2013-09-02 10:22:38','Administrador','2013-09-02 10:27:18'),
  (72,10,1,'e.guaman.u','yeLP2Obr3qCcpg==','0000000013','0000000022','A','Administrador','2013-09-06 11:01:29','Administrador','2013-09-06 11:01:29'),
  (73,2,33,'JONATHAN','rr+vtMe/sMA=','','','A','Administrador','2013-09-12 16:33:21','Administrador','2013-09-12 16:33:21'),
  (74,2,81,'DAULE','qLG2v7g=','0000000024','','A','ADMINISTRADOR','2013-09-27 14:03:04','ADMINISTRADOR','2013-09-27 14:03:04'),
  (75,2,82,'Q2-005','taKOo6Os','0000000006','','A','Administrador','2013-10-01 08:55:20','Administrador','2013-10-01 08:55:20');
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;