<?php
declare(strict_types=1);

namespace Hyva\Header\Block;

class Links extends \Magento\Framework\View\Element\Html\Links
{
    /**
     * @return array
     */
    public function getLinks(): array
    {
        $result = [];
        $links = parent::getLinks();

        foreach ($links as $block) {
            if ($block->hasPosition()) {
                $pos = $block->getPosition();
                while (array_key_exists($pos, $result)) {
                    $pos++;
                }
                $result[$pos] = $block;
            }
        }

        ksort($result);
        $key = array_key_last($result);
        $key++;

        foreach ($links as $block) {
            if (!$block->hasPosition()) {
                $result[$key++] = $block;
            }
        }

        return $result;
    }

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
        $links = $this->getLinks();

        if ($links) {
            $itemClass = $this->getItemClass() ? ' class="' . $this->escapeHtml($this->getItemClass()) . '"' : '';
            $html = '<ul' . ($this->hasCssClass() ? ' class="' . $this->escapeHtml($this->getCssClass()) . '"' : '') . '>';
            foreach ($links as $link) {
                $content = trim($this->renderLink($link));
                if (!empty($content)) {
                    $html .= '<li' . $itemClass . '>' . $content . '</li>';
                }
            }
            $html .= '</ul>';
        }

        return $html;
    }
}