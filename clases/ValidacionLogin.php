// clases/ValidacionLogin.php
<?php
require_once("mod_db.php");
require_once("SanitizarEntrada.php");

final class ValidacionLogin {
    private $usuario, $contrasena, $hashGuardado, $pdo;
    private $loginExitoso = false;

    public function __construct($usuario, $contrasena, $pdo) {
        $this->usuario = SanitizarEntrada::limpiarCadena($usuario);
        $this->contrasena = SanitizarEntrada::limpiarCadena($contrasena);
        $this->pdo = $pdo;
    }

    public function logger() {
        $usuarioBD = $this->pdo->log($this->usuario);

        if ($usuarioBD) {
            $this->hashGuardado = $usuarioBD->clave;
            return true;
        }
        return false;
    }

    public function autenticar() {
        if (password_verify($this->contrasena, $this->hashGuardado)) {
            $this->loginExitoso = true;
        }
    }

    public function loginExitoso() {
        return $this->loginExitoso;
    }

    public function getUsuario() {
        return $this->usuario;
    }
}
?>
