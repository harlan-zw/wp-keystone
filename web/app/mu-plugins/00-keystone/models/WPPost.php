<?php

namespace App\Helpers;

use WP_Error;
use WP_Post;

/**
 * Class SmartPost.
 *
 * @property int post_author
 * @property int ID
 * @property string post_date
 * @property string post_date_gmt
 * @property string post_content
 * @property string post_title
 * @property string post_excerpt
 * @property string post_name
 * @property string post_modified
 * @property string post_status
 * @property int menu_order
 * @property string post_type
 * @property bool is_valid
 */
class WPPost
{
    public const POST_FIELDS = [
        'ID',
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_title',
        'post_content',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count;',
    ];

    public static $slug = 'post';

    /**
     * Was the post found and created correctly. We define this here to avoid saving it within our database.
     *
     * @var bool
     */
    public $is_valid;
    /**
     * @var [] Magic fields
     */
    public $fields;
    private $dirty_keys = [];
    private $original_values = [];

    /**
     * Post constructor.
     * This finds our post based on what we give it. We can give it a post id or post object
     * It then initializes all our fields for us within our post and if ACF is active, that too.
     *
     * @param int|WP_Post|false $id           The post id
     * @param bool              $preload_meta
     */
    public function __construct($id = false, $preload_meta = false)
    {
        if (empty($id)) {
            $id = get_the_ID();
        }
        if (is_numeric($id)) {
            $post = \WP_Post::get_instance($id);
            if (empty($post)) {
                $this->is_valid = false;

                return;
            }
        } else {
            if (\is_object($id)) {
                $post = $id;
                $id = $post->ID;
            } else {
                $this->is_valid = false;

                return;
            }
        }

        if (empty($post)) {
            $this->is_valid = false;

            return;
        }

        // Check the post type matches - otherwise someone forged an ID
        if ($post->post_type !== static::$slug) {
            $this->is_valid = false;

            return;
        }
        // Since we are extending loader we have the magic setters & getters
        foreach ($post as $k => $v) {
            $this->$k = $v;
        }
        $this->is_valid = true;
        // check if we should load acf
        if (!$preload_meta) {
            return;
        }
        // do acf loading
        $this->preload_meta();
    }

    public function preload_meta(array $meta = [])
    {
        if (empty($meta)) {
            foreach (get_post_meta($this->ID) as $k => $v) {
                $this->$k = $v[0];
            }
        } else {
            foreach ($meta as $meta_keys) {
                $this->$meta = get_post_meta($this->ID, $meta_keys, true);
            }
        }
    }

    /**
     * Find the latest posts by publish date.
     *
     * @param $limit
     *
     * @return static[]
     */
    public static function find_latest($limit)
    {
        return static::find_all([
            'limit'   => $limit,
            'order'   => 'DESC',
            'orderby' => 'post_date',
        ]);
    }

    /**
     * Finds all posts and wraps them in this class.
     *
     * @param array $args
     *
     * @return static[]
     */
    public static function find_all(array $args = [])
    {
        // Allow user to use limit instead of posts_per_page
        if (isset($args['limit'])) {
            $args['posts_per_page'] = $args['limit'];
            unset($args['limit']);
        }
        $defaults = [
            'post_type'      => static::$slug,
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ];
        $posts = get_posts(wp_parse_args($args, $defaults));

        return collect($posts)->map(function ($post) {
            return new static($post);
        })->toArray();
    }

    /**
     * Gets the wp_query for a find_all request.
     *
     * @param array $args
     *
     * @return \WP_Query
     */
    public static function query_all($args = [])
    {
        $defaults = [
            'post_type'   => static::$slug,
            'numberposts' => -1,
            'post_status' => 'publish',
        ];

        return new \WP_Query(wp_parse_args($args, $defaults));
    }

    /**
     * Create a new post.
     *
     * @param $args
     *
     * @return static|false
     */
    public static function create($args)
    {
        $args = wp_parse_args($args, [
            'post_status' => 'publish',
            'post_type'   => static::$slug,
        ]);
        $id = wp_insert_post($args);
        if ($id instanceof WP_Error) {
            return false;
        }
        $post = static::find($id);
        $post->ID = $id;
        $post->after_insert();

        return $post;
    }

    /**
     * Finds a single post and wraps it in this class.
     *
     * @param $id
     *
     * @return bool|static False if found otherwise the business unit
     *
     * @internal param $slug
     */
    public static function find($id)
    {
        return new static($id);
    }

    public function after_insert()
    {
        // Do something
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
            if (isset($this->$key)) {
                $return[$key] = $this->$key;
            }
        }

        return $return;
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
        $value = false;

        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }
        if (property_exists($this, 'ID') || property_exists($this, 'fields')) {
            // This is where the MAGIC happens
            $value = get_post_meta($this->ID, $name, true);

            $this->original_values[$name] = $value;
            $this->$name = $value;
        }

        return $value;
    }

    /**
     * Sets our magic value in our fields array.
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        // only if the value has changed
        if (isset($this->fields[$name]) && $this->fields[$name] === $value) {
            return;
        }
        $this->fields[$name] = $value;
        if ($this->is_valid && !\in_array($name, $this->dirty_keys, true)) {
            $this->dirty_keys[] = $name;
        }
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
            $this->$name = get_field($name, $this->ID);
        }

        return isset($this->fields[$name]);
    }

    /**
     * Fills the model with an array of key value pairs.
     *
     * @param $values
     *
     * @return $this
     */
    public function fill($values)
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * Attempt to save all dirty fields.
     *
     * @return bool
     */
    public function save()
    {
        $success = true;

        foreach ($this->dirty_keys as $key) {
            $value = $this->$key;

            // we don't save objects or callables
            if (\is_object($value) || (\is_callable($value) && !\is_string($value))) {
                continue;
            }
            if (isset($this->original_values[$key]) && $this->original_values[$key] === $value) {
                continue;
            }
            if (\in_array($key, self::POST_FIELDS, true)) {
                wp_update_post([
                    'ID' => $this->ID,
                    $key => $value,
                ]);
                continue;
            }
            $success = update_post_meta($this->ID, $key, $value) && $success;
        }
        // reset keys
        $this->dirty_keys = [];

        return $success;
    }

    /**
     * Gets the URL for the post.
     *
     * @return string
     */
    public function get_permalink()
    {
        return get_permalink($this->ID);
    }

    /**
     * Permanently remove a post.
     *
     * @return array|false|WP_Post
     */
    public function delete()
    {
        return wp_delete_post($this->ID, true);
    }

    /**
     * Gets the current posts thumbnail.
     *
     * @param string $size
     *
     * @return string
     */
    public function get_thumbnail_url($size = 'full')
    {
        return get_the_post_thumbnail_url($this->ID, $size);
    }
}
