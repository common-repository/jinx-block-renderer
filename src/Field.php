<?php

  namespace Jinx\BlockRenderer;
  
  class Field extends Component
  {
    
    const ITEM_FIRST = 0;
    const ITEM_LAST = -1;
        
    public $path;
    public $attr;
    public $item;
    public $filter;
    
    public function init()
    {
      
      if (is_string($this->item)) {
        
        switch (strtolower($this->item)) {
          
          case 'first':
            $this->item = self::ITEM_FIRST;
          break;
        
          case 'last':
            $this->item = self::ITEM_LAST;
          break;
          
        }
        
      }
      
    }
    
    public function query($xpath)
    {
      
      $results = $xpath->query($this->path);
      
      if (isset($this->item)) {
        
        $index = $this->item === self::ITEM_LAST ? $results->length-1 : $this->item;
        
        $result = $results->item($index);
        if (isset($result)) {
          
          return $this->getData($result);
          
        }
        
        return null;
        
      }
      
      $data = [];
      
      foreach ($results as $result) {
        $data[] = $this->getData($result);
      }
      
      return $data;
      
    }
    
    protected function getData($elment)
    {
      
      $data = isset($this->attr) ? $elment->getAttribute($this->attr) : $elment->nodeValue;
      
      return is_callable($this->filter) ? call_user_func($this->filter, $data) : $data;
      
    }
    
  }