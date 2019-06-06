<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 10:58
 */

namespace Aplab\AplabAdminBundle\EventListener;




use Aplab\AplabAdminBundle\Component\SystemState\SystemStateManager;
use Psr\Log\LoggerInterface;

class TerminateListener
{
    private $systemStateManager;

    private $logger;

    public function __construct(SystemStateManager $systemStateManager, LoggerInterface $logger)
    {
        $this->systemStateManager = $systemStateManager;
        $this->logger = $logger;
    }

    public function onKernelTerminate($event)
    {
        $this->systemStateManager->flush($this->logger);
    }
}