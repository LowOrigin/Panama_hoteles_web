<?php
class SanitizarEntrada {
    public static function limpiarCadena($cadena) {
        $cadena = trim($cadena);
        $cadena = strip_tags($cadena);
        return htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
    }
}
?>