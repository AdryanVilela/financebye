-- Criação da tabela de metas financeiras
CREATE TABLE IF NOT EXISTS `metas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `valor_alvo` decimal(10,2) NOT NULL,
  `valor_atual` decimal(10,2) DEFAULT 0.00,
  `data_inicio` date NOT NULL,
  `data_alvo` date NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `icone` varchar(50) DEFAULT NULL,
  `cor` varchar(20) DEFAULT NULL,
  `status` enum('em_andamento','concluida','cancelada') NOT NULL DEFAULT 'em_andamento',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_metas_usuario` (`usuario_id`),
  KEY `fk_metas_empresa` (`empresa_id`),
  KEY `fk_metas_categoria` (`categoria_id`),
  CONSTRAINT `fk_metas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_metas_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_metas_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserir exemplo de metas para demonstração (opcional)
INSERT INTO `metas` (`usuario_id`, `empresa_id`, `titulo`, `descricao`, `valor_alvo`, `valor_atual`, `data_inicio`, `data_alvo`, `categoria_id`, `icone`, `cor`, `status`, `created_at`, `updated_at`) 
VALUES
(1, 1, 'Viagem para o Nordeste', 'Férias de verão com a família no próximo ano', 5000.00, 1500.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 6 MONTH), NULL, 'fa-plane', '#f39c12', 'em_andamento', NOW(), NOW()),
(1, 1, 'Notebook novo', 'Comprar um notebook gamer para trabalhar e jogar', 4000.00, 2500.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), NULL, 'fa-laptop', '#3c8dbc', 'em_andamento', NOW(), NOW()),
(1, 1, 'Curso de especialização', 'Fazer uma pós-graduação na área de finanças', 3000.00, 3000.00, DATE_SUB(CURDATE(), INTERVAL 4 MONTH), CURDATE(), NULL, 'fa-graduation-cap', '#00a65a', 'concluida', NOW(), NOW());

-- Adicionar link no menu (Atualize esta parte de acordo com a estrutura de menu do seu sistema)
-- Se você tiver uma tabela de menu, adicione um registro para o módulo de metas
-- Exemplo:
-- INSERT INTO `menu` (`nome`, `link`, `icone`, `permissao`, `ordem`, `ativo`) 
-- VALUES ('Metas Financeiras', 'metas', 'fa-bullseye', 'usuario', 5, 1); 