<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XMLSerializer
 *
 * @author Andrew
 */
class Test {
    private $OpenTag = "<";
    private $CloseTag = ">";
    private $BackSlash = "/";
    public $Root = "root";
    
    public function __construct() {
    }
    
    private function Array_To_XML($array, $xmlWriter, $arrayElementName = "element_")
    {
        xmlwriter_start_element($xmlWriter, $arrayElementName);
        
        foreach($array as $key => $value){
            if(gettype($value) === "string" || gettype($value) === "boolean" || gettype($value) === "integer" || gettype($value) === "double" || gettype($value) === "float")
            {  
                if(is_numeric($key) === true)
                {
                    $key = "{$arrayElementName}_{$key}";
                }
                xmlwriter_start_element($xmlWriter, $key);
                xmlwriter_text($xmlWriter, $value);
                xmlwriter_end_element($xmlWriter);
                continue;
            }
            else if(gettype($value) === "array")
            {
                $this->Array_To_XML($value, $arrayElementName, $xmlWriter);
                continue;
            }
            else if(gettype($value) === "object")
            {
                $this->Object_To_XML($value, $xmlWriter);
                continue;
            }
            else
            {                
                continue;
            }
        }
        xmlwriter_end_element($xmlWriter);
        return $xmlWriter;
    }
    
    private function Object_To_XML($objElement, $xmlWriter)
    {
        foreach($objElement as $key => $value){
            if(gettype($value) !== "array" && gettype($value) !== "object")
            {
                xmlwriter_start_element($xmlWriter, $key);
                xmlwriter_text($xmlWriter, (string)$value);
                xmlwriter_end_element($xmlWriter);
                continue;
            }
            else if(gettype($value) === "array")
            {
                $this->Array_To_XML($value, $xmlWriter, $key);
                continue;
            }
            else if(gettype($value) === "object")
            {
                $this->Object_To_XML($value, $xmlWriter);
                continue;
            }
            else
            { 
                continue;
            }
        }
        return $xmlWriter;
    }
    
    public function Serialize_Object($element)
    {
        $xmlWriter = xmlwriter_open_memory();
        xmlwriter_set_indent($xmlWriter, true);
        xmlwriter_set_indent_string($xmlWriter, '   ');
        xmlwriter_start_document($xmlWriter, '1.0', 'UTF-8');
        
        xmlwriter_start_element($xmlWriter, $this->Root);
        $this->Object_To_XML($element, $xmlWriter);
        xmlwriter_end_element($xmlWriter);
        
        xmlwriter_end_document($xmlWriter);
        
        return xmlwriter_output_memory($xmlWriter);
    }
    
    public function Serialize_Array($element)
    {   
        $xmlWriter = xmlwriter_open_memory();
        xmlwriter_set_indent($xmlWriter, true);
        xmlwriter_set_indent_string($xmlWriter, '   ');
        xmlwriter_start_document($xmlWriter, '1.0', 'UTF-8');
        
        xmlwriter_start_element($xmlWriter, $this->Root);
        $this->Array_To_XML($element, $xmlWriter);
        xmlwriter_end_element($xmlWriter);
        
        xmlwriter_end_document($xmlWriter);
        
        return xmlwriter_output_memory($xmlWriter);
    }
}

