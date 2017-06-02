<?php

namespace Tests\CircuitBreakerBundle\Service;

use CircuitBreakerBundle\Service\CircuitBreakerService;

class CircuitBreakerServiceTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->mockCacheApp = $this->getMockBuilder('Symfony\Component\Cache\Adapter\AdapterInterface')
            ->disableOriginalConstructor()
            ->setMethods(['getItem', 'save', 'doHave', 'doFetch', 'doClear', 'doSave', 'doDelete'])
            ->getMock();

        $this->mockLogger = $this->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->setMethods(['log', 'emergency', 'alert', 'error', 'info', 'debug', 'notice', 'critical', 'warning'])
            ->getMock();

        $this->mockItem = $this->getMockBuilder('Psr\Cache\CacheItemInterface')
            ->disableOriginalConstructor()
            ->setMethods(['get', 'set', 'getKey', 'isHit', 'expiresAt', 'expiresAfter'])
            ->getMock();
    }

    public function testIsClosedByDefault()
    {
        $this->mockCacheApp->expects($this->any())
            ->method('getItem')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 2, 20);
        $this->assertEquals(false, $service->isOpen('test'));
    }

    public function testIsClosedWithCallSucceeds()
    {
        $this->mockCacheApp->expects($this->once())
            ->method('getItem')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 2, 20);
        $service->setLogger($this->mockLogger);
        $service->save('test', true);

        $this->assertEquals(false, $service->isOpen('test'));
    }

    public function testIsClosedWithManyCallSucceeds()
    {
        $this->mockCacheApp->expects($this->any())
            ->method('getItem')
            ->with('test')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 5, 20);
        $service->setLogger($this->mockLogger);
        $service->save('test', true);
        $service->save('test', true);
        $service->save('test', true);
        $service->save('test', true);

        $this->assertEquals(false, $service->isOpen('test'));
    }

    public function testIsClosedWithOneFail()
    {
        $this->mockItem->expects($this->once())
            ->method('set')
            ->with(1);

        $this->mockItem->expects($this->once())
            ->method('expiresAfter')
            ->with(20);

        $this->mockCacheApp->expects($this->once())
            ->method('getItem')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 2, 20);
        $service->setLogger($this->mockLogger);
        $service->save('test', false);

        $this->assertEquals(false, $service->isOpen('test'));
    }

    public function testIsClosedWithFailLessThanThreshold()
    {
        $this->mockItem->expects($this->exactly(3))
            ->method('set');

        $this->mockItem->expects($this->exactly(3))
            ->method('expiresAfter')
            ->with(20);

        $this->mockCacheApp->expects($this->exactly(3))
            ->method('getItem')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 5, 20);
        $service->setLogger($this->mockLogger);
        $service->save('test', false);
        $service->save('test', false);
        $service->save('test', false);

        $this->assertEquals(false, $service->isOpen('test'));
    }

    public function testIsClosedWithFailAndSucceedsAlternaly()
    {
        $this->mockItem->expects($this->exactly(3))
            ->method('set');

        $this->mockItem->expects($this->exactly(2))
            ->method('expiresAfter')
            ->with(20);

        $this->mockCacheApp->expects($this->exactly(3))
            ->method('getItem')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 3, 20);
        $service->setLogger($this->mockLogger);
        $service->save('test', false);
        $service->save('test', false);
        $service->save('test', true);

        $this->assertEquals(false, $service->isOpen('test'));
    }

    public function testIsOpenAfterFailExactlyThreshold()
    {
        $this->mockItem->expects($this->any())
            ->method('set');

        $this->mockItem->expects($this->any())
            ->method('get')
            ->willReturn(3);

        $this->mockItem->expects($this->any())
            ->method('expiresAfter')
            ->with(20);

        $this->mockCacheApp->expects($this->any())
            ->method('getItem')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 3, 20);
        $service->setLogger($this->mockLogger);
        $service->save('test', false);

        $this->assertEquals(true, $service->isOpen('test'));
    }

    public function testIsOpenAfterFailMoreThreshold()
    {
        $this->mockItem->expects($this->any())
            ->method('set');

        $this->mockItem->expects($this->any())
            ->method('get')
            ->willReturn(5);

        $this->mockItem->expects($this->any())
            ->method('expiresAfter')
            ->with(20);

        $this->mockCacheApp->expects($this->any())
            ->method('getItem')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 3, 20);
        $service->setLogger($this->mockLogger);
        $service->save('test', false);

        $this->assertEquals(true, $service->isOpen('test'));
    }

    public function testIsOpenAfterFailAgainAfterThreshold()
    {
        $this->mockItem->expects($this->any())
            ->method('set');

        $this->mockItem->expects($this->any())
            ->method('get')
            ->willReturn(5);

        $this->mockItem->expects($this->any())
            ->method('expiresAfter')
            ->with(20);

        $this->mockCacheApp->expects($this->any())
            ->method('getItem')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 3, 20);
        $service->setLogger($this->mockLogger);
        $service->save('test', false);
        $service->save('test', false);

        $this->assertEquals(true, $service->isOpen('test'));
    }

    public function testIsClosedAfterHalfOpenWithCallSucceeds()
    {
        $this->mockItem->expects($this->any())
            ->method('set');

        $this->mockItem->expects($this->any())
            ->method('get')
            ->willReturn(5);

        $this->mockItem->expects($this->any())
            ->method('expiresAfter')
            ->with(20);

        $this->mockCacheApp->expects($this->any())
            ->method('getItem')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 3, 20);
        $service->setLogger($this->mockLogger);
        $service->save('test', false);
        $service->save('test', true);

        $this->assertEquals(false, $service->isOpen('test'));
    }

    public function testIsOpenWhenCacheValueOverThreshold()
    {
        $this->mockItem->expects($this->once())
            ->method('get')
            ->will($this->returnValue(15));

        $this->mockCacheApp->expects($this->once())
            ->method('getItem')
            ->willReturn($this->mockItem);

        $service = new CircuitBreakerService($this->mockCacheApp, 3, 20);
        $service->setLogger($this->mockLogger);

        $this->assertEquals(true, $service->isOpen('test'));
    }
}
