<?php
header('Content-Type: application/json');
require 'conecta.php';

// NOVO: Pega o email do autor enviado pelo JavaScript
$userEmail = $_POST['autor_email'] ?? null; 
if (!$userEmail) {
    echo json_encode(['success' => false, 'message' => 'Autor não identificado.']);
    exit;
}

if (!is_dir('../uploads')) mkdir('../uploads', 0777, true);

$nome = $_POST['nome'] ?? 'Sem nome';
$tipo = $_POST['tipo'] ?? 'Geral';
$tempo = $_POST['tempo'] ?? '0';
$ingredientes = $_POST['ingredientes'] ?? '';
$preparo = $_POST['preparo'] ?? '';

$caminhoImagem = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=1200&q=80';

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $novoNome = uniqid() . "." . $ext;
    if (move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $novoNome)) {
        $caminhoImagem = 'uploads/' . $novoNome;
    }
}

try {
    // INSERINDO O autor_email
    $stmt = $pdo->prepare("INSERT INTO receitas (nome, tipo, tempo_preparo, imagem, ingredientes, modo_preparo, autor_email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $tipo, $tempo . ' min', $caminhoImagem, $ingredientes, $preparo, $userEmail]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>