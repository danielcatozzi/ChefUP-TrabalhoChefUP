<?php
header('Content-Type: application/json');
require 'conecta.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    // Se não houver ID na URL, retorna falha.
    echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
    exit;
}

try {
    // Busca a receita pelo ID
    $stmt = $pdo->prepare("SELECT * FROM receitas WHERE id = ?");
    $stmt->execute([$id]);
    $receita = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($receita) {
        // Se a receita for encontrada, formata e devolve os dados
        $dados = [
            'id' => $receita['id'],
            'nome' => $receita['nome'],
            'tipo' => $receita['tipo'],
            'tempo' => $receita['tempo_preparo'],
            'imagem' => $receita['imagem'],
            'ingredientes' => nl2br($receita['ingredientes']),
            'preparo' => nl2br($receita['modo_preparo'])
        ];
        echo json_encode(['success' => true, 'receita' => $dados]);
    } else {
        // Se o ID não for encontrado no banco
        echo json_encode(['success' => false, 'message' => 'Receita não encontrada']);
    }
} catch (Exception $e) {
    // Retorna um erro genérico do servidor
    echo json_encode(['success' => false, 'message' => 'Erro de conexão ou consulta: ' . $e->getMessage()]);
}
?>