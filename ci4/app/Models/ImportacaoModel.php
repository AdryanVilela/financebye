<?php

namespace App\Models;

use CodeIgniter\Model;

class ImportacaoModel extends Model
{
    protected $table            = 'importacoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'usuario_id', 'nome', 'formato', 'status', 'arquivo', 
        'data_importacao', 'total_registros', 'registros_importados', 
        'erros', 'mapeamento', 'opcoes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'usuario_id'    => 'required|numeric',
        'nome'          => 'required|min_length[3]|max_length[100]',
        'formato'       => 'required|in_list[csv,ofx,xls,xlsx,json]',
    ];
    
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'O ID do usuário é obrigatório',
            'numeric'  => 'O ID do usuário deve ser numérico'
        ],
        'nome' => [
            'required'    => 'O nome da importação é obrigatório',
            'min_length'  => 'O nome deve ter pelo menos {param} caracteres',
            'max_length'  => 'O nome não pode ter mais de {param} caracteres'
        ],
        'formato' => [
            'required' => 'O formato do arquivo é obrigatório',
            'in_list'  => 'O formato deve ser um dos valores aceitos'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setDefaults'];
    protected $beforeUpdate = ['serializarDados'];
    protected $afterFind    = ['deserializarDados'];
    
    protected function setDefaults(array $data)
    {
        // Definir status inicial
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'pendente';
        }
        
        // Definir data atual
        if (!isset($data['data']['data_importacao'])) {
            $data['data']['data_importacao'] = date('Y-m-d H:i:s');
        }
        
        // Definir 0 para contadores
        if (!isset($data['data']['total_registros'])) {
            $data['data']['total_registros'] = 0;
        }
        
        if (!isset($data['data']['registros_importados'])) {
            $data['data']['registros_importados'] = 0;
        }
        
        // Serializar arrays
        if (isset($data['data']['mapeamento']) && is_array($data['data']['mapeamento'])) {
            $data['data']['mapeamento'] = json_encode($data['data']['mapeamento']);
        }
        
        if (isset($data['data']['opcoes']) && is_array($data['data']['opcoes'])) {
            $data['data']['opcoes'] = json_encode($data['data']['opcoes']);
        }
        
        if (isset($data['data']['erros']) && is_array($data['data']['erros'])) {
            $data['data']['erros'] = json_encode($data['data']['erros']);
        }
        
        return $data;
    }
    
    protected function serializarDados(array $data)
    {
        // Serializar arrays antes de salvar
        if (isset($data['data']['mapeamento']) && is_array($data['data']['mapeamento'])) {
            $data['data']['mapeamento'] = json_encode($data['data']['mapeamento']);
        }
        
        if (isset($data['data']['opcoes']) && is_array($data['data']['opcoes'])) {
            $data['data']['opcoes'] = json_encode($data['data']['opcoes']);
        }
        
        if (isset($data['data']['erros']) && is_array($data['data']['erros'])) {
            $data['data']['erros'] = json_encode($data['data']['erros']);
        }
        
        return $data;
    }
    
    protected function deserializarDados(array $data)
    {
        // Se não tiver resultados ou for vazio, retornar como está
        if (empty($data['data'])) {
            return $data;
        }
        
        // Se for resultado único
        if (isset($data['data']['mapeamento'])) {
            $this->deserializarRegistro($data['data']);
        } 
        // Se for resultado múltiplo (array de resultados)
        else if (is_array($data['data'])) {
            foreach ($data['data'] as &$registro) {
                if (is_array($registro)) {
                    $this->deserializarRegistro($registro);
                }
            }
        }
        
        return $data;
    }
    
    private function deserializarRegistro(&$registro)
    {
        // Deserializar JSON para array
        if (isset($registro['mapeamento']) && is_string($registro['mapeamento'])) {
            $registro['mapeamento'] = json_decode($registro['mapeamento'], true);
        }
        
        if (isset($registro['opcoes']) && is_string($registro['opcoes'])) {
            $registro['opcoes'] = json_decode($registro['opcoes'], true);
        }
        
        if (isset($registro['erros']) && is_string($registro['erros'])) {
            $registro['erros'] = json_decode($registro['erros'], true);
        }
    }
    
    // Buscar importações do usuário
    public function getImportacoesUsuario($usuarioId)
    {
        return $this->where('usuario_id', $usuarioId)
                    ->orderBy('data_importacao', 'DESC')
                    ->findAll();
    }
    
    // Processar arquivo CSV
    public function processarCSV($importacaoId, $arquivo, $mapeamento, $opcoes = [])
    {
        // Buscar detalhes da importação
        $importacao = $this->find($importacaoId);
        
        if (!$importacao) {
            return false;
        }
        
        // Configurar opções padrão
        $opcoesDefault = [
            'delimitador' => ',',
            'tem_cabecalho' => true,
            'ignorar_linhas' => 0,
            'categoria_padrao' => null,
            'carteira_padrao' => null,
            'converter_negativos' => true
        ];
        
        $opcoes = array_merge($opcoesDefault, $opcoes);
        
        // Atualizar status
        $this->update($importacaoId, [
            'status' => 'processando',
            'mapeamento' => $mapeamento,
            'opcoes' => $opcoes
        ]);
        
        // Abrir o arquivo
        $handle = fopen($arquivo, 'r');
        
        if (!$handle) {
            $this->update($importacaoId, [
                'status' => 'erro',
                'erros' => ['Não foi possível abrir o arquivo.']
            ]);
            return false;
        }
        
        // Contar o total de linhas (menos cabeçalho se houver)
        $totalLinhas = 0;
        while (!feof($handle)) {
            if (fgets($handle)) {
                $totalLinhas++;
            }
        }
        
        // Ajustar contagem se tiver cabeçalho
        if ($opcoes['tem_cabecalho']) {
            $totalLinhas--;
        }
        
        // Reiniciar leitura do arquivo
        rewind($handle);
        
        // Pular linhas iniciais se necessário
        for ($i = 0; $i < $opcoes['ignorar_linhas']; $i++) {
            fgets($handle);
            $totalLinhas--;
        }
        
        // Ignorar cabeçalho se houver
        if ($opcoes['tem_cabecalho']) {
            fgetcsv($handle, 0, $opcoes['delimitador']);
        }
        
        // Preparar modelos para inserção
        $transacaoModel = new \App\Models\TransacaoModel();
        
        // Inicializar contadores
        $linhasProcessadas = 0;
        $linhasImportadas = 0;
        $erros = [];
        
        // Processar cada linha
        while (($linha = fgetcsv($handle, 0, $opcoes['delimitador'])) !== FALSE) {
            $linhasProcessadas++;
            
            // Ignorar linhas vazias
            if (empty($linha) || count($linha) < 2) {
                continue;
            }
            
            try {
                // Preparar dados da transação
                $transacao = [
                    'usuario_id' => $importacao['usuario_id'],
                    'data' => date('Y-m-d'),
                    'valor' => 0,
                    'descricao' => 'Importado',
                    'tipo' => 'despesa'
                ];
                
                // Aplicar mapeamento
                foreach ($mapeamento as $campo => $indice) {
                    if (isset($linha[$indice])) {
                        $valor = trim($linha[$indice]);
                        
                        switch ($campo) {
                            case 'data':
                                $transacao['data'] = $this->formatarData($valor);
                                break;
                            case 'valor':
                                $transacao['valor'] = $this->formatarValor($valor, $opcoes['converter_negativos']);
                                break;
                            case 'descricao':
                                $transacao['descricao'] = $valor;
                                break;
                            case 'tipo':
                                $transacao['tipo'] = strtolower($valor) === 'receita' ? 'receita' : 'despesa';
                                break;
                            case 'categoria':
                                $transacao['categoria_id'] = $this->buscarOuCriarCategoria($valor, $importacao['usuario_id']);
                                break;
                            case 'carteira':
                                $transacao['carteira_id'] = $this->buscarOuCriarCarteira($valor, $importacao['usuario_id']);
                                break;
                        }
                    }
                }
                
                // Aplicar valores padrão para campos faltantes
                if (!isset($transacao['categoria_id']) && $opcoes['categoria_padrao']) {
                    $transacao['categoria_id'] = $opcoes['categoria_padrao'];
                }
                
                if (!isset($transacao['carteira_id']) && $opcoes['carteira_padrao']) {
                    $transacao['carteira_id'] = $opcoes['carteira_padrao'];
                }
                
                // Se o valor for positivo e o tipo não estiver explícito no arquivo,
                // determinar o tipo com base no valor (positivo = receita, negativo = despesa)
                if (!isset($mapeamento['tipo']) && $transacao['valor'] > 0) {
                    $transacao['tipo'] = 'receita';
                }
                
                // Inserir transação
                $resultado = $transacaoModel->insert($transacao);
                
                if ($resultado) {
                    $linhasImportadas++;
                } else {
                    $erros[] = "Linha {$linhasProcessadas}: " . json_encode($transacaoModel->errors());
                }
            } catch (\Exception $e) {
                $erros[] = "Linha {$linhasProcessadas}: " . $e->getMessage();
            }
            
            // Atualizar progresso a cada 10 linhas
            if ($linhasProcessadas % 10 === 0) {
                $this->update($importacaoId, [
                    'total_registros' => $totalLinhas,
                    'registros_importados' => $linhasImportadas,
                    'erros' => $erros
                ]);
            }
        }
        
        // Fechar arquivo
        fclose($handle);
        
        // Atualizar status final
        $this->update($importacaoId, [
            'status' => empty($erros) ? 'concluido' : 'concluido_com_erros',
            'total_registros' => $totalLinhas,
            'registros_importados' => $linhasImportadas,
            'erros' => $erros
        ]);
        
        return [
            'total' => $totalLinhas,
            'importados' => $linhasImportadas,
            'erros' => $erros
        ];
    }
    
    // Formatar data do arquivo para o formato do banco
    private function formatarData($data)
    {
        // Tentar diferentes formatos comuns de data
        $formatos = [
            'd/m/Y' => 'Y-m-d',
            'd-m-Y' => 'Y-m-d',
            'Y-m-d' => 'Y-m-d',
            'm/d/Y' => 'Y-m-d',
            'd/m/y' => 'Y-m-d',
            'Y/m/d' => 'Y-m-d'
        ];
        
        foreach ($formatos as $formato => $saida) {
            $date = \DateTime::createFromFormat($formato, $data);
            if ($date !== false) {
                return $date->format($saida);
            }
        }
        
        // Se não conseguir interpretar, retornar data atual
        return date('Y-m-d');
    }
    
    // Formatar valor do arquivo para decimal
    private function formatarValor($valor, $converterNegativos = true)
    {
        // Remover caracteres não numéricos, exceto pontos, vírgulas e sinais
        $valor = preg_replace('/[^\d.,\-+]/', '', $valor);
        
        // Determinar o separador decimal (último ponto ou vírgula)
        $ultimo_ponto = strrpos($valor, '.');
        $ultima_virgula = strrpos($valor, ',');
        
        // Se tiver ambos, o último é considerado o separador decimal
        if ($ultimo_ponto !== false && $ultima_virgula !== false) {
            $separador_decimal = ($ultimo_ponto > $ultima_virgula) ? '.' : ',';
        } else {
            $separador_decimal = ($ultimo_ponto !== false) ? '.' : ',';
        }
        
        // Normalizar para formato com ponto como separador decimal
        if ($separador_decimal === ',') {
            $valor = str_replace('.', '', $valor); // Remover pontos (milhar)
            $valor = str_replace(',', '.', $valor); // Substituir vírgula por ponto
        }
        
        // Converter para float
        $valor = (float) $valor;
        
        // Verificar se deve inverter o sinal para despesas
        if ($converterNegativos && $valor < 0) {
            $valor = abs($valor);
        }
        
        return $valor;
    }
    
    // Buscar categoria existente por nome ou criar nova
    private function buscarOuCriarCategoria($nome, $usuarioId)
    {
        $categoriaModel = new \App\Models\CategoriaModel();
        
        // Limpar o nome
        $nome = trim($nome);
        
        // Buscar por nome similar
        $categoria = $categoriaModel->where('nome', $nome)
                                    ->where('usuario_id', $usuarioId)
                                    ->first();
        
        // Se encontrou, retornar o ID
        if ($categoria) {
            return $categoria['id'];
        }
        
        // Criar nova categoria
        $novaCategoria = [
            'usuario_id' => $usuarioId,
            'nome' => $nome,
            'tipo' => 'despesa', // Tipo padrão
            'icone' => 'fa-tag' // Ícone padrão
        ];
        
        $categoriaId = $categoriaModel->insert($novaCategoria);
        
        return $categoriaId;
    }
    
    // Buscar carteira existente por nome ou criar nova
    private function buscarOuCriarCarteira($nome, $usuarioId)
    {
        $carteiraModel = new \App\Models\CarteiraModel();
        
        // Limpar o nome
        $nome = trim($nome);
        
        // Buscar por nome similar
        $carteira = $carteiraModel->where('nome', $nome)
                                  ->where('usuario_id', $usuarioId)
                                  ->first();
        
        // Se encontrou, retornar o ID
        if ($carteira) {
            return $carteira['id'];
        }
        
        // Criar nova carteira
        $novaCarteira = [
            'usuario_id' => $usuarioId,
            'nome' => $nome,
            'saldo' => 0,
            'tipo' => 'outros' // Tipo padrão
        ];
        
        $carteiraId = $carteiraModel->insert($novaCarteira);
        
        return $carteiraId;
    }
    
    // Processar arquivos OFX (Open Financial Exchange)
    public function processarOFX($importacaoId, $arquivo, $opcoes = [])
    {
        // Implementar processamento de OFX
        // Similar ao processarCSV, mas usando um parser OFX
        // ...
    }
    
    // Processar arquivos Excel (XLS/XLSX)
    public function processarExcel($importacaoId, $arquivo, $mapeamento, $opcoes = [])
    {
        // Implementar processamento de Excel
        // Similar ao processarCSV, mas usando biblioteca para ler Excel
        // ...
    }
} 