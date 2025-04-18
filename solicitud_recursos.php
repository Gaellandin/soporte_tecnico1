<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Solicitud de Recursos</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background-color: #f4f4f9;
    }
    h2 {
      color: #333;
    }
    .form-container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      border: 1px solid #ccc;
      max-width: 600px;
      margin: auto;
    }
    label {
      font-weight: bold;
      display: block;
      margin: 10px 0 5px;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    input[type="submit"] {
      background-color: #3498db;
      color: white;
      cursor: pointer;
      border: none;
    }
    input[type="submit"]:hover {
      background-color: #2980b9;
    }
  </style>
</head>
<body>

<h2>Formulario de Solicitud de Recursos 💵</h2>



<!-- Formulario para solicitar recursos -->
<div class="form-container">
  <form action="procesar_solicitud.php" method="POST" enctype="multipart/form-data">
    
    <label for="nombre">Nombre del Solicitante:</label>
    <input type="text" name="nombre" id="nombre" required>

    <label for="email">Correo Electrónico:</label>
    <input type="email" name="email" id="email" >

    <label for="departamento">Departamento:</label>
    <select name="departamento" id="departamento" required>
      <option value="Sistemas">sistemas</option>
      <option value="Marketing">Marketing</option>
      <option value="Ingenieria">Ingenieria</option>
      <option value="Recursos Humanos">Recursos Humanos</option>
      <option value="Finanzas">Finanzas</option>
      <option value="Mantenimiento">Mantenimiento</option>
    </select>

    <label for="recurso">Recurso Solicitado:</label>
    <input type="text" name="recurso" id="recurso" required>

    <label for="cantidad">Cantidad:</label>
    <input type="number" name="cantidad" id="cantidad" required>

    <label for="fecha">Fecha Deseada:</label>
    <input type="date" name="fecha" id="fecha" required>

    <label for="motivo">Motivo de la Solicitud:</label>
    <textarea name="motivo" id="motivo" rows="4" required></textarea>

    <label for="documento">Documentos Adjuntos (Opcional):</label>
    <input type="file" name="documento" id="documento">

    <input type="submit" value="Enviar Solicitud">
  </form>
</div>

</body>
</html>
