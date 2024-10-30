=== Jinx-Block-Renderer ===
Contributors: Lugat
Tags: blocks, gutenberg, manipulate, template
Requires at least: 5.0
Tested up to: 5.5.3
Requires PHP: 7.1
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Render WP Gutenberg Block the way you want.

== Description ==

The plugin allows you to parse all kinds gutenberg blocks and render them in your own template.

== Installation ==

1. Unzip the downloaded package
2. Upload `jinx-block-renderer` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

== Usage ==

Use the filter 'jinx_block_renderers' in your theme to render gutenberg blocks.

`<?php

  function my_render_function(array $fields)
  {
    echo $fields['url'];
  }
  
  add_filter('jinx_block_renderers', function($renderers) {
    
    $renderers[] = [
      'block' => 'core-embed/youtube', // name of the block
      'conditions' => [], // default - accepts an array of key value pairs
      // 'conditions' => [
      //   'className' => 'is-style-custom', // strings are used inside a regular expression
      //   'field' => function($value) { // callbacks will receive the fields value and must return true or false
      //     return $value === 'some value';
      //   }
      // ],
      'fields' => [
        'url' => [ // name of your parameter
          'path' => '//iframe[@src]',
          'item' => 0,
          // 'item' => 9, // if not exists, will return null
          // 'item': 'first', // constant Jinx\BlockRenderer\Field::ITEM_FIRST
          // 'item': 'last', // constant Jinx\BlockRenderer\Field::ITEM_LAST
          // 'item': null // default - returns all results as an array
          'attr' => 'src',
          //'attr' => null // default - returns the results value
          //'filter' => 'my_filter_function' // filters each matched field
        ],
      ],
      // 'insert' => '//*[contains(@class,"wp-block-")]' // default - inserts rendered block back into the block-wrapper
      'template' => __DIR__.'/youtube-video.php', // relative path, templates will handle each field as a variable
      // 'callback' => 'my_render_function'
    ];
    
    return $renderers;
    
  });

?>`