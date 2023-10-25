CREATE DATABASE new_Horas_Extras;
use new_Horas_Extras;

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'Config' and xtype = 'U')
CREATE table Config (
    id int identity (1, 1),
    LIMIT_HE int default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'HorasExtras_Estados' and xtype = 'U')
CREATE table HorasExtras_Estados (
    id int identity (1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = '  ' and xtype = 'U')
CREATE table HorasExtras_TipoComentario(
    id int identity (1, 1),
    nombre varchar(max) default null,
    icon varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'HorasExtras_Comentario' and xtype = 'U')
CREATE table HorasExtras_Comentario (
    id int identity(1, 1),
    fechaRegistro datetime default CURRENT_TIMESTAMP,
    titulo varchar(max) default null,
    cuerpo varchar(max) default null,
    id_reporte int default null,
    id_tipoComentario int default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'HorasExtras_Historial_Reportes' and xtype = 'U')
CREATE table HorasExtras_Historial_Reportes (
    id int identity (1, 1),
    fechaRegistro datetime default CURRENT_TIMESTAMP,
    id_reporte int default null,
    titulo varchar(max) default null,
    cuerpo varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'HorasExtras_Aprobador_Tipo' and xtype = 'U')
CREATE TABLE HorasExtras_Aprobador_Tipo (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'HorasExtras_Aprobador_Gestiona' and xtype = 'U')
CREATE TABLE HorasExtras_Aprobador_Gestiona (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'HorasExtras_Aprobador_Administra' and xtype = 'U')
CREATE TABLE HorasExtras_Aprobador_Administra (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'HorasExtras_Aprobador_SolicitudPersonal' and xtype = 'U')
CREATE TABLE HorasExtras_Aprobador_SolicitudPersonal (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'requisicion_proceso' and xtype = 'U')
CREATE TABLE requisicion_proceso (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'requisicion_contrato' and xtype = 'U')
CREATE TABLE requisicion_contrato (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'requisicion_meses' and xtype = 'U')
CREATE TABLE requisicion_meses (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'requisicion_horario' and xtype = 'U')
CREATE TABLE requisicion_horario (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'requisicion_motivo' and xtype = 'U')
CREATE TABLE requisicion_motivo (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'requisicion_recursos' and xtype = 'U')
CREATE TABLE requisicion_recursos (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'requisicion_estado' and xtype = 'U')
CREATE TABLE requisicion_estado (
    id INT identity(1, 1),
    nombre varchar(max) default null
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'requisicion_hojas_de_vida' and xtype = 'U')
CREATE TABLE requisicion_hojas_de_vida (
    id INT identity(1, 1),
    fechaRegistro datetime default CURRENT_TIMESTAMP,
    id_requisicion int,
    hojasDeVida varchar(max) default null,
    estado int
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = 'requisicion_estado_hojas_de_vida' and xtype = 'U')
CREATE TABLE requisicion_estado_hojas_de_vida (
    id INT identity(1, 1),
    nombre varchar(max) default null
);