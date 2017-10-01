<?php
namespace App\Tests;

use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;

/**
 * WordpressContext is used for any functionality that is common on any wordpress setup
 */
class WordpressContext extends RawWordpressContext {


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
	 * Log user in.
	 *
	 * Example: Given I am logged in as an admin
	 *
	 * @Given /^I am logged in as "(.+)"$/
	 * @param $user
	 */
	public function iAmLoggedInAsUser($username)
	{
		$redirect_to = '/dashboard/my-profile/';
		$password = 'test';

		$this->visitPath('/wp/wp-login.php?redirect_to=' . urlencode($this->locatePath($redirect_to)));
		$page = $this->getSession()->getPage();

		$node = $page->findField('user_login');
		try {
			$node->focus();
		} catch (UnsupportedDriverActionException $e) {
			// This will fail for GoutteDriver but neither is it necessary
		}
		$node->setValue('');
		$node->setValue($username);

		$node = $page->findField('user_pass');
		try {
			$node->focus();
		} catch (UnsupportedDriverActionException $e) {
			// This will fail for GoutteDriver but neither is it necessary
		}
		$node->setValue('');
		$node->setValue($password);

		$page->findButton('wp-submit')->click();

		if (! $this->loggedIn()) {
			throw new ExpectationException('The user could not be logged-in.', $this->getSession()->getDriver());
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

    /**
     * @description ~ Get post meta value
     * @param int $postId Post ID
     * @param string $field Field
     * @return string Post meta value
     */
    private function getPostMetaValue($postId, $field) {
        $postMeta = $this->getDriver()->wpcli("post", "meta get $postId $field");
        return $postMeta['stdout'];
    }

    /**
     * @description ~ Get taxonomy value
     * @param int $postId Post ID
     * @param string $taxonomy Taxonomy
     * @return string Taxonomy value
     */
    private function getTermTaxonomyValue($postId, $taxonomy) {
        $termList = $this->getDriver()->wpcli("post", "term list $postId $taxonomy --fields=name --format=json");
        $termListJson = json_decode($termList['stdout']);
        return $termListJson[0]->name;
    }

    /**
     * @description ~ Get taxonomy list
     * @param string $objectType Object type
     * @return array Taxonomy names
     */
    private function getTaxonomyList($objectType) {
        $taxonomies = $this->getDriver()->wpcli("taxonomy", "list --object_type=$objectType --format=json");
        $taxonomies = json_decode($taxonomies['stdout']);

        $resp = array();

        foreach ($taxonomies AS $aTaxonomy) {
            $resp[] = $aTaxonomy->name;
        }

        return $resp;
    }

}
