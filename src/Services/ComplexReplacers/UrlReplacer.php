<?php


namespace wadelphillips\ForumConverter\Services\ComplexReplacers;

use Illuminate\Support\Str;

use function sprintf;
use function substr;

class UrlReplacer extends BaseReplacer
{
    /**
     * UrlReplacer constructor.
     *
     * @param string $body
     * @param string $tagOpening
     * @param string $closingBracket
     * @param string $closingTag
     */
    public function __construct(
        string $body,
        $tagOpening = '[url=',
        $closingBracket = ']',
        $closingTag = '[/url]'
    ) {
        parent::__construct($body, $tagOpening, $closingBracket, $closingTag);
    }

    /**
     * Generates the link tag to replace the pseudo tag.
     *
     * @param $content
     * @param $attributes
     *
     * @return string
     */
    protected function getReplacementTag($content, $attributes): string
    {
        $inner = (empty($content)) ? $attributes[ 'href' ] : $content;
//        dump($inner);

        return sprintf("<a href='%s'>%s</a>", $attributes[ 'href' ], $inner);
    }

    /**
     * Gets the attributes that exist within the tag and returns them as an array
     *
     * @param string $part
     * @param int    $positionClosingBracket
     *
     * @return array
     */
    protected function getTagAttributes(string $part, int $positionClosingBracket): array
    {
        $attributes = [];

        $attributes[ 'href' ] = substr($part, 0, $positionClosingBracket);

        return $attributes;
    }

    /**
     * Get the inner html of the pseudo link.  Return an empty string if no closing tag is present
     *
     * @param string $part
     *
     * @return string
     */
    protected function getInnerHtml(string $part): string
    {
        $inner = '';
        // check for closing tag, if exists use parent method
        if (Str::contains($part, $this->closingTag)) {
            $inner = parent::getInnerHtml($part);
        }

        //else return empty string

        return $inner;
    }

    /**
     * Recombine the corrected link with the trailing content
     *
     * @param string $part
     * @param string $updatedTag
     *
     * @return string
     */
    protected function rebuildPart(string $part, string $updatedTag): string
    {
        if (! Str::contains($part, $this->closingTag)) {
            return $updatedTag . Str::of($part)->after($this->closingBracket)->start(' ');
        }

        return parent::rebuildPart($part, $updatedTag);
    }
}
