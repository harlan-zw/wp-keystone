<?php

namespace App\Models;

use WP_User;

class User extends WP_User
{
    public static $fillable = [];
    /**
     * A static reference to the current user - avoid database lookups.
     *
     * @var static
     */
    private static $cached_current_user;
    /**
     * @var [] Magic fields
     */
    public $fields;

    public function __construct($id = 0, $preload = false)
    {
        parent::__construct($id);

        if ($preload) {
            // we preload all user meta
            $meta_cache = update_meta_cache('user', $this->ID)[$this->ID];
            $this->fields = collect($meta_cache)->mapWithKeys(function ($array, $key) {
                return [$key => $array[0]];
            })->toArray();
        }

        $this->custom_picture = $this->get_profile_picture_url();

        $this->fields += $this->to_array();
    }

    /**
     * Finds a user with their email address.
     *
     * @param $email
     *
     * @return User
     */
    public static function for_email($email)
    {
        return new self(get_user_by('email', $email));
    }

    /**
     * Instantiates a User for the current logged in user.
     *
     * @return User|bool The user object, otherwise false if the session isn't set
     */
    public static function for_current_user()
    {
        // check if we've already looked this up before
        if (!empty(self::$cached_current_user)) {
            return self::$cached_current_user;
        }

        $current_user = wp_get_current_user();
        if ($current_user->ID === 0) {
            return false;
        }

        $user = self::$cached_current_user = new self($current_user);
        if (empty($user->ID)) {
            return false;
        }

        return $user;
    }

    /**
     * Instantiates a User for the current logged in user.
     *
     * @return User
     */
    public static function for_user($int)
    {
        return new self($int);
    }

    /**
     * Finds all posts and wraps them in this class.
     *
     * @param array $args
     *
     * @return static[]
     */
    public static function find_all($args = [])
    {
        return collect(get_users($args))->map(function ($user) {
            return new self($user);
        })->toArray();
    }

    /**
     * Get an array of fields for the keys provided.
     *
     * @param $keys [] The key values to return
     *
     * @return array The requested fields
     */
    public function fields($keys)
    {
        $return = [];
        foreach ($keys as $key) {
            $return[$key] = $this->$key;
        }

        return $return;
    }

    /**
     * Fills all users fields with the provided array and attempts to save them.
     *
     * @param $fields
     */
    public function fill_and_save($fields)
    {
        foreach ($fields as $key => $val) {
            if (in_array($key, self::$fillable)) {
                $this->save_field($key, $val, true);
            }
        }
    }

    /**
     * Saves a field for the user. This is a wrapper for {@see update_user_meta() }.
     *
     * @param $key
     * @param $value
     * @param bool $safe Should the value be sanitized for bad input
     */
    public function save_field($key, $value, $safe = false)
    {
        if ($safe) {
            $value = sanitize_text_field(wp_kses($value, []));
        }
        $this->$key = $value;
        update_user_meta($this->ID, $key, $value);
    }

    /**
     * Gets the concatenated first name + last name.
     *
     * @return string
     */
    public function get_full_name()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * The extra magic get function.
     *
     * @param $name
     *
     * @return object|string|bool
     */
    public function __get($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }

        return parent::__get($name);
    }

    /**
     * Sets our magic value in our fields array.
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->fields[$name] = $value;

        parent::__set($name, $value);
    }

    /**
     * Make sure our empty() and isset() functions behave correctly.
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        if (!isset($this->fields[$name])) {
            $this->$name = get_user_meta($this->ID, $name, true);
        }

        return parent::__isset($name);
    }
}
