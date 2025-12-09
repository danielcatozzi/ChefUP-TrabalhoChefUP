<?php
header('Content-Type: application/json');
require 'conecta.php';

$userEmail = $_GET['userEmail'] ?? null; // Recebe o filtro do JavaScript
$termo = $_GET['search'] ?? '';
$termo_sql = "%$termo%"; 

try {
    $sql = "SELECT id, nome, tipo, tempo_preparo, imagem, autor_email FROM receitas";
    $params = [];
    $where_clauses = [];

    // 1. FILTRAGEM POR USUÁRIO (Apenas se o filtro de email estiver presente)
    if ($userEmail) {
        $where_clauses[] = "autor_email = ?";
        $params[] = $userEmail;
    }

    // 2. FILTRAGEM POR BUSCA
    if (!empty(trim($_GET['search'] ?? ''))) {
        $where_clauses[] = "nome LIKE ?";
        $params[] = $termo_sql;
    }

    // 3. Monta a QUERY FINAL
    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $json = array_map(function($r){
        return [
            'id' => $r['id'],
            'nome' => $r['nome'],
            'tipo' => $r['tipo'],
            'tempo_forno' => $r['tempo_preparo'],
            'imagem' => $r['imagem'],
            'autor_email' => $r['autor_email']
        ];
    }, $receitas);

    echo json_encode(['receitas' => $json]);
} catch (Exception $e) {
    echo json_encode(['receitas' => []]);
}
?>