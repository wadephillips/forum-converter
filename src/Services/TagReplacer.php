<?php


namespace wadephillips\ForumConverter\Services;

use Closure;
use Illuminate\Support\Str;
use wadephillips\ForumConverter\Services\ComplexReplacers\QuoteReplacer;
use wadephillips\ForumConverter\Services\ComplexReplacers\UrlReplacer;

use function count;
use function explode;
use function implode;
use function strpos;
use function substr_replace;

class TagReplacer
{

    private array $tagsToBeReplaced;

    /**
     * TagReplacer constructor.
     *
     * @param array|null $tagsToBeReplaced
     */
    public function __construct(array $tagsToBeReplaced = null)
    {

        $this->tagsToBeReplaced = $tagsToBeReplaced ?? $this->setTagsToBeReplaced();
    }

    /**
     * This
     * @return array
     */
    public function setTagsToBeReplaced(array $tags = []): array
    {

        $this->tagsToBeReplaced = (!empty($tags))
            ? $tags
            : [
                'opening' => '[',
                'closing' => ']',
                'simple' => [
                    'b' => '<b>',
                    'strong' => '<strong>',
                    'em' => '<em>',
                    'i' => '<i>',
                    'u' => '<u>',
                    'b' => '<b>',
                    'h1' => '<h1>',
                    'h2' => '<h2>',
                    'h3' => '<h3>',
                    'h4' => '<h4>',
                    'h5' => '<h5>',
                    'h6' => '<h6>',
                    '/strong' => '</strong>',
                    '/b' => '</b>',
                    '/em' => '</em>',
                    '/i' => '</i>',
                    '/u' => '</u>',
                    '/size' => '</span>',
                    '/email' => '</a>',
                    '/color' => '</b>',
                    '/h1' => '</h1>',
                    '/h2' => '</h2>',
                    '/h3' => '</h3>',
                    '/h4' => '</h4>',
                    '/h5' => '</h5>',
                    '/h6' => '</h6>',
                ],
                'complex' => [

                    'size' => [
                        'tagStart' => 'size=',
                        'openingReplacement' => '<b data-size="',
                        'closingReplacement' => '">',
                    ],
                    'email' => [
                        'tagStart' => 'email=',
                        'openingReplacement' => '<a href="mailto:',
                        'closingReplacement' => '">',
                    ],
                    'color' => [
                        'tagStart' => 'color=',
                        'openingReplacement' => '<span style="color:',
                        'closingReplacement' => ';">',
                    ],
                    'url' => function ($body) {

                        return (new UrlReplacer($body))->process()->getBody();
                    },
                    'quote' => function ($body) {

                        return (new QuoteReplacer($body))->process()->getBody();
                    }

                    ,
                ],
            ];
    }

    /**
     * Searches and replaces pseudo tags as defined in the 'simple' subarray
     * of the tagsToBeReplaced array.
     * This works well for pseudo tags that can be directly replace with HTML E.G.
     * [strong]...[/strong] should be replaced with <strong>...</strong>
     *
     * @param string $topic
     *
     * @return string
     */
    public function reformatSimpleTags(string $topic): string
    {

        foreach ($this->tagsToBeReplaced[ 'simple' ] as $existing => $replacement) {
            $tag = $this->tagsToBeReplaced[ 'opening' ] . $existing . $this->tagsToBeReplaced[ 'closing' ];
            $topic = Str::replace($tag, $replacement, $topic);
        };

        return $topic;
    }

    /**
     *
     * @param string $topic
     *
     * @return string
     */
    public function reformatComplexTags(string $topic): string
    {

        foreach ($this->tagsToBeReplaced[ 'complex' ] as $existing) {
            $topic = $this->doComplexTagReplacement(
                $topic,
                $existing
            );
        }

        return $topic;
    }

    /**
     * Searches and replaces using a more complex manipulation or applies a closure to a more complicated Pseudo Tag.
     * This is good for pseudo tags that need some kind of programmatic manipulation, E.G.
     * [url=http://example/] should be replaced with <a href="http://example">http://example</a>
     *
     * @param string          $topic the haystack
     * @param array | Closure $map
     *
     * @return string
     */
    private function doComplexTagReplacement(string $topic, $map): string
    {

        if ($map instanceof Closure) {
            $topic = $map($topic);
        } else {
            $topic = $this->doBaseComplexTagReplacement(
                $topic,
                $this->tagsToBeReplaced[ 'opening' ] . $map[ 'tagStart' ],
                $this->tagsToBeReplaced[ 'closing' ],
                $map[ 'openingReplacement' ],
                $map[ 'closingReplacement' ]
            );
        }

        return $topic;
    }

    /**
     * Provides a base method of replacing complex tags that require less manipulation
     *
     * @param string $body
     * @param string $opening
     * @param string $closing
     * @param string $openingReplacement
     * @param string $closingReplacement
     *
     * @return string
     */
    private function doBaseComplexTagReplacement(
        string $body,
        string $opening,
        string $closing,
        string $openingReplacement,
        string $closingReplacement
    ): string {

        // need a guard to ensure that the tag exists in the body
        if (!Str::contains($body, $opening)) {
            return $body;
        }

        //split string into parts to isolate the tag
        $parts = explode($opening, $body);
        for ($i = 1; $i < count($parts); $i++) {
            //find index of next closing tag
            $pos = strpos($parts[ $i ], $closing);
            //replace closing tag
            $parts[ $i ] = ($pos) ? substr_replace($parts[ $i ], $closingReplacement, $pos, 1) : $parts[ $i ];
        }

        //replace opening tag and recombine
        return implode("$openingReplacement", $parts);
    }

}