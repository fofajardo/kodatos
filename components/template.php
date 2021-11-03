<?php

/* @FILE: template.php
   © Francis Dominic Fajardo - All Rights Reserved
   Unauthorized copying of this file, via any medium is strictly prohibited
   Proprietary and confidential
*/

class Template
{
    private $template;
    private $data = [];
    private $content = [];
    private $child_templates = [];

    public function __construct(
        string $template,
        bool $auto_retrieve = false
    ) {
        if ($auto_retrieve) {
            $this->setTemplate(Utils::getTemplate($template));
            return;
        }
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

    public function attachData(array &$data)
    {
        $this->data = &$data;
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
    
    public function attach($template)
    {
        $this->child_templates[] = &$template;
    }

    public function output()
    {
        $content_merged = implode(PHP_EOL, $this->content);
        $template_count = count($this->child_templates);
        for ($i = 0; $i < $template_count; $i++)
        {
            $content_merged .= $this->child_templates[$i]->output() . PHP_EOL;
        }

        $search = array_merge(
            [
                "CONTENT_INSERT",
                "DIR_AST",
                "DIR_IMG",
            ],
            array_keys($this->data)
        );
        $replace = array_merge(
            [
                $content_merged,
                Framework::$dir["S_AST"],
                Framework::$dir["S_IMG"],
            ],
            array_values($this->data)
        );

        return str_replace($search, $replace, $this->template);
    }
}
