<?php

declare(strict_types=1);

namespace SchedulerBundle\SchedulePolicy;

use SchedulerBundle\Task\TaskInterface;
use function uasort;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class RoundRobinPolicy implements PolicyInterface
{
    /**
     * @var string
     */
    private const POLICY = 'round_robin';

    /**
     * @return TaskInterface[]
     */
    public function sort(array $tasks): array
    {
        uasort($tasks, fn (TaskInterface $task, TaskInterface $nextTask): int => $task->getExecutionComputationTime() >= $task->getMaxDuration() && $task->getExecutionComputationTime() < $nextTask->getExecutionComputationTime() ? 1 : -1);

        return $tasks;
    }

    /**
     * {@inheritdoc}
     */
    public function support(string $policy): bool
    {
        return self::POLICY === $policy;
    }
}
