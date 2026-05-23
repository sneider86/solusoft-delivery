<?php
namespace Solusoft\Delivery\Logger;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\State;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class Logger extends MonologLogger
{
    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * @var State $appState
     */
    private State $appState;

    /**
     * @var String $classMethod
     */
    private String $classMethod;

    /**
     * @param string $name
     * @param string $handlers
     * @param State $appState
     */
    public function __construct(
        string $name,
        array $handlers,
        State $appState
    ) {
        $this->classMethod = "Logger";
        parent::__construct(
            $name,
            $handlers
        );
        $this->appState = $appState;
    }

    /**
     * Method setClassMethodDebug
     *
     * @param String $input
     */
    public function setClassMethodDebug(String $input)
    {
        $this->classMethod = $input;
    }

    /**
     * Print logs only on mode developer
     *
     * @param String $message
     * @param array $context
     */
    public function debug($message, array $context = []): void
    {
        if ($this->appState->getMode() === State::MODE_DEVELOPER) {
            parent::debug($this->classMethod.': '.$message, $context);
        }
    }

    /**
     * Print logs only on mode production
     *
     * @param String $message
     * @param array $context
     */
    public function error($message, array $context = []): void
    {
        if ($this->appState->getMode() === State::MODE_PRODUCTION ||
            $this->appState->getMode() === State::MODE_DEVELOPER ||
            $this->appState->getMode() === State::MODE_DEFAULT
        ) {
            parent::error($this->classMethod.': '.$message, $context);
        }
    }

    /**
     * Print logs only on mode production
     *
     * @param String $message
     * @param array $context
     */
    public function info($message, array $context = []): void
    {
        if ($this->appState->getMode() === State::MODE_PRODUCTION ||
            $this->appState->getMode() === State::MODE_DEVELOPER ||
            $this->appState->getMode() === State::MODE_DEFAULT
        ) {
            parent::info($this->classMethod.': '.$message, $context);
        }
    }
}
