<?php

/**
 *
 * @category   Banking
 * @package    OnBoarding
 * @author     Davide Bernardo <davide.bernardo@bancasistema.it>
 * @author     Paolo Cambieri  <paolo.cambieri@bancasistema.it>
 * @copyright  2012-2013 BancaSistema S.p.A
 *
 */

   require_once("configs/ConnectionConfig.php");

   class ArrayList {
   	
   	  var $LIST;
   	  var $SORT;
   	  var $DIRECTION;
   	  
      function ArrayList() {
      	
         $this->LIST = array();
         
      }	
      
      function add($OBJECT) {

         $this->LIST[$this->size()] = $OBJECT;
      	 
      }	
      
      function remove($INDEX) {
      	
      	 $DELETED = false;
      	 
         if ($this->checkIndex($INDEX)) {
         	
            if (isset($this->LIST[$INDEX])) {
            	
               unset($this->LIST[$INDEX]);
               $this->LIST = array_merge($this->LIST);
               
               $DELETED = true;
               
            } 
             
         }   
                
         return $DELETED;
          
      }
      
      function get($INDEX) {
      	
         return ($this->checkIndex($INDEX)) ?
                                              $this->LIST[$INDEX] :
                                              null;

      }		
      
      function getFirst() {
      	return  $this->LIST[0];
      }
      
      function clear() {
      	
         while(!($this->isEmpty()))
            $this->remove(0);
      	
      }	
      
      function isEmpty() {
      	
         return ($this->size() > 0) ? false : true;	
      	
      }	
      
      function checkIndex($INDEX) { 
      
         return (($INDEX >= 0) && ($INDEX < $this->size())) ? true : false;
      
      }
      
      function size() { return count($this->LIST); }
      
      function sort($sortfield, $criteria = 'asc', $type = "") { 
      	
      	 $this->SORT     = $sortfield;
      	  
      	 if ($type != 'date') {
      	 	
      	    if (strtolower($criteria) == 'asc') {
      	       $this->DIRECTION = 1;
               usort($this->LIST, array('ArrayList', 'compare'));
      	    }
      	    else {
      	       $this->DIRECTION = -1;
               usort($this->LIST, array('ArrayList', 'compare'));
      	    }
      	    
      	 } else {
      	 	
      	 	if (strtolower($criteria) == 'asc') {
      	 	    $this->DIRECTION = 1;
      	 		usort($this->LIST, array('ArrayList', 'compare_date'));
      	 	}
      	 	else {
      	 		$this->DIRECTION = -1;
      	 		usort($this->LIST, array('ArrayList', 'compare_date'));
      	 	}   

      	 }
      	
      }
      
      function compare($a, $b) {
      	
         $fnc     = "get". ucfirst($this->SORT);
         $methodA = array($a, $fnc);
         $methodB = array($b, $fnc);
         
         if ( (is_callable($methodA, true, $callable_name)) &&
         	  (is_callable($methodB, true, $callable_name))
         	) {
         	
            if ($a->$fnc() == $b->$fnc()) {
      	       return 0;
      	    }
      	 
            return ($a->$fnc() < $b->$fnc()) ? -1 * $this->DIRECTION : 1 * $this->DIRECTION;
            
         }
         
      }
      
      function compare_date($a, $b) {
      	 
      	$fnc     = "get". ucfirst($this->SORT);
      	$methodA = array($a, $fnc);
      	$methodB = array($b, $fnc);
      	 
      	if ( (is_callable($methodA, true, $callable_name)) &&
      	 	 (is_callable($methodB, true, $callable_name))
      	) {
      		
      		if (strtotime($a->$fnc()) == strtotime($b->$fnc())) {
      			return 0;
      		}
      
      		return (strtotime($a->$fnc()) < strtotime($b->$fnc())) ? -1 * $this->DIRECTION : 1 * $this->DIRECTION;
      
      	}
      	 
      }
      
      function toArray() {
      	
         return $this->LIST;
         	
      }
   	
   }
   
?>