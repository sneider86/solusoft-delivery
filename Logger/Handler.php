<?php
namespace Solusoft\Delivery\Logger;

use Monolog\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * Path of file
     * @var string
     */
    protected $fileName = '/var/log/solusoft_delivery.log';
}