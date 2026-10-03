<?php
class BaseController
{
    /**
     * Magic method para manejar llamadas a métodos inexistentes.
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', ['HTTP/1.1 404 Not Found']);
    }

    /**
     * Obtiene los segmentos de la URI.
     */
    protected function getUriSegments(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        return array_values(array_filter(explode('/', $uri)));
    }

    /**
     * Obtiene los parámetros del query string.
     */
    protected function getQueryStringParams(): array
    {
        $params = [];
        parse_str($_SERVER['QUERY_STRING'] ?? '', $params);
        return $params;
    }

    /**
     * Obtiene el cuerpo de la petición (JSON).
     */
    protected function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?: [];
    }

    /**
     * Envía respuesta JSON.
     */
    protected function sendJson($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Envía salida HTTP con headers personalizados.
     */
    protected function sendOutput($data, array $httpHeaders = []): void
    {
        header_remove('Set-Cookie');

        foreach ($httpHeaders as $httpHeader) {
            header($httpHeader);
        }

        echo $data;
        exit;
    }

    /**
     * Valida que los campos requeridos estén presentes.
     */
    protected function validateRequired(array $data, array $fields): array
    {
        $missing = [];
        foreach ($fields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $missing[] = $field;
            }
        }
        return $missing;
    }
}
?>
