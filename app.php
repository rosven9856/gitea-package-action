<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Configuration\Configuration;

$configuration = (new Configuration())
    ->set('param', 123)
;

dd($configuration->getCastingType('param', 'string')->getAsCasting());

exit();

\define('RED', "\033[0;31m");
\define('GREEN', "\033[1;32m");
\define('YELLOW', "\033[1;33m");
\define('LITE_CYAN', "\e[96m");
\define('NC', "\033[0m");

function sendRequest ($method = 'GET', $endpoint = '', $data = []): array {

    if (!\extension_loaded('curl')) {
        throw new \Exception('CURL extension is not loaded');
    }

    $curl = \curl_init();

    if(!$curl) {
        throw new \Exception('CURL extension is not loaded');
    }

    $payload = '';

    if (isset($data['request']) && \is_array($data['request']) && \count($data['request']) > 0) {
        $payload = \json_encode($data['request']);
    }

    $fh = null;
    $fileSize = 0;

    if (isset($data['file']) && !empty($data['file']) && \file_exists($data['file'])) {
        $fh = \fopen($data['file'], 'r');
        $fileSize = \filesize($data['file']);
    }

    if (isset($data['user']) && !empty($data['user'])) {
        \curl_setopt($curl, CURLOPT_USERPWD, $data['user'] . ':' . \getenv('gitea_access_token'));
    } else {
        $endpoint .= '?access_token=' . \getenv('gitea_access_token');
    }

    \curl_setopt($curl, CURLOPT_URL, \getenv('gitea_instance_base_url') . $endpoint /* . '?access_token=' . \getenv('gitea_access_token')*/);
    \curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    \curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    \curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    \curl_setopt($curl, CURLOPT_HEADER, true);

    if ($method === 'POST' || $method === 'PUT') {
        if (!empty($payload)) {
            \curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
            \curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
    }

    if ($method === 'PUT') {
        \curl_setopt($curl, CURLOPT_PUT, true);
    }

    if ($fh) {
        \curl_setopt($curl, CURLOPT_INFILE, $fh);
        \curl_setopt($curl, CURLOPT_INFILESIZE, $fileSize);
    }

    \curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
    \curl_setopt($curl, CURLOPT_TIMEOUT, 20);

    $response = \curl_exec($curl);

    if(\curl_errno($curl) > 0) {
        throw new \Exception('Curl error: #' . \curl_errno($curl)  . ' - ' . \curl_error($curl));
    }

    $header = \substr($response, 0, \curl_getinfo($curl, CURLINFO_HEADER_SIZE));
    $body = \substr($response, \curl_getinfo($curl, CURLINFO_HEADER_SIZE));
    $httpCode = (int) \curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $contentType = \curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

    $headers = \explode("\r\n", $header);

    \curl_close($curl);

    if (isset($data['file']) && !empty($data['file']) && \file_exists($data['file'])) {
        \fclose($fh);
    }

    return [
        'http_code' => $httpCode,
        'content_type' => $contentType,
        'headers' => $headers,
        'body' => $body,
    ];
}

function responseEncode (array $response): mixed {

    $data = [];

    if ($response['content_type'] !== 'application/json;charset=utf-8') {
        return $data;
    }

    $data = \json_decode($response['body'], true);

    if (\json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception('Invalid response json: ' . json_last_error_msg());
    }

    return $data;
}

function showTerminalMessage (string $message = '', string $color = ''): void
{
    echo $color . $message . NC . "\r\n";
}

try {

    if (empty(\getenv('gitea_instance_base_url'))) {
        throw new \Exception('gitea_instance_base_url empty');
    }

    if (empty(\getenv('gitea_access_token'))) {
        throw new \Exception('gitea_access_token empty');
    }

    if (empty(\getenv('gitea_owner'))) {
        throw new \Exception('gitea_owner empty');
    }

    if (empty(\getenv('gitea_repository'))) {
        throw new \Exception('gitea_repository empty');
    }

    if (empty(\getenv('gitea_package_registry'))) {
        throw new \Exception('gitea_package_registry empty');
    }

    if (!\in_array(\getenv('gitea_package_registry'), ['composer'])) {
        throw new \Exception('Package registry {' . \getenv('gitea_package_registry') . '} is not supported');
    }

    $response = sendRequest('GET', '/api/v1/user');

    if ($response['http_code'] !== 200) {
        throw new \Exception('Failed to get user information. Access denied. Response http code: ' . $response['http_code']);
    }

    $data = responseEncode($response);

    if ($response['http_code'] !== 200) {
        throw new \Exception('Failed to get user information. Response http code: ' . $response['http_code'] . ', Message: ' . $data['message']);
    }

    $user = $data;
    $login = $user['login'];
    showTerminalMessage('User data: OK', GREEN);



    $response = sendRequest('GET', '/api/v1/repos/' . \getenv('gitea_owner') . '/' . \getenv('gitea_repository') . '/releases', [
        'user' => $login,
    ]);

    if ($response['http_code'] !== 200) {
        throw new \Exception('Failed to get repository releases information. Access denied. Response http code: ' . $response['http_code']);
    }

    $data = responseEncode($response);

    if ($response['http_code'] !== 200) {
        throw new \Exception('Failed to get repository releases information. Response http code: ' . $response['http_code'] . ', Message: ' . $data['message']);
    }

    if (!isset($data[0]) || !\is_array($data[0])) {
        throw new \Exception('Unexpected release data structure');
    }

    $lastRelease = $data[0];
    $tag = $lastRelease['tag_name'];
    showTerminalMessage('Last release data: OK', GREEN);


    $response = sendRequest('GET', '/api/v1/repos/' . \getenv('gitea_owner') . '/' . \getenv('gitea_repository') . '/archive/' . $tag . '.zip', [
        'user' => $login,
    ]);
    $zipContent = $response['body'];

    if ($response['http_code'] !== 200) {
        throw new \Exception('Failed receiving zip archive. Response http code: ' . $response['http_code']);
    }

    if (empty($response['body'])) {
        throw new \Exception('Failed receiving zip archive. Empty file');
    }

    \file_put_contents(__DIR__ . '/package.zip', $zipContent);
    showTerminalMessage('Download zip archive: OK', GREEN);



    $response = sendRequest('PUT', '/api/packages/' . \getenv('gitea_owner') . '/composer?version=' . $tag, [
        'user' => $login,
        'file' => __DIR__ . '/package.zip',
    ]);

    \unlink(__DIR__ . '/package.zip');

    if ($response['http_code'] !== 201) {
        $data = responseEncode($response);

        throw new \Exception('Failed update package. Response http code: ' . $response['http_code'] . ', Message: ' . $data['errors'][0]['message']);
    }

    showTerminalMessage('Update package: OK', GREEN);


} catch (\Exception $e) {

    showTerminalMessage("\r\n");
    showTerminalMessage( 'FAILED!', RED);
    showTerminalMessage( "Error: " . $e->getMessage(), RED);
    exit(1);
}

showTerminalMessage("\r\n");
showTerminalMessage('SUCCESS!', GREEN);
