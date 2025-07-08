<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión o Registrarse</title>
    <link rel="stylesheet" href="Vista/css/styles.css">
</head>
<body class="login-body">
    <div class="container">
        <div class="form-box login-form">
            <h2>Iniciar Sesión</h2>
            <form action="index.php?accion=ingresar" method="POST">
                <div class="input-group">
                    <label for="login-username">Correo:</label>
                    <input type="email" id="login-username" name="correo" required>
                </div>
                <div class="input-group">
                    <label for="login-password">Contraseña:</label>
                    <input type="password" id="login-password" name="contrasena" required>
                </div>
                <button type="submit">Entrar</button>
            </form>
            <p class="switch-form">¿No tienes cuenta? <a href="#" id="show-register">Regístrate aquí</a></p>
        </div>

        
        <div class="form-box register-form hidden">
            <h2>Registrarse</h2>
            <form action="index.php?accion=guardarCliente" method="POST">
                <div class="input-group">
                    <label for="register-username">Nombre:</label>
                    <input type="text" id="register-username" name="nombre" required>
                </div>
                <div class="input-group">
                    <label for="register-email">Correo:</label>
                    <input type="email" id="register-email" name="correo" required>
                </div>
                <div class="input-group">
                    <label for="register-password">Contraseña:</label>
                    <input type="password" id="register-password" name="contrasena" required>
                </div>
              
                <button type="submit">Registrar</button>
            </form>
            <p class="switch-form">¿Ya tienes cuenta? <a href="#" id="show-login">Iniciar Sesión aquí </a></p>
        </div>
    </div>
    <script src="Vista/script/script.js"></script>
</body>
</html>