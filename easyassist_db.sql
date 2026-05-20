-- =============================================
--  EasyAssist · Base de datos PostgreSQL
--  Servidor: 192.168.1.11
--  Base de datos: easyassist_db
--  Usuario: root
-- =============================================

-- Ejecutar como superusuario si la BD no existe:
-- CREATE DATABASE easyassist_db ENCODING 'UTF8';

\c easyassist_db;

-- ---------------------------------------------
--  CATEGORÍAS
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS categorias (
  id         SERIAL PRIMARY KEY,
  nombre     VARCHAR(100) NOT NULL,
  slug       VARCHAR(100) NOT NULL UNIQUE,
  creado_en  TIMESTAMP DEFAULT NOW()
);

INSERT INTO categorias (nombre, slug) VALUES
  ('General',       'general'),
  ('Sin Internet',  'sin-internet'),
  ('Roaming',       'roaming'),
  ('Datos Móviles', 'datos')
ON CONFLICT (slug) DO NOTHING;

-- ---------------------------------------------
--  PÁGINAS
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS paginas (
  id           SERIAL PRIMARY KEY,
  nombre       VARCHAR(150) NOT NULL,
  ruta         VARCHAR(255) NOT NULL,
  categoria_id INT NOT NULL REFERENCES categorias(id),
  activo       BOOLEAN DEFAULT TRUE,
  creado_en    TIMESTAMP DEFAULT NOW()
);

-- General (IDs 1-6)
INSERT INTO paginas (nombre, ruta, categoria_id) VALUES
  ('Presentación',   'general/presentacion.html',  1),
  ('Selección',      'general/seleccion.html',      1),
  ('Contacto',       'general/contacto.html',       1),
  ('Login',          'general/login.html',          1),
  ('Registro',       'general/registro.html',       1),
  ('Quiénes somos',  'general/quienes_somos.html',  1);

-- Sin Internet (IDs 7-22)
INSERT INTO paginas (nombre, ruta, categoria_id) VALUES
  ('Internet',            'sin-internet/internet.html',           2),
  ('Router Sí',           'sin-internet/router_si.html',          2),
  ('Router No',           'sin-internet/router_no.html',          2),
  ('Cableado',            'sin-internet/cableado.html',           2),
  ('Resultado Cableado',  'sin-internet/resultado_cableado.html', 2),
  ('Cable Dañado',        'sin-internet/cable_dañado.html',       2),
  ('Luces',               'sin-internet/luces.html',              2),
  ('Repetidores',         'sin-internet/repetidores.html',        2),
  ('Repetidores Sí',      'sin-internet/repetidores_si.html',     2),
  ('Reinicio',            'sin-internet/reinicio.html',           2),
  ('Reinicio 2',          'sin-internet/reinicio2.html',          2),
  ('Reset',               'sin-internet/reset.html',              2),
  ('Reset OK',            'sin-internet/reset_ok.html',           2),
  ('Verificación',        'sin-internet/verificacion.html',       2),
  ('Avería',              'sin-internet/averia.html',             2),
  ('Fin',                 'sin-internet/fin.html',                2);

-- Roaming (IDs 23-46)
INSERT INTO paginas (nombre, ruta, categoria_id) VALUES
  ('Roaming',                     'roaming/roaming.html',                     3),
  ('Roaming SO',                  'roaming/roaming_so.html',                  3),
  ('Roaming Android Marca',       'roaming/roaming_android_marca.html',       3),
  ('Roaming Activar',             'roaming/roaming_activar.html',             3),
  ('Roaming Problemas',           'roaming/roaming_problemas.html',           3),
  ('Roaming Problemas 2',         'roaming/roaming_problemas2.html',          3),
  ('Roaming Problemas 3',         'roaming/roaming_problemas3.html',          3),
  ('Roaming Reiniciar',           'roaming/roaming_reiniciar.html',           3),
  ('Roaming Verificación',        'roaming/roaming_verificacion.html',        3),
  ('Roaming Compañía',            'roaming/roaming_compania.html',            3),
  ('Roaming Fin',                 'roaming/roaming_fin.html',                 3),
  ('Roaming Consejos',            'roaming/roaming_consejos.html',            3),
  ('Roaming Samsung Activar',     'roaming/roaming_samsung_activar.html',     3),
  ('Roaming Samsung Problemas',   'roaming/roaming_samsung_problemas.html',   3),
  ('Roaming Samsung Problemas 2', 'roaming/roaming_samsung_problemas2.html',  3),
  ('Roaming Samsung Problemas 3', 'roaming/roaming_samsung_problemas3.html',  3),
  ('Roaming Samsung Reiniciar',   'roaming/roaming_samsung_reiniciar.html',   3),
  ('Roaming Samsung Verif.',      'roaming/roaming_samsung_verificacion.html',3),
  ('Roaming Xiaomi Activar',      'roaming/roaming_xiaomi_activar.html',      3),
  ('Roaming Xiaomi Problemas',    'roaming/roaming_xiaomi_problemas.html',    3),
  ('Roaming Xiaomi Problemas 2',  'roaming/roaming_xiaomi_problemas2.html',   3),
  ('Roaming Xiaomi Problemas 3',  'roaming/roaming_xiaomi_problemas3.html',   3),
  ('Roaming Xiaomi Reiniciar',    'roaming/roaming_xiaomi_reiniciar.html',    3),
  ('Roaming Xiaomi Verif.',       'roaming/roaming_xiaomi_verificacion.html', 3);

-- Datos Móviles (IDs 47-69)
INSERT INTO paginas (nombre, ruta, categoria_id) VALUES
  ('Datos',                     'datos/datos.html',                     4),
  ('Datos SO',                  'datos/datos_so.html',                  4),
  ('Datos Android Marca',       'datos/datos_android_marca.html',       4),
  ('Datos iPhone Sin',          'datos/datos_iphone_sin.html',          4),
  ('Datos iPhone Lento',        'datos/datos_iphone_lento.html',        4),
  ('Datos iPhone SIM',          'datos/datos_iphone_sim.html',          4),
  ('Datos iPhone Avión',        'datos/datos_iphone_avion.html',        4),
  ('Datos iPhone Reiniciar',    'datos/datos_iphone_reiniciar.html',    4),
  ('Datos iPhone Verificación', 'datos/datos_iphone_verificacion.html', 4),
  ('Datos iPhone Fin',          'datos/datos_iphone_fin.html',          4),
  ('Datos Compañía',            'datos/datos_compania.html',            4),
  ('Datos Samsung Sin',         'datos/datos_samsung_sin.html',         4),
  ('Datos Samsung Lento',       'datos/datos_samsung_lento.html',       4),
  ('Datos Samsung SIM',         'datos/datos_samsung_sim.html',         4),
  ('Datos Samsung Avión',       'datos/datos_samsung_avion.html',       4),
  ('Datos Samsung Reiniciar',   'datos/datos_samsung_reiniciar.html',   4),
  ('Datos Samsung Verif.',      'datos/datos_samsung_verificacion.html',4),
  ('Datos Xiaomi Sin',          'datos/datos_xiaomi_sin.html',          4),
  ('Datos Xiaomi Lento',        'datos/datos_xiaomi_lento.html',        4),
  ('Datos Xiaomi SIM',          'datos/datos_xiaomi_sim.html',          4),
  ('Datos Xiaomi Avión',        'datos/datos_xiaomi_avion.html',        4),
  ('Datos Xiaomi Reiniciar',    'datos/datos_xiaomi_reiniciar.html',    4),
  ('Datos Xiaomi Verif.',       'datos/datos_xiaomi_verificacion.html', 4);

-- ---------------------------------------------
--  TRANSICIONES
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS transiciones (
  id                INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  pagina_actual_id  INT NOT NULL REFERENCES paginas(id),
  boton             VARCHAR(50) NOT NULL,
  pagina_destino_id INT NOT NULL REFERENCES paginas(id),
  activo            BOOLEAN DEFAULT TRUE,
  creado_en         TIMESTAMP DEFAULT NOW()
);

-- Sin Internet
INSERT INTO transiciones (pagina_actual_id, boton, pagina_destino_id) VALUES
  (7,  'si',        8),   -- internet → router_si
  (7,  'no',        9),   -- internet → router_no
  (8,  'continuar', 10),  -- router_si → cableado
  (9,  'continuar', 7),   -- router_no → internet
  (10, 'continuar', 11),  -- cableado → resultado_cableado
  (11, 'bien',      13),  -- resultado_cableado → luces
  (11, 'dañado',    12),  -- resultado_cableado → cable_dañado
  (13, 'si',        15),  -- luces → repetidores
  (13, 'no',        17),  -- luces → reinicio
  (15, 'si',        16),  -- repetidores → repetidores_si
  (15, 'no',        17),  -- repetidores → reinicio
  (16, 'continuar', 19),  -- repetidores_si → verificacion
  (17, 'continuar', 18),  -- reinicio → reinicio2
  (18, 'si',        22),  -- reinicio2 → fin
  (18, 'no',        20),  -- reinicio2 → reset
  (20, 'continuar', 21),  -- reset → reset_ok
  (21, 'si',        22),  -- reset_ok → fin
  (21, 'no',        21),  -- reset_ok → averia
  (19, 'si',        22),  -- verificacion → fin
  (19, 'no',        21);  -- verificacion → averia

-- Roaming
INSERT INTO transiciones (pagina_actual_id, boton, pagina_destino_id) VALUES
  (23, 'activar',   24),  -- roaming → roaming_so
  (23, 'problemas', 24),  -- roaming → roaming_so
  (23, 'consejos',  34),  -- roaming → roaming_consejos
  (24, 'iphone',    26),  -- roaming_so → roaming_activar
  (24, 'android',   25),  -- roaming_so → roaming_android_marca
  (26, 'hecho',     33),  -- roaming_activar → roaming_fin
  (27, 'si',        28),  -- roaming_problemas → roaming_problemas2
  (27, 'no',        26),  -- roaming_problemas → roaming_activar
  (28, 'continuar', 29),  -- roaming_problemas2 → roaming_problemas3
  (29, 'continuar', 30),  -- roaming_problemas3 → roaming_reiniciar
  (30, 'continuar', 31),  -- roaming_reiniciar → roaming_verificacion
  (31, 'si',        33),  -- roaming_verificacion → roaming_fin
  (31, 'no',        32);  -- roaming_verificacion → roaming_compania

-- Datos
INSERT INTO transiciones (pagina_actual_id, boton, pagina_destino_id) VALUES
  (47, 'sin',       48),  -- datos → datos_so
  (47, 'lento',     48),  -- datos → datos_so
  (48, 'iphone',    50),  -- datos_so → datos_iphone_sin
  (48, 'android',   49),  -- datos_so → datos_android_marca
  (50, 'continuar', 52),  -- datos_iphone_sin → datos_iphone_sim
  (51, 'continuar', 52),  -- datos_iphone_lento → datos_iphone_sim
  (52, 'continuar', 53),  -- datos_iphone_sim → datos_iphone_avion
  (53, 'continuar', 54),  -- datos_iphone_avion → datos_iphone_reiniciar
  (54, 'continuar', 55),  -- datos_iphone_reiniciar → datos_iphone_verificacion
  (55, 'si',        56),  -- datos_iphone_verificacion → datos_iphone_fin
  (55, 'no',        57);  -- datos_iphone_verificacion → datos_compania

-- ---------------------------------------------
--  SPEEDTEST (IDs 70-73)
-- ---------------------------------------------
INSERT INTO paginas (nombre, ruta, categoria_id) VALUES
  ('Speedtest',           'speedtest/speedtest.html',          1),
  ('Speedtest Cable',     'speedtest/speedtest_cable.html',    1),
  ('Speedtest Test',      'speedtest/speedtest_test.html',     1),
  ('Speedtest Resultado', 'speedtest/speedtest_resultado.html',1);

-- Transiciones speedtest
INSERT INTO transiciones (pagina_actual_id, boton, pagina_destino_id) VALUES
  (70, 'continuar',  71),  -- speedtest → speedtest_cable
  (71, 'cable',      72),  -- speedtest_cable → speedtest_test
  (71, 'wifi',       72),  -- speedtest_cable → speedtest_test (sin cable)
  (72, 'bueno',      73),  -- speedtest_test → resultado bueno
  (72, 'malo',       73),  -- speedtest_test → resultado malo
  -- Desde seleccion
  (2,  'speedtest',  70),  -- seleccion → speedtest
  (2,  'internet',   7),   -- seleccion → sin-internet
  (2,  'datos',      47),  -- seleccion → datos
  (2,  'roaming',    23);  -- seleccion → roaming

-- Transiciones roaming con marca y modo guardados en BD
-- roaming_so → según modo
INSERT INTO transiciones (pagina_actual_id, boton, pagina_destino_id) VALUES
  -- roaming_so: iphone según modo
  (24, 'iphone_activar',   26),  -- roaming_so iphone+activar → roaming_activar
  (24, 'iphone_problemas', 27),  -- roaming_so iphone+problemas → roaming_problemas
  (24, 'samsung_activar',  35),  -- roaming_so samsung+activar → roaming_samsung_activar
  (24, 'samsung_problemas',36),  -- roaming_so samsung+problemas → roaming_samsung_problemas
  (24, 'xiaomi_activar',   41),  -- roaming_so xiaomi+activar → roaming_xiaomi_activar
  (24, 'xiaomi_problemas', 42),  -- roaming_so xiaomi+problemas → roaming_xiaomi_problemas
  -- roaming_android_marca según modo
  (25, 'samsung_activar',  35),
  (25, 'samsung_problemas',36),
  (25, 'xiaomi_activar',   41),
  (25, 'xiaomi_problemas', 42),
  -- roaming_activar (iphone) según modo
  (26, 'hecho_activar',    33),  -- → roaming_fin
  (26, 'hecho_problemas',  28),  -- → roaming_problemas2
  -- roaming_samsung_activar según modo
  (35, 'hecho_activar',    33),
  (35, 'hecho_problemas',  37),  -- → roaming_samsung_problemas2
  -- roaming_xiaomi_activar según modo
  (41, 'hecho_activar',    33),
  (41, 'hecho_problemas',  43),  -- → roaming_xiaomi_problemas2
  -- roaming_fin: sigue sin funcionar según marca
  (33, 'sin_funcionar_iphone',  28),
  (33, 'sin_funcionar_samsung', 37),
  (33, 'sin_funcionar_xiaomi',  43),
  -- datos_so según tipo y marca
  (48, 'iphone_sin',    50),
  (48, 'iphone_lento',  51),
  (48, 'android',       49),
  -- datos_android_marca según tipo
  (49, 'samsung_sin',   58),
  (49, 'samsung_lento', 59),
  (49, 'xiaomi_sin',    64),
  (49, 'xiaomi_lento',  65),
  -- datos_iphone_sim volver según tipo
  (52, 'volver_sin',    50),
  (52, 'volver_lento',  51),
  -- datos_samsung_sim volver según tipo
  (60, 'volver_sin',    58),
  (60, 'volver_lento',  59),
  -- datos_xiaomi_sim volver según tipo
  (66, 'volver_sin',    64),
  (66, 'volver_lento',  65);
