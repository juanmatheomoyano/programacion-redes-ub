-- Tabla auxiliar: tipos de comprobante
CREATE TABLE IF NOT EXISTS tipos_comprobante (
    codigoTipo    VARCHAR(5)   NOT NULL PRIMARY KEY,
    DescripcionTipo VARCHAR(100) NOT NULL
);

INSERT INTO tipos_comprobante (codigoTipo, DescripcionTipo) VALUES
('01', 'Diferencia de Cambio'),
('02', 'Bonificación de producto'),
('03', 'Devolución de producto');

-- Tabla principal: notas de crédito de ventas
CREATE TABLE IF NOT EXISTS notas_credito (
    idRegistro             INT AUTO_INCREMENT PRIMARY KEY,
    NroComprobante         VARCHAR(20)  NOT NULL,
    NroDeFacturaImputada   VARCHAR(20)  NOT NULL,
    codigoTipo             VARCHAR(5)   NOT NULL,
    CodCliente             VARCHAR(20)  NOT NULL,
    Observaciones          TEXT,
    fechaComprobante       DATE,
    TotalNetoComprobante   DOUBLE,
    archivo                LONGBLOB,
    FOREIGN KEY (codigoTipo) REFERENCES tipos_comprobante(codigoTipo)
);

INSERT INTO notas_credito (NroComprobante, NroDeFacturaImputada, codigoTipo, CodCliente, Observaciones, fechaComprobante, TotalNetoComprobante) VALUES
('NC-0001-00004512', 'FC-0001-00008920', '03', 'CLI-1042', 'Devolución por producto defectuoso en lote B', '2026-03-15', 154500.50),
('NC-0001-00004513', 'FC-0001-00009105', '02', 'CLI-0387', 'Bonificación acordada en contrato anual', '2026-03-18', 32000.00),
('NC-0001-00004514', 'FC-0001-00009230', '01', 'CLI-2201', 'Ajuste por diferencia de tipo de cambio del día', '2026-03-22', 87300.75),
('NC-0001-00004515', 'FC-0001-00009411', '03', 'CLI-0590', 'Devolución parcial de mercadería dañada en transporte', '2026-04-01', 210000.00),
('NC-0001-00004516', 'FC-0001-00009580', '02', 'CLI-1755', 'Descuento por volumen de compra del trimestre', '2026-04-05', 45800.25);

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    idUsuario        INT AUTO_INCREMENT PRIMARY KEY,
    login            VARCHAR(50)  NOT NULL UNIQUE,
    Apellido         VARCHAR(50)  NOT NULL,
    Nombres          VARCHAR(50)  NOT NULL,
    Password         VARCHAR(100) NOT NULL,
    ContadorSesiones INT(5)       DEFAULT 0
);

INSERT INTO usuarios (login, Apellido, Nombres, Password, ContadorSesiones) VALUES
('jmoyano', 'Moyano', 'Juan', SHA1('1234'), 0);
