<?php
header('Content-Type: application/json');
require 'conecta.php';

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$assunto = $_POST['assunto'] ?? '';
$mensagem = $_POST['mensagem'] ?? '';

// Validação de campos obrigatórios
if (empty($nome) || empty($email) || empty($mensagem)) {
    echo json_encode(['success' => false, 'message' => 'Nome, email e mensagem são obrigatórios.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO contatos (nome, email, cpf, assunto, mensagem) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $email, $cpf, $assunto, $mensagem]);

    echo json_encode(['success' => true, 'message' => 'Sua mensagem foi enviada com sucesso!']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar contato: ' . $e->getMessage()]);
}
?>