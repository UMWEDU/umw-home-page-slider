<?php
/**
 * Define the UMW_Home_Slide class
 */
class UMW_Home_Slide {
	var $img = null;
	var $caption = null;
	var $link = null;
	
	function __construct( $img=array(), $caption=array(), $link=array() ) {
		$this->img = (object) array( 'src' => null, 'alt' => null );
		$this->caption = (object) array( 'title' => null, 'text' => null );
		$this->link = (object) array( 'url' => null );
		
		if ( is_array( $img ) )
			$this->img = (object)$img;
		if ( is_array( $caption ) )
			$this->caption = (object)$caption;
		if ( is_array( $link ) )
			$this->link = (object)$link;
		
		$this->caption->text = strip_tags( apply_filters( 'the_content', $this->caption->text ) );
		
		if ( str_word_count( $this->caption->text ) > 25 ) {
			$tmp = explode( ' ', $this->caption->text );
			$tmp = implode( ' ', array_slice( $tmp, 0, 24 ) );
			$this->caption->text = $tmp . '&hellip;';
		}
	}
}

