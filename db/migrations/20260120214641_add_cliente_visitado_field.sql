-- migrate:up
ALTER TABLE candidatos ADD COLUMN cliente_visitado BOOLEAN DEFAULT FALSE;

-- migrate:down
ALTER TABLE candidatos DROP COLUMN cliente_visitado;
