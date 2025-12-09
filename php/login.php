<?php
header('Content-Type: application/json');
require 'conecta.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($senha, $user['senha'])) {
    // ✅ CORREÇÃO: Retorna o email do usuário na resposta JSON para o JS armazenar
    echo json_encode([
        'success' => true,
        'userEmail' => $user['email'] 
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Login inválido.']);
}
?>