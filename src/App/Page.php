<?php

declare(strict_types=1);

namespace GuestBook\App;

class Page
{
    private string $pageContent;
    private string $template;
    private string $page;

    public function __construct(string $template, string $page)
    {
        $this->template = $template;
        $this->page = $page;
        $this->pageContent = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../templates/' . $this->template . '.html');
    }

    public function render(): void
    {
        echo str_replace('{{content}}', '', $this->pageContent);
    }

    public function addBlock(string $block, array $params = [])
    {
        $blockContent = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../resource/' . $this->template . '/' . $this->page . '/' . $block . '.html');
        $this->pageContent = str_replace('{{content}}', $blockContent.'{{content}}', $this->pageContent);
        $this->replaceBlockContent($params);
    }

    private function replaceBlockContent(array $params)
    {
        foreach ($params as $paramName => $paramValue) {
            if ($this->setPropertyMultipleValue($paramName, $paramValue) === null) {
                $this->setPropertyValue($paramName, $paramValue);
            }
        }
    }

    public function setPropertyMultipleValue(string $property, $value): ?bool
    {
        $pattern = '#{{' . $property . '}}(.*)?{{/' . $property . '}}#is';
        preg_match($pattern, $this->pageContent, $matches);
        $matchedTemplate = $matches[1];
        $fullMultipleContent = '';
        if (!empty($matchedTemplate)) {
            foreach ($value as $multiValue) {
                $multipleContent = $matchedTemplate;
                foreach ($multiValue as $name => $val) {
                    $multipleContent = str_replace('{{'.$name.'}}', $val, $multipleContent);
                }
                $fullMultipleContent .= $multipleContent;
            }
            $this->pageContent = preg_replace($pattern, $fullMultipleContent, $this->pageContent);
            return true;
        }
        return null;
    }

    public function setPropertyValue(string $property, $value)
    {
        $this->pageContent = str_replace('{{'.$property.'}}', $value, $this->pageContent);
    }
}