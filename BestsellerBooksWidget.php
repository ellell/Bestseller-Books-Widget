<?php
/**
* @package Bestseller Books Widget
* @version 1.0
 */
/*
Plugin Name: Bestseller Books Widget
Description: This plugin is a widget that displays toplist from either bokus.com or amazon.com
Author: Marcus Olsson & Lisa Övermyr
Version: 1.0
*/

include_once ('book/BookList.php');
add_action( 'widgets_init', create_function( '', 'register_widget("BestsellerBooks_Widget");' ) );
class BestsellerBooks_Widget extends WP_Widget {
	function BestsellerBooks_Widget(){
		// Set widget title
		parent::WP_Widget( false, 'Bestseller Books Widget' );
	}
	function widget($args, $instance) {
		extract( $args );
		// Get options set from adminpanel
		$title = apply_filters( 'widget_title', $instance['title'] );
		$bookstore = apply_filters( 'widget_bookstore', $instance['bookstore'] );
		echo $before_widget;
		if ( $title ){
			echo $before_title . $title . $after_title;
		}	
		$bl = new BookList();
		// Get and display toplist from selected bookstore
		$toplist = $bl->createBooks($bookstore);
		echo "<ul>";
		foreach ($toplist as $book) {
			echo "<li><strong>".utf8_encode($book->getTitle())." </strong>";
			echo utf8_encode($book->getAuthor())."</li>";
		}
		echo "</ul>";
		echo $after_widget;
	}
	// Handle updates from adminpanel
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['bookstore'] = strip_tags($new_instance['bookstore']);
		return $instance;
	}
	// Add options form to adminpanel, options include setting title and choosing which toplist to display
	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			<input type="radio" name="<?php echo $this->get_field_name( 'bookstore' ); ?>" value="Bokus" <?php if ( 'Bokus' == $instance['bookstore'] ) echo 'checked="checked"'; ?> />Bokus<br/>
			<input type="radio" name="<?php echo $this->get_field_name( 'bookstore' ); ?>" value="Amazon" <?php if ( 'Amazon' == $instance['bookstore'] ) echo 'checked="checked"'; ?> />Amazon<br/>
		</p>
		<?php 
	}
}
?>
