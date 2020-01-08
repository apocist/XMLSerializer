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
class XMLSerializer {
    private $OpenTag = "<";
    private $CloseTag = ">";
    private $BackSlash = "/";
    public $Root = "root";
    
    public function __construct() {
    }
    
    private function Array_To_XML($array, $arrayElementName = "element_", $xmlString = "")
    {
        if($xmlString === "")
        {
            $xmlString = "{$this->OpenTag}{$this->Root}{$this->CloseTag}";
        }
        $startTag = "{$this->OpenTag}{$arrayElementName}{$this->CloseTag}";
        $xmlString .= $startTag;
        foreach($array as $key => $value){
            if(gettype($value) === "string" || gettype($value) === "boolean" || gettype($value) === "integer" || gettype($value) === "double" || gettype($value) === "float")
            {
                $elementStartTag = "{$this->OpenTag}{$arrayElementName}_{$key}{$this->CloseTag}";
                $elementEndTag = "{$this->OpenTag}{$this->BackSlash}{$arrayElementName}_{$key}{$this->CloseTag}";
                $xmlString .= "{$elementStartTag}{$value}{$elementEndTag}";
                continue;
            }
            else if(gettype($value) === "array")
            {
                $xmlString = $this->Array_To_XML($value, $arrayElementName, $xmlString);
                continue;
            }
            else if(gettype($value) === "object")
            {
                $xmlString = $this->Object_To_XML($value, $xmlString);
                continue;
            }
            else
            {                
                continue;
            }
        }
        $endTag = "{$this->OpenTag}{$this->BackSlash}{$arrayElementName}{$this->CloseTag}";
        $xmlString .= $endTag;
        return $xmlString;
    }
    
    private function Object_To_XML($objElement, $xmlString = "")
    {
        if($xmlString === "")
        {
            $xmlString = "{$this->OpenTag}{$this->Root}{$this->CloseTag}";
        }
        foreach($objElement as $key => $value){
            if(gettype($value) !== "array" && gettype($value) !== "object")
            {
                $startTag = "{$this->OpenTag}{$key}{$this->CloseTag}";
                $endTag = "{$this->OpenTag}{$this->BackSlash}{$key}{$this->CloseTag}";
                $xmlString .= "{$startTag}{$value}{$endTag}";
                continue;
            }
            else if(gettype($value) === "array")
            {
                $xmlString = $this->Array_To_XML($value, $key, $xmlString);
                continue;
            }
            else if(gettype($value) === "object")
            {
                $xmlString = $this->Object_To_XML($value, $xmlString);
                continue;
            }
            else
            { 
                continue;
            }
        }
        return $xmlString;
    }
    
    public function Convert_Object($element, $xmlString = "")
    {
        $endTag = "{$this->OpenTag}{$this->BackSlash}{$this->Root}{$this->CloseTag}";
        return "{$this->Object_To_XML($element, $xmlString)}{$endTag}";
    }
    
    public function Convert_Array($element, $xmlString = "")
    {
        $endTag = "{$this->OpenTag}{$this->BackSlash}{$this->Root}{$this->CloseTag}";
        return "{$this->Array_To_XML($element, $xmlString)}{$endTag}";
    }
}

