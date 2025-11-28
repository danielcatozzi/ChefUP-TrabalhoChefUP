<?php
header('Content-Type: application/json');
require 'conecta.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID não fornecido.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM receitas WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Receita excluída com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Receita não encontrada.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a exclusão: ' . $e->getMessage()]);
}
?>