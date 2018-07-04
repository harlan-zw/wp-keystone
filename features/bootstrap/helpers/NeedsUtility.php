<?php
namespace App\Tests\Helpers;

/**
 * UtilityContext is used for any function which is irrelevant of code base.
 */
trait NeedsUtility {

    /**
     * @When /^I resize the window to desktop$/
     */
    public function resizeTheWindowToDesktop()
    {
        $this->getSession()->resizeWindow(1440, 900, 'current');
    }

	/**
	 * @Given /^wait for "([^"]*)" second|s$/
	 */
	public function waitForSeconds($seconds) {

		$this->getSession()->getPage()->waitFor($seconds, function() { });
	}

	/**
	 * @Given /^I refresh|reload the page$/
	 */
	public function iRefreshThePage() {
		$this->getSession()->reload();
	}

	/**
	 * @Given /^the response header "([^"]*)" should be set$/
	 */
	public function theResponseHeaderShouldBeSet($header) {
		return !empty($this->getSession()->getResponseHeader($header));
	}

	/**
	 * @Then /^I should receive a redirect$/
	 */
	public function iShouldReceiveARedirect() {
		return $this->getSession()->getStatusCode() == 302 && !empty($headers[$this->getSession()->getResponseHeader('Location')]);
	}

	/**
	 * @Given /^I click on element with selector "([^"]*)"$/
	 */
	public function iClickWithSelector( $selector ) {
		$link = $this->getSession()->getPage()->find('css', $selector);
		$link->click();
	}

	/**
	 * @Then /^I should wait and see "([^"]*)"$/
	 */
	public function iShouldWaitAndSee( $text ) {

		BehatUtility::spins(function() use ($text) {
			// wil throw an exception if we can't see it
			$this->assertSession()->pageTextContains($text);
			// only if no exception is thrown
			return true;
		});
	}


}
