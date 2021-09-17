<?php


namespace wadelphillips\ForumConverter\Services;


use DateTime;

use function explode;
use function is_null;
use function is_numeric;

use function sprintf;

use function strpos;

use const PHP_EOL;

class QuoteReplacer
{

    private string $body;

    private string $startTag;

    private string $closingTag;

    /**
     * QuoteReplacer constructor.
     *
     * @param string $body
     * @param string $startTag
     * @param string $closingTag
     */
    public function __construct(
        string $body,
        $startTag = '[quote',
        $closingTag = ']'
    ) {

        $this->body = $body;
        $this->startTag = $startTag;
        $this->closingTag = $closingTag;
    }

    public function process(): QuoteReplacer
    {
        //break the string apart at the opening
        dump(explode($this->startTag, $this->body));
        $parts = explode($this->startTag, $this->body);
        foreach ($parts as $part){

            // find the end of the opening tag and get the attributes if they are set
            $positionClosing = strpos($part, $this->closingTag);
            if ($positionClosing > 0) {
                //get the attributes
                sub
            }
            // cut out the content

            // remove the closing [/quote] tag

            // build up a block quote
            $quote = $this->getBlockQuote($content, $author , $date );
        }

        //rebuild and set the body
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {

        return $this->body;
    }

    private function getBlockQuote($content, $author = null, $date = null): string
    {
        $date = (is_numeric($date)) ? DateTime::setTimeStamp($date): $date;

        $tag = sprintf("<figure>%s", PHP_EOL);
        $tag .= sprintf("<blockquote>%s", PHP_EOL);
        $tag .= sprintf("<p>%s</p>%s", $content,  PHP_EOL);
        $tag .= sprintf("</blockquote>%s", PHP_EOL);

        if (! is_null($author) ) {
            $tag .= sprintf('<figcaption>%s', $author);
            $tag .= (! is_null($date) ) ? sprintf(' - %s', $date->format('M d, Y')) : '';
            $tag .= sprintf('</figcaption>%s', PHP_EOL);
        }

        $tag .= sprintf("</figure>%s", PHP_EOL);

        return $tag;
    }


}