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
use Ekino\BehatHelpers\ExtraWebAssertTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Rémi Marseille <remi.marseille@ekino.com>
 */
class ExtraWebAssertTraitTest extends TestCase
{
    /**
     * Tests the assertElementAttributeExists method.
     */
    public function testAssertElementAttributeExists()
    {
        $webAssert = $this->createMock(WebAssert::class);
        $webAssert->expects($this->once())->method('elementAttributeExists')->with($this->equalTo('css'), $this->equalTo('a.action_bar__next'));

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('assertSession')->willReturn($webAssert);
        $mock->expects($this->once())->method('fixStepArgument')->with($this->equalTo('disabled'));

        $mock->assertElementAttributeExists('a.action_bar__next', 'disabled');
    }

    /**
     * Tests the assertElementNotVisible method.
     */
    public function testAssertElementNotVisible()
    {
        $element = $this->createMock(NodeElement::class);
        $element->expects($this->once())->method('isVisible')->willReturn(false);

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn($element);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->assertElementNotVisible('.foo');
    }

    /**
     * Tests the assertElementNotVisible method.
     *
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage Element ".foo" found on the page, but should not be visible
     */
    public function testAssertElementNotVisibleThrowsExceptionIfElementVisible()
    {
        $element = $this->createMock(NodeElement::class);
        $element->expects($this->once())->method('isVisible')->willReturn(true);

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn($element);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->assertElementNotVisible('.foo');
    }

    /**
     * Tests the assertElementNotVisible method.
     *
     * @expectedException \Behat\Mink\Exception\ElementNotFoundException
     * @expectedExceptionMessage Element matching css ".foo" not found.
     */
    public function testAssertElementNotVisibleThrowsExceptionIfElementNotFound()
    {
        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'));

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);
        $session->expects($this->once())->method('getDriver')->willReturn($this->createMock(DriverInterface::class));

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->exactly(2))->method('getSession')->willReturn($session);

        $mock->assertElementNotVisible('.foo');
    }

    /**
     * Tests the assertElementVisible method.
     */
    public function testAssertElementVisible()
    {
        $element = $this->createMock(NodeElement::class);
        $element->expects($this->once())->method('isVisible')->willReturn(true);

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn($element);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->assertElementVisible('.foo');
    }

    /**
     * Tests the assertElementVisible method.
     *
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage Element ".foo" found on the page, but should be visible
     */
    public function testAssertElementVisibleThrowsExceptionIfElementNotVisible()
    {
        $element = $this->createMock(NodeElement::class);
        $element->expects($this->once())->method('isVisible')->willReturn(false);

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn($element);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->assertElementVisible('.foo');
    }

    /**
     * Tests the assertElementVisible method.
     *
     * @expectedException \Behat\Mink\Exception\ElementNotFoundException
     * @expectedExceptionMessage Element matching css ".foo" not found.
     */
    public function testAssertElementVisibleThrowsExceptionIfElementNotFound()
    {
        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'));

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);
        $session->expects($this->once())->method('getDriver')->willReturn($this->createMock(DriverInterface::class));

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->exactly(2))->method('getSession')->willReturn($session);

        $mock->assertElementVisible('.foo');
    }

    /**
     * Tests the assertAtLeastNumElements method.
     */
    public function testAssertAtLeastNumElements()
    {
        $element = $this->createMock(NodeElement::class);

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn([$element, $element, $element]);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->assertAtLeastNumElements(2, '.foo');
    }

    /**
     * Tests the assertAtLeastNumElements method.
     */
    public function testAssertAtLeastNumElementsExactly()
    {
        $element = $this->createMock(NodeElement::class);

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn([$element, $element]);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->assertAtLeastNumElements(2, '.foo');
    }

    /**
     * Tests the assertAtLeastNumElements method.
     *
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage 1 ".foo" found on the page, but should at least 2.
     */
    public function testAssertAtLeastNumElementsNotEnough()
    {
        $element = $this->createMock(NodeElement::class);

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn($element);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->assertAtLeastNumElements(2, '.foo');
    }

    /**
     * Tests the assertElementVisible method.
     *
     * @expectedException \Behat\Mink\Exception\ElementNotFoundException
     * @expectedExceptionMessage Element matching css ".foo" not found.
     */
    public function testAssertAtLeastNumElementsThrowsExceptionIfElementNotFound()
    {
        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'));

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);
        $session->expects($this->once())->method('getDriver')->willReturn($this->createMock(DriverInterface::class));

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->exactly(2))->method('getSession')->willReturn($session);

        $mock->assertAtLeastNumElements(2, '.foo');
    }

    // @todo : tests assertExactlyNumElement method

    /**
     * Tests the assertExactlyNumElement method.
     */
    public function testAssertExactlyNumElement()
    {
        $element = $this->createMock(NodeElement::class);

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn([$element, $element]);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->assertExactlyNumElement(2, '.foo');
    }

    /**
     * Tests the assertExactlyNumElement method.
     *
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage 1 ".foo" found on the page, but should find 2.
     */
    public function testAssertExactlyNumElementNotEnough()
    {
        $element = $this->createMock(NodeElement::class);

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn($element);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->assertExactlyNumElement(2, '.foo');
    }

    /**
     * Tests the assertExactlyNumElement method.
     *
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage 3 ".foo" found on the page, but should find 2.
     */
    public function testAssertExactlyNumElementTooMuch()
    {
        $element = $this->createMock(NodeElement::class);

        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'))->willReturn([$element, $element, $element]);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->once())->method('getSession')->willReturn($session);

        $mock->assertExactlyNumElement(2, '.foo');
    }

    /**
     * Tests the assertExactlyNumElement method.
     *
     * @expectedException \Behat\Mink\Exception\ElementNotFoundException
     * @expectedExceptionMessage Element matching css ".foo" not found.
     */
    public function testAssertExactlyNumElementThrowsExceptionIfElementNotFound()
    {
        $page = $this->createMock(DocumentElement::class);
        $page->expects($this->once())->method('find')->with($this->equalTo('css'), $this->equalTo('.foo'));

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('getPage')->willReturn($page);
        $session->expects($this->once())->method('getDriver')->willReturn($this->createMock(DriverInterface::class));

        $mock = $this->getExtraWebAssertMock();
        $mock->expects($this->exactly(2))->method('getSession')->willReturn($session);

        $mock->assertExactlyNumElement(2, '.foo');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getExtraWebAssertMock()
    {
        return $this->getMockForTrait(
            ExtraWebAssertTrait::class,
            [],
            '',
            true,
            true,
            true,
            ['assertSession', 'fixStepArgument', 'getSession']
        );
    }
}
