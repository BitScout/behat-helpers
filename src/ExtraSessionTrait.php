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

/**
 * @author Rémi Marseille <remi.marseille@ekino.com>
 * @author Benoit de Jacobet <benoit.de-jacobet@ekino.com>
 */
trait ExtraSessionTrait
{
    /**
     * @BeforeScenario
     */
    public function maximizeWindowOnBeforeScenario()
    {
        $this->getSession()->getDriver()->maximizeWindow();
    }

    /**
     * @When /^I scroll to (\d+) and (\d+)?$/
     *
     * @param int $x
     * @param int $y
     */
    public function scrollTo($x, $y)
    {
        $this->getSession()->executeScript("(function(){window.scrollTo($x, $y);})();");
    }

    /**
     * @When /^I wait for (\d+) seconds?$/
     *
     * @param int $seconds
     */
    public function waitForSeconds($seconds)
    {
        $this->getSession()->wait($seconds * 1000);
    }

    /**
     * Wait for the given css element being visible.
     *
     * @Given /^I wait for "([^"]*)" element being visible for (\d+) seconds$/
     *
     * @param string $element
     * @param int    $seconds
     *
     * @return bool
     */
    public function iWaitForCssElementBeingVisible($element, $seconds)
    {
        return $this->getSession()->wait($seconds * 1000, sprintf("$('%s').length >= 1", $element));
    }

    /**
     * Wait for the given css element being masked.
     *
     * @Given /^I wait for "([^"]*)" element being invisible for (\d+) seconds$/
     *
     * @param string $element
     * @param int    $seconds
     *
     * @return bool
     */
    public function iWaitForCssElementBeingInvisible($element, $seconds)
    {
        return $this->getSession()->wait($seconds * 1000, sprintf("$('%s').length == false", $element));
    }

    /**
     * Click on the element matching given selector
     *
     * @Given /^I click on element "(?P<selector>[^"]*)"$/
     *
     * @param string $selector
     *
     * @throws ElementNotFoundException
     */
    public function iClickOnCssElement($selector)
    {
        $page    = $this->getSession()->getPage();
        $element = $page->find('css', $selector);

        if (null === $element) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'element', 'css', $selector);
        }

        $element->click();
    }

    /**
     * Click on the matching text
     *
     * @Given /^I click on (?:link|button) containing "(?P<text>[^"]*)"$/
     *
     * @param string $text
     *
     * @throws ElementNotFoundException
     */
    public function iClickOnText($text)
    {
        $page    = $this->getSession()->getPage();
        $element = $page->find('xpath', sprintf("//*[contains(.,'%s')]", $text));

        if (null === $element) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'text', 'xpath', $text);
        }

        $element->click();
    }
}
