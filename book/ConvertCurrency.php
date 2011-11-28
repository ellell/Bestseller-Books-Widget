<?php
/**
 * class ConvertCurrency
 *
 * @author Pontus Karlsson och Tomas Karlsson
 * @version 1.0
 * @category book
 * @package Book
 */
/**
 * Money money money
 * 
 * This class is used for converting currency. Here is an example:
 * <code>
 * $convert = new ConvertCurrency();
 * $convert->currencyConverter("USD", "SEK", "8")</code>  
 * //8 USD is
 * converted with the current course exchange to 
 * Swedish kronor 
 * @author Pontus Karlsson och Tomas Karlsson
 * @version 1.0
 * @category book
 * @package Book
 */
class ConvertCurrency {
	/**
     * Start a session
     */
	public function __construct() {
       session_start();
	}
    /**
     * Convert all types of currency
     * @param string $from
     * @param string $to
     * @param string $value
     * @return string convertedValue
	 * @access public
     */
    public function currencyConverter($from, $to, $value) {
        if(!isset($_SESSION[$from . $to])) {
			$url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s=' . $from . $to . '=X';
			$handle = @fopen($url, 'r');
			if ($handle) {
				$result = fgets($handle, 4096);
				fclose($handle);
			}
			$allData = explode(',', $result);
			$_SESSION[$from . $to] = $allData[1];	
        }
        return round($_SESSION[$from . $to] * $value);
    }
}
?>