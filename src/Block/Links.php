<?php
declare(strict_types=1);

namespace Hyva\Header\Block;

class Links extends \Magento\Framework\View\Element\Html\Links
{
    /**
     * Improvements:
     * 1. Check if child block actually yields any content before adding it to the output.
     * 2. Wrap each block into <li></li> tags.
     * 3. Sortable via position argument (layout)
     *
     * @return string
     */
    protected function _toHtml(): string
    {
        if ($this->getTemplate()) {
            return parent::_toHtml();
        }

        $html = '';

        if ($this->getLinks()) {
            $links = [];

            foreach ($this->getLinks() as $block) {
                if ($block->hasPosition()) {
                    $pos = $block->getPosition();
                    while (array_key_exists($pos, $links)) {
                        $pos++;
                    }
                    $links[$pos] = $block;
                }
            }

            ksort($links);
            $key = array_key_last($links);
            $key++;

            foreach ($this->getLinks() as $block) {
                if (!$block->hasPosition()) {
                    $links[$key++] = $block;
                }
            }

            $html = '<ul' . ($this->hasCssClass() ? ' class="' . $this->escapeHtml($this->getCssClass()) . '"' : '') . '>';
            foreach ($links as $link) {
                $content = trim($this->renderLink($link));
                if (!empty($content)) {
                    $html .= '<li>' . $content . '</li>';
                }
            }
            $html .= '</ul>';
        }

        return $html;
    }
}