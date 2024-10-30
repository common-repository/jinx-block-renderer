<?php

  namespace Jinx\BlockRenderer;
  
  abstract class Plugin
  {
    
    public static function apply($content, $block)
    {
      
      $renderers = self::getRenderers($block['blockName']);
      
      foreach ($renderers as $renderer) {
        
        if ($renderer->matchConditions($block['attrs'])) {
          $content = $renderer->render($content, $block);
        }
        
      }
      
      return $content;
      
    }
    
    protected static function getRenderers($blockName)
    {
      
      $renderers = [];
      
      foreach (apply_filters('jinx_block_renderers', []) as $renderer) {
        
        if ($renderer['block'] === $blockName) {
          $renderers[] = new Renderer($renderer);
        }
        
      }
      
      return $renderers;
            
    }
    
  }