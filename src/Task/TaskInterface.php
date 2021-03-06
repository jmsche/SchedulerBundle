<?php

declare(strict_types=1);

namespace SchedulerBundle\Task;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use SchedulerBundle\TaskBag\NotificationTaskBag;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface TaskInterface
{
    /**
     * @var string
     */
    public const ENABLED = 'enabled';

    /**
     * @var string
     */
    public const PAUSED = 'paused';

    /**
     * @var string
     */
    public const DISABLED = 'disabled';

    /**
     * @var string
     */
    public const UNDEFINED = 'undefined';

    /**
     * @var string[]
     */
    public const ALLOWED_STATES = [
        self::ENABLED,
        self::PAUSED,
        self::DISABLED,
        self::UNDEFINED,
    ];

    /**
     * @var string
     */
    public const SUCCEED = 'succeed';

    /**
     * @var string
     */
    public const RUNNING = 'running';

    /**
     * @var string
     */
    public const DONE = 'done';

    /**
     * @var string
     */
    public const INCOMPLETE = 'incomplete';

    /**
     * @var string
     */
    public const ERRORED = 'errored';

    /**
     * @var string
     */
    public const TO_RETRY = 'to_retry';

    /**
     * @var string[]
     */
    public const EXECUTION_STATES = [
        self::SUCCEED,
        self::RUNNING,
        self::DONE,
        self::INCOMPLETE,
        self::ERRORED,
        self::TO_RETRY,
    ];

    public function getName(): string;

    public function setName(string $name): self;

    public function setArrivalTime(DateTimeImmutable $dateTimeImmutable = null): self;

    public function getArrivalTime(): ?DateTimeImmutable;

    public function setBackground(bool $background): self;

    public function mustRunInBackground(): bool;

    public function beforeScheduling($beforeSchedulingCallable = null): TaskInterface;

    public function getBeforeScheduling();

    public function beforeSchedulingNotificationBag(NotificationTaskBag $notificationTaskBag = null): TaskInterface;

    public function getBeforeSchedulingNotificationBag(): ?NotificationTaskBag;

    public function afterSchedulingNotificationBag(NotificationTaskBag $notificationTaskBag = null): TaskInterface;

    public function getAfterSchedulingNotificationBag(): ?NotificationTaskBag;

    public function beforeExecutingNotificationBag(NotificationTaskBag $notificationTaskBag = null): TaskInterface;

    public function getBeforeExecutingNotificationBag(): ?NotificationTaskBag;

    public function afterExecutingNotificationBag(NotificationTaskBag $notificationTaskBag = null): TaskInterface;

    public function getAfterExecutingNotificationBag(): ?NotificationTaskBag;

    /**
     * @param null|callable|array $afterSchedulingCallable
     */
    public function afterScheduling($afterSchedulingCallable = null): TaskInterface;

    /**
     * @return null|callable|array
     */
    public function getAfterScheduling();

    /**
     * @param null|callable|array $beforeExecutingCallable
     */
    public function beforeExecuting($beforeExecutingCallable = null): TaskInterface;

    /**
     * @return null|callable|array
     */
    public function getBeforeExecuting();

    public function afterExecuting($afterExecutingCallable = null): TaskInterface;

    public function getAfterExecuting();

    public function setDescription(string $description = null): self;

    public function getDescription(): ?string;

    public function setExpression(string $expression): self;

    public function getExpression(): string;

    public function setExecutionAbsoluteDeadline(DateInterval $dateInterval = null): self;

    public function getExecutionAbsoluteDeadline(): ?DateInterval;

    public function getExecutionComputationTime(): ?float;

    public function setExecutionComputationTime(float $executionComputationTime = null): self;

    public function getExecutionDelay(): ?int;

    public function setExecutionDelay(int $executionDelay = null): self;

    public function getExecutionMemoryUsage(): ?int;

    public function setExecutionMemoryUsage(int $executionMemoryUsage = null): self;

    public function getExecutionPeriod(): ?float;

    public function setExecutionPeriod(float $executionPeriod = null): self;

    public function getExecutionRelativeDeadline(): ?DateInterval;

    public function setExecutionRelativeDeadline(DateInterval $dateInterval = null): self;

    public function setExecutionStartDate(string $executionStartDate = null): self;

    public function getExecutionStartDate(): ?DateTimeImmutable;

    public function setExecutionEndDate(string $executionEndDate = null): self;

    public function getExecutionEndDate(): ?DateTimeImmutable;

    public function setExecutionStartTime(DateTimeImmutable $dateTimeImmutable = null): self;

    public function getExecutionStartTime(): ?DateTimeImmutable;

    public function setExecutionEndTime(DateTimeImmutable $dateTimeImmutable = null): self;

    public function getExecutionEndTime(): ?DateTimeImmutable;

    public function setLastExecution(DateTimeImmutable $dateTimeImmutable = null): self;

    public function getLastExecution(): ?DateTimeImmutable;

    public function setMaxDuration(float $maxDuration = null): self;

    public function getMaxDuration(): ?float;

    public function setMaxExecutions(int $maxExecutions = null): TaskInterface;

    public function getMaxExecutions(): ?int;

    public function setMaxRetries(int $maxRetries = null): TaskInterface;

    public function getMaxRetries(): ?int;

    public function getNice(): ?int;

    public function setNice(int $nice = null): self;

    /**
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    public function getState(): string;

    public function setState(string $state): self;

    public function getExecutionState(): ?string;

    public function setExecutionState(string $executionState = null): self;

    public function isOutput(): bool;

    public function setOutput(bool $output): self;

    public function storeOutput(bool $storeOutput = false): TaskInterface;

    public function mustStoreOutput(): bool;

    public function getPriority(): int;

    public function setPriority(int $priority): self;

    public function isQueued(): bool;

    public function setQueued(bool $queued): self;

    public function setScheduledAt(DateTimeImmutable $scheduledAt): self;

    public function getScheduledAt(): ?DateTimeImmutable;

    public function isSingleRun(): bool;

    public function setSingleRun(bool $singleRun): self;

    /**
     * @return array<int, string>
     */
    public function getTags(): array;

    /**
     * @param array<int, string> $tags
     */
    public function setTags(array $tags): self;

    public function addTag(string $tag): self;

    public function getTimezone(): ?DateTimeZone;

    public function setTimezone(DateTimeZone $dateTimeZone = null): self;

    public function isTracked(): bool;

    public function setTracked(bool $tracked): self;
}
