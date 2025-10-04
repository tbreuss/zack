<?php declare(strict_types=1);

namespace tebe\zack\config;

readonly class PhpConfig
{
    public bool $displayErrors;
    public bool $displayStartupErrors;
    public int $errorLevel;
    public string $errorLog;
    public bool $logErrors;

    /**
     * @param mixed[] $config
     */
    public function __construct(array $config, string $logPath)
    {
        $this->displayErrors = $config['displayErrors'] ?? false;
        $this->displayStartupErrors = $config['displayStartupErrors'] ?? false;
        $this->errorLevel = $config['errorLevel'] ?? E_ALL;
        $this->errorLog = $config['errorLog'] ?? $logPath . '/errors.log';
        $this->logErrors = $config['logErrors'] ?? true;
    }
}
