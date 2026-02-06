<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Log para debug
error_log("=== SAVE_CONTACT.PHP INICIADO ===");

require_once 'config.php';

// Função para validar email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para limpar dados
function limparDados($dados) {
    return htmlspecialchars(strip_tags(trim($dados)));
}

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Método não é POST: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido. Use POST.'
    ]);
    exit;
}

// Receber dados JSON
$json = file_get_contents('php://input');
error_log("JSON recebido: " . $json);

$dados = json_decode($json, true);
error_log("Dados decodificados: " . print_r($dados, true));

// Validar se os dados foram recebidos
if (!$dados) {
    error_log("Dados não puderam ser decodificados");
    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos ou não recebidos.'
    ]);
    exit;
}

// Extrair e limpar dados - CORRIGIDO para usar as chaves corretas
$nome = isset($dados['name']) ? limparDados($dados['name']) : '';
$email = isset($dados['email']) ? limparDados($dados['email']) : '';
$assunto = isset($dados['subject']) ? limparDados($dados['subject']) : '';
$mensagem = isset($dados['message']) ? limparDados($dados['message']) : '';

error_log("Nome extraído: '$nome' (length: " . strlen($nome) . ")");
error_log("Email extraído: '$email' (length: " . strlen($email) . ")");
error_log("Assunto extraído: '$assunto' (length: " . strlen($assunto) . ")");
error_log("Mensagem extraída: '$mensagem' (length: " . strlen($mensagem) . ")");

// Array para armazenar erros
$erros = [];

// Validações
if (empty($nome)) {
    $erros[] = 'Nome é obrigatório';
    error_log("ERRO: Nome vazio");
} else if (strlen($nome) < 3) {
    $erros[] = 'Nome deve ter pelo menos 3 caracteres';
    error_log("ERRO: Nome muito curto: " . strlen($nome));
}

if (empty($email)) {
    $erros[] = 'E-mail é obrigatório';
    error_log("ERRO: Email vazio");
} else if (!validarEmail($email)) {
    $erros[] = 'E-mail inválido';
    error_log("ERRO: Email inválido: $email");
}

if (empty($assunto)) {
    $erros[] = 'Assunto é obrigatório';
    error_log("ERRO: Assunto vazio");
} else if (strlen($assunto) < 3) {
    $erros[] = 'Assunto deve ter pelo menos 3 caracteres';
    error_log("ERRO: Assunto muito curto: " . strlen($assunto));
}

if (empty($mensagem)) {
    $erros[] = 'Mensagem é obrigatória';
    error_log("ERRO: Mensagem vazia");
} else if (strlen($mensagem) < 10) {
    $erros[] = 'Mensagem deve ter pelo menos 10 caracteres';
    error_log("ERRO: Mensagem muito curta: " . strlen($mensagem));
}

// Se houver erros, retornar
if (!empty($erros)) {
    error_log("Erros de validação: " . implode(', ', $erros));
    echo json_encode([
        'success' => false,
        'message' => 'Erros de validação: ' . implode('. ', $erros)
    ]);
    exit;
}

error_log("Validação OK! Tentando salvar no banco...");

// Tentar salvar no banco de dados
try {
    $conn = getConnection();
    
    if (!$conn) {
        throw new Exception('Não foi possível conectar ao banco de dados.');
    }
    
    error_log("Conexão com banco OK");
    
    // Preparar query
    $stmt = $conn->prepare("INSERT INTO contatos (nome, email, assunto, mensagem) VALUES (?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception('Erro ao preparar query: ' . $conn->error);
    }
    
    error_log("Query preparada");
    
    $stmt->bind_param("ssss", $nome, $email, $assunto, $mensagem);
    
    error_log("Parâmetros vinculados, executando...");
    
    if ($stmt->execute()) {
        $id_inserido = $stmt->insert_id;
        
        error_log("SUCESSO! ID inserido: $id_inserido");
        
        echo json_encode([
            'success' => true,
            'message' => 'Mensagem enviada com sucesso!',
            'id' => $id_inserido
        ]);
    } else {
        throw new Exception('Erro ao executar query: ' . $stmt->error);
    }
    
    $stmt->close();
    closeConnection($conn);
    
} catch (Exception $e) {
    error_log("ERRO no catch: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao salvar no banco de dados: ' . $e->getMessage()
    ]);
}

error_log("=== SAVE_CONTACT.PHP FINALIZADO ===");
?>