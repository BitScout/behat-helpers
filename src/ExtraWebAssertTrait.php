<?php

/*
 * This file is part of the behat/helpers project.
 *
 * (c) Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\BehatHelpers;

use Behat\Mink\Exception\ElementNotFoundException;
use PHPUnit\Framework\Assert;

/**
 * @author Rémi Marseille <remi.marseille@ekino.com>
 * @author Benoit de Jacobet <benoit.de-jacobet@ekino.com>
 */
trait ExtraWebAssertTrait
{
    /**
     * Checks element has a specific attribute
     *
     * @Then /^the "(?P<element>[^"]*)" element should have attribute "(?P<value>(?:[^"]|\\")*)"$/
     *
     * @param string $element
     * @param string $value
     */
    public function assertElementAttributeExists($element, $value)
    {
        $this->assertSession()->elementAttributeExists('css', $element, $this->fixStepArgument($value));
    }

    /**
     * Checks the CSS element is not visible on page
     *
     * @Then /^the "([^"]*)" element should not be visible$/
     *
     * @param string $selector
     *
     * @throws \Exception
     */
    public function assertElementNotVisible($selector)
    {
        $element = $this->getSession()->getPage()->find('css', $selector);

        if (null === $element) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'element', 'css', $selector);
        }

        $message = sprintf( 'Element "%s" found on the page, but should not be visible.', $selector);

        Assert::assertFalse($element->isVisible(), $message);
    }

    /**
     * Checks the CSS element is visible on page
     *
     * @Then /^the "([^"]*)" element should be visible$/
     *
     * @param string $selector
     *
     * @throws \Exception
     */
    public function assertElementVisible($selector)
    {
        $element = $this->getSession()->getPage()->find('css', $selector);

        if (null === $element) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'element', 'css', $selector);
        }

        $message = sprintf( 'Element "%s" found on the page, but should be visible.', $selector);

        Assert::assertTrue($element->isVisible(), $message);
    }

    /**
     * Checks at least X CSS elements exist on the page
     *
     * @Then /^(?:|I )should see at least (?P<num>\d+) "(?P<element>[^"]*)" elements?$/
     *
     * @param int $num
     * @param     $selector
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function assertAtLeastNumElements($num, $selector)
    {
        $elements = $this->getSession()->getPage()->find('css', $selector);

        if (null === $elements) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'element', 'css', $selector);
        }

        $message = sprintf( '%d "%s" found on the page, but should at least %d.', count($elements), $selector, $num);

        Assert::assertTrue(intval($num) <= count($elements), $message);
    }

    /**
     * Checks exactly X CSS element exists on the page
     *
     * @Then /^(?:|I )should see exactly (?P<num>\d+) "(?P<element>[^"]*)" elements?$/
     *
     * @param int    $num
     * @param string $selector
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function assertExactlyNumElement($num, $selector)
    {
        $elements = $this->getSession()->getPage()->find('css', $selector);

        if (null === $elements) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'element', 'css', $selector);
        }

        $message = sprintf('%d "%s" found on the page, but should find %d.', count($elements), $selector, $num);

        Assert::assertTrue(count($elements) === intval($num), $message);
    }
}
