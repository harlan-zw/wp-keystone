<?php

namespace App\Drivers;

use Illuminate\Contracts\Cache\Store;

class TransientStore implements Store {

    protected $prefix;

    public function __construct($prefix = '') {
        $this->prefix = $prefix;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string $key
     * @return mixed
     */
    public function get($key) {
    	return get_transient($this->prefix . $key);
    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @param  array $keys
     * @return array
     */
    public function many(array $keys) {
        $return = [];
        foreach ($keys as $key) {
            $return[$key] = $this->get($key);
        }
        return $return;
    }

    /**
     * Store multiple items in the cache for a given number of minutes.
     *
     * @param  array $values
     * @param  float|int $minutes
     * @return void
     */
    public function putMany(array $values, $minutes) {
        foreach ($values as $key => $value) {
            $this->put($key, $value, $minutes);
        }
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  float|int $minutes
     * @return void
     */
    public function put($key, $value, $minutes) {
        set_transient($this->prefix . $key, $value, $minutes * 60);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function increment($key, $value = 1) {
        $cacheVal = $this->get($this->prefix . $key);
        $this->put($key, $cacheVal + $value, 0);
        return ($cacheVal + $value);
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function decrement($key, $value = 1) {
        $cacheVal = $this->get($key);
        $this->put($key, $cacheVal - $value, 0);
        return ($cacheVal - $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function forever($key, $value) {
        $this->put($key, $value, 0);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     * @return bool
     */
    public function forget($key) {
        return delete_transient($this->prefix . $key);
    }

    public function flush() {
        global $wpdb;
        $wpdb->query('DELETE FROM `wp_options` WHERE `option_name` LIKE (\'_transient_%\')');
        $wpdb->query('DELETE FROM `wp_options` WHERE `option_name` LIKE (\'_site_transient_%\')');
    }

    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    public function getPrefix() {
        return $this->prefix;
    }
}
