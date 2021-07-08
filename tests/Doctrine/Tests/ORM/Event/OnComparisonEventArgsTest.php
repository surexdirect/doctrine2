<?php

namespace Doctrine\Tests\ORM\Event;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnComparisonEventArgs;
use Doctrine\Tests\Models\Quote\City;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see \Doctrine\ORM\Event\OnComparisonEventArgsTest}
 *
 * @covers \Doctrine\ORM\Event\OnComparisonEventArgsTest
 */
class OnComparisonEventArgsTest extends TestCase
{
    public function testOnComparisonEventArgs()
    {
        /* @var $objectManager \Doctrine\Common\Persistence\ObjectManager */
        $objectManager = $this->createMock(EntityManagerInterface::class);
        $entity        = new City('foo');

        $args = new OnComparisonEventArgs($objectManager, $entity, 'name', $entity->name, 'bar');
        $this->assertInstanceOf(LifecycleEventArgs::class, $args);

        $this->assertSame('foo', $args->getOriginalValue());
        $this->assertSame('bar', $args->getActualValue());
        $this->assertSame('name', $args->getPropertyName());
        $this->assertSame($objectManager, $args->getObjectManager());
        $this->assertSame($entity, $args->getObject());

        $this->assertNull($args->getComparisonResult());

        $args->setComparisonResult(-1);
        $this->assertSame(-1, $args->getComparisonResult());

        $args->setComparisonResult(0);
        $this->assertSame(0, $args->getComparisonResult());

        $args->setComparisonResult(1);
        $this->assertSame(1, $args->getComparisonResult());
    }
}
