<?php
require('fpdf/fpdf.php');

// Conexión a la base de datos
$host = 'localhost';  
$usuario = 'root';    
$contrasena = '';     
$basededatos = 'soporte_clientes'; 

$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error); // Si hay un error de conexión, muestra el mensaje
}

// Construir la consulta SQL con filtros si se han enviado
$sql = "SELECT id, categoria, descripcion, archivo, fecha, estado FROM reportes WHERE 1";

if (isset($_GET['estado']) && $_GET['estado'] != '') {
    $estado = $_GET['estado'];
    $sql .= " AND estado = '$estado'";
}

if (isset($_GET['categoria']) && $_GET['categoria'] != '') {
    $categoria = $_GET['categoria'];
    $sql .= " AND categoria = '$categoria'";
}

if (isset($_GET['fecha']) && $_GET['fecha'] != '') {
    $fecha = $_GET['fecha'];
    $sql .= " AND fecha = '$fecha'";
}

$sql .= " ORDER BY fecha DESC";
$resultado = $conn->query($sql);

// Crear un nuevo objeto FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Título del PDF
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Lista de Reportes', 0, 1, 'C');

// Agregar los reportes al PDF
$pdf->SetFont('Arial', '', 12);
while($fila = $resultado->fetch_assoc()) {
    $pdf->Cell(0, 10, 'Categoria: ' . $fila['categoria'], 0, 1);
    $pdf->Cell(0, 10, 'Descripcion: ' . $fila['descripcion'], 0, 1);
    $pdf->Cell(0, 10, 'Fecha: ' . $fila['fecha'], 0, 1);
    $pdf->Cell(0, 10, 'Estado: ' . $fila['estado'], 0, 1);
    $pdf->Ln(5);
}

// Salida del archivo PDF
$pdf->Output('I', 'reportes.pdf');

// Cerrar la conexión
$conn->close();
?>
