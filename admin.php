<?php
/**
 * =============================================
 * ADMIN.PHP - Painel Administrativo
 * =============================================
 * Interface para operações CRUD
 */

require_once 'config.php';

$db = getDB();
$conn = $db->getConnection();

// Processar ações CRUD
$action = $_GET['action'] ?? '';
$message = '';
$messageType = '';

switch ($action) {
    case 'delete':
        $id = $_GET['id'] ?? 0;
        $table = $_GET['table'] ?? '';
        
        if ($id && $table) {
            $allowedTables = ['contatos', 'projetos', 'habilidades'];
            if (in_array($table, $allowedTables)) {
                $stmt = $conn->prepare("DELETE FROM $table WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $message = "Registro excluído com sucesso!";
                $messageType = "success";
            }
        }
        break;
        
    case 'update_status':
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';
        
        if ($id && $status) {
            $stmt = $conn->prepare("UPDATE contatos SET status = :status WHERE id = :id");
            $stmt->execute(['status' => $status, 'id' => $id]);
            $message = "Status atualizado!";
            $messageType = "success";
        }
        break;
}

// Buscar dados
$contatos = $conn->query("SELECT * FROM contatos ORDER BY data_envio DESC")->fetchAll();

// Estatísticas
$stats = [
    'total_contatos' => $conn->query("SELECT COUNT(*) FROM contatos")->fetchColumn(),
    'contatos_novos' => $conn->query("SELECT COUNT(*) FROM contatos WHERE status = 'novo'")->fetchColumn(),
    'contatos_lidos' => $conn->query("SELECT COUNT(*) FROM contatos WHERE status = 'lido'")->fetchColumn(),
    'contatos_respondidos' => $conn->query("SELECT COUNT(*) FROM contatos WHERE status = 'respondido'")->fetchColumn(),
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Portfolio MK</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0e1111;
            color: #fff;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        h1 {
            color: #9aca3c;
            margin-bottom: 30px;
            font-size: 2.5rem;
            text-align: center;
            text-transform: uppercase;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #1a1d18 0%, #2a2d28 100%);
            padding: 25px;
            border-radius: 10px;
            border: 2px solid #9aca3c;
            text-align: center;
        }
        
        .stat-card h3 {
            color: #9aca3c;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            font-size: 3rem;
            font-weight: bold;
            color: #fff;
        }
        
        .section {
            background: #1a1d18;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 2px solid #333;
        }
        
        .section h2 {
            color: #9aca3c;
            margin-bottom: 20px;
            font-size: 1.8rem;
            border-bottom: 2px solid #9aca3c;
            padding-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #333;
        }
        
        th {
            background: #2a2d28;
            color: #9aca3c;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        
        tr:hover {
            background: #2a2d28;
        }
        
        .status {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status.novo { background: #ff6b6b; }
        .status.lido { background: #4ecdc4; }
        .status.respondido { background: #95e1d3; color: #000; }
        .status.arquivado { background: #666; }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            margin: 2px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        .btn-edit {
            background: #3498db;
            color: white;
        }
        
        .btn-edit:hover {
            background: #2980b9;
        }
        
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .message.success {
            background: #27ae60;
            color: white;
        }
        
        .message.error {
            background: #e74c3c;
            color: white;
        }
        
        .tag {
            background: #9aca3c;
            color: #000;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin: 2px;
            display: inline-block;
        }
        
        select {
            padding: 5px 10px;
            background: #2a2d28;
            color: #fff;
            border: 1px solid #9aca3c;
            border-radius: 5px;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #9aca3c;
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .back-link:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.html" class="back-link">◄ Voltar ao Portfolio</a>
        
        <h1>⚙️ Painel Administrativo</h1>
        
        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <!-- ESTATÍSTICAS -->
        <div class="stats">
            <div class="stat-card">
                <h3>📧 Total Contatos</h3>
                <div class="number"><?= $stats['total_contatos'] ?></div>
            </div>
            <div class="stat-card">
                <h3>🆕 Novos</h3>
                <div class="number"><?= $stats['contatos_novos'] ?></div>
            </div>
            <div class="stat-card">
                <h3>👁️ Lidos</h3>
                <div class="number"><?= $stats['contatos_lidos'] ?></div>
            </div>
            <div class="stat-card">
                <h3>✅ Respondidos</h3>
                <div class="number"><?= $stats['contatos_respondidos'] ?></div>
            </div>
        </div>
        
        <!-- CONTATOS -->
        <div class="section">
            <h2>📧 Mensagens de Contato</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Assunto</th>
                        <th>Mensagem</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contatos as $contato): ?>
                    <tr>
                        <td><?= $contato['id'] ?></td>
                        <td><?= htmlspecialchars($contato['nome']) ?></td>
                        <td><?= htmlspecialchars($contato['email']) ?></td>
                        <td><?= htmlspecialchars($contato['assunto']) ?></td>
                        <td><?= substr(htmlspecialchars($contato['mensagem']), 0, 50) ?>...</td>
                        <td><?= date('d/m/Y H:i', strtotime($contato['data_envio'])) ?></td>
                        <td>
                            <form method="POST" action="?action=update_status" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $contato['id'] ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="novo" <?= $contato['status'] == 'novo' ? 'selected' : '' ?>>Novo</option>
                                    <option value="lido" <?= $contato['status'] == 'lido' ? 'selected' : '' ?>>Lido</option>
                                    <option value="respondido" <?= $contato['status'] == 'respondido' ? 'selected' : '' ?>>Respondido</option>
                                    <option value="arquivado" <?= $contato['status'] == 'arquivado' ? 'selected' : '' ?>>Arquivado</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="?action=delete&table=contatos&id=<?= $contato['id'] ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Deseja realmente excluir?')">🗑️ Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>