<?php


namespace wadephillips\ForumConverter\Services\ComplexReplacers;

use function count;
use Illuminate\Support\Str;

use wadephillips\ForumConverter\Contracts\Replaceable;

abstract class BaseReplacer implements Replaceable
{
    protected string $tagOpening;

    protected string $closingTag;

    protected string $closingBracket;

    protected string $body;

    /**
     * QuoteReplacer constructor.
     *
     * @param string $body
     * @param string $tagOpening
     * @param string $closingBracket
     * @param string $closingTag
     */
    public function __construct(
        string $body,
        string $tagOpening,
        string $closingBracket,
        string $closingTag
    ) {
        $this->body = $body;
        $this->tagOpening = $tagOpening;
        $this->closingBracket = $closingBracket;
        $this->closingTag = $closingTag;
    }

    /**
     * Accessor for thee body after processing
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Primary method for this class that performs the replacement of the pseduo tags
     * @return $this
     */
    public function process(): BaseReplacer
    {

        //break the string apart at any instance of the tag opening
        $parts = explode($this->tagOpening, $this->body);

        //iterate over substrings and replace the pseudo tags
        for ($i = 1; $i < count($parts); $i++) {
            if (empty($parts[ $i ])) {
                continue;
            }

            $positionClosingBracket = strpos($parts[ $i ], $this->closingBracket);

            $attributes = $this->getTagAttributes($parts[ $i ], $positionClosingBracket);

            $tagBody = $this->getInnerHtml($parts[ $i ]);

            $updatedTag = $this->getReplacementTag($tagBody, $attributes);

            // combine replacement tag and remaining portion of part
            $parts[ $i ] = $this->rebuildPart($parts[ $i ], $updatedTag);
        }

        //rebuild and set the body
        $this->body = implode(' ', $parts);

        return $this;
    }

    /**
     * Abstract method for getting the attributes that exist within the tag and
     * returning them as an array
     *
     * @param string $part
     * @param int    $positionClosingBracket
     *
     * @return array
     */
    abstract protected function getTagAttributes(string $part, int $positionClosingBracket): array;

    /**
     * Get the inner html of the pseudo tag
     *
     * @param string $part
     *
     * @return string
     */
    protected function getInnerHtml(string $part): string
    {
        return Str::of($part)
            ->after($this->closingBracket)
            ->before($this->closingTag)
            ->trim();
    }

    /**
     * Abstract method for generating the correct html tag
     *
     * @param $content
     * @param $attributes
     *
     * @return string
     */
    abstract protected function getReplacementTag($content, $attributes): string;

    /**
     * Recombine the corrected tag with the trailing content
     *
     * @param string $part
     * @param string $updatedTag
     *
     * @return string
     */
    protected function rebuildPart(string $part, string $updatedTag): string
    {
        return $updatedTag . Str::of($part)->after($this->closingTag)->start(' ');
    }
}
