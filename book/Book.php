<?php
/**
 * class Book
 *
 * @author Pontus Karlsson och Tomas Karlsson
 * @copyright  2011 The Authors
 * @version 1.0
 * @category book
 * @package Book
 */
 
 /**
 * Create Book objects
 * 
 * @author Pontus Karlsson och Tomas Karlsson
 * @copyright  2011 The Authors
 * @version 1.0
 * @category book
 * @package Book
 */
class Book {
    /**
     * @var string
     * @access private
     */
	private $author;
	/**
     * @var string
     * @access private
     */
    private $title;
	/**
     * @var string
     * @access private
     */
    private $price;
	
	/**
     * Set private var author, title and price
     *
     */
    function __construct($author_in, $title_in, $price_in) {
        $this->author = $author_in;
        $this->title = $title_in;
        $this->price = $price_in;
    }
    /**
     * Get author from a Book object
     *
     * @return string Author
	 * @access public
     */
    function getAuthor() {
        return $this->author;
    }
    /**
     * Get title from a Book object
     *
     * @return string Title
	 * @access public
     */
    function getTitle() {
        return $this->title;
    }
    /**
     * Get price from a Book object
     *
     * @return string Price
	 * @access public
     */
    function getPrice() {
        return $this->price;
    }
}
?>  