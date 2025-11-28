<?php
header('Content-Type: application/json');
require 'conecta.php';

$id = $_POST['id'] ?? null;
if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID não fornecido para edição.']);
    exit;
}

$nome = $_POST['nome'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$tempo = $_POST['tempo'] ?? '';
$ingredientes = $_POST['ingredientes'] ?? '';
$preparo = $_POST['preparo'] ?? '';

// 1. Lógica de Upload (A mesma que salvar_receita.php)
$caminhoImagem = $_POST['imagem_atual'] ?? ''; // Pega o caminho da imagem que já está no DB
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    // Se uma nova foto foi enviada, faz o upload
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $novoNome = uniqid() . "." . $ext;
    if (move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $novoNome)) {
        $caminhoImagem = 'uploads/' . $novoNome;
    }
}

try {
    // 2. Executa o UPDATE
    $stmt = $pdo->prepare("
        UPDATE receitas SET 
            nome = ?, 
            tipo = ?, 
            tempo_preparo = ?, 
            imagem = ?, 
            ingredientes = ?, 
            modo_preparo = ? 
        WHERE id = ?
    ");
    $stmt->execute([
        $nome, 
        $tipo, 
        $tempo . ' min', 
        $caminhoImagem, 
        $ingredientes, 
        $preparo, 
        $id
    ]);

    echo json_encode(['success' => true, 'message' => 'Receita atualizada com sucesso.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar: ' . $e->getMessage()]);
}
?>