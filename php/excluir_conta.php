<?php
header('Content-Type: application/json');
require 'conecta.php';

$email = $_POST['email'] ?? '';

// Verifica se o email foi enviado
if(empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email não informado.']);
    exit;
}

try {
    // Tenta deletar o usuário com esse email
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    // Verifica se alguma linha foi afetada (se o usuário existia)
    if ($stmt->rowCount() > 0) {
        // Se apagou o usuário, apaga também as receitas dele (opcional, mas recomendado para limpar o banco)
        // Por enquanto vamos apagar apenas o usuário para simplificar
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'E-mail incorreto ou não encontrado.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>