<?php

namespace Anax\Uri;

/**
 * A helper to create urls.
 *
 */
class UriTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic test to create class.
     */
    public function testCreateUriBuilder()
    {
        $uri = new Uri("");
        $this->assertInstanceOf(Uri::class, $uri);
    }



    /**
     * Check that we can get the uri and that it does not end with a slash
     */
    public function testUri()
    {
        $full = new Uri("http://dbwebb.se/about");
        $fullTrailing = new Uri("http://dbwebb.se/about/");

        $this->assertEquals("http://dbwebb.se/about", $full->uri());
        $this->assertEquals("http://dbwebb.se/about", $fullTrailing->uri());
    }


    /**
     * Provider for isEmpty test.
     */
    public function isEmptyTestProvider()
    {
        return [
            ["", true],
            [0, true],
            [0.0, true],
            ["0", true],
            [null, true],
            [false, true],
            [[], true],
            //[$notInitialized, true], // Causes notice in testsuit
            ["whatever", false],
            ["http://thisis.not/empty", false],
        ];
    }



    /**
     * Check that empty is empty
     *
     * @dataProvider isEmptyTestProvider
     */
    public function testIsEmpty($testThing, $expected)
    {
        $uri = new Uri($testThing);
        $this->assertEquals($expected, $uri->isEmpty());
    }



    /**
     * Check startsWith
     */
    public function testStartsWith()
    {
        $whatever       = new Uri("whatever");
        $http           = new Uri("http://dbwebb.se");
        $doubleSlash    = new Uri("//dbwebb.se");

        $this->assertEquals(false, $whatever->startsWith());

        $this->assertEquals(true, $whatever->startsWith("what"));
        $this->assertEquals(false, $whatever->startsWith("ever"));

        $this->assertEquals(true, $http->startsWith("http"));
        $this->assertEquals(false, $http->startsWith("dbwebb"));

        $this->assertEquals(true, $doubleSlash->startsWith("//"));
        $this->assertEquals(false, $doubleSlash->startsWith("dbwebb"));

        $this->assertEquals(true, $whatever->startsWith("notThis", "what", "neitherThis"));
        $this->assertEquals(true, $whatever->startsWith("w", "what", "whatev"));
        $this->assertEquals(false, $whatever->startsWith("notThis", "hat", "neitherThis"));

        $this->assertEquals(true, $http->startsWith("dbwebb", "http://"));
        $this->assertEquals(false, $http->startsWith("dbwebb", ".se"));
    }



    /**
     * Provider for prepend and append test
     */
    public function prependAppendTestProvider()
    {
        $expected = "http://dbwebb.se/about";

        return [
            [
                'base'      => "http://dbwebb.se",
                'path'      => "about",
                'expected'  => $expected
            ],
            [
                'base'      => "http://dbwebb.se",
                'path'      => "about/",
                'expected'  => $expected
            ],
            [
                'base'      => "http://dbwebb.se",
                'path'      => "/about",
                'expected'  => $expected
            ],
            [
                'base'      => "http://dbwebb.se/",
                'path'      => "about",
                'expected'  => $expected
            ],
            [
                'base'      => "http://dbwebb.se/",
                'path'      => "/about",
                'expected'  => $expected
            ],
            [
                'base'      => "http://dbwebb.se/",
                'path'      => "/about/",
                'expected'  => $expected
            ],
        ];
    }



    /**
     * Check that prepend prepends. Get uri with uri method
     *
     * @dataProvider prependAppendTestProvider
     */
    public function testPrepend($base, $path, $expected)
    {
        $baseUrl = new Uri($base);
        $uri = new Uri($path);
        $uri->prepend($baseUrl);

        $this->assertEquals($expected, $uri->uri());
    }



    /**
     * Check that appends appends. Get uri with uri method
     *
     * @dataProvider prependAppendTestProvider
     */
    public function testAppend($base, $path, $expected)
    {
        $pathUri = new Uri($path);
        $uri = new Uri($base);
        $uri->append($pathUri);

        $this->assertEquals($expected, $uri->uri());
    }



    /**
     * Provider for removeBasename test
     */
    public function removeBasenameTestProvider()
    {
        return [
            [
                "http://dbwebb.se/about",
                "about",
                "http://dbwebb.se"
            ],
            [
                "http://dbwebb.se/",
                "about",
                "http://dbwebb.se"
            ],
            [
                "http://dbwebb.se",
                "about",
                "http://dbwebb.se"
            ],
            [
                "http://dbwebb.se/about.html",
                "about.html",
                "http://dbwebb.se"
            ],
            [
                "http://dbwebb.se/about",
                "index",
                "http://dbwebb.se/about"
            ],
            [
                "http://dbwebb.se/about",
                "about.html",
                "http://dbwebb.se/about"
            ],
            [
                "http://dbwebb.se/about/this.html",
                "index.html",
                "http://dbwebb.se/about/this.html"
            ],
            [
                "http://dbwebb.se/about/this.html",
                "this.html",
                "http://dbwebb.se/about"
            ],
        ];
    }



    /**
     * Check removeBasename
     *
     * @dataProvider removeBasenameTestProvider
     */
    public function testRemoveBasename($fullUrl, $basename, $expected)
    {
        $url = new Uri($fullUrl);
        $url->removeBasename($basename);
        $this->assertEquals($expected, $url->uri());
    }



    /**
     * Check that methods that should return this returns this
     */
    public function testReturnThis()
    {
        $uri = new Uri("");
        $this->assertInstanceOf(Uri::class, $uri->prepend($uri));
        $this->assertInstanceOf(Uri::class, $uri->append($uri));
        $this->assertInstanceOf(Uri::class, $uri->removeBasename(""));
    }
}
