<?php

/*
 * This file is part of the behat/helpers project.
 *
 * (c) Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Ekino\BehatHelpers;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Behat\Mink\WebAssert;
use Ekino\BehatHelpers\ExtraSessionTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Rémi Marseille <remi.marseille@ekino.com>
 * @author Benoit de Jacobet <benoit.de-jacobet@ekino.com>
 */
class ExtraSessionTraitTest extends TestCase
{
    /**
     * Tests the maximizeWindowOnBeforeScenario method.
     */
    public function testMaximizeWindowOnBeforeScenario()
    {
        $driver  = $this->createMock(DriverInterface::class);
        $session = $this->createMock(Session::class);
        $driver->expects($this->once())->method('maximizeWindow');
        $session->expects($this->once())->method('getDriver')->willReturn($driver);

        $mock = $this->getExtraSessionMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->maximizeWindowOnBeforeScenario();
    }

    /**
     * Tests the scrollTo method.
     */
    public function testScrollTo()
    {
        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('executeScript')->with($this->equalTo('(function(){window.scrollTo(0, 10);})();'));

        $mock = $this->getExtraSessionMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->scrollTo(0, 10);
    }

    /**
     * Tests the waitForSeconds method.
     */
    public function testWaitForSeconds()
    {
        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('wait')->with($this->equalTo(1000));

        $mock = $this->getExtraSessionMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->waitForSeconds(1);
    }

    /**
     * Tests the iWaitForCssElementBeingVisible method.
     */
    public function testIWaitForCssElementBeingVisible()
    {
        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('wait')->with($this->equalTo(1000), $this->equalTo("$('foo').length >= 1"))->willReturn(true);

        $mock = $this->getExtraSessionMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $this->assertTrue($mock->iWaitForCssElementBeingVisible('foo', 1));
    }

    /**
     * Tests the iWaitForCssElementBeingInvisible method.
     */
    public function testIWaitForCssElementBeingInvisible()
    {
        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('wait')->with($this->equalTo(1000), $this->equalTo("$('foo').length == false"))->willReturn(true);

        $mock = $this->getExtraSessionMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $this->assertTrue($mock->iWaitForCssElementBeingInvisible('foo', 1));
    }

    /**
     * Asserts the method iClickOnCssElement throws an exception if element not found.
     *
     * @expectedException \Behat\Mink\Exception\ElementNotFoundException
     * @expectedExceptionMessage Element matching css ".foo" not found.
     */
    public function testIClickOnCssElementThrowsExceptionIfElementNotFound()
    {
        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'));

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);
        $session->expects($this->once())->method('getDriver')->willReturn($this->createMock(DriverInterface::class));

        $mock = $this->getExtraSessionMock();
        $mock->expects($this->exactly(2))->method('getSession')->willReturn($session);

        $mock->iClickOnCssElement('.foo');
    }

    /**
     * Tests the method iClickOnCssElement.
     */
    public function testIClickOnCssElement()
    {
        $element = $this->createMock(NodeElement::class);
        $element->expects($this->once())->method('click');

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn($element);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraSessionMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->iClickOnCssElement('.foo');
    }

    /**
     * Asserts the method iClickOnText throws an exception if element not found.
     *
     * @expectedException \Behat\Mink\Exception\ElementNotFoundException
     * @expectedExceptionMessage Text matching xpath "foo" not found.
     */
    public function testIClickOnTextThrowsExceptionIfElementNotFound()
    {
        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('xpath'), $this->equalTo("//*[contains(.,'foo')]"));

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);
        $session->expects($this->once())->method('getDriver')->willReturn($this->createMock(DriverInterface::class));

        $mock = $this->getExtraSessionMock();
        $mock->expects($this->exactly(2))->method('getSession')->willReturn($session);

        $mock->iClickOnText('foo');
    }

    /**
     * Tests the method iClickOnCssElement.
     */
    public function testIClickOnText()
    {
        $element = $this->createMock(NodeElement::class);
        $element->expects($this->once())->method('click');

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('xpath'), $this->equalTo("//*[contains(.,'foo')]"))->willReturn($element);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraSessionMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->iClickOnText('foo');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getExtraSessionMock()
    {
        return $this->getMockForTrait(
            ExtraSessionTrait::class,
            [],
            '',
            true,
            true,
            true,
            ['getSession']
        );
    }
}
