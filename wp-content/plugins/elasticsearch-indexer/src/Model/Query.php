<?php

/*
 * This file is part of Elasticsearch Indexer.
 *
 * (c) Wallmander & Co <mikael@wallmanderco.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wallmander\ElasticsearchIndexer\Model;

use stdClass;
use Wallmander\ElasticsearchIndexer\Model\Query\BuilderTrait;
use Wallmander\ElasticsearchIndexer\Model\Query\WpConverter;
use WP_Post;
use WP_Query;

/**
 * Fetches posts.
 *
 * @author Mikael Mattsson <mikael@wallmanderco.se>
 */
class Query extends Client
{
    /**
     * @var WP_Query|null
     */
    public $wp_query;

    /**
     * @var int
     */
    public $found_posts = 0;

    /**
     * @var array
     */
    public $posts = [];

    /**
     * @var bool
     */
    public $disabled = false;

    /**
     * @var bool
     */
    public $updatePostTermCache = false;

    /**
     * @var bool
     */
    public $updatePostMetaCache = false;

    use BuilderTrait;

    public function __construct()
    {
        parent::__construct();
        $this->builderConstruct();
    }

    /**
     * @param \WP_Query $wpQuery
     *
     * @return \Wallmander\ElasticsearchIndexer\Model\Query
     */
    public static function fromWpQuery(WP_Query $wpQuery)
    {
        $q        = new static();
        $q->applyWpQuery($wpQuery);

        return $q;
    }

    /**
     * @param \WP_Query $wpQuery
     */
    public function applyWpQuery(WP_Query $wpQuery)
    {
        $this->wp_query = $wpQuery;
        $this->formatArgs($wpQuery);
    }

    /**
     * @return array|bool
     */
    public function getPosts()
    {
        if ($this->disabled) {
            return false;
        }
        $result = $this->search([
            'index' => $this->getIndexName(),
            'type'  => 'post',
            'body'  => $this->args,
        ]);

        $this->found_posts = $result['hits']['total'];
        if ($this->wp_query) {
            $wpQuery                = $this->wp_query;
            $wpQuery->found_posts   = $this->found_posts;
            $ppp                    = $wpQuery->get('posts_per_page') ? $wpQuery->get('posts_per_page') : get_option('posts_per_page');
            $wpQuery->max_num_pages = ceil($this->found_posts / $ppp);
        }

        $this->posts = [];

        foreach ($result['hits']['hits'] as $p) {
            $p                 = $p['_source'];
            $this->posts[]     = $post     = new WP_Post(new stdClass());
            $post->ID          = $p['post_id'];
            $post->site_id     = get_current_blog_id();
            $post->post_author = $p['post_author']['id'];
            if (empty($p['site_id'])) {
                $post->site_id = get_current_blog_id();
            } else {
                $post->site_id = $p['site_id'];
            }
            $post->post_type         = $p['post_type'];
            $post->post_name         = $p['post_name'];
            $post->post_status       = $p['post_status'];
            $post->post_title        = $p['post_title'];
            $post->post_parent       = $p['post_parent'];
            $post->post_content      = $p['post_content'];
            $post->post_excerpt      = $p['post_excerpt'];
            $post->post_date         = $p['post_date'];
            $post->post_date_gmt     = $p['post_date_gmt'];
            $post->post_modified     = $p['post_modified'];
            $post->post_modified_gmt = $p['post_modified_gmt'];
            $post->permalink         = $p['permalink'];
            $post->post_mime_type    = $p['post_mime_type'];
            $post->menu_order        = $p['menu_order'];
            $post->guid              = $p['guid'];
            $post->comment_count     = $p['comment_count'];
            $post->filter            = 'raw';
            $post->elasticsearch     = $p;

            if ($this->updatePostTermCache) {
                foreach ($p['terms'] as $taxonomy => $terms) {
                    foreach ($terms as $key => $value) {
                        $terms[$key]     = $value     = (object) $value;
                        $value->taxonomy = $taxonomy;
                        $value->filter   = 'raw';
                    }
                    wp_cache_add($post->ID, $terms, $taxonomy.'_relationships');
                }
            }

            if ($this->updatePostMetaCache) {
                wp_cache_add($post->ID, $p['post_meta'], 'post_meta');
            }
        }

        return $this->posts;
    }

    public function formatArgs(WP_Query $wpQuery)
    {
        WpConverter::formatArgs($this, $wpQuery);

        return $this;
    }
}
