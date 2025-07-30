-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ahj_ende_pinedah
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cita`
--

DROP TABLE IF EXISTS `cita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cita` (
  `id_cit` int(11) NOT NULL AUTO_INCREMENT,
  `cit_cit` date NOT NULL DEFAULT curdate(),
  `hor_cit` time NOT NULL DEFAULT '08:00:00',
  `nom_cit` varchar(255) DEFAULT NULL,
  `tel_cit` varchar(255) DEFAULT NULL,
  `id_eje2` int(10) unsigned DEFAULT NULL,
  `id_eje_admin` int(10) unsigned DEFAULT NULL COMMENT 'FK referencia al ejecutivo administrativo que atiende la cita',
  `id_eje_admision` int(10) unsigned DEFAULT NULL COMMENT 'FK referencia al ejecutivo de admisión que atiende la cita',
  `eli_cit` int(11) NOT NULL DEFAULT 1 COMMENT '1=visible, 0=oculta',
  `est_cit` varchar(50) DEFAULT 'CITA AGENDADA' COMMENT 'Estatus de la cita',
  `efe_cit` varchar(50) DEFAULT NULL,
  `pla_cit` int(11) DEFAULT NULL COMMENT 'FK al plantel donde se cre├│ la cita',
  PRIMARY KEY (`id_cit`),
  KEY `idx_id_eje2` (`id_eje2`),
  KEY `idx_pla_cit` (`pla_cit`),
  KEY `fk_cita_ejecutivo_admin` (`id_eje_admin`),
  KEY `fk_cita_ejecutivo_admision` (`id_eje_admision`),
  CONSTRAINT `fk_cita_ejecutivo` FOREIGN KEY (`id_eje2`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cita_ejecutivo_admin` FOREIGN KEY (`id_eje_admin`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cita_ejecutivo_admision` FOREIGN KEY (`id_eje_admision`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cita_plantel` FOREIGN KEY (`pla_cit`) REFERENCES `plantel` (`id_pla`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cita`
--

LOCK TABLES `cita` WRITE;
/*!40000 ALTER TABLE `cita` DISABLE KEYS */;
INSERT INTO `cita` VALUES (143,'2025-07-25','09:00:00','sonido','555-2001',2,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',3),(144,'2025-07-25','11:30:00','Entrevista candidato Senior','555-2002',2,NULL,NULL,1,'CITA NO ATENDIDA','CITA EFECTIVA',3),(145,'2025-07-25','14:00:00','Revisión presupuesto anual','555-2003',2,NULL,NULL,1,'PERDIDO POR HORARIO','CITA EFECTIVA',3),(146,'2025-07-25','16:15:00','Capacitación nuevo personal','555-2004',2,NULL,NULL,1,'INVASIÓN DE CICLO',NULL,3),(147,'2025-07-25','10:00:00','Junta directiva mensual','555-2005',4,NULL,NULL,1,'PAGO ESPERADO',NULL,6),(148,'2025-07-25','08:30:00','cita modificada','555-4001',4,NULL,NULL,1,'REGISTRO','CITA NO EFECTIVA',3),(149,'2025-07-25','13:00:00','Reunión con cliente VIP','555-4002',4,NULL,NULL,1,'CITA CONFIRMADA',NULL,3),(150,'2025-07-25','09:45:00','Auditoria sistemas internos','555-4003',NULL,NULL,NULL,1,'CITA AGENDADA',NULL,6),(151,'2025-07-25','15:30:00','Presentación resultados Q2','555-4004',NULL,NULL,NULL,1,'CITA NO ATENDIDA','CITA EFECTIVA',6),(152,'2025-07-25','11:00:00','Workshop innovación digital','555-4005',NULL,NULL,NULL,1,'CITA AGENDADA',NULL,6),(153,'2025-07-25','10:15:00','sonido de cambio','555-6001',NULL,NULL,NULL,1,'CITA NO ATENDIDA',NULL,6),(154,'2025-07-25','14:30:00','Analisis competencia mercado','555-6002',NULL,NULL,NULL,1,'CITA AGENDADA',NULL,2),(155,'2025-07-25','08:00:00','Desayuno ejecutivo networking','555-6003',NULL,NULL,NULL,1,'CITA REAGENDADA','CITA EFECTIVA',2),(156,'2025-07-25','17:00:00','Cierre negociación contrato','555-6004',NULL,NULL,NULL,1,'CITA AGENDADA',NULL,2),(157,'2025-07-25','12:30:00','Revisión métricas performance','555-6005',NULL,NULL,NULL,1,'CITA AGENDADA',NULL,6),(158,'2025-07-25','09:30:00','Supervisión equipo ventas','555-9001',NULL,NULL,NULL,1,'PAGO ESPERADO','CITA EFECTIVA',3),(159,'2025-07-25','12:00:00','Negociación proveedor clave','555-9002',NULL,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',3),(160,'2025-07-25','10:30:00','Evaluación desempeño trimestral','555-9003',NULL,NULL,NULL,1,'CITA AGENDADA',NULL,3),(161,'2025-07-25','16:45:00','Planificación campaña publicitaria','555-9004',9,NULL,NULL,1,'CITA AGENDADA',NULL,3),(162,'2025-07-25','14:00:00','Reunión comité ejecutivo','555-9005',9,NULL,NULL,1,'CITA AGENDADA',NULL,3),(163,'2025-07-25','08:00:00','SONIDO','555-1000',4,NULL,NULL,1,'REGISTRO',NULL,3),(164,'2025-07-25','15:00:00','Entrenamiento liderazgo','555-1002',10,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',2),(165,'2025-07-25','11:15:00','Analisis indicadores KPI','555-1003',10,NULL,NULL,1,'CITA AGENDADA',NULL,2),(166,'2025-07-25','14:45:00','Sesión feedback 360','555-1004',10,NULL,NULL,1,'CITA AGENDADA',NULL,2),(167,'2025-07-25','09:15:00','Planeación estrategica anual','555-1005',6,NULL,NULL,1,'CITA AGENDADA',NULL,6),(168,'2025-07-25','11:00:00','Workshop desarrollo personal','555-1101',11,NULL,NULL,1,'CITA AGENDADA','CITA NO EFECTIVA',6),(169,'2025-07-25','16:30:00','Reunión recursos humanos','555-1102',11,NULL,NULL,1,'CITA AGENDADA',NULL,6),(170,'2025-07-25','09:00:00','Capacitación normativas legales','555-1103',11,NULL,NULL,1,'CITA AGENDADA',NULL,6),(171,'2025-07-25','13:30:00','Evaluación clima organizacional','555-1104',11,NULL,NULL,1,'CITA AGENDADA',NULL,6),(172,'2025-07-25','15:45:00','Sesión coaching ejecutivo','555-1105',11,NULL,NULL,1,'CITA AGENDADA',NULL,6),(173,'2025-07-25','10:45:00','Revisión portafolio inversiones','555-1201',12,NULL,NULL,1,'CITA AGENDADA','CITA NO EFECTIVA',3),(174,'2025-07-25','13:45:00','Analílisis riesgo crediticio','555-1202',12,NULL,NULL,1,'REGISTRO',NULL,3),(175,'2025-07-25','08:15:00','Comité finanzas corporativas','555-1203',12,NULL,NULL,1,'CITA AGENDADA',NULL,3),(176,'2025-07-25','15:00:00','Presentación estados financieros','555-1204',12,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',3),(177,'2025-07-25','11:30:00','Reunión auditoria externa','555-1205',12,NULL,NULL,1,'CITA AGENDADA',NULL,6),(178,'2025-07-25','09:15:00','Sesión innovación tecnológica','555-1301',20,NULL,NULL,1,'NO LE INTERESA','CITA NO EFECTIVA',2),(179,'2025-07-25','14:00:00','Revisión arquitectura sistemas','555-1302',13,NULL,NULL,1,'REGISTRO',NULL,3),(180,'2025-07-25','10:00:00','Workshop metodologías ágiles','555-1303',13,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',3),(181,'2025-07-25','17:30:00','Evaluación herramientas DevOps','555-1304',13,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',3),(182,'2025-07-25','13:00:00','Planeación roadmap tecnologico','555-1305',13,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',3),(183,'2025-07-25','08:45:00','Reunión equipo desarrollo','555-1401',14,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',3),(184,'2025-07-25','12:15:00','SONIDO','555-1402',14,NULL,NULL,1,'NO LE INTERESA','CITA EFECTIVA',3),(185,'2025-07-25','14:30:00','Capacitación nuevas tecnologías','555-1403',14,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',3),(186,'2025-07-25','16:00:00','Analisis performance aplicaciones','555-1404',14,NULL,NULL,1,'CITA NO ATENDIDA','CITA EFECTIVA',3),(187,'2025-07-25','10:30:00','Sesión arquitectura microservicios','555-1405',16,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',3),(188,'2025-07-25','11:45:00','Evaluación satisfacción cliente','555-1501',15,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',2),(189,'2025-07-25','15:30:00','PANKE','555-1502',15,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',2),(190,'2025-07-25','09:30:00','Analisis journey del cliente','555-1503',15,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',2),(191,'2025-07-25','13:00:00','Workshop experiencia usuario','555-1504',15,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',2),(192,'2025-07-25','16:15:00','Reunión equipo customer success','555-1505',15,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',6),(193,'2025-07-25','10:00:00','Supervisión cadena suministro','555-1601',16,NULL,NULL,1,'CITA NO ATENDIDA','CITA EFECTIVA',6),(194,'2025-07-25','13:15:00','Negociación contratos logística','555-1602',16,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',6),(195,'2025-07-25','08:30:00','Optimización procesos distribución','555-1603',16,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',6),(196,'2025-07-25','15:45:00','Evaluación proveedores estrategicos','555-1604',16,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',6),(197,'2025-07-25','12:00:00','Reunión comité operaciones','555-1605',16,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',2),(198,'2025-07-25','09:45:00','Sesión mentoría ejecutiva','555-1801',18,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',6),(199,'2025-07-25','14:15:00','Revisión estrategia corporativa','555-1802',18,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',6),(200,'2025-07-25','11:00:00','Workshop liderazgo transformacional','555-1803',18,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',6),(201,'2025-07-25','16:30:00','Evaluación cultura organizacional','555-1804',18,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',6),(202,'2025-07-25','13:45:00','Planeación sucesión ejecutiva','555-1805',18,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',6),(203,'2025-07-25','08:15:00','Anáílisis mercado internacional','555-2001',20,NULL,NULL,1,'CITA AGENDADA','CITA EFECTIVA',2),(204,'2025-07-25','11:15:00','Estrategia expansión regional','555-2002',20,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',2),(205,'2025-07-25','14:15:00','Evaluación alianzas estrategicas','555-2003',20,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',2),(206,'2025-07-25','17:15:00','Sesión innovación disruptiva','555-2004',20,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',2),(207,'2025-07-25','10:45:00','Reunión consejo administración','555-2005',6,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',3),(208,'2025-07-25','12:30:00','Supervisión seguridad informatica','555-2101',21,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',2),(209,'2025-07-25','16:00:00','Auditoria compliance regulatorio','555-2102',21,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',2),(210,'2025-07-25','09:15:00','Capacitación protección datos','555-2103',21,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',2),(211,'2025-07-25','12:45:00','Evaluación riesgos operacionales','555-2104',21,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',2),(212,'2025-07-25','15:00:00','Reunión comité riesgos','555-2105',21,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',3),(213,'2025-07-25','08:00:00','cita','1111 0',15,11,6,0,'CITA REAGENDADA','CITA EFECTIVA',3),(214,'2025-07-25','09:00:00','nueva citaaaaa','0000',NULL,11,15,0,'REGISTRO','CITA EFECTIVA',3),(215,'2025-07-25','08:00:00','cita','312312',15,4,12,0,'CITA AGENDADA','CITA EFECTIVA',6),(216,'2025-07-25','08:00:00','nueva cita 2','00',12,11,12,0,'REGISTRO','CITA EFECTIVA',2),(217,'2025-07-25','08:00:00',NULL,NULL,9,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',2),(218,'2025-07-25','09:00:00','SONIDO','0000',11,NULL,NULL,1,'REGISTRO','CITA EFECTIVA',6),(219,'2025-07-25','08:00:00','citaaa','000',9,16,6,0,NULL,NULL,3),(220,'2025-07-25','11:11:00','NUEVO CAMBIO','000',11,18,6,1,'CITA NO ATENDIDA','CITA NO EFECTIVA',16),(221,'2025-07-25','23:00:04','PRUEBA P28','555-TEST',2,2,6,1,'CITA AGENDADA',NULL,2),(222,'2025-07-25','09:00:00','Cita eliminada 1','555-0001',11,18,1,0,'CITA AGENDADA',NULL,2),(223,'2025-07-25','10:00:00','muestra solo las citas elimindas','555-0002',4,NULL,NULL,0,'CITA AGENDADA',NULL,6),(224,'2025-07-25','11:00:00','cita','555-0003',2,NULL,NULL,1,'CITA AGENDADA',NULL,6),(225,'2025-07-25','09:00:00','no te deja seleccionar más de una celda a la vez','000',NULL,18,1,0,NULL,NULL,6),(226,'2025-07-25','09:00:00','no se actulizaba por tener mismo id',NULL,NULL,11,6,0,NULL,NULL,6),(227,'2025-07-25','09:00:00','Cita desde el celular',NULL,NULL,NULL,NULL,1,NULL,NULL,6);
/*!40000 ALTER TABLE `cita` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = cp850 */ ;
/*!50003 SET character_set_results = cp850 */ ;
/*!50003 SET collation_connection  = cp850_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER tr_cita_asignar_plantel
BEFORE INSERT ON cita
FOR EACH ROW
BEGIN
    DECLARE plantel_ejecutivo INT DEFAULT NULL;
    
    
    IF NEW.pla_cit IS NULL AND NEW.id_eje2 IS NOT NULL THEN
        SELECT id_pla INTO plantel_ejecutivo
        FROM ejecutivo 
        WHERE id_eje = NEW.id_eje2;
        
        SET NEW.pla_cit = plantel_ejecutivo;
    END IF;
    
    
    IF NEW.pla_cit IS NULL THEN
        SET NEW.pla_cit = 6;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = cp850 */ ;
/*!50003 SET character_set_results = cp850 */ ;
/*!50003 SET collation_connection  = cp850_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER tr_cita_actualizar_plantel
BEFORE UPDATE ON cita
FOR EACH ROW
BEGIN
    DECLARE plantel_ejecutivo INT DEFAULT NULL;
    
    
    
    IF OLD.pla_cit IS NULL AND NEW.pla_cit IS NULL AND NEW.id_eje2 IS NOT NULL THEN
        SELECT id_pla INTO plantel_ejecutivo
        FROM ejecutivo 
        WHERE id_eje = NEW.id_eje2;
        
        SET NEW.pla_cit = plantel_ejecutivo;
    END IF;
    
    
    IF OLD.pla_cit IS NULL AND NEW.pla_cit IS NULL THEN
        SET NEW.pla_cit = 6;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `clase`
--

DROP TABLE IF EXISTS `clase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clase` (
  `id_clase` int(11) NOT NULL AUTO_INCREMENT,
  `id_curso` int(11) NOT NULL COMMENT 'Curso al que pertenece la clase',
  `tit_clase` varchar(200) NOT NULL COMMENT 'Título de la clase',
  `des_clase` text DEFAULT NULL COMMENT 'Descripción de la clase',
  `ord_clase` int(11) NOT NULL DEFAULT 1 COMMENT 'Orden de la clase dentro del curso',
  `id_eje_creador` int(10) unsigned NOT NULL COMMENT 'Ejecutivo que creó la clase',
  `fec_creacion_clase` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación',
  `fec_actualizacion_clase` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Fecha de última actualización',
  `eli_clase` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Borrado lógico (1=activo, 0=eliminado)',
  PRIMARY KEY (`id_clase`),
  KEY `idx_clase_curso` (`id_curso`),
  KEY `idx_clase_creador` (`id_eje_creador`),
  KEY `idx_clase_orden` (`ord_clase`),
  KEY `idx_clase_eliminado` (`eli_clase`),
  CONSTRAINT `fk_clase_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_clase_ejecutivo` FOREIGN KEY (`id_eje_creador`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Clases de los cursos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clase`
--

LOCK TABLES `clase` WRITE;
/*!40000 ALTER TABLE `clase` DISABLE KEYS */;
INSERT INTO `clase` VALUES (1,1,'Bienvenida','Introducción general al sistema y sus funcionalidades',1,1,'2025-07-25 13:00:51',NULL,1),(2,1,'Navegación Básica','Aprende a navegar por las diferentes secciones',2,1,'2025-07-25 13:00:51',NULL,1),(3,1,'Funciones Avanzadas','Características avanzadas del sistema',3,1,'2025-07-25 13:00:51',NULL,1),(4,2,'Crear Citas','Cómo crear y programar nuevas citas',1,2,'2025-07-25 13:00:51',NULL,1),(5,2,'Modificar Citas','Edición y actualización de citas existentes',2,2,'2025-07-25 13:00:51',NULL,1),(6,2,'Reportes de Citas','Generación de reportes sobre citas',3,2,'2025-07-25 13:00:51',NULL,1),(7,2,'Filtros Avanzados','Uso de filtros para búsquedas específicas',4,2,'2025-07-25 13:00:51',NULL,1),(8,2,'Gestión de Estados','Manejo de estados y seguimiento de citas',5,2,'2025-07-25 13:00:51',NULL,1),(9,3,'Reportes Básicos','Introducción a la generación de reportes',1,4,'2025-07-25 13:00:51',NULL,1),(10,3,'Análisis de Datos','Interpretación y análisis de la información',2,4,'2025-07-25 13:00:51',NULL,1),(11,3,'Dashboards','Creación y personalización de dashboards',3,4,'2025-07-25 13:00:51',NULL,1),(12,3,'Exportación','Exportar datos a diferentes formatos',4,4,'2025-07-25 13:00:51',NULL,1),(17,5,'nueva clase de panke','nueva clase 1',1,4,'2025-07-28 13:50:32',NULL,1),(18,5,'nueva clase de panke 2','nueva clase 2',1,4,'2025-07-28 13:50:47',NULL,1),(19,5,'nueva clase de panke 3','nueva clase 3',1,4,'2025-07-28 13:51:01',NULL,1);
/*!40000 ALTER TABLE `clase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colores_celda`
--

DROP TABLE IF EXISTS `colores_celda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colores_celda` (
  `id_color` int(11) NOT NULL AUTO_INCREMENT,
  `id_cit` int(11) NOT NULL,
  `fila_color` int(11) NOT NULL,
  `columna_color` int(11) NOT NULL,
  `color_fondo` varchar(7) NOT NULL,
  `color_texto` varchar(7) DEFAULT '#000000',
  `id_eje_color` int(10) unsigned NOT NULL,
  `fecha_color` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id_color`),
  KEY `fk_color_cita` (`id_cit`),
  KEY `fk_color_ejecutivo` (`id_eje_color`),
  CONSTRAINT `fk_color_cita` FOREIGN KEY (`id_cit`) REFERENCES `cita` (`id_cit`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_color_ejecutivo` FOREIGN KEY (`id_eje_color`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colores_celda`
--

LOCK TABLES `colores_celda` WRITE;
/*!40000 ALTER TABLE `colores_celda` DISABLE KEYS */;
INSERT INTO `colores_celda` VALUES (1,148,2,4,'#ff9800','#ffffff',1,'2025-07-16 12:35:48','2025-07-16 12:36:20',1),(2,198,7,4,'#2196f3','#ffffff',2,'2025-07-16 12:37:18',NULL,1),(3,180,11,4,'#f44336','#ffffff',1,'2025-07-16 12:58:41','2025-07-16 13:53:51',0),(4,159,16,4,'#ffeb3b','#000000',2,'2025-07-16 12:59:20',NULL,1),(5,208,18,4,'#2196f3','#ffffff',2,'2025-07-16 13:03:46',NULL,1),(6,208,18,5,'#ff9800','#ffffff',2,'2025-07-16 13:03:52','2025-07-16 14:02:39',0),(7,168,12,4,'#ff9800','#ffffff',1,'2025-07-16 13:54:08','2025-07-16 13:54:22',1),(8,144,14,4,'#9c27b0','#ffffff',2,'2025-07-16 13:54:18',NULL,1),(9,159,16,2,'#f44336','#ffffff',2,'2025-07-16 13:54:30',NULL,1),(10,174,22,4,'#ffeb3b','#000000',1,'2025-07-16 14:01:14',NULL,1),(11,174,22,3,'#f44336','#ffffff',4,'2025-07-16 14:01:36',NULL,1),(12,154,26,4,'#050505','#67fb04',1,'2025-07-16 14:02:18',NULL,1),(13,154,26,5,'#4caf50','#ffffff',4,'2025-07-16 14:02:23',NULL,1),(14,176,30,3,'#2196f3','#ffffff',4,'2025-07-16 14:02:46',NULL,1),(16,189,29,4,'#2196f3','#ffffff',1,'2025-07-16 18:30:17',NULL,1),(18,176,30,4,'#4caf50','#ffffff',1,'2025-07-16 18:30:44',NULL,1),(20,158,6,2,'#4caf50','#ffffff',4,'2025-07-16 18:31:35',NULL,1),(21,193,8,4,'#2196f3','#ffffff',4,'2025-07-16 18:31:42','2025-07-23 12:27:15',0),(22,206,37,4,'#9c27b0','#ffffff',4,'2025-07-16 18:31:50',NULL,1),(23,156,36,4,'#ff9800','#ffffff',1,'2025-07-16 18:31:57',NULL,1),(24,203,1,7,'#ff9800','#ffffff',1,'2025-07-18 10:36:52',NULL,1),(25,146,35,6,'#2196f3','#ffffff',1,'2025-07-18 14:00:29',NULL,1),(32,215,2,4,'#2196f3','#ffffff',1,'2025-07-21 11:31:40','2025-07-21 13:20:41',0),(33,187,9,4,'#ffeb3b','#000000',1,'2025-07-21 11:31:52',NULL,1),(34,157,17,4,'#f44336','#ffffff',1,'2025-07-21 11:31:59',NULL,1),(35,202,21,4,'#2196f3','#ffffff',1,'2025-07-21 11:35:31',NULL,1),(38,172,29,4,'#f44336','#ffffff',1,'2025-07-21 11:36:54',NULL,1),(39,192,32,4,'#ffeb3b','#000000',1,'2025-07-21 11:37:02',NULL,1),(41,224,13,4,'#2196f3','#ffffff',1,'2025-07-24 13:26:53',NULL,1),(42,220,12,6,'#ff9800','#ffffff',1,'2025-07-24 13:27:01',NULL,1),(43,153,9,4,'#2196f3','#ffffff',1,'2025-07-24 14:24:07',NULL,1),(44,220,12,4,'#ffeb3b','#000000',1,'2025-07-25 12:05:16','2025-07-25 12:28:28',0);
/*!40000 ALTER TABLE `colores_celda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentario_elearning`
--

DROP TABLE IF EXISTS `comentario_elearning`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comentario_elearning` (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `id_contenido` int(11) NOT NULL COMMENT 'Contenido al que pertenece el comentario',
  `tex_comentario` text NOT NULL COMMENT 'Texto del comentario',
  `id_eje_comentario` int(10) unsigned NOT NULL COMMENT 'Ejecutivo que hizo el comentario',
  `id_comentario_padre` int(11) DEFAULT NULL COMMENT 'Comentario padre (para respuestas recursivas)',
  `fec_comentario` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha del comentario',
  `fec_actualizacion_comentario` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Fecha de última actualización',
  `eli_comentario` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Borrado lógico (1=activo, 0=eliminado)',
  PRIMARY KEY (`id_comentario`),
  KEY `idx_comentario_contenido` (`id_contenido`),
  KEY `idx_comentario_ejecutivo` (`id_eje_comentario`),
  KEY `idx_comentario_padre` (`id_comentario_padre`),
  KEY `idx_comentario_fecha` (`fec_comentario`),
  KEY `idx_comentario_eliminado` (`eli_comentario`),
  CONSTRAINT `fk_comentario_contenido` FOREIGN KEY (`id_contenido`) REFERENCES `contenido` (`id_contenido`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_comentario_ejecutivo` FOREIGN KEY (`id_eje_comentario`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_comentario_padre` FOREIGN KEY (`id_comentario_padre`) REFERENCES `comentario_elearning` (`id_comentario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Comentarios recursivos del sistema e-learning';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario_elearning`
--

LOCK TABLES `comentario_elearning` WRITE;
/*!40000 ALTER TABLE `comentario_elearning` DISABLE KEYS */;
INSERT INTO `comentario_elearning` VALUES (1,1,'Excelente explicación en el video. Me ayudó mucho a entender el proceso.',2,NULL,'2025-07-25 13:00:51',NULL,1),(2,2,'El manual está muy completo, gracias por la documentación.',4,NULL,'2025-07-25 13:00:51',NULL,1),(3,1,'Muy útil para los nuevos ejecutivos.',6,NULL,'2025-07-25 13:00:51',NULL,1),(4,1,'Me alegra que te haya sido útil. Si tienes más dudas, no dudes en preguntar.',1,1,'2025-07-25 13:00:51',NULL,1),(5,1,'Totalmente de acuerdo, es una excelente introducción.',9,3,'2025-07-25 13:00:51',NULL,1),(6,2,'Si necesitas ayuda con alguna sección específica, avísame.',1,2,'2025-07-25 13:00:51',NULL,1),(7,1,'comentario nuevo',1,NULL,'2025-07-28 11:36:28',NULL,1),(8,1,'respuesta al nuevo comentario',1,7,'2025-07-28 11:41:43',NULL,1),(9,1,'nuevo comentario',1,NULL,'2025-07-28 13:45:15',NULL,1),(10,1,'respuesta',1,9,'2025-07-28 13:45:21',NULL,1),(11,2,'comentario en pdf',1,NULL,'2025-07-28 13:45:50',NULL,1),(12,1,'nuevo comentario video',1,NULL,'2025-07-28 13:47:04',NULL,1),(13,1,'nuevo comentario video',1,NULL,'2025-07-28 13:47:37',NULL,1),(14,1,'respuesta al nuevo comentario video',1,12,'2025-07-28 13:48:02',NULL,1),(15,2,'reespueta comentario pdf',1,11,'2025-07-28 13:48:24',NULL,1),(16,5,'no aparecen porque no estan configurados los archivos, pero ya se muestra funcional en caso de tener contenido',1,NULL,'2025-07-28 13:49:07',NULL,1),(17,7,'permite agregar videos, pdfs o fotos,',1,NULL,'2025-07-28 13:49:36',NULL,1),(18,7,'dependiendo el numero de clases lo muestra y cambia la barra horizontal',1,NULL,'2025-07-28 13:49:50',NULL,1),(19,1,'se iria agregando el contenido dependiendo a la clase',1,NULL,'2025-07-28 13:51:27',NULL,1);
/*!40000 ALTER TABLE `comentario_elearning` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentarios_cita`
--

DROP TABLE IF EXISTS `comentarios_cita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comentarios_cita` (
  `id_com` int(11) NOT NULL AUTO_INCREMENT,
  `id_cit` int(11) NOT NULL,
  `fila_com` int(11) NOT NULL,
  `columna_com` int(11) NOT NULL,
  `contenido_com` text NOT NULL,
  `id_eje_com` int(10) unsigned NOT NULL,
  `fecha_com` datetime DEFAULT current_timestamp(),
  `fecha_edit_com` datetime DEFAULT NULL,
  `eli_com` int(11) DEFAULT 1 COMMENT '1=visible, 0=eliminado',
  PRIMARY KEY (`id_com`),
  KEY `id_eje_com` (`id_eje_com`),
  KEY `idx_id_cit` (`id_cit`),
  KEY `idx_fila_columna` (`fila_com`,`columna_com`),
  KEY `idx_fecha` (`fecha_com`),
  CONSTRAINT `comentarios_cita_ibfk_1` FOREIGN KEY (`id_cit`) REFERENCES `cita` (`id_cit`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comentarios_cita_ibfk_2` FOREIGN KEY (`id_eje_com`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentarios_cita`
--

LOCK TABLES `comentarios_cita` WRITE;
/*!40000 ALTER TABLE `comentarios_cita` DISABLE KEYS */;
INSERT INTO `comentarios_cita` VALUES (1,214,4,4,'b',1,'2025-07-16 11:14:45','2025-07-16 11:32:47',1),(2,216,1,4,'comentario jeje',1,'2025-07-16 11:34:44','2025-07-16 11:35:15',1),(3,216,1,5,'comentario',1,'2025-07-16 11:36:20',NULL,1),(4,203,1,4,'comentario sobre el nombre',2,'2025-07-16 11:42:13',NULL,1),(5,143,4,4,'comentario en nombre',1,'2025-07-16 13:54:51',NULL,1),(6,143,4,3,'comentario en hora',1,'2025-07-16 13:55:24',NULL,1),(7,174,22,4,'nuevo comentario en el nombre',1,'2025-07-16 14:01:24',NULL,1),(8,174,22,3,'nuevo comentario en la hora',4,'2025-07-16 14:01:48',NULL,1);
/*!40000 ALTER TABLE `comentarios_cita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contenido`
--

DROP TABLE IF EXISTS `contenido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contenido` (
  `id_contenido` int(11) NOT NULL AUTO_INCREMENT,
  `id_clase` int(11) NOT NULL COMMENT 'Clase a la que pertenece el contenido',
  `tit_contenido` varchar(200) NOT NULL COMMENT 'Título del contenido',
  `tip_contenido` enum('video_archivo','video_youtube','audio','pdf','imagen') NOT NULL COMMENT 'Tipo de contenido',
  `arc_contenido` varchar(255) DEFAULT NULL COMMENT 'Nombre del archivo (para tipos archivo)',
  `url_contenido` text DEFAULT NULL COMMENT 'URL del contenido (para YouTube)',
  `des_contenido` text DEFAULT NULL COMMENT 'Descripción del contenido',
  `ord_contenido` int(11) NOT NULL DEFAULT 1 COMMENT 'Orden del contenido dentro de la clase',
  `id_eje_creador` int(10) unsigned NOT NULL COMMENT 'Ejecutivo que creó el contenido',
  `fec_creacion_contenido` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación',
  `fec_actualizacion_contenido` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Fecha de última actualización',
  `eli_contenido` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Borrado lógico (1=activo, 0=eliminado)',
  PRIMARY KEY (`id_contenido`),
  KEY `idx_contenido_clase` (`id_clase`),
  KEY `idx_contenido_tipo` (`tip_contenido`),
  KEY `idx_contenido_creador` (`id_eje_creador`),
  KEY `idx_contenido_orden` (`ord_contenido`),
  KEY `idx_contenido_eliminado` (`eli_contenido`),
  CONSTRAINT `fk_contenido_clase` FOREIGN KEY (`id_clase`) REFERENCES `clase` (`id_clase`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_contenido_ejecutivo` FOREIGN KEY (`id_eje_creador`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contenido multimedia de las clases';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contenido`
--

LOCK TABLES `contenido` WRITE;
/*!40000 ALTER TABLE `contenido` DISABLE KEYS */;
INSERT INTO `contenido` VALUES (1,1,'Video Introductorio','video_youtube',NULL,'https://www.youtube.com/embed/XLLkSBLqlvQ?si=ENTBIh8jACdCCtQX','Introducción general al sistema de gestión',1,1,'2025-07-25 13:00:51','2025-07-28 11:17:02',1),(2,1,'Manual de Usuario','pdf','manual_usuario.pdf',NULL,'Documentación completa del sistema',2,1,'2025-07-25 13:00:51',NULL,1),(3,2,'Guía de Navegación','pdf','guia_navegacion.pdf',NULL,'Manual paso a paso para navegar en el sistema',1,1,'2025-07-25 13:00:51',NULL,1),(4,2,'Tutorial de Menús','video_youtube',NULL,'https://www.youtube.com/embed/XLLkSBLqlvQ?si=ENTBIh8jACdCCtQX','Explicación de los menús principales',2,1,'2025-07-25 13:00:51','2025-07-28 11:17:02',1),(5,3,'Configuraciones Avanzadas','pdf','config_avanzadas.pdf',NULL,'Guía de configuraciones especializadas',1,1,'2025-07-25 13:00:51',NULL,1),(6,4,'Video Tutorial - Crear Citas','video_youtube',NULL,'https://www.youtube.com/embed/XLLkSBLqlvQ?si=ENTBIh8jACdCCtQX','Proceso completo de creación de citas',1,2,'2025-07-25 13:00:51','2025-07-28 11:17:02',1),(7,4,'Formulario de Citas','imagen','formulario_citas.png',NULL,'Vista del formulario de creación',2,2,'2025-07-25 13:00:51',NULL,1);
/*!40000 ALTER TABLE `contenido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `curso`
--

DROP TABLE IF EXISTS `curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL AUTO_INCREMENT,
  `nom_curso` varchar(200) NOT NULL COMMENT 'Nombre del curso',
  `des_curso` text DEFAULT NULL COMMENT 'Descripción del curso',
  `id_eje_creador` int(10) unsigned NOT NULL COMMENT 'Ejecutivo que creó el curso',
  `fec_creacion_curso` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación',
  `fec_actualizacion_curso` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Fecha de última actualización',
  `eli_curso` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Borrado lógico (1=activo, 0=eliminado)',
  PRIMARY KEY (`id_curso`),
  KEY `idx_curso_creador` (`id_eje_creador`),
  KEY `idx_curso_eliminado` (`eli_curso`),
  CONSTRAINT `fk_curso_ejecutivo` FOREIGN KEY (`id_eje_creador`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Cursos del sistema de e-learning';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curso`
--

LOCK TABLES `curso` WRITE;
/*!40000 ALTER TABLE `curso` DISABLE KEYS */;
INSERT INTO `curso` VALUES (1,'Introducción al Sistema','Curso básico para nuevos ejecutivos del sistema SICAM',1,'2025-07-25 13:00:51',NULL,1),(2,'Gestión de Citas','Manejo avanzado del sistema de citas y seguimiento',2,'2025-07-25 13:00:51',NULL,1),(3,'Reportes y Análisis','Generación de reportes y análisis de datos del sistema',4,'2025-07-25 13:00:51',NULL,1),(5,'nuevo curso','descripcion del nuevo curso',4,'2025-07-28 13:47:16',NULL,1);
/*!40000 ALTER TABLE `curso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ejecutivo`
--

DROP TABLE IF EXISTS `ejecutivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ejecutivo` (
  `id_eje` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_eje` varchar(100) NOT NULL,
  `tel_eje` varchar(15) NOT NULL,
  `fot_eje` varchar(500) DEFAULT NULL COMMENT 'Nombre del archivo de la foto del ejecutivo',
  `eli_eje` int(11) NOT NULL DEFAULT 1 COMMENT '1=visible, 0=oculto',
  `id_padre` int(10) unsigned DEFAULT NULL COMMENT 'FK para relaci??n jer??rquica',
  `id_pla` int(11) DEFAULT NULL,
  `ult_eje` datetime DEFAULT NULL COMMENT '┌ltima fecha y hora de sesi¾n del ejecutivo',
  `tipo` enum('Administrativo','Admisión') NOT NULL DEFAULT 'Admisión' COMMENT 'Tipo de ejecutivo: Administrativo o Admisión',
  PRIMARY KEY (`id_eje`),
  KEY `idx_eli_eje` (`eli_eje`),
  KEY `idx_id_padre` (`id_padre`),
  KEY `idx_id_pla` (`id_pla`),
  KEY `idx_tipo` (`tipo`),
  CONSTRAINT `fk_ejecutivo_padre` FOREIGN KEY (`id_padre`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_ejecutivo_plantel` FOREIGN KEY (`id_pla`) REFERENCES `plantel` (`id_pla`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ejecutivo`
--

LOCK TABLES `ejecutivo` WRITE;
/*!40000 ALTER TABLE `ejecutivo` DISABLE KEYS */;
INSERT INTO `ejecutivo` VALUES (1,'Juan Carlos Pérez','555-0123',NULL,1,16,6,'2025-07-25 10:52:34','Admisión'),(2,'María Fernanda López','555-0456',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(4,'Francisco Pineda','11',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(6,'Fatima Nava','555-111',NULL,1,4,3,'2025-07-25 10:52:34','Admisión'),(9,'Ana Garcia Silva','555-2001',NULL,1,NULL,16,'2025-07-25 10:52:34','Admisión'),(10,'Luis Rodriguez','555-2002',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(11,'Carmen Morales Vega','555-2003',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(12,'Diego Herrera Luna','555-3001',NULL,1,NULL,2,'2025-07-25 10:52:34','Admisión'),(13,'Sofia Mendoza','555-3002',NULL,1,2,2,'2025-07-25 10:52:34','Admisión'),(14,'Pablo Jimenez','555-3003',NULL,1,NULL,2,'2025-07-25 10:52:34','Admisión'),(15,'Elena Vargas','555-6001',NULL,1,NULL,3,'2025-07-25 10:52:34','Admisión'),(16,'Andres Castro','555-6002',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(18,'Eric Valenzuela','111-111',NULL,1,15,3,'2025-07-25 10:52:34','Administrativo'),(20,'Samuel Ortigoza','1111-111',NULL,1,NULL,13,'2025-07-25 10:52:34','Admisión'),(21,'Rodrigo Ramirez','0000-111',NULL,1,NULL,6,'2025-07-25 10:52:34','Admisión'),(47,'Director General Naucalpan','555-2001',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(48,'Coordinador Académico Naucalpan','555-2002',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(49,'Jefe de Admisiones Naucalpan','555-2003',NULL,1,NULL,2,'2025-07-25 10:52:34','Admisión'),(50,'Subdirector Administrativo Naucalpan','555-2011',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(51,'Gerente de Recursos Humanos Naucalpan','555-2012',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(52,'Coordinador de Servicios Escolares','555-2013',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(53,'Supervisor de Admisiones Naucalpan','555-2014',NULL,1,NULL,2,'2025-07-25 10:52:34','Admisión'),(54,'Asistente Administrativo 1 Naucalpan','555-2021',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(55,'Asistente Administrativo 2 Naucalpan','555-2022',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(56,'Especialista en Nóminas Naucalpan','555-2023',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(57,'Ejecutivo de Servicios Escolares 1','555-2024',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(58,'Ejecutivo de Servicios Escolares 2','555-2025',NULL,1,NULL,2,'2025-07-25 10:52:34','Administrativo'),(59,'Asesor de Admisiones 1 Naucalpan','555-2026',NULL,1,NULL,2,'2025-07-25 10:52:34','Admisión'),(60,'Asesor de Admisiones 2 Naucalpan','555-2027',NULL,1,NULL,2,'2025-07-25 10:52:34','Admisión'),(61,'Asesor de Admisiones 3 Naucalpan','555-2028',NULL,1,NULL,2,'2025-07-25 10:52:34','Admisión'),(62,'Ejecutivo de Atención al Cliente Naucalpan','555-2029',NULL,1,NULL,2,'2025-07-25 10:52:34','Admisión'),(63,'Director General Ecatepec','555-3001',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(64,'Coordinador Académico Ecatepec','555-3002',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(65,'Jefe de Admisiones Ecatepec','555-3003',NULL,1,NULL,3,'2025-07-25 10:52:34','Admisión'),(66,'Subdirector Académico Ecatepec','555-3011',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(67,'Gerente de Operaciones Ecatepec','555-3012',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(68,'Coordinador de Programas Ecatepec','555-3013',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(69,'Supervisor de Procesos Ecatepec','555-3014',NULL,1,NULL,3,'2025-07-25 10:52:34','Admisión'),(70,'Especialista Académico 1 Ecatepec','555-3021',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(71,'Especialista Académico 2 Ecatepec','555-3022',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(72,'Analista de Operaciones Ecatepec','555-3023',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(73,'Coordinador de Eventos Ecatepec','555-3024',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(74,'Asesor Educativo 1 Ecatepec','555-3025',NULL,1,NULL,3,'2025-07-25 10:52:34','Administrativo'),(75,'Asesor de Inscripciones 1 Ecatepec','555-3026',NULL,1,NULL,3,'2025-07-25 10:52:34','Admisión'),(76,'Asesor de Inscripciones 2 Ecatepec','555-3027',NULL,1,NULL,3,'2025-07-25 10:52:34','Admisión'),(77,'Ejecutivo de Seguimiento Ecatepec','555-3028',NULL,1,NULL,3,'2025-07-25 10:52:34','Admisión'),(78,'Representante de Ventas Ecatepec','555-3029',NULL,1,NULL,3,'2025-07-25 10:52:34','Admisión'),(79,'Director General Cuautitlán','555-6001',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(80,'Coordinador Académico Cuautitlán','555-6002',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(81,'Jefe de Admisiones Cuautitlán','555-6003',NULL,1,NULL,6,'2025-07-25 10:52:34','Admisión'),(82,'Subdirector de Finanzas Cuautitlán','555-6011',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(83,'Gerente de Tecnología Cuautitlán','555-6012',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(84,'Coordinador de Calidad Cuautitlán','555-6013',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(85,'Supervisor de Captación Cuautitlán','555-6014',NULL,1,NULL,6,'2025-07-25 10:52:34','Admisión'),(86,'Contador General Cuautitlán','555-6021',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(87,'Asistente Contable Cuautitlán','555-6022',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(88,'Técnico en Sistemas Cuautitlán','555-6023',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(89,'Auditor de Calidad Cuautitlán','555-6024',NULL,1,NULL,6,'2025-07-25 10:52:34','Administrativo'),(90,'Analista de Procesos Cuautitlán','555-6025','foto-ejecutivo-90-f22ed374af2844367fb75b6366b3c05f3136c004.png',1,NULL,16,'2025-07-25 10:52:34','Administrativo'),(91,'Ejecutivo de Captación 1 Cuautitlán','555-6026',NULL,1,NULL,6,'2025-07-25 10:52:34','Admisión'),(92,'Ejecutivo de Captación 2 Cuautitlán','555-6027',NULL,1,NULL,6,'2025-07-25 10:52:34','Admisión'),(93,'Promotor Educativo Cuautitlán','555-6028',NULL,1,NULL,6,'2025-07-25 10:52:34','Admisión'),(94,'Asesor Comercial Cuautitlán','555-6029',NULL,1,NULL,6,'2025-07-25 10:52:34','Admisión'),(95,'Director Regional Querétaro','555-8001',NULL,1,NULL,8,'2025-07-25 10:52:34','Administrativo'),(96,'Coordinador Académico Querétaro','555-8002',NULL,1,NULL,8,'2025-07-25 10:52:34','Administrativo'),(97,'Jefe de Admisiones Querétaro','555-8003',NULL,1,NULL,8,'2025-07-25 10:52:34','Admisión'),(98,'Subdirector Operativo Querétaro','555-8011',NULL,1,NULL,8,'2025-07-25 10:52:34','Administrativo'),(99,'Gerente Regional Querétaro','555-8012',NULL,1,NULL,8,'2025-07-25 10:52:34','Administrativo'),(100,'Coordinador de Desarrollo Querétaro','555-8013',NULL,1,NULL,8,'2025-07-25 10:52:34','Administrativo'),(101,'Supervisor Regional Querétaro','555-8014',NULL,1,NULL,8,'2025-07-25 10:52:34','Admisión'),(102,'Coordinador de Proyectos Querétaro','555-8021',NULL,1,NULL,8,'2025-07-25 10:52:34','Administrativo'),(103,'Especialista en Logística Querétaro','555-8022',NULL,1,NULL,8,'2025-07-25 10:52:34','Administrativo'),(104,'Analista Regional Querétaro','555-8023',NULL,1,NULL,8,'2025-07-25 10:52:34','Administrativo'),(105,'Coordinador de Capacitación Querétaro','555-8024',NULL,1,NULL,8,'2025-07-25 10:52:34','Administrativo'),(106,'Instructor Especializado Querétaro','555-8025',NULL,1,NULL,8,'2025-07-25 10:52:34','Administrativo'),(107,'Consultor de Admisiones 1 Querétaro','555-8026',NULL,1,NULL,8,'2025-07-25 10:52:34','Admisión'),(108,'Consultor de Admisiones 2 Querétaro','555-8027',NULL,1,NULL,8,'2025-07-25 10:52:34','Admisión'),(109,'Especialista en Mercadotecnia Querétaro','555-8028',NULL,1,NULL,8,'2025-07-25 10:52:34','Admisión'),(110,'Ejecutivo de Relaciones Públicas Querétaro','555-8029',NULL,1,NULL,8,'2025-07-25 10:52:34','Admisión'),(111,'Director General Pachuca','555-9001',NULL,1,NULL,9,'2025-07-25 10:52:34','Administrativo'),(112,'Coordinador Académico Pachuca','555-9002',NULL,1,NULL,9,'2025-07-25 10:52:34','Administrativo'),(113,'Jefe de Admisiones Pachuca','555-9003',NULL,1,NULL,9,'2025-07-25 10:52:34','Admisión'),(114,'Subdirector de Expansión Pachuca','555-9011',NULL,1,NULL,9,'2025-07-25 10:52:34','Administrativo'),(115,'Gerente de Nuevos Negocios Pachuca','555-9012',NULL,1,NULL,9,'2025-07-25 10:52:34','Administrativo'),(116,'Coordinador de Innovación Pachuca','555-9013',NULL,1,NULL,9,'2025-07-25 10:52:34','Administrativo'),(117,'Supervisor de Territorio Pachuca','555-9014',NULL,1,NULL,9,'2025-07-25 10:52:34','Admisión'),(118,'Coordinador de Expansión Pachuca','555-9021',NULL,1,NULL,9,'2025-07-25 10:52:34','Administrativo'),(119,'Analista de Mercados Pachuca','555-9022','foto-ejecutivo-119-d60e1b285785dc234ca117ff7abfe3915cbaa679.png',1,NULL,16,'2025-07-25 10:52:34','Administrativo'),(120,'Especialista en Desarrollo Pachuca','555-9023',NULL,1,NULL,9,'2025-07-25 10:52:34','Administrativo'),(121,'Coordinador de Investigación Pachuca','555-9024',NULL,1,NULL,9,'2025-07-25 10:52:34','Administrativo'),(122,'Analista de Innovación Pachuca','555-9025',NULL,1,NULL,9,'2025-07-25 10:52:34','Administrativo'),(123,'Ejecutivo Territorial 1 Pachuca','555-9026',NULL,1,NULL,9,'2025-07-25 10:52:34','Admisión'),(124,'Ejecutivo Territorial 2 Pachuca','555-9027',NULL,1,NULL,9,'2025-07-25 10:52:34','Admisión'),(125,'Representante de Zona Pachuca','555-9028',NULL,1,NULL,9,'2025-07-25 10:52:34','Admisión'),(126,'Coordinador de Campo Pachuca','555-9029',NULL,1,112,9,'2025-07-25 10:52:34','Admisión'),(127,'Director Regional San Luis Potosí','555-1301',NULL,1,NULL,13,'2025-07-25 10:52:34','Administrativo'),(128,'Coordinador Académico San Luis Potosí','555-1302',NULL,1,NULL,13,'2025-07-25 10:52:34','Administrativo'),(129,'Jefe de Admisiones San Luis Potosí','555-1303',NULL,1,NULL,13,'2025-07-25 10:52:34','Admisión'),(130,'Subdirector de Estrategia San Luis Potosí','555-1311',NULL,1,NULL,13,'2025-07-25 10:52:34','Administrativo'),(131,'Gerente de Comunicaciones San Luis Potosí','555-1312',NULL,1,NULL,13,'2025-07-25 10:52:34','Administrativo'),(132,'Coordinador de Excelencia San Luis Potosí','555-1313',NULL,1,NULL,13,'2025-07-25 10:52:34','Administrativo'),(133,'Supervisor de Estrategias San Luis Potosí','555-1314',NULL,1,NULL,13,'2025-07-25 10:52:34','Admisión'),(134,'Planificador Estratégico San Luis Potosí','555-1321',NULL,1,NULL,13,'2025-07-25 10:52:34','Administrativo'),(135,'Analista de Tendencias San Luis Potosí','555-1322',NULL,1,NULL,13,'2025-07-25 10:52:34','Administrativo'),(136,'Especialista en Comunicación San Luis Potosí','555-1323',NULL,1,NULL,13,'2025-07-25 10:52:34','Administrativo'),(137,'Coordinador de Estándares San Luis Potosí','555-1324',NULL,1,NULL,13,'2025-07-25 10:52:34','Administrativo'),(138,'Auditor de Excelencia San Luis Potosí','555-1325',NULL,1,NULL,13,'2025-07-25 10:52:34','Administrativo'),(139,'Ejecutivo Estratégico 1 San Luis Potosí','555-1326',NULL,1,NULL,13,'2025-07-25 10:52:34','Admisión'),(140,'Ejecutivo Estratégico 2 San Luis Potosí','555-1327',NULL,0,NULL,13,NULL,'Admisión'),(141,'Consultor de Posicionamiento San Luis Potosí','555-1328',NULL,0,NULL,13,NULL,'Admisión'),(142,'Especialista en Captación San Luis Potosí','555-1329',NULL,0,NULL,13,NULL,'Admisión'),(143,'Director General CDMX Portales','555-1601',NULL,0,NULL,16,NULL,'Administrativo'),(144,'Coordinador Académico CDMX Portales','555-1602',NULL,0,NULL,16,NULL,'Administrativo'),(145,'Jefe de Admisiones CDMX Portales','555-1603',NULL,0,NULL,16,NULL,'Admisión'),(146,'Subdirector Corporativo CDMX Portales','555-1611',NULL,0,NULL,16,NULL,'Administrativo'),(147,'Gerente de Alianzas CDMX Portales','555-1612',NULL,0,NULL,16,NULL,'Administrativo'),(148,'Coordinador de Transformación CDMX Portales','555-1613',NULL,0,NULL,16,NULL,'Administrativo'),(149,'Supervisor de Mercados CDMX Portales','555-1614',NULL,0,NULL,16,NULL,'Admisión'),(150,'Coordinador Corporativo CDMX Portales','555-1621',NULL,0,NULL,16,NULL,'Administrativo'),(151,'Especialista en Compliance CDMX Portales','555-1622',NULL,0,NULL,16,NULL,'Administrativo'),(152,'Gestor de Alianzas CDMX Portales','555-1623',NULL,0,NULL,16,NULL,'Administrativo'),(153,'Coordinador de Cambio CDMX Portales','555-1624',NULL,0,NULL,16,NULL,'Administrativo'),(154,'Analista de Transformación CDMX Portales','555-1625',NULL,0,NULL,16,NULL,'Administrativo'),(155,'Ejecutivo de Mercado 1 CDMX Portales','555-1626',NULL,0,NULL,16,NULL,'Admisión'),(156,'Ejecutivo de Mercado 2 CDMX Portales','555-1627',NULL,0,NULL,16,NULL,'Admisión'),(157,'Analista de Penetración CDMX Portales','555-1628','foto-ejecutivo-157-7888ec1a399584b8301cdb46fde2226692fcf606.png',0,NULL,16,NULL,'Admisión'),(158,'Especialista en Segmentación CDMX Portales','555-1629',NULL,0,NULL,16,NULL,'Admisión');
/*!40000 ALTER TABLE `ejecutivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_cita`
--

DROP TABLE IF EXISTS `historial_cita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historial_cita` (
  `id_his_cit` int(11) NOT NULL AUTO_INCREMENT,
  `fec_his_cit` datetime NOT NULL DEFAULT current_timestamp(),
  `res_his_cit` varchar(100) NOT NULL,
  `mov_his_cit` enum('alta','cambio','baja') NOT NULL,
  `des_his_cit` text NOT NULL,
  `id_cit11` int(11) NOT NULL,
  PRIMARY KEY (`id_his_cit`),
  KEY `idx_id_cit11` (`id_cit11`),
  KEY `idx_fec_his_cit` (`fec_his_cit`),
  CONSTRAINT `historial_cita_ibfk_1` FOREIGN KEY (`id_cit11`) REFERENCES `cita` (`id_cit`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=304 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_cita`
--

LOCK TABLES `historial_cita` WRITE;
/*!40000 ALTER TABLE `historial_cita` DISABLE KEYS */;
INSERT INTO `historial_cita` VALUES (23,'2025-07-11 11:11:11','Sofia Mendoza','cambio','Se modificó NOM CIT de \'Revisi├│n procesos operativos\' a \'Revisión procesos operativos\' en la cita \'Revisi├│n procesos operativos\'',163),(24,'2025-07-11 11:26:18','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'An├ílisis mercado internacional\' a \'Anáílisis mercado internacional\' en la cita \'An├ílisis mercado internacional\'',203),(25,'2025-07-11 11:26:24','Sofia Mendoza','cambio','Se modificó NOM CIT de \'Evaluaci├│n proyecto Alpha\' a \'Evaluación proyecto Alpha\' en la cita \'Evaluaci├│n proyecto Alpha\'',148),(26,'2025-07-11 11:26:30','Elena Vargas','cambio','Se modificó NOM CIT de \'Reuni├│n equipo desarrollo\' a \'Reunión equipo desarrollo\' en la cita \'Reuni├│n equipo desarrollo\'',183),(27,'2025-07-11 11:26:39','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'Reuni├│n de planificaci├│n Q3\' a \'Reunión de planificación Q3\' en la cita \'Reuni├│n de planificaci├│n Q3\'',143),(28,'2025-07-11 11:26:48','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'Sesi├│n innovaci├│n tecnol├│gica\' a \'Sesión innovación tecnológica\' en la cita \'Sesi├│n innovaci├│n tecnol├│gica\'',178),(29,'2025-07-11 11:26:54','Fatima Nava','cambio','Se modificó NOM CIT de \'Supervisi├│n equipo ventas\' a \'Supervisión equipo ventas\' en la cita \'Supervisi├│n equipo ventas\'',158),(30,'2025-07-11 11:27:01','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'Sesi├│n mentor├¡a ejecutiva\' a \'Sesión mentoría ejecutiva\' en la cita \'Sesi├│n mentor├¡a ejecutiva\'',198),(31,'2025-07-11 11:27:05','Francisco Pineda','cambio','Se modificó NOM CIT de \'Supervisi├│n cadena suministro\' a \'Supervisión cadena suministro\' en la cita \'Supervisi├│n cadena suministro\'',193),(32,'2025-07-11 11:27:09','Sofia Mendoza','cambio','Se modificó NOM CIT de \'Sesi├│n estrategia marketing\' a \'Sesión estrategia marketing\' en la cita \'Sesi├│n estrategia marketing\'',153),(33,'2025-07-11 11:27:13','Carmen Morales Vega','cambio','Se modificó NOM CIT de \'Revisi├│n portafolio inversiones\' a \'Revisión portafolio inversiones\' en la cita \'Revisi├│n portafolio inversiones\'',173),(34,'2025-07-11 11:27:18','Ana Garcia Silva','cambio','Se modificó NOM CIT de \'Estrategia expansi├│n regional\' a \'Estrategia expansión regional\' en la cita \'Estrategia expansi├│n regional\'',204),(35,'2025-07-11 11:27:31','Francisco Pineda Hernández','cambio','Se modificó NOM CIT de \'Evaluaci├│n satisfacci├│n cliente\' a \'Evaluaci├│n satisfacción cliente\' en la cita \'Evaluaci├│n satisfacci├│n cliente\'',188),(36,'2025-07-11 11:27:36','Fatima Nava','cambio','Se modificó NOM CIT de \'Evaluaci├│n satisfacción cliente\' a \'Evaluación satisfacción cliente\' en la cita \'Evaluaci├│n satisfacción cliente\'',188),(37,'2025-07-11 11:27:41','Francisco Pineda','cambio','Se modificó NOM CIT de \'Negociaci├│n proveedor clave\' a \'Negociación proveedor clave\' en la cita \'Negociaci├│n proveedor clave\'',159),(38,'2025-07-11 11:27:44','Pablo Jimenez','cambio','Se modificó NOM CIT de \'Revisi├│n calidad software\' a \'Revisión calidad software\' en la cita \'Revisi├│n calidad software\'',184),(39,'2025-07-11 11:27:58','Francisco Pineda','cambio','Se modificó NOM CIT de \'Supervisi├│n seguridad inform├ítica\' a \'Supervisión seguridad informatica\' en la cita \'Supervisi├│n seguridad inform├ítica\'',208),(40,'2025-07-11 11:28:04','María Fernanda López','cambio','Se modificó NOM CIT de \'Reuni├│n con cliente VIP\' a \'Reunión con cliente VIP\' en la cita \'Reuni├│n con cliente VIP\'',149),(41,'2025-07-11 11:28:12','Luis Rodriguez','cambio','Se modificó NOM CIT de \'Negociaci├│n contratos log├¡stica\' a \'Negociación contratos logística\' en la cita \'Negociaci├│n contratos log├¡stica\'',194),(42,'2025-07-11 11:28:22','Diego Herrera Luna','cambio','Se modificó NOM CIT de \'An├ílisis riesgo crediticio\' a \'Analílisis riesgo crediticio\' en la cita \'An├ílisis riesgo crediticio\'',174),(43,'2025-07-11 11:28:28','Sofia Mendoza','cambio','Se modificó NOM CIT de \'Revisi├│n arquitectura sistemas\' a \'Revisión arquitectura sistemas\' en la cita \'Revisi├│n arquitectura sistemas\'',179),(44,'2025-07-11 11:28:32','Samuel Ortigoza','cambio','Se modificó NOM CIT de \'Revisi├│n estrategia corporativa\' a \'Revisión estrategia corporativa\' en la cita \'Revisi├│n estrategia corporativa\'',199),(45,'2025-07-11 11:28:38','Elena Vargas','cambio','Se modificó NOM CIT de \'An├ílisis competencia mercado\' a \'Analisis competencia mercado\' en la cita \'An├ílisis competencia mercado\'',154),(46,'2025-07-11 11:28:44','María Fernanda López','cambio','Se modificó NOM CIT de \'Estrategia retenci├│n clientes\' a \'Estrategia retención clientes\' en la cita \'Estrategia retenci├│n clientes\'',189),(47,'2025-07-11 11:28:49','Rodrigo Ramirez','cambio','Se modificó NOM CIT de \'Reuni├│n recursos humanos\' a \'Reunión recursos humanos\' en la cita \'Reuni├│n recursos humanos\'',169),(48,'2025-07-11 11:29:09','Fatima Nava','cambio','Se modificó NOM CIT de \'Comit├® finanzas corporativas\' a \'Comité finanzas corporativas\' en la cita \'Comit├® finanzas corporativas\'',175),(49,'2025-07-11 11:29:12','Samuel Ortigoza','cambio','Se modificó NOM CIT de \'Optimizaci├│n procesos distribuci├│n\' a \'Optimizaci├│n procesos distribución\' en la cita \'Optimizaci├│n procesos distribuci├│n\'',195),(50,'2025-07-11 11:29:17','Rodrigo Ramirez','cambio','Se modificó NOM CIT de \'Optimizaci├│n procesos distribución\' a \'Optimización procesos distribución\' en la cita \'Optimizaci├│n procesos distribución\'',195),(51,'2025-07-11 11:29:22','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'Capacitaci├│n normativas legales\' a \'Capacitación normativas legales\' en la cita \'Capacitaci├│n normativas legales\'',170),(52,'2025-07-11 11:29:29','Rodrigo Ramirez','cambio','Se modificó NOM CIT de \'Capacitaci├│n protecci├│n datos\' a \'Capacitación protección datos\' en la cita \'Capacitaci├│n protecci├│n datos\'',210),(53,'2025-07-11 11:29:38','Pablo Jimenez','cambio','Se modificó NOM CIT de \'An├ílisis journey del cliente\' a \'Analisis journey del cliente\' en la cita \'An├ílisis journey del cliente\'',190),(54,'2025-07-11 11:29:47','Elena Vargas','cambio','Se modificó NOM CIT de \'Workshop metodolog├¡as ├ígiles\' a \'Workshop metodologías ágiles\' en la cita \'Workshop metodolog├¡as ├ígiles\'',180),(55,'2025-07-11 11:29:56','Luis Rodriguez','cambio','Se modificó NOM CIT de \'Evaluaci├│n desempe├▒o trimestral\' a \'Evaluación desempeño trimestral\' en la cita \'Evaluaci├│n desempe├▒o trimestral\'',160),(56,'2025-07-11 11:30:04','Sofia Mendoza','cambio','Se modificó NOM CIT de \'An├ílisis indicadores KPI\' a \'Analisis indicadores KPI\' en la cita \'An├ílisis indicadores KPI\'',165),(57,'2025-07-11 11:30:47','Ana Garcia Silva','cambio','Se modificó NOM CIT de \'Evaluaci├│n riesgos operacionales\' a \'Evaluación riesgos operacionales\' en la cita \'Evaluaci├│n riesgos operacionales\'',211),(58,'2025-07-11 11:30:54','Pablo Jimenez','cambio','Se modificó NOM CIT de \'Evaluaci├│n clima organizacional\' a \'Evaluación clima organizacional\' en la cita \'Evaluaci├│n clima organizacional\'',171),(59,'2025-07-11 11:31:00','Ana Garcia Silva','cambio','Se modificó NOM CIT de \'Revisi├│n presupuesto anual\' a \'Revisión presupuesto anual\' en la cita \'Revisi├│n presupuesto anual\'',145),(60,'2025-07-11 11:31:08','Luis Rodriguez','cambio','Se modificó NOM CIT de \'Evaluaci├│n alianzas estrat├®gicas\' a \'Evaluación alianzas estrategicas\' en la cita \'Evaluaci├│n alianzas estrat├®gicas\'',205),(61,'2025-07-11 11:31:20','Samuel Ortigoza','cambio','Se modificó NOM CIT de \'Capacitaci├│n nuevas tecnolog├¡as\' a \'Capacitación nuevas tecnologías\' en la cita \'Capacitaci├│n nuevas tecnolog├¡as\'',185),(62,'2025-07-11 11:32:10','Francisco Pineda Hernández','cambio','Se modificó NOM CIT de \'Sesi├│n feedback 360┬░\' a \'Sesión feedback 360\' en la cita \'Sesi├│n feedback 360┬░\'',166),(63,'2025-07-11 11:32:14','Fatima Nava','cambio','Se modificó NOM CIT de \'Presentaci├│n estados financieros\' a \'Presentación estados financieros\' en la cita \'Presentaci├│n estados financieros\'',176),(64,'2025-07-11 11:32:19','Ana Garcia Silva','cambio','Se modificó NOM CIT de \'Presentaci├│n resultados Q2\' a \'Presentación resultados Q2\' en la cita \'Presentaci├│n resultados Q2\'',151),(65,'2025-07-11 11:32:26','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'Evaluaci├│n proveedores estrat├®gicos\' a \'Evaluación proveedores estrategicos\' en la cita \'Evaluaci├│n proveedores estrat├®gicos\'',196),(66,'2025-07-11 11:32:34','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'An├ílisis performance aplicaciones\' a \'Analisis performance aplicaciones\' en la cita \'An├ílisis performance aplicaciones\'',186),(67,'2025-07-11 11:32:38','Sofia Mendoza','cambio','Se modificó NOM CIT de \'Capacitaci├│n nuevo personal\' a \'Capacitación nuevo personal\' en la cita \'Capacitaci├│n nuevo personal\'',146),(68,'2025-07-11 11:32:43','Elena Vargas','cambio','Se modificó NOM CIT de \'Evaluaci├│n cultura organizacional\' a \'Evaluación cultura organizacional\' en la cita \'Evaluaci├│n cultura organizacional\'',201),(69,'2025-07-11 11:32:50','Luis Rodriguez','cambio','Se modificó NOM CIT de \'Planificaci├│n campa├▒a publicitaria\' a \'Planificación campaña publicitaria\' en la cita \'Planificaci├│n campa├▒a publicitaria\'',161),(70,'2025-07-11 11:32:54','Fatima Nava','cambio','Se modificó NOM CIT de \'Cierre negociaci├│n contrato\' a \'Cierre negociación contrato\' en la cita \'Cierre negociaci├│n contrato\'',156),(71,'2025-07-11 11:33:01','Ana Garcia Silva','cambio','Se modificó NOM CIT de \'Sesi├│n innovaci├│n disruptiva\' a \'Sesión innovación disruptiva\' en la cita \'Sesi├│n innovaci├│n disruptiva\'',206),(72,'2025-07-11 11:33:04','María Fernanda López','cambio','Se modificó NOM CIT de \'Evaluaci├│n herramientas DevOps\' a \'Evaluación herramientas DevOps\' en la cita \'Evaluaci├│n herramientas DevOps\'',181),(73,'2025-07-11 11:34:15','Carmen Morales Vega','cambio','Se modificó NOM CIT de \'Planeaci├│n estrat├®gica anual\' a \'Planeación estrategica anual\' en la cita \'Planeaci├│n estrat├®gica anual\'',167),(74,'2025-07-11 11:34:21','Sofia Mendoza','cambio','Se modificó NOM CIT de \'Sesi├│n arquitectura microservicios\' a \'Sesión arquitectura microservicios\' en la cita \'Sesi├│n arquitectura microservicios\'',187),(75,'2025-07-11 11:34:27','Francisco Pineda','cambio','Se modificó NOM CIT de \'Reuni├│n consejo administraci├│n\' a \'Reunión consejo administración\' en la cita \'Reuni├│n consejo administraci├│n\'',207),(76,'2025-07-11 11:34:31','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'Workshop innovaci├│n digital\' a \'Workshop innovación digital\' en la cita \'Workshop innovaci├│n digital\'',152),(77,'2025-07-11 11:34:35','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'Reuni├│n auditoria externa\' a \'Reunión auditoria externa\' en la cita \'Reuni├│n auditoria externa\'',177),(78,'2025-07-11 11:34:42','Rodrigo Ramirez','cambio','Se modificó NOM CIT de \'Reuni├│n comit├® operaciones\' a \'Reunión comité operaciones\' en la cita \'Reuni├│n comit├® operaciones\'',197),(79,'2025-07-11 11:34:54','Elena Vargas','cambio','Se modificó NOM CIT de \'Revisi├│n m├®tricas performance\' a \'Revisión métricas performance\' en la cita \'Revisi├│n m├®tricas performance\'',157),(80,'2025-07-11 11:35:05','Rodrigo Ramirez','cambio','Se modificó NOM CIT de \'Planeaci├│n roadmap tecnol├│gico\' a \'Planeación roadmap tecnologico\' en la cita \'Planeaci├│n roadmap tecnol├│gico\'',182),(81,'2025-07-11 11:35:14','Fatima Nava','cambio','Se modificó NOM CIT de \'Planeaci├│n sucesi├│n ejecutiva\' a \'Planeación sucesión ejecutiva\' en la cita \'Planeaci├│n sucesi├│n ejecutiva\'',202),(82,'2025-07-11 11:35:25','Carmen Morales Vega','cambio','Se modificó NOM CIT de \'Reuni├│n comit├® ejecutivo\' a \'Reunión comité ejecutivo\' en la cita \'Reuni├│n comit├® ejecutivo\'',162),(83,'2025-07-11 11:35:45','Ana Garcia Silva','cambio','Se modificó NOM CIT de \'Reuni├│n comit├® riesgos\' a \'Reunión comité riesgos\' en la cita \'Reuni├│n comit├® riesgos\'',212),(84,'2025-07-11 11:35:53','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'Sesi├│n coaching ejecutivo\' a \'Sesión coaching ejecutivo\' en la cita \'Sesi├│n coaching ejecutivo\'',172),(85,'2025-07-11 11:35:58','Elena Vargas','cambio','Se modificó NOM CIT de \'Reuni├│n equipo customer success\' a \'Reunión equipo customer success\' en la cita \'Reuni├│n equipo customer success\'',192),(86,'2025-07-15 13:18:46','María Fernanda López','alta','Se creó nueva cita: \'nueva cita\'',213),(87,'2025-07-15 13:19:27','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'nueva cita\' a \'esta es la nueva cita\' en la cita \'nueva cita\'',213),(88,'2025-07-15 13:19:49','Samuel Ortigoza','cambio','Se modificó NOM CIT de \'esta es la nueva cita\' a \'nueva cita de nuevi\' en la cita \'esta es la nueva cita\'',213),(89,'2025-07-15 13:19:52','Elena Vargas','cambio','Se modificó NOM CIT de \'nueva cita de nuevi\' a \'nueva cita de nuevo\' en la cita \'nueva cita de nuevi\'',213),(90,'2025-07-15 13:19:56','Diego Herrera Luna','cambio','Se modificó TEL CIT de \'(vacío)\' a \'1111\' en la cita \'nueva cita de nuevo\'',213),(91,'2025-07-15 13:20:08','Rodrigo Ramirez','cambio','Se modificó TEL CIT de \'1111\' a \'1111 000\' en la cita \'nueva cita de nuevo\'',213),(92,'2025-07-15 13:20:19','Luis Rodriguez','cambio','Se modificó TEL CIT de \'1111 000\' a \'1111 0\' en la cita \'nueva cita de nuevo\'',213),(93,'2025-07-15 13:20:23','Sofia Mendoza','cambio','Se modificó ID EJE2 de \'(vacío)\' a \'15\' en la cita \'nueva cita de nuevo\'',213),(94,'2025-07-15 13:20:27','Eric Valenzuela','cambio','Se modificó NOM CIT de \'nueva cita de nuevo\' a \'nueva cita\' en la cita \'nueva cita de nuevo\'',213),(95,'2025-07-15 13:25:55','Ana Garcia Silva','cambio','Se modificó NOM CIT de \'Revisión procesos operativos\' a \'Revisión procesos operativosss\' en la cita \'Revisión procesos operativos\'',163),(96,'2025-07-15 13:26:03','Francisco Pineda','cambio','Se modificó NOM CIT de \'Revisión procesos operativosss\' a \'Revisión procesos operativossss\' en la cita \'Revisión procesos operativosss\'',163),(97,'2025-07-15 13:29:57','Luis Rodriguez','cambio','Se modificó NOM CIT de \'Revisión procesos operativossss\' a \'Cita modificada\' en la cita \'Revisión procesos operativossss\'',163),(98,'2025-07-15 13:30:16','Andres Castro','alta','Se creó nueva cita: \'nueva cita 2\'',214),(99,'2025-07-15 13:30:25','Juan Carlos Pérez','cambio','Se modificó TEL CIT de \'(vacío)\' a \'1111\' en la cita \'nueva cita 2\'',214),(100,'2025-07-15 13:30:50','Rodrigo Ramirez','cambio','Se modificó NOM CIT de \'nueva cita 2\' a \'nuevo nombre para la cita\' en la cita \'nueva cita 2\'',214),(101,'2025-07-15 13:31:07','Ana Garcia Silva','cambio','Se modificó HOR CIT de \'09:00:00\' a \'9:00\' en la cita \'nuevo nombre para la cita\'',214),(102,'2025-07-15 13:31:07','Francisco Pineda','cambio','Se modificó CIT CIT de \'2025-07-15\' a \'2025-07-16\' en la cita \'nuevo nombre para la cita\'',214),(103,'2025-07-15 13:31:15','Andres Castro','cambio','Se modificó NOM CIT de \'nuevo nombre para la cita\' a \'nueva cita\' en la cita \'nuevo nombre para la cita\'',214),(104,'2025-07-15 13:31:27','Francisco Pineda','cambio','Se modificó TEL CIT de \'1111\' a \'0000\' en la cita \'nueva cita\'',214),(105,'2025-07-15 13:31:42','Eric Valenzuela','cambio','Se modificó NOM CIT de \'nueva cita\' a \'nueva citaaaaa\' en la cita \'nueva cita\'',214),(106,'2025-07-16 11:30:41','Ana Garcia Silva','alta','Se creó nueva cita: \'cita con comentario\'',215),(107,'2025-07-16 11:31:52','Sofia Mendoza','cambio','Se modificó TEL CIT de \'(vacío)\' a \'000\' en la cita \'cita con comentario\'',215),(108,'2025-07-16 11:32:16','Diego Herrera Luna','cambio','Se modificó ID EJE2 de \'(vacío)\' a \'4\' en la cita \'cita con comentario\'',215),(109,'2025-07-16 11:34:39','Francisco Pineda','alta','Se creó nueva cita: \'nueva cita\'',216),(110,'2025-07-16 11:34:55','Sofia Mendoza','cambio','Se modificó NOM CIT de \'nueva cita\' a \'nueva cita 2\' en la cita \'nueva cita\'',216),(111,'2025-07-16 11:36:11','Rodrigo Ramirez','cambio','Se modificó TEL CIT de \'(vacío)\' a \'00\' en la cita \'nueva cita 2\'',216),(112,'2025-07-16 18:32:26','Eric Valenzuela','cambio','Se modificó NOM CIT de \'Estrategia retención clientes\' a \'PANKE\' en la cita \'Estrategia retención clientes\'',189),(113,'2025-07-18 10:31:42','Eric Valenzuela','cambio','Se modificó ID EJE2 de \'13\' a \'20\' en la cita \'Sesión innovación tecnológica\'',178),(114,'2025-07-18 10:36:42','Juan Carlos Pérez','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'Cita modificada\'',163),(115,'2025-07-18 10:49:26','Elena Vargas','alta','Se creó nueva cita: \'Sin nombre\'',217),(116,'2025-07-18 11:00:19','Fatima Nava','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'CITA NO ATENDIDA\' en la cita \'Supervisión cadena suministro\'',193),(117,'2025-07-18 11:00:32','Eric Valenzuela','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'CITA NO ATENDIDA\' en la cita \'Sesión estrategia marketing\'',153),(118,'2025-07-18 11:00:52','Elena Vargas','cambio','Se modificó ID EJE2 de \'10\' a \'4\' en la cita \'Cita modificada\'',163),(119,'2025-07-18 11:00:52','Ana Garcia Silva','cambio','Se modificó TEL CIT de \'555-1001\' a \'555-1000\' en la cita \'Cita modificada\'',163),(120,'2025-07-18 11:03:19','Carmen Morales Vega','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'NO LE INTERESA\' en la cita \'Revisión calidad software\'',184),(121,'2025-07-18 11:03:25','Andres Castro','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'Revisión arquitectura sistemas\'',179),(122,'2025-07-18 11:03:44','Juan Carlos Pérez','cambio','Se modificó EST CIT de \'INVASIËN DE CICLO\' a \'INVASIÓN DE CICLO\' en la cita \'Capacitación nuevo personal\'',146),(123,'2025-07-18 11:03:56','Rodrigo Ramirez','cambio','Se modificó EST CIT de \'ASESOR═A REALIZADA\' a \'PERDIDO POR HORARIO\' en la cita \'Revisión presupuesto anual\'',145),(124,'2025-07-18 11:04:49','Samuel Ortigoza','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'PAGO ESPERADO\' en la cita \'Supervisión equipo ventas\'',158),(125,'2025-07-18 11:32:29','Samuel Ortigoza','baja','Se eliminó (ocultó) la cita \'Cita modificada\'',163),(126,'2025-07-18 11:54:10','Diego Herrera Luna','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Anáílisis mercado internacional\'',203),(127,'2025-07-18 11:54:12','María Fernanda López','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA NO EFECTIVA\' en la cita \'Evaluación proyecto Alpha\'',148),(128,'2025-07-18 11:54:13','Pablo Jimenez','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Desayuno ejecutivo networking\'',155),(129,'2025-07-18 11:54:14','Sofia Mendoza','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Reunión equipo desarrollo\'',183),(130,'2025-07-18 11:54:17','Sofia Mendoza','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA NO EFECTIVA\' en la cita \'Sesión innovación tecnológica\'',178),(131,'2025-07-18 11:54:27','Eric Valenzuela','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Supervisión cadena suministro\'',193),(132,'2025-07-18 11:54:31','Francisco Pineda','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA NO EFECTIVA\' en la cita \'Revisión portafolio inversiones\'',173),(133,'2025-07-18 11:57:44','Diego Herrera Luna','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Supervisión equipo ventas\'',158),(134,'2025-07-18 12:17:07','Eric Valenzuela','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA NO EFECTIVA\' en la cita \'Workshop desarrollo personal\'',168),(135,'2025-07-18 12:18:43','Luis Rodriguez','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Entrevista candidato Senior\'',144),(136,'2025-07-18 12:19:30','Francisco Pineda','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Analisis performance aplicaciones\'',186),(137,'2025-07-18 12:20:10','Francisco Pineda','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Entrenamiento liderazgo\'',164),(138,'2025-07-18 12:20:15','Samuel Ortigoza','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Presentación estados financieros\'',176),(139,'2025-07-18 12:20:16','Carmen Morales Vega','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA NO EFECTIVA\' en la cita \'Revisión estrategia corporativa\'',199),(140,'2025-07-18 12:20:20','Juan Carlos Pérez','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Revisión presupuesto anual\'',145),(141,'2025-07-18 13:22:46','Eric Valenzuela','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'Desayuno ejecutivo networking\'',155),(142,'2025-07-18 13:22:50','Juan Carlos Pérez','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'Anáílisis mercado internacional\'',203),(143,'2025-07-18 13:27:59','María Fernanda López','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Reunión de planificación Q3\'',143),(144,'2025-07-18 13:28:06','Pablo Jimenez','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Workshop metodologías ágiles\'',180),(145,'2025-07-18 13:34:54','Samuel Ortigoza','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Negociación proveedor clave\'',159),(146,'2025-07-18 13:34:55','Pablo Jimenez','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'Negociación proveedor clave\'',159),(147,'2025-07-18 13:35:03','Fatima Nava','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'Estrategia expansión regional\'',204),(148,'2025-07-18 13:37:18','Rodrigo Ramirez','alta','Se creó nueva cita: \'Sin nombre\'',218),(149,'2025-07-18 13:37:23','Ana Garcia Silva','cambio','Se modificó NOM CIT de \'(vacío)\' a \'nueva citaaa\' en la cita \'Sin nombre\'',218),(150,'2025-07-18 13:37:25','Diego Herrera Luna','cambio','Se modificó TEL CIT de \'nueva cita\' a \'0000\' en la cita \'nueva citaaa\'',218),(151,'2025-07-18 13:37:27','Ana Garcia Silva','cambio','Se modificó ID EJE2 de \'(vacío)\' a \'11\' en la cita \'nueva citaaa\'',218),(152,'2025-07-18 13:37:30','Luis Rodriguez','cambio','Se modificó EST CIT de \'(vacío)\' a \'CITA AGENDADA\' en la cita \'nueva citaaa\'',218),(153,'2025-07-18 13:37:41','Francisco Pineda','cambio','Se modificó HOR CIT de \'08:00:00\' a \'9:00\' en la cita \'nueva citaaa\'',218),(154,'2025-07-18 13:39:25','María Fernanda López','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'nueva citaaa\'',218),(155,'2025-07-18 13:39:35','Rodrigo Ramirez','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'nueva citaaa\'',218),(156,'2025-07-18 13:45:05','Ana Garcia Silva','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA NO EFECTIVA\' en la cita \'Evaluación riesgos operacionales\'',211),(157,'2025-07-18 13:45:07','Francisco Pineda','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Negociación contratos logística\'',194),(158,'2025-07-18 13:49:48','Fatima Nava','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'Reunión equipo desarrollo\'',183),(159,'2025-07-18 13:49:51','Elena Vargas','cambio','Se modificó EST CIT de \'PERDIDO POR PRECIO\' a \'REGISTRO\' en la cita \'Evaluación proyecto Alpha\'',148),(160,'2025-07-18 13:49:55','María Fernanda López','cambio','Se modificó EST CIT de \'REGISTRO\' a \'CITA AGENDADA\' en la cita \'Anáílisis mercado internacional\'',203),(161,'2025-07-18 13:50:01','Eric Valenzuela','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'NO LE INTERESA\' en la cita \'Sesión innovación tecnológica\'',178),(162,'2025-07-18 13:50:04','Juan Carlos Pérez','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'PERDIDO POR HORARIO\' en la cita \'Workshop metodologías ágiles\'',180),(163,'2025-07-18 13:50:07','María Fernanda López','cambio','Se modificó EST CIT de \'PERDIDO POR HORARIO\' a \'REGISTRO\' en la cita \'Workshop metodologías ágiles\'',180),(164,'2025-07-18 13:50:14','Andres Castro','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'Analílisis riesgo crediticio\'',174),(165,'2025-07-18 13:50:39','Samuel Ortigoza','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'Presentación estados financieros\'',176),(166,'2025-07-18 13:50:44','Carmen Morales Vega','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'REGISTRO\' en la cita \'PANKE\'',189),(167,'2025-07-18 13:50:48','Carmen Morales Vega','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA EFECTIVA\' en la cita \'Presentación resultados Q2\'',151),(168,'2025-07-18 14:01:54','Elena Vargas','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'CITA NO ATENDIDA\' en la cita \'Presentación resultados Q2\'',151),(169,'2025-07-18 14:02:00','Samuel Ortigoza','cambio','Se modificó EST CIT de \'CITA AGENDADA\' a \'PAGO ESPERADO\' en la cita \'Analisis performance aplicaciones\'',186),(170,'2025-07-18 14:02:02','María Fernanda López','cambio','Se modificó EST CIT de \'PAGO ESPERADO\' a \'CITA NO ATENDIDA\' en la cita \'Analisis performance aplicaciones\'',186),(171,'2025-07-18 14:05:20','Francisco Pineda','cambio','Se modificó EST CIT de \'REGISTRO\' a \'CITA REAGENDADA\' en la cita \'Desayuno ejecutivo networking\'',155),(172,'2025-07-21 10:47:14','Ana Garcia Silva','cambio','Se modificó ID EJE2 de \'4\' a \'(vacío)\' en la cita \'Workshop innovación digital\'',152),(173,'2025-07-21 11:13:09','Eric Valenzuela','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'cita con comentario\'',215),(174,'2025-07-21 11:13:12','Eric Valenzuela','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Auditoria sistemas internos\'',150),(175,'2025-07-21 11:13:48','María Fernanda López','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Presentación resultados Q2\'',151),(176,'2025-07-21 11:14:18','Fatima Nava','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Evaluación proyecto Alpha\'',148),(177,'2025-07-21 11:14:20','Fatima Nava','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Sesión estrategia marketing\'',153),(178,'2025-07-21 11:15:57','Sofia Mendoza','cambio','Se modificó PLA CIT de \'6\' a \'3\' en la cita \'Evaluación proyecto Alpha\'',148),(179,'2025-07-21 11:23:46','Luis Rodriguez','cambio','Se modificó PLA CIT de \'2\' a \'6\' en la cita \'nueva cita\'',213),(180,'2025-07-21 11:25:12','Samuel Ortigoza','cambio','Se modificó PLA CIT de \'6\' a \'2\' en la cita \'cita con comentario\'',215),(181,'2025-07-21 11:25:20','Luis Rodriguez','cambio','Se modificó PLA CIT de \'2\' a \'3\' en la cita \'cita con comentario\'',215),(182,'2025-07-21 11:26:45','Rodrigo Ramirez','cambio','Se modificó ID EJE2 de \'10\' a \'6\' en la cita \'Planeación estrategica anual\'',167),(183,'2025-07-21 11:27:20','Samuel Ortigoza','cambio','Se modificó EST CIT de \'REGISTRO\' a \'CITA REAGENDADA\' en la cita \'nueva cita\'',213),(184,'2025-07-21 11:27:24','Elena Vargas','cambio','Se modificó EST CIT de \'REGISTRO\' a \'CITA AGENDADA\' en la cita \'cita con comentario\'',215),(185,'2025-07-21 11:28:49','Elena Vargas','cambio','Se modificó ID EJE2 de \'4\' a \'15\' en la cita \'cita con comentario\'',215),(186,'2025-07-21 11:28:52','Rodrigo Ramirez','cambio','Se modificó ID EJE2 de \'14\' a \'18\' en la cita \'Sesión arquitectura microservicios\'',187),(187,'2025-07-21 11:28:56','Andres Castro','cambio','Se modificó ID EJE2 de \'20\' a \'9\' en la cita \'Reunión consejo administración\'',207),(188,'2025-07-21 11:36:14','Rodrigo Ramirez','cambio','Se modificó PLA CIT de \'3\' a \'2\' en la cita \'Junta directiva mensual\'',147),(189,'2025-07-21 11:36:19','Eric Valenzuela','cambio','Se modificó PLA CIT de \'2\' a \'3\' en la cita \'Reunión consejo administración\'',207),(190,'2025-07-21 11:36:23','Ana Garcia Silva','cambio','Se modificó ID EJE2 de \'2\' a \'4\' en la cita \'Junta directiva mensual\'',147),(191,'2025-07-21 11:36:25','Juan Carlos Pérez','cambio','Se modificó ID EJE2 de \'18\' a \'16\' en la cita \'Sesión arquitectura microservicios\'',187),(192,'2025-07-21 11:36:33','Sofia Mendoza','cambio','Se modificó ID EJE2 de \'9\' a \'6\' en la cita \'Reunión consejo administración\'',207),(193,'2025-07-21 11:37:28','Samuel Ortigoza','cambio','Se modificó PLA CIT de \'3\' a \'2\' en la cita \'Reunión comité ejecutivo\'',162),(194,'2025-07-21 11:43:08','Juan Carlos Pérez','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Sin nombre\'',217),(195,'2025-07-21 11:43:25','Eric Valenzuela','cambio','Se modificó PLA CIT de \'2\' a \'3\' en la cita \'Junta directiva mensual\'',147),(196,'2025-07-21 11:43:35','Juan Carlos Pérez','cambio','Se modificó PLA CIT de \'6\' a \'2\' en la cita \'Reunión comité operaciones\'',197),(197,'2025-07-21 11:43:39','Sofia Mendoza','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Reunión auditoria externa\'',177),(198,'2025-07-21 11:43:46','Eric Valenzuela','cambio','Se modificó PLA CIT de \'2\' a \'6\' en la cita \'Reunión comité riesgos\'',212),(199,'2025-07-21 11:43:48','Fatima Nava','cambio','Se modificó PLA CIT de \'2\' a \'3\' en la cita \'Reunión equipo customer success\'',192),(200,'2025-07-21 11:44:48','Sofia Mendoza','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Reunión equipo customer success\'',192),(201,'2025-07-21 11:50:26','Pablo Jimenez','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'cita con comentario\'',215),(202,'2025-07-21 11:50:39','Eric Valenzuela','cambio','Se modificó PLA CIT de \'2\' a \'6\' en la cita \'Planeación estrategica anual\'',167),(203,'2025-07-21 11:50:41','Diego Herrera Luna','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Junta directiva mensual\'',147),(204,'2025-07-21 12:05:05','Francisco Pineda','cambio','Se modificó PLA CIT de \'2\' a \'3\' en la cita \'Reunión comité ejecutivo\'',162),(205,'2025-07-21 12:05:19','Ana Garcia Silva','cambio','Se modificó PLA CIT de \'2\' a \'6\' en la cita \'Revisión métricas performance\'',157),(206,'2025-07-21 12:05:58','Sofia Mendoza','cambio','Se modificó PLA CIT de \'6\' a \'3\' en la cita \'Planeación estrategica anual\'',167),(207,'2025-07-21 12:06:03','Francisco Pineda','cambio','Se modificó PLA CIT de \'6\' a \'2\' en la cita \'nueva cita\'',213),(208,'2025-07-21 12:06:06','Juan Carlos Pérez','cambio','Se modificó PLA CIT de \'6\' a \'3\' en la cita \'cita con comentario\'',215),(209,'2025-07-21 12:06:15','María Fernanda López','cambio','Se modificó PLA CIT de \'6\' a \'3\' en la cita \'Sin nombre\'',217),(210,'2025-07-21 12:06:20','Fatima Nava','cambio','Se modificó PLA CIT de \'6\' a \'3\' en la cita \'nueva citaaaaa\'',214),(211,'2025-07-21 12:06:22','Juan Carlos Pérez','cambio','Se modificó PLA CIT de \'6\' a \'3\' en la cita \'nueva cita 2\'',216),(212,'2025-07-21 12:06:26','Samuel Ortigoza','cambio','Se modificó PLA CIT de \'2\' a \'3\' en la cita \'nueva cita\'',213),(213,'2025-07-21 12:13:12','María Fernanda López','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Sin nombre\'',217),(214,'2025-07-21 12:13:21','Samuel Ortigoza','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'cita con comentario\'',215),(215,'2025-07-21 12:13:23','Diego Herrera Luna','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'nueva cita 2\'',216),(216,'2025-07-21 12:13:24','Rodrigo Ramirez','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Planeación estrategica anual\'',167),(217,'2025-07-21 12:17:30','Samuel Ortigoza','cambio','Se modificó PLA CIT de \'6\' a \'2\' en la cita \'nueva cita 2\'',216),(218,'2025-07-21 12:18:01','Samuel Ortigoza','cambio','Se modificó PLA CIT de \'3\' a \'6\' en la cita \'Reunión comité ejecutivo\'',162),(219,'2025-07-21 12:18:07','Francisco Pineda','cambio','Se modificó PLA CIT de \'6\' a \'2\' en la cita \'Reunión comité riesgos\'',212),(220,'2025-07-21 12:18:25','María Fernanda López','cambio','Se modificó PLA CIT de \'6\' a \'3\' en la cita \'Reunión comité ejecutivo\'',162),(221,'2025-07-21 12:18:27','Andres Castro','cambio','Se modificó PLA CIT de \'2\' a \'3\' en la cita \'Reunión comité riesgos\'',212),(222,'2025-07-21 13:20:03','Sofia Mendoza','cambio','Se modificó PLA CIT de \'6\' a \'2\' en la cita \'Sin nombre\'',217),(223,'2025-07-21 13:20:20','Francisco Pineda','cambio','Se modificó NOM CIT de \'nueva cita\' a \'(vacío)\' en la cita \'nueva cita\'',213),(224,'2025-07-21 13:20:26','Elena Vargas','cambio','Se modificó NOM CIT de \'cita con comentario\' a \'(vacío)\' en la cita \'cita con comentario\'',215),(225,'2025-07-21 13:20:51','Sofia Mendoza','cambio','Se modificó TEL CIT de \'0000\' a \'(vacío)\' en la cita \'Sin nombre\'',215),(226,'2025-07-22 12:57:45','Francisco Pineda','cambio','Se modificó NOM CIT de \'Evaluación proyecto Alpha\' a \'cita modificada\' en la cita \'Evaluación proyecto Alpha\'',148),(227,'2025-07-22 14:08:21','Ana Garcia Silva','cambio','Se modificó ID EJE3 de \'20\' a \'(vacío)\' en la cita \'Sesión innovación tecnológica\'',178),(228,'2025-07-22 14:08:34','Samuel Ortigoza','cambio','Se modificó ID EJE3 de \'16\' a \'(vacío)\' en la cita \'Supervisión cadena suministro\'',193),(229,'2025-07-22 14:10:11','Andres Castro','alta','Se creó nueva cita: \'Sin nombre\'',219),(230,'2025-07-22 14:21:32','Luis Rodriguez','cambio','Se modificó ID EJE3 de \'(vacío)\' a \'16\' en la cita \'nueva citaaaaa\'',214),(231,'2025-07-22 14:21:38','María Fernanda López','cambio','Se modificó ID EJE3 de \'9\' a \'(vacío)\' en la cita \'Sin nombre\'',219),(232,'2025-07-22 14:21:39','Fatima Nava','cambio','Se modificó ID EJE3 de \'15\' a \'(vacío)\' en la cita \'Sin nombre\'',215),(233,'2025-07-22 14:21:42','Eric Valenzuela','cambio','Se modificó ID EJE3 de \'15\' a \'(vacío)\' en la cita \'Sin nombre\'',213),(234,'2025-07-22 14:21:47','Diego Herrera Luna','alta','Se creó nueva cita: \'Sin nombre\'',220),(235,'2025-07-22 14:21:56','María Fernanda López','cambio','Se modificó HOR CIT de \'10:00:00\' a \'11:11\' en la cita \'Sin nombre\'',220),(236,'2025-07-22 14:22:02','Francisco Pineda','cambio','Se modificó NOM CIT de \'(vacío)\' a \'NUEVA CITA\' en la cita \'Sin nombre\'',220),(237,'2025-07-22 14:22:05','Samuel Ortigoza','cambio','Se modificó TEL CIT de \'NUEVA CITA\' a \'000\' en la cita \'NUEVA CITA\'',220),(238,'2025-07-22 14:22:07','Juan Carlos Pérez','cambio','Se modificó ID EJE2 de \'(vacío)\' a \'11\' en la cita \'NUEVA CITA\'',220),(239,'2025-07-22 14:22:08','Carmen Morales Vega','cambio','Se modificó ID EJE3 de \'(vacío)\' a \'16\' en la cita \'NUEVA CITA\'',220),(240,'2025-07-22 14:22:12','Eric Valenzuela','cambio','Se modificó EST CIT de \'(vacío)\' a \'CITA NO ATENDIDA\' en la cita \'NUEVA CITA\'',220),(241,'2025-07-22 14:22:16','Diego Herrera Luna','cambio','Se modificó EFE CIT de \'(vacío)\' a \'CITA NO EFECTIVA\' en la cita \'NUEVA CITA\'',220),(242,'2025-07-22 14:22:18','María Fernanda López','cambio','Se modificó PLA CIT de \'6\' a \'3\' en la cita \'NUEVA CITA\'',220),(243,'2025-07-22 14:37:13','María Fernanda López','cambio','Se modificó ID EJE ADMIN de \'(vacío)\' a \'4\' en la cita \'Sin nombre\'',215),(244,'2025-07-22 14:37:59','Pablo Jimenez','cambio','Se modificó ID EJE ADMIN de \'(vacío)\' a \'11\' en la cita \'nueva cita 2\'',216),(245,'2025-07-22 14:43:56','María Fernanda López','cambio','Se modificó ID EJE ADMISION de \'(vacío)\' a \'9\' en la cita \'Sin nombre\'',215),(246,'2025-07-22 14:43:57','Samuel Ortigoza','cambio','Se modificó ID EJE ADMIN de \'(vacío)\' a \'18\' en la cita \'Sin nombre\'',219),(247,'2025-07-22 14:43:59','María Fernanda López','cambio','Se modificó ID EJE ADMISION de \'(vacío)\' a \'6\' en la cita \'Sin nombre\'',219),(248,'2025-07-22 15:03:30','Pablo Jimenez','cambio','Se modificó ID EJE ADMISION de \'(vacío)\' a \'12\' en la cita \'nueva citaaaaa\'',214),(249,'2025-07-22 15:03:31','Ana Garcia Silva','cambio','Se modificó ID EJE ADMIN de \'(vacío)\' a \'10\' en la cita \'nueva citaaaaa\'',214),(250,'2025-07-22 15:03:37','Fatima Nava','cambio','Se modificó ID EJE ADMIN de \'(vacío)\' a \'18\' en la cita \'NUEVA CITA\'',220),(251,'2025-07-22 15:03:39','Luis Rodriguez','cambio','Se modificó ID EJE ADMISION de \'(vacío)\' a \'6\' en la cita \'NUEVA CITA\'',220),(252,'2025-07-22 15:27:56','Sistema P29','cambio','Cita restaurada desde la papelera por ejecutivo administrativo',163),(253,'2025-07-22 15:33:08','Fatima Nava','cambio','Se modificó ID EJE ADMISION de \'9\' a \'12\' en la cita \'Sin nombre\'',215),(254,'2025-07-22 15:33:11','Samuel Ortigoza','cambio','Se modificó ID EJE ADMISION de \'12\' a \'15\' en la cita \'nueva citaaaaa\'',214),(255,'2025-07-22 15:33:14','Ana Garcia Silva','cambio','Se modificó ID EJE ADMIN de \'10\' a \'11\' en la cita \'nueva citaaaaa\'',214),(256,'2025-07-22 15:33:16','María Fernanda López','cambio','Se modificó ID EJE ADMIN de \'18\' a \'16\' en la cita \'Sin nombre\'',219),(257,'2025-07-22 15:33:20','Luis Rodriguez','cambio','Se modificó ID EJE ADMISION de \'(vacío)\' a \'12\' en la cita \'nueva cita 2\'',216),(258,'2025-07-22 15:33:28','Francisco Pineda','baja','Se eliminó (ocultó) la cita \'Sin nombre\'',219),(259,'2025-07-22 15:33:33','Eric Valenzuela','baja','Se eliminó (ocultó) la cita \'NUEVA CITA\'',220),(260,'2025-07-22 15:33:39','Andres Castro','baja','Se eliminó (ocultó) la cita \'nueva citaaaaa\'',214),(261,'2025-07-22 15:34:21','Rodrigo Ramirez','cambio','Se modificó NOM CIT de \'NUEVA CITA\' a \'CITA ELIMINADA\' en la cita \'NUEVA CITA\'',220),(262,'2025-07-22 15:34:27','Fatima Nava','cambio','Se modificó NOM CIT de \'Cita eliminada 3\' a \'Cita eliminada mil\' en la cita \'Cita eliminada 3\'',224),(263,'2025-07-22 15:34:47','Sistema P29','cambio','Cita restaurada desde la papelera por ejecutivo administrativo',219),(264,'2025-07-22 15:45:39','Fatima Nava','cambio','Se modificó ID EJE ADMIN de \'(vacío)\' a \'11\' en la cita \'Sin nombre\'',213),(265,'2025-07-22 15:45:40','Sofia Mendoza','cambio','Se modificó ID EJE ADMISION de \'(vacío)\' a \'6\' en la cita \'Sin nombre\'',213),(266,'2025-07-22 15:45:44','Samuel Ortigoza','cambio','Se modificó ID EJE2 de \'(vacío)\' a \'12\' en la cita \'nueva cita 2\'',216),(267,'2025-07-22 15:45:46','Sofia Mendoza','cambio','Se modificó TEL CIT de \'(vacío)\' a \'000\' en la cita \'Sin nombre\'',219),(268,'2025-07-22 15:45:48','Rodrigo Ramirez','cambio','Se modificó TEL CIT de \'(vacío)\' a \'312312\' en la cita \'Sin nombre\'',215),(269,'2025-07-22 15:45:50','María Fernanda López','cambio','Se modificó NOM CIT de \'(vacío)\' a \'cita\' en la cita \'Sin nombre\'',215),(270,'2025-07-22 15:45:52','Elena Vargas','cambio','Se modificó NOM CIT de \'(vacío)\' a \'cita\' en la cita \'Sin nombre\'',213),(271,'2025-07-22 15:45:54','Eric Valenzuela','cambio','Se modificó NOM CIT de \'(vacío)\' a \'citaaa\' en la cita \'Sin nombre\'',219),(272,'2025-07-22 15:47:13','Rodrigo Ramirez','alta','Se creó nueva cita: \'no te deja seleccionar más de una celda a la vez\'',225),(273,'2025-07-22 15:47:16','Ana Garcia Silva','cambio','Se modificó TEL CIT de \'(vacío)\' a \'000\' en la cita \'no te deja seleccionar más de una celda a la vez\'',225),(274,'2025-07-22 15:47:45','Eric Valenzuela','cambio','Se modificó ID EJE ADMIN de \'(vacío)\' a \'18\' en la cita \'no te deja seleccionar más de una celda a la vez\'',225),(275,'2025-07-22 15:47:49','Luis Rodriguez','cambio','Se modificó ID EJE ADMISION de \'(vacío)\' a \'1\' en la cita \'no te deja seleccionar más de una celda a la vez\'',225),(276,'2025-07-22 15:48:20','Pablo Jimenez','alta','Se creó nueva cita: \'no se actulizaba por tener mismo id\'',226),(277,'2025-07-22 15:48:34','Diego Herrera Luna','cambio','Se modificó ID EJE ADMIN de \'(vacío)\' a \'11\' en la cita \'no se actulizaba por tener mismo id\'',226),(278,'2025-07-22 15:48:37','Francisco Pineda','cambio','Se modificó ID EJE ADMISION de \'(vacío)\' a \'6\' en la cita \'no se actulizaba por tener mismo id\'',226),(279,'2025-07-22 15:48:43','Andres Castro','baja','Se eliminó (ocultó) la cita \'no te deja seleccionar más de una celda a la vez\'',225),(280,'2025-07-22 15:48:45','Pablo Jimenez','baja','Se eliminó (ocultó) la cita \'no se actulizaba por tener mismo id\'',226),(281,'2025-07-22 15:48:51','Rodrigo Ramirez','baja','Se eliminó (ocultó) la cita \'citaaa\'',219),(282,'2025-07-22 15:48:52','Diego Herrera Luna','baja','Se eliminó (ocultó) la cita \'nueva cita 2\'',216),(283,'2025-07-22 15:48:54','Pablo Jimenez','baja','Se eliminó (ocultó) la cita \'cita\'',215),(284,'2025-07-22 15:48:55','Juan Carlos Pérez','baja','Se eliminó (ocultó) la cita \'cita\'',213),(285,'2025-07-22 15:49:25','Pablo Jimenez','cambio','Se modificó ID EJE2 de \'2\' a \'11\' en la cita \'Cita eliminada 1\'',222),(286,'2025-07-22 15:49:25','Andres Castro','cambio','Se modificó ID EJE ADMIN de \'(vacío)\' a \'18\' en la cita \'Cita eliminada 1\'',222),(287,'2025-07-22 15:49:25','María Fernanda López','cambio','Se modificó ID EJE ADMISION de \'(vacío)\' a \'1\' en la cita \'Cita eliminada 1\'',222),(288,'2025-07-22 15:49:33','Juan Carlos Pérez','cambio','Se modificó NOM CIT de \'Cita eliminada 2\' a \'muestra solo las citas elimindas\' en la cita \'Cita eliminada 2\'',223),(289,'2025-07-22 15:50:04','Francisco Pineda','cambio','Se modificó NOM CIT de \'Cita eliminada mil\' a \'y te deja restaurarlas\' en la cita \'Cita eliminada mil\'',224),(290,'2025-07-22 15:50:10','Sistema P29','cambio','Cita restaurada desde la papelera por ejecutivo administrativo',224),(291,'2025-07-22 15:50:15','Sistema P29','cambio','Cita restaurada desde la papelera por ejecutivo administrativo',220),(292,'2025-07-24 13:26:18','Promotor Educativo Cuautitlán','cambio','Se modificó NOM CIT de \'y te deja restaurarlas\' a \'cita\' en la cita \'y te deja restaurarlas\'',224),(293,'2025-07-24 13:26:23','Ejecutivo de Servicios Escolares 2','cambio','Se modificó NOM CIT de \'CITA ELIMINADA\' a \'NUEVO CAMBIO\' en la cita \'CITA ELIMINADA\'',220),(294,'2025-07-24 13:27:05','Asesor de Admisiones 1 Naucalpan','cambio','Se modificó PLA CIT de \'2\' a \'6\' en la cita \'cita\'',224),(295,'2025-07-24 13:27:08','Consultor de Admisiones 2 Querétaro','cambio','Se modificó PLA CIT de \'3\' a \'9\' en la cita \'NUEVO CAMBIO\'',220),(296,'2025-07-24 14:22:24','Consultor de Admisiones 1 Querétaro','cambio','Se modificó NOM CIT de \'Cita modificada\' a \'SONIDO\' en la cita \'Cita modificada\'',163),(297,'2025-07-24 14:22:29','Analista de Penetración CDMX Portales','cambio','Se modificó NOM CIT de \'nueva citaaa\' a \'SONIDO\' en la cita \'nueva citaaa\'',218),(298,'2025-07-24 14:22:38','Director General CDMX Portales','cambio','Se modificó NOM CIT de \'Revisión calidad software\' a \'SONIDO\' en la cita \'Revisión calidad software\'',184),(299,'2025-07-24 14:23:53','Ejecutivo Territorial 1 Pachuca','cambio','Se modificó NOM CIT de \'Reunión de planificación Q3\' a \'sonido\' en la cita \'Reunión de planificación Q3\'',143),(300,'2025-07-24 14:24:00','Especialista Académico 1 Ecatepec','cambio','Se modificó NOM CIT de \'Sesión estrategia marketing\' a \'sonido de cambio\' en la cita \'Sesión estrategia marketing\'',153),(301,'2025-07-25 12:16:43','Andres Castro','cambio','Se modificó PLA CIT de \'9\' a \'16\' en la cita \'NUEVO CAMBIO\'',220),(302,'2025-07-25 12:19:48','Gerente de Recursos Humanos Naucalpan','alta','Se creó nueva cita: \'Cita desde el celular\'',227),(303,'2025-07-25 12:19:55','Director General Pachuca','cambio','Se modificó CIT CIT de \'2025-07-25\' a \'2025-07-08\' en la cita \'Cita desde el celular\'',227);
/*!40000 ALTER TABLE `historial_cita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_ejecutivo`
--

DROP TABLE IF EXISTS `historial_ejecutivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historial_ejecutivo` (
  `id_his_eje` int(11) NOT NULL AUTO_INCREMENT,
  `fec_his_eje` datetime NOT NULL DEFAULT current_timestamp(),
  `res_his_eje` varchar(100) NOT NULL,
  `mov_his_eje` enum('alta','cambio','baja') NOT NULL,
  `des_his_eje` text NOT NULL,
  `id_eje11` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_his_eje`),
  KEY `idx_id_eje11` (`id_eje11`),
  KEY `idx_fec_his_eje` (`fec_his_eje`),
  CONSTRAINT `historial_ejecutivo_ibfk_1` FOREIGN KEY (`id_eje11`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_ejecutivo`
--

LOCK TABLES `historial_ejecutivo` WRITE;
/*!40000 ALTER TABLE `historial_ejecutivo` DISABLE KEYS */;
INSERT INTO `historial_ejecutivo` VALUES (4,'2025-07-08 10:00:00','Sistema','alta','Se cre¾ nuevo ejecutivo: Juan Carlos PÚrez',1),(5,'2025-07-08 11:30:00','MarÝa Fernanda L¾pez','cambio','Se modific¾ TEL_EJE de 555-0123 a 555-0124 en el ejecutivo Juan Carlos PÚrez',1),(6,'2025-07-08 12:00:00','Roberto Gonzßlez','baja','Se ocult¾ el ejecutivo Juan Carlos PÚrez',1),(7,'2025-07-09 08:00:00','Sistema','alta','Se cre¾ nuevo ejecutivo: MarÝa Fernanda L¾pez',2),(8,'2025-07-09 14:20:00','Francisco Pineda','cambio','Se modific¾ NOM_EJE de MarÝa L¾pez a MarÝa Fernanda L¾pez en el ejecutivo MarÝa Fernanda L¾pez',2),(9,'2025-07-10 14:50:24','Francisco Pineda','cambio','Se modificó TEL_EJE de \'555-0789\' a \'11\', ELI_EJE de \'inactivo\' a \'activo\' en el ejecutivo \'Francisco Pineda\'',4),(10,'2025-07-10 16:05:04','Nuevo ejecutivo','alta','Se creó nuevo ejecutivo: \'Nuevo ejecutivo\'',18),(11,'2025-07-10 16:06:37','FRANCISCO PINEDA HERNANDEZ','cambio','Se modificó NOM_EJE de \'Nuevo ejecutivo\' a \'FRANCISCO PINEDA HERNANDEZ\', TEL_EJE de \'000000\' a \'111-111\', ID_PLA de \'6\' a \'3\' en el ejecutivo \'FRANCISCO PINEDA HERNANDEZ\'',18),(15,'2025-07-11 10:13:51','Francisco Pineda Hernández','cambio','Se modificó NOM_EJE de \'FRANCISCO PINEDA HERNANDEZ\' a \'Francisco Pineda Hernández\' en el ejecutivo \'Francisco Pineda Hernández\'',18),(16,'2025-07-11 10:23:25','Samuel Ortigoza','alta','Se creó nuevo ejecutivo: \'Samuel Ortigoza\'',20),(17,'2025-07-11 10:23:39','Rodrigo Ramirez','alta','Se creó nuevo ejecutivo: \'Rodrigo Ramirez\'',21),(18,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Admisi├│n\" al ejecutivo Juan Carlos Pérez',1),(19,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Administrativo\" al ejecutivo María Fernanda López',2),(20,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Administrativo\" al ejecutivo Francisco Pineda',4),(21,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Admisi├│n\" al ejecutivo Fatima Nava',6),(22,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Admisi├│n\" al ejecutivo Ana Garcia Silva',9),(23,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Administrativo\" al ejecutivo Luis Rodriguez',10),(24,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Administrativo\" al ejecutivo Carmen Morales Vega',11),(25,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Admisi├│n\" al ejecutivo Diego Herrera Luna',12),(26,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Admisi├│n\" al ejecutivo Sofia Mendoza',13),(27,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Admisi├│n\" al ejecutivo Pablo Jimenez',14),(28,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Admisi├│n\" al ejecutivo Elena Vargas',15),(29,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Administrativo\" al ejecutivo Andres Castro',16),(30,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Administrativo\" al ejecutivo Eric Valenzuela',18),(31,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Admisi├│n\" al ejecutivo Samuel Ortigoza',20),(32,'2025-07-22 13:36:26','Sistema Pr├íctica 28','cambio','Actualizaci├│n P28: Asignado tipo \"Admisi├│n\" al ejecutivo Rodrigo Ramirez',21),(33,'2025-07-23 11:08:26','Francisco Pineda','cambio','Se modificó ID_PLA de \'6\' a \'(sin plantel)\' en el ejecutivo \'Francisco Pineda\'',4),(34,'2025-07-23 11:15:55','Fatima Nava','cambio','Se modificó ID_PLA de \'2\' a \'(sin plantel)\' en el ejecutivo \'Fatima Nava\'',6),(35,'2025-07-23 11:31:11','Carmen Morales Vega','cambio','Se modificó ID_PLA de \'6\' a \'(sin plantel)\' en el ejecutivo \'Carmen Morales Vega\'',11),(36,'2025-07-23 11:35:26','Samuel Ortigoza','cambio','Se modificó ID_PLA de \'2\' a \'(sin plantel)\' en el ejecutivo \'Samuel Ortigoza\'',20),(37,'2025-07-23 11:55:39','Rodrigo Ramirez','cambio','Se modificó ID_PLA de \'2\' a \'(sin plantel)\' en el ejecutivo \'Rodrigo Ramirez\'',21),(38,'2025-07-23 12:09:10','Luis Rodriguez','cambio','Se modificó ID_PLA de \'3\' a \'(sin plantel)\' en el ejecutivo \'Luis Rodriguez\'',10),(39,'2025-07-23 12:19:20','Eric Valenzuela','cambio','Se modificó ID_PLA de \'3\' a \'(sin plantel)\' en el ejecutivo \'Eric Valenzuela\'',18),(40,'2025-07-23 12:25:27','Elena Vargas','cambio','Se modificó ID_PLA de \'2\' a \'(sin plantel)\' en el ejecutivo \'Elena Vargas\'',15),(41,'2025-07-23 12:29:44','Diego Herrera Luna','cambio','Se modificó ID_PLA de \'6\' a \'(sin plantel)\' en el ejecutivo \'Diego Herrera Luna\'',12),(42,'2025-07-23 12:54:01','Juan Carlos Pérez','alta','Se mostró el ejecutivo \'Juan Carlos Pérez\'',1);
/*!40000 ALTER TABLE `historial_ejecutivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantel`
--

DROP TABLE IF EXISTS `plantel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantel` (
  `id_pla` int(11) NOT NULL,
  `nom_pla` varchar(100) NOT NULL,
  `fec_pla` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantel`
--

LOCK TABLES `plantel` WRITE;
/*!40000 ALTER TABLE `plantel` DISABLE KEYS */;
INSERT INTO `plantel` VALUES (2,'Plantel Naucalpan','2025-07-09 10:52:31'),(3,'Plantel Ecatepec','2025-07-09 10:52:31'),(6,'Plantel Cuautitlán','2025-07-09 10:52:31'),(8,'Querétaro','2025-07-23 13:49:52'),(9,'Pachuca','2025-07-23 13:50:10'),(13,'San Luis Potosí','2025-07-23 13:50:42'),(16,'CDMX Portales','2025-07-23 13:50:37');
/*!40000 ALTER TABLE `plantel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `planteles_ejecutivo`
--

DROP TABLE IF EXISTS `planteles_ejecutivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `planteles_ejecutivo` (
  `id_pla_eje` int(11) NOT NULL AUTO_INCREMENT,
  `fec_pla_eje` datetime DEFAULT current_timestamp(),
  `id_pla` int(11) NOT NULL,
  `id_eje` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_pla_eje`),
  UNIQUE KEY `unique_plantel_ejecutivo` (`id_pla`,`id_eje`),
  KEY `fk_planteles_ejecutivo_ejecutivo` (`id_eje`),
  CONSTRAINT `fk_planteles_ejecutivo_ejecutivo` FOREIGN KEY (`id_eje`) REFERENCES `ejecutivo` (`id_eje`) ON DELETE CASCADE,
  CONSTRAINT `fk_planteles_ejecutivo_plantel` FOREIGN KEY (`id_pla`) REFERENCES `plantel` (`id_pla`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `planteles_ejecutivo`
--

LOCK TABLES `planteles_ejecutivo` WRITE;
/*!40000 ALTER TABLE `planteles_ejecutivo` DISABLE KEYS */;
INSERT INTO `planteles_ejecutivo` VALUES (1,'2025-07-10 13:17:59',2,1),(2,'2025-07-10 13:17:59',6,1),(3,'2025-07-10 13:17:59',3,2),(4,'2025-07-10 13:17:59',6,2),(5,'2025-07-10 13:17:59',2,4),(7,'2025-07-10 13:17:59',6,4),(8,'2025-07-10 15:53:05',6,9),(9,'2025-07-10 15:53:18',3,9),(10,'2025-07-10 16:15:11',3,6),(11,'2025-07-10 16:15:24',6,6),(14,'2025-07-11 12:09:52',2,18);
/*!40000 ALTER TABLE `planteles_ejecutivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vista_citas_plantel`
--

DROP TABLE IF EXISTS `vista_citas_plantel`;
/*!50001 DROP VIEW IF EXISTS `vista_citas_plantel`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vista_citas_plantel` AS SELECT
 1 AS `id_pla`,
  1 AS `nom_pla`,
  1 AS `total_citas`,
  1 AS `citas_activas`,
  1 AS `citas_eliminadas`,
  1 AS `citas_con_ejecutivo`,
  1 AS `citas_sin_ejecutivo`,
  1 AS `citas_hoy`,
  1 AS `citas_futuras`,
  1 AS `citas_pasadas` */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vista_citas_plantel`
--

/*!50001 DROP VIEW IF EXISTS `vista_citas_plantel`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = cp850 */;
/*!50001 SET character_set_results     = cp850 */;
/*!50001 SET collation_connection      = cp850_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_citas_plantel` AS select `p`.`id_pla` AS `id_pla`,`p`.`nom_pla` AS `nom_pla`,count(`c`.`id_cit`) AS `total_citas`,count(case when `c`.`eli_cit` = 1 then 1 end) AS `citas_activas`,count(case when `c`.`eli_cit` = 0 then 1 end) AS `citas_eliminadas`,count(case when `c`.`id_eje2` is not null then 1 end) AS `citas_con_ejecutivo`,count(case when `c`.`id_eje2` is null then 1 end) AS `citas_sin_ejecutivo`,count(case when cast(`c`.`cit_cit` as date) = curdate() then 1 end) AS `citas_hoy`,count(case when cast(`c`.`cit_cit` as date) >= curdate() then 1 end) AS `citas_futuras`,count(case when cast(`c`.`cit_cit` as date) < curdate() then 1 end) AS `citas_pasadas` from (`plantel` `p` left join `cita` `c` on(`p`.`id_pla` = `c`.`pla_cit`)) group by `p`.`id_pla`,`p`.`nom_pla` order by `p`.`nom_pla` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-29 11:52:58
