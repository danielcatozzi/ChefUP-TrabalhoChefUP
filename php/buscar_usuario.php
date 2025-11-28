<?php
header('Content-Type: application/json');
require 'conecta.php';

$email = $_GET['email'] ?? null;

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email do usuário não fornecido.']);
    exit;
}

try {
    // Busca apenas nome e email (NUNCA a senha!)
    $stmt = $pdo->prepare("SELECT nome, email FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        echo json_encode(['success' => true, 'usuario' => $usuario]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuário não encontrado.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar dados: ' . $e->getMessage()]);
}
?>