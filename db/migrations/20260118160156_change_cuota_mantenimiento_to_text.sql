-- migrate:up
ALTER TABLE candidatos MODIFY COLUMN cuota_mantenimiento_propuesta VARCHAR(255) NULL;

-- migrate:down
ALTER TABLE candidatos MODIFY COLUMN cuota_mantenimiento_propuesta DECIMAL(10,2) NULL;
