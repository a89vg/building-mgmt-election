-- migrate:up
ALTER TABLE candidatos ADD COLUMN costo_propuesta_limpieza VARCHAR(100) NULL;
ALTER TABLE candidatos ADD COLUMN costo_propuesta_mantenimiento VARCHAR(100) NULL;
ALTER TABLE candidatos ADD COLUMN costo_propuesta_vigilancia VARCHAR(100) NULL;

-- migrate:down
ALTER TABLE candidatos DROP COLUMN costo_propuesta_limpieza;
ALTER TABLE candidatos DROP COLUMN costo_propuesta_mantenimiento;
ALTER TABLE candidatos DROP COLUMN costo_propuesta_vigilancia;
