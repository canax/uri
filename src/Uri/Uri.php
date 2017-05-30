<?php

namespace Anax\Uri;

/**
 * Class to help the buildup of an uri.
 * Wraps a string and provides methods to check and manipulate uri.
 */
class Uri
{
    private $uri;



    /**
     * Uri constructor.
     *
     * @param string $uri       Initial uri-string.
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
    }



    /**
     * Check if uri is empty by php standards (see http://php.net/manual/en/function.empty.php).
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->uri);
    }


    /**
     * Check if uri starts with string.
     *
     * Private helper class helper.
     *
     * @param  string $string   String to check for in start of uri.
     * @return bool             True if uri strarts with $string.
     */
    private function uriBeginsWith($string)
    {
        $len = strlen($string);
        return substr($this->uri, 0, $len) == $string;
    }



    /**
     * Check if uri starts with one or more strings.
     *
     * @param  string ...$string   Strings to check for in start of uri.
     * @return bool                True if uri strarts with $string.
     */
    public function startsWith(...$strings)
    {
        return array_reduce($strings, function ($collectedCondition, $string) {
            return $this->uriBeginsWith($string) || $collectedCondition;
        }, false);
    }



    /**
     * Prepend this uri with another uri with a slash inbetween.
     *
     * Example:
     *  $relativeUri = new Uri("about"); // $relativeUrl->uri() == "about"
     *  $baseUrl     = new Uri("http://dbwebb.se"); // $baseUrl->uri() == "http://dbwebb.se"
     *  $urlString   = $relativeUri->prepend($baseUrl)->uri(); // $urlString == "http://dbwebb.se/about"
     *
     * @param  Uri $uri  Uri to prepend this uri
     * @return Uri self  Reference to this Uri for chaining.
     */
    public function prepend(Uri $uri)
    {
        $this->uri = $uri->uri() . "/" . ltrim($this->uri(), "/");
        return $this;
    }



    /**
     * Appends supplied $uri to this uri with a slash inbetween.
     *
     * Example:
     *  $relativeUri = new Uri("about"); // $relativeUrl->uri() == "about"
     *  $baseUrl     = new Uri("http://dbwebb.se"); // $baseUrl->uri() == "http://dbwebb.se"
     *  $urlString   = $baseUri->append($relativeUri)->uri(); // $urlString == "http://dbwebb.se/about"
     *
     * @param  Uri $uri  Uri to append this uri
     * @return Uri self  Reference to this Uri for chaining.
     */
    public function append(Uri $uri)
    {
        $this->uri = $this->uri() . "/" . ltrim($uri->uri(), "/");
        return $this;
    }



    /**
     * Remove the basename part of uri if it is same as argument.
     *
     * Example:
     *  $theUri = new Uri("http://dbwebb.se/about/this.html");
     *  theUri->removeBasename("index.html"); // theUri->uri() == "http://dbwebb.se/about/this.html"
     *  theUri->removeBasename("this.html"); // theUri->uri() == "http://dbwebb.se/about"
     *
     * @param  string $basename     The basename to remove.
     * @return Uri self      Reference to this Uri for chaining.
     */
    public function removeBasename($basename)
    {
        $this->uri = basename($this->uri) == $basename
            ? dirname($this->uri)
            : $this->uri;
        return $this;
    }



    /**
     * Get the boxed uri as string without any trailing slash.
     *
     * @return string   The uri
     */
    public function uri()
    {
        return rtrim($this->uri, "/");
    }
}
