-- migrate:up
ALTER TABLE candidatos ADD COLUMN mostrar_en_listado BOOLEAN DEFAULT TRUE AFTER estatus;

-- migrate:down
ALTER TABLE candidatos DROP COLUMN mostrar_en_listado;
