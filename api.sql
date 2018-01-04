-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 04, 2018 at 06:40 PM
-- Server version: 5.5.54-0ubuntu0.14.04.1
-- PHP Version: 7.0.1-2+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api`
--

-- --------------------------------------------------------

--
-- Table structure for table `acciones`
--

CREATE TABLE `acciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asignacion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regla_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `area_mensajerias`
--

CREATE TABLE `area_mensajerias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuentasCorreo_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `area_mensajerias`
--

INSERT INTO `area_mensajerias` (`id`, `nombre`, `cuentasCorreo_id`, `created_at`, `updated_at`) VALUES
(1, 'Correspondencia Clientes', 1, '2018-01-03 10:00:00', '2018-01-15 10:00:00'),
(2, 'Correspondencia Hoteles', 1, '2018-01-10 10:00:00', '2018-01-04 10:00:00'),
(3, 'Pozo de Correspondencia', 1, '2018-01-04 10:00:00', '2018-01-11 10:00:00'),
(7, 'Pozo de Correspondencia', 2, '2018-01-05 04:25:41', '2018-01-05 04:25:41'),
(8, 'Correspondencia Hoteles', 2, '2018-01-05 04:36:43', '2018-01-05 04:36:43');

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido1` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido2` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genero` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ultimaConexion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion_id` int(10) UNSIGNED NOT NULL,
  `datosbanck_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cliente_usuarios`
--

CREATE TABLE `cliente_usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `condicions`
--

CREATE TABLE `condicions` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cumple` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regla_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cuentas_correos`
--

CREATE TABLE `cuentas_correos` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `servidor_entrante` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `puerto_entrante` int(11) NOT NULL,
  `anular_puerto_entrante` tinyint(1) NOT NULL DEFAULT '0',
  `usar_ssl_entrante` tinyint(1) NOT NULL DEFAULT '0',
  `servidor_saliente` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `puerto_saliente` int(11) NOT NULL,
  `anular_puerto_saliente` tinyint(1) NOT NULL DEFAULT '0',
  `usar_ssl_saliente` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cuentas_correos`
--

INSERT INTO `cuentas_correos` (`id`, `email`, `password`, `tipo`, `servidor_entrante`, `puerto_entrante`, `anular_puerto_entrante`, `usar_ssl_entrante`, `servidor_saliente`, `puerto_saliente`, `anular_puerto_saliente`, `usar_ssl_saliente`, `created_at`, `updated_at`) VALUES
(1, 'ddf@df.d', 'dsfdsf', 'Imap', '', 0, 0, 0, '', 0, 0, 0, '2018-01-05 03:55:23', '2018-01-05 03:55:23'),
(2, 'sdfdsf@df.d', 'sdfsdff', 'Imap', '', 0, 0, 0, '', 0, 0, 0, '2018-01-05 04:21:35', '2018-01-05 04:21:35');

-- --------------------------------------------------------

--
-- Table structure for table `datos_bancks`
--

CREATE TABLE `datos_bancks` (
  `id` int(10) UNSIGNED NOT NULL,
  `metodoPago` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resolucion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuenta` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fechaIngreso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fechaTransaccion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuentascorreo_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `direccions`
--

CREATE TABLE `direccions` (
  `id` int(10) UNSIGNED NOT NULL,
  `pais` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigoPostal` int(11) NOT NULL,
  `idPais` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calle` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitud` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitud` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `municipio` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colonia` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numeroEx` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numeroInt` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentos_adjuntos`
--

CREATE TABLE `documentos_adjuntos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentos_solicitars`
--

CREATE TABLE `documentos_solicitars` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estacions`
--

CREATE TABLE `estacions` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estacions`
--

INSERT INTO `estacions` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Registro', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Oferta', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Cobranza', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Verificacion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Reconciliacion', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `galerias`
--

CREATE TABLE `galerias` (
  `id` int(10) UNSIGNED NOT NULL,
  `hotel_id` int(10) UNSIGNED NOT NULL,
  `ruta` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `smallName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '0',
  `direccion_id` int(10) UNSIGNED NOT NULL,
  `marca_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_paquetes`
--

CREATE TABLE `hotel_paquetes` (
  `id` int(10) UNSIGNED NOT NULL,
  `hotel_id` int(10) UNSIGNED NOT NULL,
  `paquete_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_servicios`
--

CREATE TABLE `hotel_servicios` (
  `id` int(10) UNSIGNED NOT NULL,
  `hotel_id` int(10) UNSIGNED NOT NULL,
  `servicio_id` int(10) UNSIGNED NOT NULL,
  `destacado` tinyint(1) NOT NULL,
  `disponible` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marcas`
--

CREATE TABLE `marcas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `url`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Vacaciones Hoy', 'vacacioneshoy.com', 'vacaciones-hoy.jpg', '2017-12-11 10:00:00', '2017-12-27 10:00:00'),
(2, 'Viaje Seguro', 'viajeseguro.com', 'viaje-seguro.jpg', '2017-12-04 10:00:00', '2017-12-27 10:00:00'),
(3, 'Viájale', 'www.viajale.com', 'viajale.png', '2017-12-11 10:00:00', '2017-12-18 10:00:00'),
(4, 'Cuba', 'http://back.resortraffic.com/viajale', 'viajale-2.png', '2017-12-31 16:13:12', '2017-12-31 16:13:12'),
(5, 'vigil', 'http://www.vigil.mx', '10906266_10153023712304729_4584394282862906461_n.jpg', '2017-12-31 16:16:09', '2017-12-31 16:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(10) UNSIGNED NOT NULL,
  `de` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `para` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asunto` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuerpo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rutaPlantilla` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rutaAdjunto` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `areaMensajeria_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2017_12_20_032637_create_acciones_table', 1),
(4, '2017_12_20_032730_create_clientes_table', 1),
(5, '2017_12_20_032854_create_cliente_usuarios_table', 1),
(6, '2017_12_20_032935_create_condicions_table', 1),
(7, '2017_12_20_033057_create_datos_bancks_table', 1),
(8, '2017_12_20_033143_create_direccions_table', 1),
(9, '2017_12_20_033300_create_documentos_adjuntos_table', 1),
(10, '2017_12_20_033517_create_documentos_solicitars_table', 1),
(11, '2017_12_20_033603_create_estacions_table', 1),
(12, '2017_12_20_033627_create_galerias_table', 1),
(13, '2017_12_20_033638_create_hotels_table', 1),
(14, '2017_12_20_033702_create_hotel_paquetes_table', 1),
(15, '2017_12_20_033721_create_hotel_servicios_table', 1),
(16, '2017_12_20_033738_create_mensajes_table', 1),
(17, '2017_12_20_033755_create_notas_table', 1),
(18, '2017_12_20_033805_create_ofertas_table', 1),
(19, '2017_12_20_034254_create_paquetes_table', 1),
(20, '2017_12_20_034308_create_paquete_documentos_table', 1),
(21, '2017_12_20_034322_create_paquete_requisitos_table', 1),
(22, '2017_12_20_034341_create_reglas_table', 1),
(23, '2017_12_20_034355_create_requisitos_table', 1),
(24, '2017_12_20_034406_create_reservas_table', 1),
(25, '2017_12_20_034434_create_respuesta_adjuntos_table', 1),
(26, '2017_12_20_034450_create_respuestas_definidas_table', 1),
(27, '2017_12_20_034503_create_servicios_table', 1),
(28, '2017_12_20_034514_create_telefonos_table', 1),
(29, '2017_12_20_040644_create_usuarios_table', 1),
(30, '2017_12_30_030043_create_marcas_table', 1),
(31, '2018_01_03_040030_create_departamentos_table', 1),
(32, '2018_01_03_214253_create_cuentas_correos_table', 1),
(33, '2018_01_03_215815_create_area_mensajerias_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notas`
--

CREATE TABLE `notas` (
  `id` int(10) UNSIGNED NOT NULL,
  `autor` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenido` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `destacada` tinyint(1) NOT NULL DEFAULT '0',
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ofertas`
--

CREATE TABLE `ofertas` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paquetes`
--

CREATE TABLE `paquetes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` int(11) NOT NULL,
  `moneda` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maximoAdulto` int(11) NOT NULL,
  `maximoNino` int(11) NOT NULL,
  `cantidadDias` int(11) NOT NULL,
  `cantidadNoches` int(11) NOT NULL,
  `costoAdicional` int(11) NOT NULL,
  `costosPersonaAdicional` int(11) NOT NULL,
  `costosXcancelacion` int(11) NOT NULL,
  `costosXaplazar` int(11) NOT NULL,
  `costosXaplaza2` int(11) NOT NULL,
  `costosXaplaza3` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `destacado` tinyint(1) NOT NULL DEFAULT '0',
  `activo` tinyint(1) NOT NULL DEFAULT '0',
  `disponible` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paquete_documentos`
--

CREATE TABLE `paquete_documentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `documentos_id` int(10) UNSIGNED NOT NULL,
  `paquete_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paquete_requisitos`
--

CREATE TABLE `paquete_requisitos` (
  `id` int(10) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `requisito_id` int(10) UNSIGNED NOT NULL,
  `paquete_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reglas`
--

CREATE TABLE `reglas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cumple` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requisitos`
--

CREATE TABLE `requisitos` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id` int(10) UNSIGNED NOT NULL,
  `cantAdulto` int(11) NOT NULL,
  `cantidadMenores` int(11) NOT NULL,
  `fechaLlegada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fechaSalida` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `operacion` int(11) NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `paquete_id` int(10) UNSIGNED NOT NULL,
  `estacion_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `respuestas_definidas`
--

CREATE TABLE `respuestas_definidas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asunto` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `encabezado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenido` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pie` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estacion_id` int(10) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `respuesta_adjuntos`
--

CREATE TABLE `respuesta_adjuntos` (
  `id` int(10) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `respuestasdefinidas_id` int(10) UNSIGNED NOT NULL,
  `documentosadjuntos_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servicios`
--

CREATE TABLE `servicios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Wifi gratis', 'wifi.png', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Alberca exterior', 'alberca-w.png', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'TV por cable', 'alberca.png', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Servicio a cuarto', 'desayuno-w.png', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Estacionamiento', 'desayuno.png', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Lavandería y tintorería', 'wifi-w.png', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Área infantil', 'wifi.png', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `telefonos`
--

CREATE TABLE `telefonos` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instancias` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED NOT NULL,
  `departamento_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acciones`
--
ALTER TABLE `acciones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area_mensajerias`
--
ALTER TABLE `area_mensajerias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clientes_email_unique` (`email`);

--
-- Indexes for table `cliente_usuarios`
--
ALTER TABLE `cliente_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `condicions`
--
ALTER TABLE `condicions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cuentas_correos`
--
ALTER TABLE `cuentas_correos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `datos_bancks`
--
ALTER TABLE `datos_bancks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `direccions`
--
ALTER TABLE `direccions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documentos_adjuntos`
--
ALTER TABLE `documentos_adjuntos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documentos_solicitars`
--
ALTER TABLE `documentos_solicitars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estacions`
--
ALTER TABLE `estacions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galerias`
--
ALTER TABLE `galerias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_paquetes`
--
ALTER TABLE `hotel_paquetes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_servicios`
--
ALTER TABLE `hotel_servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ofertas`
--
ALTER TABLE `ofertas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paquetes`
--
ALTER TABLE `paquetes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paquete_documentos`
--
ALTER TABLE `paquete_documentos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paquete_requisitos`
--
ALTER TABLE `paquete_requisitos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `reglas`
--
ALTER TABLE `reglas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requisitos`
--
ALTER TABLE `requisitos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `respuestas_definidas`
--
ALTER TABLE `respuestas_definidas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `respuesta_adjuntos`
--
ALTER TABLE `respuesta_adjuntos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `telefonos`
--
ALTER TABLE `telefonos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acciones`
--
ALTER TABLE `acciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `area_mensajerias`
--
ALTER TABLE `area_mensajerias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cliente_usuarios`
--
ALTER TABLE `cliente_usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `condicions`
--
ALTER TABLE `condicions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cuentas_correos`
--
ALTER TABLE `cuentas_correos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `datos_bancks`
--
ALTER TABLE `datos_bancks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `direccions`
--
ALTER TABLE `direccions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documentos_adjuntos`
--
ALTER TABLE `documentos_adjuntos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documentos_solicitars`
--
ALTER TABLE `documentos_solicitars`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `estacions`
--
ALTER TABLE `estacions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `galerias`
--
ALTER TABLE `galerias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hotel_paquetes`
--
ALTER TABLE `hotel_paquetes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hotel_servicios`
--
ALTER TABLE `hotel_servicios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ofertas`
--
ALTER TABLE `ofertas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `paquete_documentos`
--
ALTER TABLE `paquete_documentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `paquete_requisitos`
--
ALTER TABLE `paquete_requisitos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reglas`
--
ALTER TABLE `reglas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `requisitos`
--
ALTER TABLE `requisitos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `respuestas_definidas`
--
ALTER TABLE `respuestas_definidas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `respuesta_adjuntos`
--
ALTER TABLE `respuesta_adjuntos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `telefonos`
--
ALTER TABLE `telefonos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
