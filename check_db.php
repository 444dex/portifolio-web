<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

try {
    $conn = getConnection();
    
    if ($conn) {
        // Verificar se a tabela contatos existe
        $result = $conn->query("SHOW TABLES LIKE 'contatos'");
        
        if ($result->num_rows > 0) {
            // Contar registros
            $count = $conn->query("SELECT COUNT(*) as total FROM contatos")->fetch_assoc();
            
            echo json_encode([
                'success' => true,
                'message' => 'Banco de dados conectado!',
                'registros' => $count['total']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Tabela contatos não encontrada. Execute o script SQL.'
            ]);
        }
        
        closeConnection($conn);
    } else {
        throw new Exception('Falha na conexão.');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro: ' . $e->getMessage()
    ]);
}
?>