<?php

class Template
{
    private $template;
    private $data = [];
    private $content = [];
    
    public function __construct(
        string $template
    ) {
        $this->setTemplate($template);
    }
    
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }
    
    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent(array $content)
    {
        $this->content = $content;
    }
    
    public static function createOpeningTag(
        string $name,
        array $attributes,
        bool $self_closing = false
    ) {
        $node = "<$name";
        foreach ($attributes as $key => $value)
        {
            $node .= " ";
            $node .= $key;
            $node .= "=\"$value\"";
        }
        $node .= ($self_closing ? "/>" : ">");
        return $node;
    }
    
    public static function createClosingTag(string $name)
    {
        return "</$name>";
    }
    
    public static function createElement(
        string $name,
        array $attributes = [],
        $content = ""
    ) {
        $node = self::createOpeningTag($name, $attributes);
        if (is_array($content))
        {
            $node .= implode(PHP_EOL, $content);
        }
        else
        {
            $node .= $content;
        }
        $node .= self::createClosingTag($name);
        return $node;
    }

    public function appendElement(
        string $name,
        $content = "",
        array $attributes = []
    ) {
        $this->content[] = self::createElement(
            $name,
            $attributes,
            $content
        );
    }

    public function appendLineBreak(int $multiplier = 1)
    {
        $this->content[] = str_repeat("<br/>", $multiplier);
    }
    
    public function append($content)
    {
        if (is_array($content))
        {
            $this->content = array_merge($this->content, $content);
            return;
        }
        $this->content[] = $content;
    }
    
    public function output()
    {
        $search = [
            "CONTENT_INSERT",
            "DIR_AST",
            "DIR_IMG",
        ];
        $replace = [
            implode(PHP_EOL, $this->content),
            Framework::$dir["S_AST"],
            Framework::$dir["S_IMG"],
        ];
        
        $output = str_replace($search, $replace, $this->template);
        
        $search = array_keys($this->data);
        $replace = array_values($this->data);

        return str_replace($search, $replace, $output);
    }
}
