<?php


namespace wadelphillips\ForumConverter\Services;


class PseudoTagReplacer
{

//    private array $replacementMap;

    /**
     * PseudoTagReplacer constructor.
     */
    public function __construct()
    {

        echo 'hey';
    }

    /**
     * Replacements tags are mapped in an associative array with the key being the tag to search for
     * and the value being it's replacement.  A string can be passed for direct find and replacement,
     * or a closure many be passed.
     *
     * @param array $mapping
     *
     * @return PseudoTagReplacer
     */
    public function setReplacementMap(array $mapping): self
    {
        $this->replacementMap = $mapping;
        return $this;
    }


    /**
     * Looks through a string for a series of  "tags" and replaces them with actual html.
     * What does it need to do that:
     * List of things to look for and a map for how to replace them
     * a string to work on.
     */

    public function handle(string $string = '')
    {
//        $string ?? this->string();
//        collect($this->replacementMap);//todo resume: we need to take the string and look for the value of each key in the map.  If we find it we should replace it with the value of the array
    }

}