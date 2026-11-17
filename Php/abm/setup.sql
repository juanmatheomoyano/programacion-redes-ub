-- =============================================================
--  ABM Notas de Credito  |  Juan Moyano  |  Tabla 9
--  Ejecutar en Clever Cloud > phpMyAdmin
-- =============================================================

CREATE DATABASE IF NOT EXISTS sistema_notas_credito
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_spanish_ci;

USE sistema_notas_credito;

-- -------------------------------------------------------------
-- 1. Tabla auxiliar parametrizable
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tipos_comprobante (
    codigoTipo     VARCHAR(2)   NOT NULL PRIMARY KEY,
    DescripcionTipo VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tipos_comprobante (codigoTipo, DescripcionTipo) VALUES
  ('01', 'Diferencia de Cambio'),
  ('02', 'Bonificacion de producto'),
  ('03', 'Devolucion de producto')
ON DUPLICATE KEY UPDATE DescripcionTipo = VALUES(DescripcionTipo);

-- -------------------------------------------------------------
-- 2. Tabla principal  (Tabla 9 asignada a Juan Moyano)
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS notas_credito (
    idNota                 INT           AUTO_INCREMENT PRIMARY KEY,
    NroComprobante         VARCHAR(20)   NOT NULL UNIQUE,
    NroDeFacturaImputada   VARCHAR(20)   NOT NULL,
    codigoTipo             VARCHAR(2)    NOT NULL,
    CodCliente             VARCHAR(15)   NOT NULL,
    Observaciones          TEXT,
    fechaComprobante       DATE          NOT NULL,
    TotalNetoComprobante   DECIMAL(12,2) NOT NULL,
    pdfComprobante         LONGBLOB,
    CONSTRAINT fk_tipo FOREIGN KEY (codigoTipo)
        REFERENCES tipos_comprobante(codigoTipo)
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO notas_credito
    (NroComprobante, NroDeFacturaImputada, codigoTipo, CodCliente,
     Observaciones, fechaComprobante, TotalNetoComprobante)
VALUES
  ('NC-0001-00001001','FC-0001-00005420','01','CLI-0101',
   'Ajuste de cambio cierre mensual','2026-10-15',12500.50),
  ('NC-0001-00001002','FC-0001-00005488','02','CLI-0205',
   'Bonificacion por volumen trimestral','2026-10-18',45000.00),
  ('NC-0001-00001003','FC-0001-00005510','03','CLI-0144',
   'Devolucion por producto defectuoso','2026-10-22',28900.75)
ON DUPLICATE KEY UPDATE Observaciones = VALUES(Observaciones);

-- -------------------------------------------------------------
-- 3. Tabla de usuarios con contador de sesiones
-- NOTA: La tabla ya existe en el DB (creada por ejer24).
-- Solo agregar el usuario ABM con SHA-256 si no existe.
-- sha256('1234') = 03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4
-- -------------------------------------------------------------
INSERT INTO usuarios (login, Apellido, Nombres, Password, ContadorSesiones)
VALUES ('juan','Moyano','Juan',
        '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 0)
ON DUPLICATE KEY UPDATE Apellido = VALUES(Apellido);
