<?php
function makeCurlRequest($url) {
    // Inicializar una sesión cURL
    $handle = curl_init($url);

    // Configurar cURL para devolver la respuesta como una cadena
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);

    // Ejecutar la solicitud cURL y almacenar la respuesta
    $response = curl_exec($handle);

    // Obtener el código de respuesta HTTP
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

    // Manejar errores HTTP
    if ($httpCode >= 400) {
        // Redirigir a una página de error personalizada
        header('Location: errores/error404.html');
        exit(); // Asegurarse de que el script se detenga después de la redirección
    }

    // Cerrar la sesión cURL
    curl_close($handle);

    // Devolver la respuesta y el código de respuesta HTTP
    return ['response' => $response, 'httpCode' => $httpCode];
}
?>