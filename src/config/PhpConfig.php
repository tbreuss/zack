<?php declare(strict_types=1);

namespace tebe\zack\config;

readonly class PhpConfig
{
    public int $errorReporting;
    public bool $displayErrors;
    public bool $displayStartupErrors;
    public bool $logErrors;
    public string $errorLog;

    public function __construct(array $config, string $logPath)
    {
        $this->errorReporting = $config['errorReporting'] ?? E_ALL;
        $this->displayErrors = $config['displayErrors'] ?? false;
        $this->displayStartupErrors = $config['displayStartupErrors'] ?? false;
        $this->logErrors = $config['logErrors'] ?? true;
        $this->errorLog = $config['errorLog'] ?? $logPath . '/errors.log';
    }
}
