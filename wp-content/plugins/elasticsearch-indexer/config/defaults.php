<?php

/*
 * This file is part of Elasticsearch Indexer.
 *
 * (c) Wallmander & Co <mikael@wallmanderco.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Wallmander\ElasticsearchIndexer\Model\Config;

return [
    // System variables
    'plugin_index_version'     => 5, // Update this value to prompt the user to reindex
    'user_index_version'       => 1,
    'is_indexing'              => false,

    // User settings
    'integration_level'        => Config::INTEGRATION_LEVEL_FULL,
    'hosts'                    => '127.0.0.1:9200',
    'index_name'               => null,
    'shards'                   => 5,
    'replicas'                 => 1,
    'index_private_post_types' => true,
    'profile_admin'            => false,
    'profile_frontend'         => false,
];
