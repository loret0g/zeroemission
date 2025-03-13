<?php
namespace App\Services;

class SessionService
{
  public static function start(): void
  {
    if (session_status() === PHP_SESSION_NONE) {
      // Configuración de la cookie de sesión
      $cookieParams = session_get_cookie_params();
      session_set_cookie_params([
        'lifetime' => $cookieParams['lifetime'],
        'path' => $cookieParams['path'],
        'domain' => $cookieParams['domain'],
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // true solo en HTTPS
        'httponly' => true,
        'samesite' => 'Strict' // o 'Lax'
      ]);
      session_start();
    }
  }

  public static function get(string $key, $default = null)
  {
    return $_SESSION[$key] ?? $default;
  }

  public static function set(string $key, $value): void
  {
    $_SESSION[$key] = $value;
  }

  public static function delete(string $key): void
  {
    unset($_SESSION[$key]);
  }

  public static function isAuthenticated(): bool
  {
    return isset($_SESSION['user_id']);
  }

  /**
   * Requiere que el usuario esté autenticado.
   * Si no lo está, redirige a la página de autenticación.
   *
   * @return void
   */
  public static function requireAuthentication(): void
  {
    if (!self::isAuthenticated()) {
      header('Location: /auth');
      exit;
    }
  }

  public static function getUserId(): ?int
  {
    return $_SESSION['user_id'] ?? null;
  }

  /**
   * Destruye la sesión actual de forma segura.
   *
   * @return void
   */
  public static function destroy(): void
  {
    // Asegurarse de que la sesión está iniciada
    self::start();

    // Limpiar todos los datos de la sesión
    $_SESSION = [];

    // Si se utilizan cookies para la sesión, eliminarlas
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }

    // Destruir la sesión
    session_destroy();
  }
}