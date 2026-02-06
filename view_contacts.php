<?php

require_once 'config.php';


session_start();
$senha_correta = "admin123"; 

if (!isset($_SESSION['logado'])) {
    if (isset($_POST['senha'])) {
        if ($_POST['senha'] === $senha_correta) {
            $_SESSION['logado'] = true;
        } else {
            $erro = "Senha incorreta!";
        }
    }
    
    if (!isset($_SESSION['logado'])) {
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>Login - Mensagens</title>
            <style>
                body { font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f0f0f0; }
                .login-box { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
                input { padding: 0.5rem; margin: 0.5rem 0; width: 100%; }
                button { padding: 0.5rem 1rem; background: #6366f1; color: white; border: none; border-radius: 5px; cursor: pointer; }
                .erro { color: red; }
            </style>
        </head>
        <body>
            <div class="login-box">
                <h2>Acesso às Mensagens</h2>
                <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
                <form method="POST">
                    <input type="password" name="senha" placeholder="Digite a senha" required>
                    <button type="submit">Entrar</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Conecta ao banco
$conn = getConnection();

if (!$conn) {
    die("Erro ao conectar ao banco de dados.");
}

// Ações de atualização de status
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = '';
    
    switch ($_GET['action']) {
        case 'marcar_lido':
            $status = 'lido';
            break;
        case 'marcar_respondido':
            $status = 'respondido';
            break;
        case 'marcar_novo':
            $status = 'novo';
            break;
        case 'deletar':
            $stmt = $conn->prepare("DELETE FROM contatos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            header("Location: view_contacts.php");
            exit;
    }
    
    if ($status) {
        $stmt = $conn->prepare("UPDATE contatos SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: view_contacts.php");
        exit;
    }
}

// Busca todas as mensagens
$query = "SELECT * FROM contatos ORDER BY data_envio DESC";
$result = $conn->query($query);

// Conta mensagens por status
$stats_query = "SELECT status, COUNT(*) as total FROM contatos GROUP BY status";
$stats_result = $conn->query($stats_query);
$stats = [];
while ($row = $stats_result->fetch_assoc()) {
    $stats[$row['status']] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens Recebidas - Portfólio</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial; background: #f8fafc; padding: 2rem; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-card h3 { font-size: 2rem; color: #6366f1; }
        .mensagem { background: white; padding: 1.5rem; border-radius: 10px; margin-bottom: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .mensagem.novo { border-left: 4px solid #10b981; }
        .mensagem.lido { border-left: 4px solid #f59e0b; }
        .mensagem.respondido { border-left: 4px solid #6366f1; }
        .mensagem-header { display: flex; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem; }
        .mensagem-info { flex: 1; }
        .mensagem-info h3 { color: #1e293b; margin-bottom: 0.5rem; }
        .mensagem-info p { color: #64748b; font-size: 0.9rem; }
        .mensagem-corpo { color: #334155; line-height: 1.6; padding: 1rem; background: #f8fafc; border-radius: 5px; margin: 1rem 0; }
        .acoes { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .btn { padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer; font-size: 0.85rem; text-decoration: none; display: inline-block; }
        .btn-lido { background: #f59e0b; color: white; }
        .btn-respondido { background: #6366f1; color: white; }
        .btn-novo { background: #10b981; color: white; }
        .btn-deletar { background: #ef4444; color: white; }
        .btn-sair { background: #64748b; color: white; }
        .status-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: bold; }
        .status-novo { background: #d1fae5; color: #065f46; }
        .status-lido { background: #fef3c7; color: #92400e; }
        .status-respondido { background: #ddd6fe; color: #5b21b6; }
        .empty { text-align: center; padding: 3rem; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>📬 Mensagens Recebidas</h1>
                <p>Painel de controle do portfólio</p>
            </div>
            <a href="?logout=1" class="btn btn-sair">Sair</a>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3><?php echo $stats['novo'] ?? 0; ?></h3>
                <p>Novas Mensagens</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['lido'] ?? 0; ?></h3>
                <p>Mensagens Lidas</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['respondido'] ?? 0; ?></h3>
                <p>Respondidas</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $result->num_rows; ?></h3>
                <p>Total</p>
            </div>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="mensagem <?php echo $row['status']; ?>">
                    <div class="mensagem-header">
                        <div class="mensagem-info">
                            <h3><?php echo htmlspecialchars($row['nome']); ?></h3>
                            <p>
                                📧 <?php echo htmlspecialchars($row['email']); ?> | 
                                📅 <?php echo date('d/m/Y H:i', strtotime($row['data_envio'])); ?> | 
                                🌐 IP: <?php echo htmlspecialchars($row['ip_address']); ?>
                            </p>
                            <p><strong>Assunto:</strong> <?php echo htmlspecialchars($row['assunto']); ?></p>
                            <p><span class="status-badge status-<?php echo $row['status']; ?>"><?php echo strtoupper($row['status']); ?></span></p>
                        </div>
                    </div>
                    
                    <div class="mensagem-corpo">
                        <?php echo nl2br(htmlspecialchars($row['mensagem'])); ?>
                    </div>

                    <div class="acoes">
                        <?php if ($row['status'] !== 'novo'): ?>
                            <a href="?action=marcar_novo&id=<?php echo $row['id']; ?>" class="btn btn-novo">Marcar como Novo</a>
                        <?php endif; ?>
                        
                        <?php if ($row['status'] !== 'lido'): ?>
                            <a href="?action=marcar_lido&id=<?php echo $row['id']; ?>" class="btn btn-lido">Marcar como Lido</a>
                        <?php endif; ?>
                        
                        <?php if ($row['status'] !== 'respondido'): ?>
                            <a href="?action=marcar_respondido&id=<?php echo $row['id']; ?>" class="btn btn-respondido">Marcar como Respondido</a>
                        <?php endif; ?>
                        
                        <a href="?action=deletar&id=<?php echo $row['id']; ?>" class="btn btn-deletar" onclick="return confirm('Tem certeza que deseja deletar esta mensagem?')">Deletar</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty">
                <h2>📭 Nenhuma mensagem ainda</h2>
                <p>Quando alguém entrar em contato, as mensagens aparecerão aqui.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: view_contacts.php");
    exit;
}
?>