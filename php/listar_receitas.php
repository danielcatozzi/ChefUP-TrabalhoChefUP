<?php
header('Content-Type: application/json');
require 'conecta.php';

$userEmail = $_GET['userEmail'] ?? null;
$termo = $_GET['search'] ?? '';
$termo_sql = "%$termo%"; 

try {
    $sql = "SELECT id, nome, tipo, tempo_preparo, imagem, autor_email FROM receitas";
    $params = [];
    
    // FILTRAGEM: Se o email do usuário foi fornecido (só acontece na página especialista.html)
    if ($userEmail) {
        $sql .= " WHERE autor_email = ?";
        $params[] = $userEmail;
    }

    // BUSCA: Adiciona o filtro de busca de texto
    if (!empty(trim($_GET['search'] ?? ''))) {
        $sql .= $userEmail ? " AND nome LIKE ?" : " WHERE nome LIKE ?";
        $params[] = $termo_sql;
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
            'autor_email' => $r['autor_email'] // Mantém para futura filtragem em JS, se necessário
        ];
    }, $receitas);

    echo json_encode(['receitas' => $json]);
} catch (Exception $e) {
    echo json_encode(['receitas' => []]);
}
?>