<?php

declare(strict_types=1);

namespace Tests\SchedulerBundle\Transport;

use Generator;
use PHPUnit\Framework\TestCase;
use SchedulerBundle\SchedulePolicy\SchedulePolicyOrchestrator;
use SchedulerBundle\Transport\Dsn;
use SchedulerBundle\Transport\InMemoryTransportFactory;
use SchedulerBundle\Transport\RoundRobinTransport;
use SchedulerBundle\Transport\RoundRobinTransportFactory;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class RoundRobinTransportFactoryTest extends TestCase
{
    public function testFactoryCanSupportTransport(): void
    {
        $roundRobinTransportFactory = new RoundRobinTransportFactory([]);

        self::assertFalse($roundRobinTransportFactory->support('test://'));
        self::assertTrue($roundRobinTransportFactory->support('roundrobin://'));
        self::assertTrue($roundRobinTransportFactory->support('rr://'));
    }

    /**
     * @dataProvider provideDsn
     */
    public function testFactoryCanCreateTransport(string $dsn): void
    {
        $serializer = $this->createMock(SerializerInterface::class);

        $roundRobinTransportFactory = new RoundRobinTransportFactory([
            new InMemoryTransportFactory(),
        ]);
        $transport = $roundRobinTransportFactory->createTransport(Dsn::fromString($dsn), [], $serializer, new SchedulePolicyOrchestrator([]));

        self::assertInstanceOf(RoundRobinTransport::class, $transport);
        self::assertSame('first_in_first_out', $transport->getExecutionMode());
        self::assertArrayHasKey('execution_mode', $transport->getOptions());
        self::assertArrayHasKey('quantum', $transport->getOptions());
        self::assertSame(2, $transport->getOptions()['quantum']);
    }

    public function provideDsn(): Generator
    {
        yield ['roundrobin://(memory://first_in_first_out && memory://last_in_first_out)'];
        yield ['rr://(memory://first_in_first_out && memory://last_in_first_out)'];
    }
}
