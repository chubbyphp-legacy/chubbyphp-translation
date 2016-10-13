<?php

namespace Chubbyphp\Tests\Translation;

use Psr\Log\LoggerInterface;

trait LoggerTestTrait
{
    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        $methods = [
            'emergency',
            'alert',
            'critical',
            'error',
            'warning',
            'notice',
            'info',
            'debug',
        ];

        /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this
            ->getMockBuilder(LoggerInterface::class)
            ->setMethods(array_merge($methods, ['log']))
            ->getMockForAbstractClass()
        ;

        $logger->__logs = [];

        foreach ($methods as $method) {
            $logger
                ->expects(self::any())
                ->method($method)
                ->willReturnCallback(
                    function (string $message, array $context = []) use ($logger, $method) {
                        $logger->log($method, $message, $context);
                    }
                )
            ;
        }

        $logger
            ->expects(self::any())
            ->method('log')
            ->willReturnCallback(
                function (string $level, string $message, array $context = []) use ($logger) {
                    $logger->__logs[] = ['level' => $level, 'message' => $message, 'context' => $context];
                }
            )
        ;

        return $logger;
    }
}
