<?php

namespace Doctrine\Tests\ORM\Functional;

use Doctrine\ORM\Event\OnComparisonEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Tests\Models\CMS\CmsUser;
use Doctrine\Tests\OrmFunctionalTestCase;

/**
 * ComparisonEventTest
 *
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
class ComparisonEventTest extends OrmFunctionalTestCase
{
    /**
     * @var ComparisonListener
     */
    private $listener;

    protected function setUp()
    {
        $this->useModelSet('cms');
        parent::setUp();
        $this->listener = new ComparisonListener();
        $evm = $this->_em->getEventManager();
        $evm->addEventListener(Events::onComparison, $this->listener);
    }

    public function testListenerShouldBeNotified()
    {
        $this->_em->persist($this->createNewValidUser());
        $this->_em->flush();
        $this->assertFalse($this->listener->wasNotified);
        $this->assertCount(0, $this->listener->receivedArgs);
    }

    public function testListenerShouldBeNotifierComputeChangeSet()
    {
        $user = $this->createNewValidUser();
        $this->_em->persist($user);
        $this->_em->flush();
        $this->_em->clear();

        $user = $this->_em->find(CmsUser::class, $user->getId());
        $originalValue = $user->username;
        $user->username = 'foo';

        $this->_em->getUnitOfWork()->computeChangeSet(
            $this->_em->getClassMetadata(CmsUser::class),
            $user
        );

        $this->assertCount(1, $this->listener->receivedArgs);
        /** @var OnComparisonEventArgs $args */
        $args = $this->listener->receivedArgs[0];
        $this->assertSame($this->_em, $args->getObjectManager());
        $this->assertSame($user, $args->getObject());
        $this->assertSame('username', $args->getPropertyName());
        $this->assertSame($originalValue, $args->getOriginalValue());
        $this->assertSame('foo', $args->getActualValue());
        $this->assertNotSame(0, $args->getComparisonResult());

        $this->assertTrue($this->listener->wasNotified);

        $changeSet = $this->_em->getUnitOfWork()->getEntityChangeSet($user);
        $this->assertCount(1, $changeSet);
        $this->assertArrayHasKey('username', $changeSet);
        $this->assertSame($originalValue, $changeSet['username'][0]);
        $this->assertSame($user->username, $changeSet['username'][1]);
    }

    public function testListenerShouldBeNotifierRecomputeChangeSet()
    {
        $user = $this->createNewValidUser();
        $this->_em->persist($user);
        $this->_em->flush();
        $this->_em->clear();

        $user = $this->_em->find(CmsUser::class, $user->getId());
        $originalValue = $user->username;
        $user->username = 'foo';

        $this->_em->getUnitOfWork()->recomputeSingleEntityChangeSet(
            $this->_em->getClassMetadata(CmsUser::class),
            $user
        );

        $this->assertCount(1, $this->listener->receivedArgs);
        /** @var OnComparisonEventArgs $args */
        $args = $this->listener->receivedArgs[0];
        $this->assertSame($this->_em, $args->getObjectManager());
        $this->assertSame($user, $args->getObject());
        $this->assertSame('username', $args->getPropertyName());
        $this->assertSame($originalValue, $args->getOriginalValue());
        $this->assertSame('foo', $args->getActualValue());
        $this->assertNotSame(0, $args->getComparisonResult());

        $this->assertTrue($this->listener->wasNotified);

        $changeSet = $this->_em->getUnitOfWork()->getEntityChangeSet($user);
        $this->assertCount(1, $changeSet);
        $this->assertArrayHasKey('username', $changeSet);
        $this->assertSame($originalValue, $changeSet['username'][0]);
        $this->assertSame($user->username, $changeSet['username'][1]);
    }

    /**
     * @return CmsUser
     */
    private function createNewValidUser()
    {
        $user = new CmsUser();
        $user->username = 'dfreudenberger';
        $user->name = 'Daniel Freudenberger';
        return $user;
    }
}

class ComparisonListener
{
    /**
     * @var bool
     */
    public $wasNotified = false;

    /**
     * @var OnComparisonEventArgs
     */
    public $receivedArgs = [];

    /**
     * @param OnComparisonEventArgs $args
     */
    public function onComparison(OnComparisonEventArgs $args)
    {
        $this->wasNotified = true;
        if ($args->getOriginalValue() === $args->getActualValue()) {
            $args->setComparisonResult(0);
        } else {
            $args->setComparisonResult(1);
            $this->receivedArgs[] = $args;
        }
    }
}
