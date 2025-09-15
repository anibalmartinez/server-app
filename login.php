<?php
session_start();

// Simple login handling - replace with proper authentication in production
if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple validation - replace with proper authentication
    if ($username && $password) {
        $_SESSION['user_id'] = $username;
        $_SESSION['company_name'] = $_POST['company_name'] ?? 'Mi Empresa';
        header('Location: index.php');
        exit;
    } else {
        $error = 'Por favor, complete todos los campos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard Empresarial</title>
    <style>
        /* Icon fallbacks */
        .fas::before {
            font-weight: bold;
        }
        .fa-building::before { content: "🏢"; }
        .fa-sign-in-alt::before { content: "🔑"; }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-building"></i>
            </div>
            <h1 class="login-title">Acceso al Dashboard</h1>
            <p class="login-subtitle">Ingrese sus credenciales para continuar</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Usuario</label>
                <input type="text" name="username" class="form-input" placeholder="Ingrese su usuario" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-input" placeholder="Ingrese su contraseña" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nombre de la Empresa</label>
                <input type="text" name="company_name" class="form-input" placeholder="Nombre de su empresa" value="Mi Empresa">
            </div>
            
            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>
    </div>
</body>
</html>