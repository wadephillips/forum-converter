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
                //todo resume: working on getting this as json in tinkerwell the decode to array
                //start from tinkerwell
                $a = '[quote] vivamus feugiat magnis parturient orci sit mauris est[/quote], netus nunc felis per blandit quis suspendisse integer hendrerit, varius duis erat auctor tortor mollis montes tellus. Posuere neque tellus conubia eu cum duis sapien risus, justo sociosqu inceptos nisi nostra magna venenatis. [quote author="obnicole" date="1327507679"] scelerisque convallis diam tristique massa[/quote] urna quam, nibh tempor hendrerit mattis facilisi eleifend vel per adipiscing pellentesque, tincidunt nulla felis donec turpis [strong]curabitur faucibus eget in. [url=Sed vehicula euismod etiam] nisl iaculis neque enim nullam ultrices, vulputate facilisi nec blandit est sagittis nunc suspendisse varius[/url], nibh cursus lobortis [color=red]laoreet id dignissim[/color] posuere sapien. Pellentesque conubia condimentum auctor nec varius suspendisse facilisis elit taciti dictum, eleifend vel nascetur feugiat lorem sit cras ullamcorper. Dui suspendisse auctor rhoncus porta tempus lectus lorem, vestibulum volutpat mi penatibus vel dignissim.
';

                $converter = new wadelphillips\ForumConverter\Services\QuoteReplacer($a);

// $converter->process()
                $b = explode('[quote', $a);
                $pos = strpos($b[2], ']');

// Str:Str::replace('author','"author"',Str::replace( '=', ':', ))
                $s = Str::of(substr($b[2], 1, $pos-1))->replace('=', ':')->replace('author', '"author"')->replace('date', '"date"')->replace(' ', ', ');

                json_decode(strval($s), true);
                strval($s);
// end from tinkerwell
                substr();
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