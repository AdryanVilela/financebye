-- Atualizações para a tabela de usuários

-- Adicionar o campo ultimo_acesso se não existir
ALTER TABLE usuarios 
ADD COLUMN IF NOT EXISTS ultimo_acesso DATETIME NULL AFTER created_at;

-- Adicionar o campo status se não existir
ALTER TABLE usuarios 
ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'ativo' AFTER ativo;

-- Preencher o campo ultimo_acesso para usuários existentes
UPDATE usuarios 
SET ultimo_acesso = created_at
WHERE ultimo_acesso IS NULL;

-- Preencher o campo status para usuários existentes
UPDATE usuarios 
SET status = CASE WHEN ativo = 1 THEN 'ativo' ELSE 'inativo' END
WHERE status IS NULL;

-- Atualizar timestamp de created_at para usuários existentes sem esse valor
UPDATE usuarios 
SET created_at = NOW()
WHERE created_at IS NULL; 