<?php


namespace wadelphillips\ForumConverter\Services;

use Closure;
use function count;

use function explode;

use Illuminate\Support\Str;

use function implode;
use function strpos;
use function substr_replace;
use wadelphillips\ForumConverter\Services\ComplexReplacers\QuoteReplacer;
use wadelphillips\ForumConverter\Services\ComplexReplacers\UrlReplacer;

class TagReplacer
{
    private array $tagsToBeReplaced;

    /**
     * TagReplacer constructor.
     *
     * @param array $tagsToBeReplaced
     */
    public function __construct(array $tagsToBeReplaced = null)
    {
        $this->tagsToBeReplaced = $tagsToBeReplaced ?? $this->setTagsToBeReplaced();
    }

    public function reformatSimpleTags(string $topic): string
    {
        foreach ($this->tagsToBeReplaced[ 'simple' ] as $existing => $replacement) {
            $tag = $this->tagsToBeReplaced[ 'opening' ] . $existing . $this->tagsToBeReplaced[ 'closing' ];
            $topic = Str::replace($tag, $replacement, $topic);
        };

        return $topic;
    }

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

    private function doComplexTagReplacement($topic, $map): string
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

    private function doBaseComplexTagReplacement(
        string $body,
        string $opening,
        string $closing,
        string $openingReplacement,
        string $closingReplacement
    ): string {

        // need a guard to ensure that the tag exists in the body
        if (! Str::contains($body, $opening)) {
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

    public function setTagsToBeReplaced()
    {
        return $this->tagsToBeReplaced = [
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
                    $urlReplacer = new UrlReplacer($body);

                    return $urlReplacer->process()->getBody();
                },
                'quote' => function ($body) {
                    $quoteReplacer = new QuoteReplacer($body);

                    return $quoteReplacer->process()->getBody();
                }

                ,
            ],
        ];
    }
}
