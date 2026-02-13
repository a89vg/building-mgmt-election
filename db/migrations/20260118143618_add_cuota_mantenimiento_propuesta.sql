-- migrate:up
ALTER TABLE candidatos ADD COLUMN cuota_mantenimiento_propuesta DECIMAL(10,2) NULL;

-- migrate:down
ALTER TABLE candidatos DROP COLUMN cuota_mantenimiento_propuesta;
