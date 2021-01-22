<?php

declare(strict_types=1);

namespace SchedulerBundle\SchedulePolicy;

use DateTimeImmutable;
use SchedulerBundle\Task\TaskInterface;
use function uasort;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class DeadlinePolicy implements PolicyInterface
{
    /**
     * @var string
     */
    private const POLICY = 'deadline';

    /**
     * @return TaskInterface[]
     */
    public function sort(array $tasks): array
    {
        foreach ($tasks as $task) {
            if (null === $task->getExecutionRelativeDeadline() || null === $task->getArrivalTime()) {
                continue;
            }

            $arrivalTime = $task->getArrivalTime();
            $absoluteDeadlineDate = $arrivalTime->add($task->getExecutionRelativeDeadline());

            $task->setExecutionAbsoluteDeadline($absoluteDeadlineDate->diff($arrivalTime));
        }

        uasort($tasks, function (TaskInterface $task, TaskInterface $nextTask): int {
            $currentDate = new DateTimeImmutable();

            return $currentDate->add($task->getExecutionAbsoluteDeadline()) < $currentDate->add($nextTask->getExecutionAbsoluteDeadline()) ? 1 : -1;
        });

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
