<?php

  namespace Jinx\BlockRenderer;
  
  class Component
  {
        
    public function __construct(array $config)
    {
             
      foreach ($config as $attr => $value) {
        
        if (property_exists($this, $attr)) {
          $this->$attr = $value;
        }
        
      } 
      
      $this->init();
      
    }
    
    public function init()
    {
      
    }
    
  }