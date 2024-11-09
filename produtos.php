<?php
require 'vendor/autoload.php'; // carrega a biblioteca mPDF
 
// Dados de coneção com o banco de dados
$host = 'localhost';
$dbname = 'biblioteca';
$username = 'root';
$password = '';
 
try {
    $pdo = new PDO(dsn: "mysql:host=$host;dbname=$dbname;charset=utf8",
    username: $username, password: $password);
    $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
}
 // Consulta SQL para buscar infomacoes dos livros
 $query = "SELECT titulo, autor, ano_publicacao, resumo FROM livros";
 $stmt = $pdo->prepare($query);
 $stmt->execute();
 
 // Recupera os dados dos livros
 $livros = $stmt->fetchALL(PDO::FETCH_ASSOC);
 
 //Cria uma instancia do mPDF
 $mpdf = new \Mpdf\Mpdf();
 
 //Configura o conteudo do PDF
 $html = .'<h1>Biblioteca - Lista de Livros</th>';
 $html .= .'<table border="1" cellpadding="10" cellspacing="0"width="100%">';
 $html .= '<tr>
            <th>Titulo</th>
            <th>Autor </th>
            <th>Ano de publicação</th>
            <th>Resumo</th>
            </tr>';
// Popula o HTML com os dados dos livros
foreach ($livros as $livro) {
    $html .= '<tr>';
    $html .= '<td'> . htmlspecialchars($livro['titulo']) . '</td>';
    $html .= '<td'> . htmlspecialchars($livro['autor']) . '</td>';
    $html .= '<td'> . htmlspecialchars($livro['ano_publicacao']) . '</td>';
    $html .= '<td'> . htmlspecialchars($livro['titulo']) . '</td>';
    $html .= '</tr>';
}
 
$html .='</table>';
// Escreve o conteudo HTML no PDF
$mdpf->WriteHTML($html);
 
// Gera o PDF e força o download
$mpdf->Output('lista_de_livros.pdf', \Mpdf\Output\Destination::DOWNLOAD);
 
 catch (PDOException $e) {
    echo "Erro na conexao com o banco de dados : " . $e->getMessage();
} catch (\Mpdf\MpdfException $e) {
    echo "Erro ao gerar o PDF:" . $e->getMessage();
}