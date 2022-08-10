<?php
//Formatear fecha
function formatearFecha($fecha){
    return date('d M, Y, g:i a', strtotime($fecha));
}

//Recortamos el texto, texto de introduccion
function textoCorto($texto, $cantCaracteres = 100){
    //$texto = $texto."";
    $texto = substr($texto, 0, $cantCaracteres);//Esto permite q la cantidad de caracteres valla desde 0 a en este caso 100
    //$texto = substr($texto, 0, strrpos($texto, ' '));
    $texto = $texto. "...";//para que donde se corte el texto aparezca los ...
    return $texto;
}

?>