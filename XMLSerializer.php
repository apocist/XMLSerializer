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
    public $Indent = true;
    public $IndentString = "   ";
    public $Version = "1.0";
    public $Encoding = "UTF-8";
    
    public function __construct() {
    }
    
    private function Array_To_XML($array, $xmlWriter, $arrayElementName = "arrayElement_")
    {
        $xmlWriter->startElement($arrayElementName);
        foreach($array as $key => $value){
            if(gettype($value) === "string" || gettype($value) === "boolean" || gettype($value) === "integer" || gettype($value) === "double" || gettype($value) === "float")
            {  
                if(is_numeric($key) === true)
                {
                    $key = "{$arrayElementName}_{$key}";
                }
                $xmlWriter->startElement($key);
                $xmlWriter->text($value);
                $xmlWriter->endElement();
                continue;
            }
            else if(gettype($value) === "array")
            {
                $this->Array_To_XML($value, $xmlWriter, $arrayElementName);
                continue;
            }
            else if(gettype($value) === "object")
            {
                $this->Object_To_XML($value, $xmlWriter, $key);
                continue;
            }
            else
            {                
                continue;
            }
        }
        $xmlWriter->endElement();
        return $xmlWriter;
    }
    
    private function Object_To_XML($objElement, $xmlWriter, $objectElementName = "objectElement")
    {
        $xmlWriter->startElement($objectElementName);
        foreach($objElement as $key => $value){
            if(gettype($value) !== "array" && gettype($value) !== "object")
            {
                $xmlWriter->startElement($key);
                $xmlWriter->text((string)$value);
                $xmlWriter->endElement();
                continue;
            }
            else if(gettype($value) === "array")
            {
                $this->Array_To_XML($value, $xmlWriter, $key);
                continue;
            }
            else if(gettype($value) === "object")
            {
                $this->Object_To_XML($value, $xmlWriter, $key);
                continue;
            }
            else
            { 
                continue;
            }
        }
        $xmlWriter->endElement();
        return $xmlWriter;
    }
    
    public function Serialize_Object($object)
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent($this->Indent);
        $xmlWriter->setIndentString($this->IndentString);
        $xmlWriter->startDocument($this->Version, $this->Encoding);
        $xmlWriter->startElement($this->Root);
        $this->Object_To_XML($object, $xmlWriter);
        $xmlWriter->endElement();
        $xmlWriter->endDocument();      
        return $xmlWriter->outputMemory();
    }
    
    public function Serialize_Array($array)
    {   
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent($this->Indent);
        $xmlWriter->setIndentString($this->IndentString);
        $xmlWriter->startDocument($this->Version, $this->Encoding);
        $xmlWriter->startElement($this->Root);
        $this->Array_To_XML($array, $xmlWriter);
        $xmlWriter->endElement();
        $xmlWriter->endDocument();      
        return $xmlWriter->outputMemory();
    }
}

