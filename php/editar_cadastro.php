<?php
header('Content-Type: application/json');
require 'conecta.php';

$email_atual = $_POST['email_atual'] ?? null; // Email original/identifier
$nome = trim($_POST['nome'] ?? '');
$email_novo = trim($_POST['email'] ?? '');
$senha_nova = $_POST['senha'] ?? ''; // Nova senha (opcional)

if (!$email_atual || empty($nome) || empty($email_novo)) {
    echo json_encode(['success' => false, 'message' => 'Dados essenciais faltando.']);
    exit;
}

try {
    // Monta a query base
    $sql = "UPDATE usuarios SET nome = ?, email = ?";
    $params = [$nome, $email_novo];

    // Se uma nova senha for fornecida, adicione-a à query
    if (!empty($senha_nova)) {
        $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
        $sql .= ", senha = ?";
        $params[] = $senha_hash;
    }

    $sql .= " WHERE email = ?";
    $params[] = $email_atual;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['success' => true, 'message' => 'Cadastro atualizado com sucesso.']);

} catch (PDOException $e) {
    // Erro de duplicidade de email (se email_novo já existir)
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'message' => 'O novo e-mail já está em uso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar: ' . $e->getMessage()]);
    }
}
?>