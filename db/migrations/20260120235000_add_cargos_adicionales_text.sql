-- migrate:up
ALTER TABLE candidatos ADD COLUMN cargos_adicionales_texto TEXT NULL;

-- Migrate existing data from candidato_cargos_adicionales table
UPDATE candidatos c
SET cargos_adicionales_texto = (
    SELECT GROUP_CONCAT(cargo SEPARATOR '\n')
    FROM candidato_cargos_adicionales
    WHERE candidato_id = c.id
)
WHERE EXISTS (
    SELECT 1 FROM candidato_cargos_adicionales WHERE candidato_id = c.id
);

-- migrate:down
ALTER TABLE candidatos DROP COLUMN cargos_adicionales_texto;
