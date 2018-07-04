<?php
namespace App\Tests;

use App\Tests\Helpers\NeedsUtility;
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;

/**
 * WordpressContext is used for any functionality that is common on any wordpress setup
 */
class WordpressContext extends RawWordpressContext {

	use NeedsUtility;

	/**
	 * @Given /^Wordpress creates an account "([^"]*)" with email "([^"]*)"$/
	 */
	public function iCreateAnAccount($username, $email) {
		// find existing user if it exists
		try {
			$user = $this->getDriver()->user->get( $username, ['by' => 'login']);
			if (!empty($user)) {
				$this->deleteUser($user->ID);
			}
		} catch(\Exception $e) {
			// do nothing
		}
		$args = [
			'user_email' => $email,
			'user_login' => $username,
			'user_pass' => 'test',
			'role' => 'subscriber',
			'send-email' => false,
		];
		try {

			$this->getDriver()->user->create( $args );
		} catch(\Exception $e) {
			// do nothing
		}
	}

	/**
	 * @Given /^I am not logged in$/
	 */
	public function iAmNotLoggedIn() {
		if (!$this->loggedIn()) {
			return;
		}
		$this->logOut();
	}

    /**
     * @Given /^No account exists with username "([^"]*)" and email "([^"]*)"$/
     * @param string $username Username
     * @param string $email Email
     * @throws \Exception
     */
    public function noAccountExistsWithUsernameAndEmail($username, $email) {

        // find existing user if it exists
        try {
            $user = $this->getDriver()->user->get($username, ['by' => 'login']);

        } catch(\Exception $e) {

        }

        if ( isset($user) && $user->user_email == $email ) {
            throw new \Exception("User $username does exist.");
        }
    }

    /**
     * @Then /^An account exists with username "([^"]*)" and email "([^"]*)"$/
     * @param string $username Username
     * @param string $email Email
     * @throws \Exception
     */
    public function anAccountExistsWithUsernameAndEmail($username, $email) {

        // find existing user if it exists
        try {
            $user = $this->getDriver()->user->get($username, ['by' => 'login']);

            if ( $email != $user->user_email ) {
                throw new \Exception("Actual email is " . $user->user_email);
            }
        } catch(\Exception $e) {
            throw new \Exception("User $username not found.");
        }
    }

    /**
     * @Then /^Wordpress deletes the account with username "([^"]*)"/
     * @param string $username Username
     * @throws \Exception
     */
    public function wordpressDeletesTheAccountWithUsername($username) {
        try {
            $user = $this->getDriver()->user->get($username, ['by' => 'login']);
	        if (!empty($user)) {
		        $this->deleteUser($user->ID, ['by' => 'login']);
	        }
        } catch(\Exception $e) {
        	// no nothing
        }

    }

}
