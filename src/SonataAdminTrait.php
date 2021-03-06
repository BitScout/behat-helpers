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

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementHtmlException;
use Behat\Mink\Exception\ElementNotFoundException;
use WebDriver\Exception\ElementNotVisible;

/**
 * @author Rémi Marseille <remi.marseille@ekino.com>
 */
trait SonataAdminTrait
{
    /**
     * @param string $username
     * @param string $password
     */
    public function login($username, $password)
    {
        $this->visitPath('sonata_user_admin_security_login');
        $this->fillField('_username', $username);
        $this->fillField('_password', $password);
        $this->pressButton('Connexion');
    }

    /**
     * Open menu item.
     *
     * @When /^I open the menu "([^"]*)"$/
     *
     * @param string $text
     *
     * @throws ElementNotFoundException
     */
    public function iOpenMenuItemByText($text)
    {
        $element = $this->getSession()->getPage()->find('xpath', '//aside//span[text()="'.$text.'"]');

        if (null === $element) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), null, 'text', $text);
        }

        $element->click();
    }

    /**
     * Should see in navbar action.
     *
     * @Then /^I should see "([^"]*)" action in navbar$/
     *
     * @param mixed $text
     *
     * @throws ElementNotFoundException
     * @throws ElementNotVisible
     */
    public function iShouldSeeActionInNavbar($text)
    {
        $element = $this->getNavbarActionElement($text);

        if (is_null($element)) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), null, 'text', $text);
        }

        if (!$element->isVisible()) {
            throw new ElementNotVisible(sprintf('Cannot find action "%s" in Navbar action', $text));
        }
    }

    /**
     * Should not see in navbar action.
     *
     * @Then /^I should not see "([^"]*)" action in navbar$/
     *
     * @param string $text
     *
     * @throws ElementHtmlException
     */
    public function iShouldNotSeeActionInNavbar($text)
    {
        $element = $this->getNavbarActionElement($text);

        if (!is_null($element)) {
            throw new ElementHtmlException(sprintf('Action "%s" has been found in Navbar action', $text), $this->getSession()->getDriver(), $element);
        }
    }

    /**
     * Click on navbar action.
     *
     * @Given /^I click on "([^"]*)" action in navbar$/
     *
     * @param string $text
     *
     * @throws ElementNotFoundException
     */
    public function iClickOnActionInNavbar($text)
    {
        $element = $this->getNavbarActionElement($text);

        if (is_null($element)) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), null, 'text', $text);
        }

        $element->click();
    }

    /**
     * Check the clicking on the element opens a popin.
     *
     * @Given /^clicking on the "([^"]*)" element should open a popin$/
     *
     * @param string $element
     *
     * @throws \RuntimeException
     */
    public function clickingOnElementShouldOpenPopin($element)
    {
        if (!in_array(ExtraSessionTrait::class, class_uses($this))) {
            throw new \RuntimeException(sprintf('Please use the trait %s in the class %s', ExtraSessionTrait::class, __CLASS__));
        }

        $this->clickElement($element);
        $this->iWaitForCssElementBeingVisible('body > .modal .modal-title', 5);
    }

    /**
     * Check if the popin is closed.
     *
     * @Then /^the popin should be closed$/
     *
     * @throws \RuntimeException
     * @throws ElementHtmlException
     */
    public function thePopinShouldBeClosed()
    {
        if (!in_array(ExtraSessionTrait::class, class_uses($this))) {
            throw new \RuntimeException(sprintf('Please use the trait %s in the class %s', ExtraSessionTrait::class, __CLASS__));
        }

        $invisible = $this->iWaitForCssElementBeingInvisible('body > .modal > .modal-dialog', 5);

        if (!$invisible) {
            $element = $this->getSession()->getPage()->find('css', 'body > .modal > .modal-dialog');

            throw new ElementHtmlException('Popin .modal-dialog was found and opened', $this->getSession()->getDriver(), $element);
        }
    }

    /**
     * Check if the popin is not opened.
     *
     * @Then /^the popin should not be opened$/
     *
     * @throws ElementHtmlException
     */
    public function thePopinShouldNotBeOpened()
    {
        $element = $this->getSession()->getPage()->find('css', 'body > .modal > .modal-dialog');

        if ($element && $element->isVisible()) {
            throw new ElementHtmlException('Popin .modal-dialog was found and opened', $this->getSession()->getDriver(), $element);
        }
    }

    /**
     * Check if the popin is opened.
     *
     * @Then /^the popin should be opened$/
     *
     * @throws ElementNotVisible
     */
    public function thePopinShouldBeOpened()
    {
        $element = $this->getSession()->getPage()->find('css', 'body > .modal > .modal-dialog');

        if (!$element || !$element->isVisible()) {
            throw new ElementNotVisible('Modal .modal-dialog should be opened and visible');
        }
    }

    /**
     * @param string $text
     *
     * @return NodeElement|null
     */
    protected function getNavbarActionElement($text)
    {
        return $this->getSession()->getPage()->find('xpath', '//nav//a[contains(.,"'.$text.'")]');
    }

    /**
     * Fills in Select2 field with specified
     *
     * @When /^(?:|I )set the select2 field "(?P<field>(?:[^"]|\\")*)" to "(?P<textValues>(?:[^"]|\\")*)"$/
     * @When /^(?:|I )set the select2 value "(?P<textValues>(?:[^"]|\\")*)" for "(?P<field>(?:[^"]|\\")*)"$/
     *
     * @param string $field
     * @param string $textValues
     */
    public function iFillInSelect2Field($field, $textValues)
    {
        $page   = $this->getSession()->getPage();
        $values = [];

        foreach (preg_split('/,\s*/', $textValues) as $value) {
            $option   = $page->find('xpath', sprintf('//select[@id="%s"]//option[text()="%s"]', $field, $value));
            $values[] = $option->getAttribute('value');
        }

        $values = json_encode($values);
        $this->getSession()->executeScript("jQuery('#{$field}').val({$values}).trigger('change');");
    }
}
