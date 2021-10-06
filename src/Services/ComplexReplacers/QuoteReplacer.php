<?php


namespace wadelphillips\ForumConverter\Services\ComplexReplacers;

use DateTime;
use Illuminate\Support\Str;

use function is_null;
use function is_numeric;
use function json_decode;
use const PHP_EOL;
use function sprintf;

use wadelphillips\ForumConverter\Contracts\Replaceable;

class QuoteReplacer extends BaseReplacer implements Replaceable
{
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
        $tagOpening = '[quote',
        $closingBracket = ']',
        $closingTag = '[/quote]'
    ) {
        parent::__construct($body, $tagOpening, $closingBracket, $closingTag);
    }

    /**
     * Builds an HTML Blockquote to replace the pseudo tag
     *
     * @param      $content
     * @param      $attributes
     *
     * @return string
     */
    protected function getReplacementTag($content, $attributes): string
    {
        $date = (isset($attributes[ 'date' ])) ? $attributes[ 'date' ] : null;
        $date = (is_numeric($date)) ? (new DateTime)->setTimeStamp((int)$date) : $date;

        $tag = sprintf("<figure>%s", PHP_EOL);
        $tag .= sprintf("<blockquote>%s", PHP_EOL);
        $tag .= sprintf("<p>%s</p>%s", $content, PHP_EOL);
        $tag .= sprintf("</blockquote>%s", PHP_EOL);

        if (isset($attributes[ 'author' ]) && ! is_null($attributes[ 'author' ])) {
            $tag .= sprintf('<figcaption>%s', $attributes[ 'author' ]);
            $tag .= (! is_null($date)) ? sprintf(' - %s', $date->format('M d, Y')) : '';
            $tag .= sprintf('</figcaption>%s', PHP_EOL);
        }

        $tag .= sprintf("</figure>%s", PHP_EOL);

        return $tag;
    }

    /**
     * @param string $part
     * @param int    $positionClosingBracket
     *
     * @return array|mixed
     */
    protected function getTagAttributes(string $part, int $positionClosingBracket): array
    {
        $attributes = [];
        if ($positionClosingBracket > 0) {
            //get the attributes

            //convert attributes to to json
            $json = Str::of(substr($part, 1, $positionClosingBracket - 1))
                ->replace('=', ':')
                ->replace('author', '"author"')
                ->replace('date', '"date"')
                ->replace(' ', ', ')
                ->start('{')
                ->finish('}');

            $attributes = json_decode((string) $json, true);
        }

        return $attributes;
    }
}
