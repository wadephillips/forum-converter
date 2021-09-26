<?php


namespace wadelphillips\ForumConverter\Services;


use Illuminate\Support\Str;
use wadelphillips\ForumConverter\Contracts\Replaceable;

use function dump;

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

    abstract public function getReplacementTag($content, $attributes): string;

    abstract public function getTagAttributes(string $part, int $positionClosingBracket): array;

    /**
     * @return string
     */
    public function getBody(): string
    {

        return $this->body;
    }

    public function process(): BaseReplacer
    {

        //break the string apart at any instance of the tag opening
        $parts = explode($this->tagOpening, $this->body);

        //iterate over substrings and replace the pseudo tags
        foreach ($parts as &$part) {

            if (empty($part)) {
                continue;
            }
            $positionClosingBracket = strpos($part, $this->closingBracket);

            $attributes = $this->getTagAttributes($part, $positionClosingBracket);

            $tagBody = $this->getInnerHtml($part);

            $updatedTag = $this->getReplacementTag($tagBody, $attributes);

            // combine replacement tag and remaining portion of part
            $part = $updatedTag . Str::of($part)->after($this->closingTag)->start(' ');
        }

        //rebuild and set the body
        $this->body = implode(' ', $parts);

        return $this;
    }

    /**
     * Get the inner html of the pseudo tag
     *
     * @param string $part
     *
     * @return string
     */
    public function getInnerHtml(string $part): string
    {

        return Str::of($part)
            ->after($this->closingBracket)
            ->before($this->closingTag)
            ->trim();
    }
}