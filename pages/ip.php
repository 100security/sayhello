<?php
// Função para obter o IP real do cliente
function getClientIP() {
    $ipKeys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            $ipList = explode(',', $_SERVER[$key]);
            foreach ($ipList as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
    }

    return 'UNKNOWN';
}

// Obtém o IP do cliente
$ipAddress = getClientIP();
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';
$timestamp = date('Y-m-d H:i:s');

// Caminho absoluto para o arquivo de log
$logDir = __DIR__ . '/../logs';
$logFile = $logDir . '/ip.txt';

// Garante que o diretório de logs exista
if (!is_dir($logDir)) {
    if (!mkdir($logDir, 0755, true)) {
        error_log("Erro: Não foi possível criar o diretório de logs em $logDir");
        return;
    }
}

// Abre o arquivo de log em modo de adição
$fp = fopen($logFile, 'a');
if ($fp) {
    $logEntry = "IP: $ipAddress | $timestamp | User-Agent: $userAgent" . PHP_EOL;
    fwrite($fp, $logEntry);
    fclose($fp);
} else {
    error_log("Erro: Não foi possível abrir o arquivo de log em $logFile");
}
?>

