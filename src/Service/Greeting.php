<?php


namespace App\Service;

use Psr\Log\LoggerInterface;

class Greeting
{
    private $logger;
    private $message;

    /**
     * Greeting constructor.
     * @param LoggerInterface $logger
     * @param string $message
     */
    public function __construct(LoggerInterface $logger, string $message)
    {
        $this->logger = $logger;
        $this->message = $message;
    }

    public function greet(string $name): string
    {
        $this->logger->info("Greeted $name");
        return "{$this->message } $name";
    }
}
