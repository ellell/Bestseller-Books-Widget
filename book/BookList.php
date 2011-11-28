<?php
/**
 * class BookList
 *
 * @author Pontus Karlsson och Tomas Karlsson
 * @version 1.0
 * @category book
 * @package Book
 */
/**
 * @see Set include path to pear
 */
define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
ini_set('include_path', WP_PLUGIN_DIR.'/bestsellerBooksWidget/pear/');
/**
 * @see book_Book.php
 */
include_once ('Book.php');
/**
 * @see pear_Cache_Lite.php
 */
require_once ('Cache/Lite.php');
/**
 * @see book_simple_html_dom.php
 */
include_once ('simple_html_dom.php');
/**
 * Scrape it!
 *
 * This class scrape Amazon or Bokus after the toplist and
 * creates and return an Array with Book objects.
 * Pear Cache Lite is used to cache the result for 2 hours.
 * Here is an example:
 * <code>
 * include_once ('BookList.php');//This is the only file you need to include
 * $toplist = $bl->createBooks("Amazon"); //alternitive use "Bokus" as @param
 * foreach ($toplist as $book) {
 *     echo $book->getTitle(); //see class Book for more info
 * }
 * </code>
 *
 * @author Pontus Karlsson och Tomas Karlsson
 * @version 1.0
 * @category book
 * @package Book
 */
class BookList {
    /**
     * Create a Pear Cache Lite object with options and a lifecycle of 2 hours
     *
     * @return Cache_Lite
     * @access private
     */
    private function createCacheLiteObject() {
        $options = array('cacheDir' => dirname(__FILE__) . "\\tmp\\", //must include trailing slash-
        'lifeTime' => 7200);
        return new Cache_Lite($options);
    }
    /**
     * Scrape Bokus url for toplist.
     *
     * @return array $articles
     * @access private
     */
    private function getToplistBokus() {
        // name a cache key
        $cache_id = 'bokus';
        //create cache lite object
        $cache = $this->createCacheLiteObject();
        $url = 'http://www.bokus.com/topplistor/bokustoppen';
        $headers = get_headers($url, 1);
        if ($headers[0] == 'HTTP/1.1 200 OK') {
            $html = file_get_html($url);
            if ($html->find('div.hitrow')) {
                 if ($articles = $cache->get($cache_id)) {
                                     //Return cached data
                                     return unserialize($articles);
                                 } else {
                    foreach ($html->find('div.hitrow') as $article) {
                        $item['author'] = $article->find('h4.author', 0)->plaintext;
                        $item['title'] = $article->find('h3.title', 0)->plaintext;
                        $item['price'] = $article->find('.rbox span', 0)->plaintext;
                        $articles[] = $item;
                    }
                    $cache->save(serialize($articles));
                    return $articles;
                }
            }
        }
        return false;
    }
    /**
     * Scrape Amazon url for toplist.
     *
     * @return array $articles
     * @access private
     */
    private function getToplistAmazon() {
        // name a cache key
        $cache_id = 'amazon';
        //create cache lite object
        $cache = $this->createCacheLiteObject();
        $url = 'http://www.amazon.com/best-sellers-books-Amazon/zgbs/books';
        $headers = get_headers($url, 1);
        if ($headers[0] == 'HTTP/1.1 200 OK') {
            $html = file_get_html($url);
            if ($html->find('div.zg_itemRow')) {
                if ($articles = $cache->get($cache_id)) {
                    //Return cached data
                    return unserialize($articles);
                } else {
                    foreach ($html->find('div.zg_itemRow') as $article) {
                        $item['author'] = $article->find('div.zg_byline', 0)->plaintext;
                        $item['title'] = $article->find('div.zg_title', 0)->plaintext;
                        $item['price'] = $article->find('span.price', 0)->plaintext;
                        $articles[] = $item;
                    }
                    $cache->save(serialize($articles));
                    return $articles;
                }
            }
            return false;
        }
    }
    /**
     * Takes one argument string ("Amazon" or "Bokus")
     *
     * @param  string $website
     * @return array Book objects
     * @access public
     */
    public function createBooks($website) {
        $array = array();
        if ($website == "Amazon") $b = $this->getToplistAmazon();
        else $b = $this->getToplistBokus();
        foreach ($b as $book) {
            array_push($array, new Book($book["author"], $book["title"], $book["price"]));
        }
        return $array;
    }
}
?>