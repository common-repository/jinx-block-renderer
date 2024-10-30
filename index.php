<?php

  /**
   * Plugin Name: Jinx Block Renderer
   * Plugin URI: https://wordpress.org/plugins/jinx-block-renderer/
   * Description: Manipulate Gutenberg Blocks
   * Version: 0.2.0
   * Author: SquareFlower Websolutions (Lukas Rydygel) <hallo@squareflower.de>
   * Author URI: https://squareflower.de
   * Text Domain: jinx
   */

  require_once(__DIR__.'/src/Plugin.php');
  require_once(__DIR__.'/src/Component.php');
  require_once(__DIR__.'/src/Renderer.php');
  require_once(__DIR__.'/src/Field.php');
  
  function jinx_render_block($content, $block) {
    return Jinx\BlockRenderer\Plugin::apply($content, $block);
  }

  add_filter('render_block', 'jinx_render_block', 9999, 2);