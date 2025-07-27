<?php
class SanitizarEntrada {
    public static function limpiarCadena($cadena) {
        $cadena = trim($cadena);
        $cadena = strip_tags($cadena);
        return htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
    }

    public static function capitalizarTituloEspañol($texto) {
    $palabras = explode(' ', strtolower(trim($texto)));
    $articulos = ['de', 'del', 'la', 'el', 'los', 'las', 'y', 'en', 'con', 'por', 'para', 'un', 'una'];

    foreach ($palabras as $i => $palabra) {
        if ($i === 0 || !in_array($palabra, $articulos)) {
            $palabras[$i] = ucfirst($palabra);
        }
        // si está en artículos y NO es la primera palabra, queda en minúscula
    }

    return implode(' ', $palabras);
}

}
?>