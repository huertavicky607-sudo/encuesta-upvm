<?php
// Configuración de la base de datos mediante variables de entorno
$servername = getenv('DB_HOST');
$port       = (int) getenv('DB_PORT');
$username   = getenv('DB_USER');
$password   = getenv('DB_PASS');
$dbname     = getenv('DB_NAME');

$mensaje = "";

// Conectar a la base de datos de Railway
$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Crear la tabla automáticamente si no existe en Railway
$table_sql = "CREATE TABLE IF NOT EXISTS respuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta1 VARCHAR(50),
    pregunta2 VARCHAR(50),
    pregunta3 VARCHAR(50),
    pregunta4 VARCHAR(255),
    pregunta5 VARCHAR(50),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($table_sql);

// Procesar el formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p1 = $conn->real_escape_string($_POST['pregunta1']);
    $p2 = $conn->real_escape_string($_POST['pregunta2']);
    $p3 = $conn->real_escape_string($_POST['pregunta3']);
    $p4 = $conn->real_escape_string($_POST['pregunta4']);
    $p5 = $conn->real_escape_string($_POST['pregunta5']);
    
    $insert_sql = "INSERT INTO respuestas (pregunta1, pregunta2, pregunta3, pregunta4, pregunta5) 
                   VALUES ('$p1', '$p2', '$p3', '$p4', '$p5')";
                   
    if ($conn->query($insert_sql) === TRUE) {
        $conn->close();
        header("Location: regalo.html");
        exit();
    } else {
        $mensaje = "<p style='color:red;'>Error al guardar: " . $conn->error . "</p>";
    }
}
if(isset($conn)) $conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPVM - Encuesta de Calidad</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f6; color: #333; display: flex; flex-direction: column; min-height: 100vh; }
        header { background-color: #004b23; color: white; padding: 20px; text-align: center; }
        .container { max-width: 600px; margin: 30px auto; padding: 25px; background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); flex: 1; }
        .profile-container { text-align: center; margin-bottom: 20px; }
        .profile-img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #004b23; }
        h1 { text-align: center; color: #004b23; margin-bottom: 20px; font-size: 1.5em; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #444; }
        select, input[type="text"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 1em; }
        .radio-group { display: flex; flex-direction: column; gap: 8px; }
        .radio-option { display: flex; align-items: center; gap: 10px; }
        .btn-submit { width: 100%; background-color: #004b23; color: white; padding: 12px; border: none; border-radius: 5px; font-size: 1.1em; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        .btn-submit:hover { background-color: #38b000; }
        footer { background-color: #333; color: white; text-align: center; padding: 15px; font-size: 0.9em; margin-top: auto; }
        @media (max-width: 600px) { .container { margin: 15px; padding: 15px; } }
    </style>
</head>
<body>
    <header>
        <h2>Evaluación Institucional UPVM</h2>
    </header>
    <div class="container">
        <div class="profile-container">
            <!-- Reemplaza 'tu-foto.jpg' con la ruta real de tu imagen -->
            <img src="image1.jpeg" alt="Mi Foto" class="profile-img">
        </div>
        <h1>Encuesta sobre la UPVM</h1>
        <?php echo $mensaje; ?>
        <form action="formulario.php" method="POST">
            
            <!-- Pregunta 1: Función SELECT -->
            <div class="form-group">
                <label for="pregunta1">1. ¿Cómo calificas el nivel académico de los profesores de la UPVM?</label>
                <select name="pregunta1" id="pregunta1" required>
                    <option value="" disabled selected>Selecciona una opción</option>
                    <option value="Excelente">Excelente</option>
                    <option value="Bueno">Bueno</option>
                    <option value="Regular">Regular</option>
                    <option value="Deficiente">Deficiente</option>
                </select>
            </div>

            <!-- Pregunta 2: Radio buttons -->
            <div class="form-group">
                <label>2. ¿Consideras que las instalaciones de la UPVM (laboratorios, aulas) están en buen estado?</label>
                <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="pregunta2" value="Totalmente de acuerdo" required> Totalmente de acuerdo</label>
                    <label class="radio-option"><input type="radio" name="pregunta2" value="En desacuerdo"> En desacuerdo</label>
                    <label class="radio-option"><input type="radio" name="pregunta2" value="Neutral"> Neutral</label>
                </div>
            </div>

            <!-- Pregunta 3: Función SELECT -->
            <div class="form-group">
                <label for="pregunta3">3. ¿Qué servicio de la universidad consideras que necesita mejorar con mayor urgencia?</label>
                <select name="pregunta3" id="pregunta3" required>
                    <option value="" disabled selected>Selecciona un servicio</option>
                    <option value="Cafetería">Cafetería</option>
                    <option value="Biblioteca">Biblioteca</option>
                    <option value="Servicios Escolares">Servicios Escolares</option>
                    <option value="Conexión de Internet (Wi-Fi)">Conexión de Internet (Wi-Fi)</option>
                </select>
            </div>

            <!-- Pregunta 4: Input de texto libre -->
            <div class="form-group">
                <label for="pregunta4">4. ¿Qué sugerencia le darías a la administración para mejorar tu carrera?</label>
                <input type="text" name="pregunta4" id="pregunta4" placeholder="Escribe tu sugerencia breve aquí..." required>
            </div>

            <!-- Pregunta 5: Función SELECT -->
            <div class="form-group">
                <label for="pregunta5">5. ¿Recomendarías la UPVM a un amigo o familiar para estudiar una ingeniería o licenciatura?</label>
                <select name="pregunta5" id="pregunta5" required>
                    <option value="" disabled selected>Selecciona tu respuesta</option>
                    <option value="Sí, definitivamente">Sí, definitivamente</option>
                    <option value="Tal vez">Tal vez</option>
                    <option value="No">No</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Enviar Encuesta</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2026 Actividad UPVM - Todos los derechos reservados</p>
    </footer>
</body>
</html>
