use new_Horas_Extras;

INSERT INTO
    Config (LIMIT_HE)
VALUES
    (48)

INSERT INTO
    HorasExtras_Estados (nombre)
VALUES
    ('APROBADO'),
    ('RECHAZO'),
    ('APROBACION_JEFE'),
    ('APROBACION_GERENTE'),
    ('RECHAZO_GERENTE'),
    ('APROBACION_RH'),
    ('RECHAZO_RH'),
    ('APROBACION_CONTABLE'),
    ('RECHAZO_CONTABLE'),
    ('EDICION');

INSERT INTO
    HorasExtras_TipoComentario (nombre, icon)
VALUES
    ('APROBADO', 'fas fa-check bg-success'),
    ('RECHAZADO', 'fas fa-times bg-red'),
    ('N/A', 'fas fa-question bg-info');

INSERT INTO
    HorasExtras_Aprobador_Tipo (nombre)
VALUES
    ('N/A'),
    ('JEFE'),
    ('GERENTE');

INSERT INTO
    HorasExtras_Aprobador_Gestiona (nombre)
VALUES
    ('N/A'),
    ('RH'),
    ('CONTABLE');

INSERT INTO
    HorasExtras_Aprobador_Administra (nombre)
VALUES
    ('N/A'),
    ('SI'),
    ('NO');

INSERT INTO
    HorasExtras_Aprobador_SolicitudPersonal (nombre)
VALUES
    ('N/A'),
    ('SI'),
    ('NO');

INSERT INTO
    requisicion_proceso (nombre)
VALUES
    ('CARGOS OPERATIVOS'),
    ('CARGOS ADMINISTRATIVOS'),
    ('CARGOS TECNICO - COMERCIALES'),
    ('CARGOS DIRECTIVOS'),
    ('CARGOS GERENCIALES');

INSERT INTO
    requisicion_contrato (nombre)
VALUES
    ('Indefinido'),
    ('Obra labor'),
    ('Fijo'),
    ('Freelance');

INSERT INTO
    requisicion_meses (nombre)
VALUES
    ('1 Mes'),
    ('2 Meses'),
    ('3 Meses'),
    ('6 Meses');

INSERT INTO
    requisicion_horario (nombre)
VALUES
    ('Oficina'),
    ('Turnos'),
    ('Inspectores');

INSERT INTO
    requisicion_motivo (nombre)
VALUES
    ('Retiro / Renuncia Empleado'),
    ('Reemplazo por Maternidad / Incapacidad'),
    ('Nuevo Cargo'),
    ('Nuevo Cupo Nómina');

INSERT INTO
    requisicion_recursos (nombre)
VALUES
    ('Computador'),
    ('Tablet'),
    ('Celular'),
    ('Equipo de medición');


-- Gestion Talento Humano: verificación de hoja de vida
-- 1. candidatos selecionado: Pasa a estado "Evaluacion de ingreso de candidatos"
-- 1.1 Evaluacion de candidatos:
-- * caso ipotetico no pasa la pruebas queda nuevamente abierto 
-- * si pasan las pruebas poder selecionar la hoja de vida 
-- * ...
-- 2. candidatos rechazados:  Pasa a estado "Evaluacion de hojas de vida - Abierto para cargar hojas de vida"

-- Para reportes: gestion de pendientes y demas
-- Crear historico

INSERT INTO
    requisicion_estado (nombre)
VALUES
    ('Pendiente'),
    ('Aprobado jefe'),
    ('Rechazo jefe'),
    
    ('Rechazado'),
    ('Gestion Talento Humano'),
    ('Cancelado') -- motivo de cancelación (gestion por parte de rh)
    ;

INSERT INTO
    requisicion_estado_hojas_de_vida (nombre)
VALUES
    ('Pendiente'),
    ('Aprobado'),
    ('Rechazado');