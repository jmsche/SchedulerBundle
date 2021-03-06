<?php

declare(strict_types=1);

namespace SchedulerBundle\Bridge\Doctrine\Transport;

use Doctrine\DBAL\Connection as DoctrineConnection;
use Doctrine\Persistence\ConnectionRegistry;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SchedulerBundle\Exception\RuntimeException;
use SchedulerBundle\Exception\TransportException;
use SchedulerBundle\SchedulePolicy\SchedulePolicyOrchestratorInterface;
use SchedulerBundle\Transport\Dsn;
use SchedulerBundle\Transport\TransportFactoryInterface;
use SchedulerBundle\Transport\TransportInterface;
use Symfony\Component\Serializer\SerializerInterface;
use function is_int;
use function sprintf;
use function strpos;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class DoctrineTransportFactory implements TransportFactoryInterface
{
    private ConnectionRegistry $registry;
    private LoggerInterface $logger;

    public function __construct(
        ConnectionRegistry $registry,
        ?LoggerInterface $logger = null
    ) {
        $this->registry = $registry;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function createTransport(Dsn $dsn, array $options, SerializerInterface $serializer, SchedulePolicyOrchestratorInterface $schedulePolicyOrchestrator): TransportInterface
    {
        try {
            $doctrineConnection = $this->registry->getConnection($dsn->getHost());
        } catch (InvalidArgumentException $invalidArgumentException) {
            throw new TransportException(sprintf('Could not find Doctrine connection from Scheduler DSN "doctrine://%s".', $dsn->getHost()), is_int($invalidArgumentException->getCode()) ? $invalidArgumentException->getCode() : 0, $invalidArgumentException);
        }

        if (!$doctrineConnection instanceof DoctrineConnection) {
            throw new RuntimeException('The connection is not a valid one');
        }

        return new DoctrineTransport([
            'auto_setup' => $dsn->getOptionAsBool('auto_setup', true),
            'connection' => $dsn->getHost(),
            'execution_mode' => $dsn->getOption('execution_mode', 'first_in_first_out'),
            'table_name' => $dsn->getOption('table_name', '_symfony_scheduler_tasks'),
        ], $doctrineConnection, $serializer, $schedulePolicyOrchestrator, $this->logger);
    }

    /**
     * {@inheritdoc}
     */
    public function support(string $dsn, array $options = []): bool
    {
        return 0 === strpos($dsn, 'doctrine://') || 0 === strpos($dsn, 'dbal://');
    }
}
