<?php

declare(strict_types=1);

namespace Doctrine\ORM\Event;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Provides event arguments for the onComparison event.
 */
class OnComparisonEventArgs extends LifecycleEventArgs
{
    /** @var string */
    private $propertyName;

    /** @var mixed */
    private $originalValue;

    /** @var mixed */
    private $actualValue;

    /**
     * Returns a negative integer, zero, or a positive integer as the actualValue is less than, equal to,
     * or greater than the specified originalValue.
     *
     * @var int
     */
    private $comparisonResult;

    /**
     * @param object $entity
     * @param mixed  $originalValue
     * @param mixed  $actualValue
     */
    public function __construct(
        EntityManagerInterface $em,
        $entity,
        string $propertyName,
        $originalValue,
        $actualValue
    ) {
        parent::__construct($entity, $em);
        $this->propertyName  = $propertyName;
        $this->originalValue = $originalValue;
        $this->actualValue   = $actualValue;
    }

    /** @return string */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /** @return mixed */
    public function getOriginalValue()
    {
        return $this->originalValue;
    }

    /** @return mixed */
    public function getActualValue()
    {
        return $this->actualValue;
    }

    /** @return int|null */
    public function getComparisonResult()
    {
        return $this->comparisonResult;
    }

    public function setComparisonResult(int $comparisonResult)
    {
        $this->comparisonResult = $comparisonResult;
    }
}
