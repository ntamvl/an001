<?php

/*
 * This file is part of Elasticsearch Indexer.
 *
 * (c) Wallmander & Co <mikael@wallmanderco.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Full Credits to 10up/ElasticPress.
 */
$dateTermFields = [
    //4 digit year (e.g. 2011)
    'year' => [
        'type' => 'integer',
    ],
    //Month number (from 1 to 12) alternate name "monthnum"
    'month' => [
        'type' => 'integer',
    ],
    //YearMonth (For e.g.: 201307)
    'm' => [
        'type' => 'integer',
    ],
    //Week of the year (from 0 to 53) alternate name "w"
    'week' => [
        'type' => 'integer',
    ],
    //Day of the month (from 1 to 31)
    'day' => [
        'type' => 'integer',
    ],
    //Accepts numbers 1-7 (1 is Sunday)
    'dayofweek' => [
        'type' => 'integer',
    ],
    //Accepts numbers 1-7 (1 is Monday)
    'dayofweek_iso' => [
        'type' => 'integer',
    ],
    //Accepts numbers 1-366
    'dayofyear' => [
        'type' => 'integer',
    ],
    //Hour (from 0 to 23)
    'hour' => [
        'type' => 'integer',
    ],
    //Minute (from 0 to 59)
    'minute' => [
        'type' => 'integer',
    ],
    //Second (0 to 59)
    'second' => [
        'type' => 'integer',
    ],
];

return [
    'post' => [
        'date_detection'    => false,
        'dynamic_templates' => [
            [
                'template_meta' => [
                    'path_match' => 'post_meta.*',
                    'mapping'    => [
                        'type'   => 'multi_field',
                        'path'   => 'full',
                        'fields' => [
                            '{name}' => [
                                'type'     => 'string',
                                'index'    => 'analyzed',
                                'analyzer' => 'esi_search_analyzer',
                            ],
                            'raw' => [
                                'type'         => 'string',
                                'index'        => 'not_analyzed',
                                'ignore_above' => 256,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'template_meta_num' => [
                    'path_match' => 'post_meta_num.*',
                    'mapping'    => [
                        'type' => 'long',
                    ],
                ],
            ],
            [
                'template_terms' => [
                    'path_match' => 'terms.*',
                    'mapping'    => [
                        'type'       => 'object',
                        'path'       => 'full',
                        'properties' => [
                            'name' => [
                                'type'     => 'string',
                                'index'    => 'analyzed',
                                'analyzer' => 'esi_search_analyzer',
                            ],
                            'term_id' => [
                                'type' => 'long',
                            ],
                            'parent' => [
                                'type' => 'long',
                            ],
                            'slug' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                            'all_slugs' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'term_suggest' => [
                    'path_match' => 'term_suggest_*',
                    'mapping'    => [
                        'type'     => 'completion',
                        'analyzer' => 'esi_search_analyzer',
                    ],
                ],
            ],
        ],
        '_all' => [
            'enabled' => false,
        ],
        'properties' => [
            'post_id' => [
                'type'  => 'long',
                'index' => 'not_analyzed',
            ],
            'post_author' => [
                'type'   => 'object',
                'path'   => 'full',
                'fields' => [
                    'display_name' => [
                        'type'     => 'string',
                        'analyzer' => 'standard',
                    ],
                    'login' => [
                        'type'     => 'string',
                        'analyzer' => 'standard',
                    ],
                    'id' => [
                        'type'  => 'long',
                        'index' => 'not_analyzed',
                    ],
                    'raw' => [
                        'type'  => 'string',
                        'index' => 'not_analyzed',
                    ],
                ],
            ],
            'post_date' => [
                'type'   => 'date',
                'format' => 'YYYY-MM-dd HH:mm:ss',
            ],
            'post_date_gmt' => [
                'type'   => 'date',
                'format' => 'YYYY-MM-dd HH:mm:ss',
            ],
            'post_title' => [
                'type'   => 'multi_field',
                'fields' => [
                    'post_title' => [
                        'type'     => 'string',
                        'analyzer' => 'esi_search_analyzer',
                        'store'    => 'yes',
                    ],
                    'raw' => [
                        'type'  => 'string',
                        'index' => 'not_analyzed',
                    ],
                ],
            ],
            'post_excerpt' => [
                'type' => 'string',
            ],
            'post_content' => [
                'type'     => 'string',
                'analyzer' => 'esi_search_analyzer',
                'index'    => 'analyzed',
            ],
            'post_status' => [
                'type'  => 'string',
                'index' => 'not_analyzed',
            ],
            'post_name' => [
                'type'  => 'string',
                'index' => 'not_analyzed',
            ],
            'post_modified' => [
                'type'   => 'date',
                'format' => 'YYYY-MM-dd HH:mm:ss',
            ],
            'post_modified_gmt' => [
                'type'   => 'date',
                'format' => 'YYYY-MM-dd HH:mm:ss',
            ],
            'post_parent' => [
                'type'  => 'long',
                'index' => 'not_analyzed',
            ],
            'post_type' => [
                'type'  => 'string',
                'index' => 'not_analyzed',
            ],
            'post_mime_type' => [
                'type'  => 'string',
                'index' => 'not_analyzed',
            ],
            'permalink' => [
                'type'  => 'string',
                'index' => 'not_analyzed',
            ],
            'terms' => [
                'type' => 'object',
            ],
            'post_meta' => [
                'type' => 'object',
            ],
            'post_meta_num' => [
                'type' => 'object',
            ],
            'post_date_object' => [
                'type'   => 'object',
                'path'   => 'full',
                'fields' => $dateTermFields,
            ],
            'post_date_gmt_object' => [
                'type'   => 'object',
                'path'   => 'full',
                'fields' => $dateTermFields,
            ],
            'post_modified_object' => [
                'type'   => 'object',
                'path'   => 'full',
                'fields' => $dateTermFields,
            ],
            'post_modified_gmt_object' => [
                'type'   => 'object',
                'path'   => 'full',
                'fields' => $dateTermFields,
            ],
            'menu_order' => [
                'type'  => 'long',
                'index' => 'not_analyzed',
            ],
            'comment_count' => [
                'type'  => 'long',
                'index' => 'not_analyzed',
            ],
            'guid' => [
                'type'  => 'string',
                'index' => 'not_analyzed',
            ],
            'order_item_names' => [
                'type'  => 'string',
                'index' => 'not_analyzed',
            ],
        ],
    ],
];
