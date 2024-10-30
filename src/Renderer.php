<?php

  namespace Jinx\BlockRenderer;
  
  class Renderer extends Component
  {
    
    public $conditions = [];
    public $fields = [];
    public $template;
    public $callback;
    public $insert = '//*[contains(@class,"wp-block-")]';
        
    public function init()
    {
      
      foreach ($this->fields as &$field) { 
        $field = new Field($field);
      }
      
    }
    
    public function matchConditions(array $data = [])
    {
      
      if (!empty($this->conditions)) {
        
        $data = array_intersect_key($data, $this->conditions);
        
        if (empty($data)) {
          return false;
        }
        
        foreach ($data as $field => $value) {
          
          foreach ((array) $this->conditions[$field] as $condition) {
            
            if (is_callable($condition)) {
              
              if (!$condition($value)) {
                return false;
              }
                            
            } elseif (is_string($condition)) {

              if (!preg_match("/{$condition}/", $value)) {
                return false;
              }
              
            } else {
              
              return false;
              
            }
            
          }
          
        }
                                
      }
      
      return true;
      
    }
    
    protected function getXPath($content)
    {
      
      $document = new \DOMDocument('1.0', 'UTF-8');
      libxml_use_internal_errors(true);
      $document->loadHtml($content);
      libxml_clear_errors();

      return new \DOMXPath($document);
      
    }
    
    public function render($content, array $block)
    {
      
      $data = $block['attrs'];
      $data['_innerHTML'] = $block['innerHTML'];
      $data['_innerBlocks'] = $block['innerBlocks'];
      
      $xpath = $this->getXPath($content);
      
      foreach ($this->fields as $key => $field) {
        $data[$key] = $field->query($xpath);
      }
      
      if (isset($this->template) && file_exists($this->template)) {
        $content = $this->renderTemplate($this->template, $data);
      } elseif (isset($this->callback) && is_callable($this->callback)) {
        $content = $this->renderCallback($data);
      }
      
      if (isset($this->insert)) {
        $content = $this->insert($content, $xpath);
      }
      
      return $content;
            
    }
    
    protected function insert($content, $xpath)
    {
      
      $element = $xpath->query($this->insert)->item(0);
      if (isset($element)) {

        $fragment = $xpath->document->createDocumentFragment();
        $fragment->appendXML($content);

        while ($element->hasChildNodes()){
          $element->removeChild($element->childNodes->item(0));
        }

        $element->appendChild($fragment); 

        $content =  $element->ownerDocument->saveHTML($element);

      }
      
      return $content;
      
    }
        
    protected function renderTemplate(string $template, array $params = [])
    {
      
      return $this->captureOutput(function() {

        extract(func_get_arg(1));
        include(func_get_arg(0));

      }, $template, $params);

    }
    
    protected function renderCallback(array $params = [])
    {
      
      return $this->captureOutput(function() {

        echo call_user_func(func_get_arg(0), func_get_arg(1));

      }, $this->callback, $params);
            
    }
    
    protected function captureOutput($callback)
    {

      $params = array_slice(func_get_args(), 1);

      ob_start();
      ob_implicit_flush(false);

      call_user_func_array($callback, $params);

      return ob_get_clean(); 
      
    }
    
  }