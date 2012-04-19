<?php
/**
 * Class definition for LBCB_Kuler_List_Table
 * Extends core WP_List_Table to allow for easy, pretty display of 
 * Kulers downloaded from Adobe.
 *
 * @package		LB-Colorbox
 * @copyright	Copyright (c) 2012, Doug Stewart
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		LB-Colorbox 0.5
 */
class LBCB_Kuler_List_Table extends WP_List_Table {
	
	protected $kuler_type;
	
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct( array(
			'singular'	=> 'kuler',
			'plural'	=> 'kulers',
			'ajax'		=> true
		));
	}
	
	/**
	 * Handles the output of the Title column
	 * 
	 * @param array $item
	 * @return string Title of the selected Kuler
	 */
	function column_title( $item ){
		$actions = array(
			'save'	=> sprintf('<a href="?page=%s&post_type=colorbox&action=%s&kuler=%s&kuler_type=%s">Save</a>',
								$_REQUEST['page'],
								'savekuler',
								urlencode($item['url']),
								$this->kuler_type)
		);
		
		return sprintf('%1$s %2$s',
            /*$1%s*/ $item['title'],
            /*$3%s*/ $this->row_actions($actions)
		);
	}
	
	/**
	 * Handles the output of the Author column
	 *
	 * @param array $item
	 * @return string Current Kuler's author's handle
	 */
	function column_author( $item ){
		return $item['author'];
	}
	
	/**
	 * Handles the output of the Link column
	 *
	 * @param array $item
	 * @return string make_clickable() version of Kuler URL
	 */
	function column_url( $item ){
		return make_clickable( $item['url'] );
	}
	
	/**
	 * Handles the output of the color swatches of the current Kuler
	 *
	 * @param array $item
	 * @return string DIV element containing inline-styled color swatches
	 */
	function column_swatches( $item ){
		$swatches = '<div class="lbcb-mini-swatch-wrapper">';
		for( $i = 1; $i<= 5; $i++ ){
			$swatches .= '<div class="lbcb-mini-swatch" style="background:' . $item['color'.$i] . '"></div>';
		}
		$swatches .= '</div></div>';
		
		return $swatches;
	}
	
	/**
	 * Returns the columns for list view display
	 *
	 * @return array Column headers
	 */
	function get_columns() {
		$columns = array(
			'title'		=> 'Title',
			'swatches'	=> 'Swatches',
			'author'	=> 'Author',
			'url'		=> 'Link',
		);
		
		return $columns;
	}
	
	/**
	 * Set certain column headers as JS-sortable
	 *
	 * @return array Array of sortable columns
	 */
	function get_sortable_columns(){
		// $sortable_columns = array(
		// 			'title'		=> array( 'title', false ),
		// 			'author'	=> array( 'author', false )
		// 		);
		return $sortable_columns;
	}
	
	/**
	 * Prepare CPT results for display in list view
	 *
	 * @param array $criteria
	 */
	function prepare_items( $criteria ) {
		$per_page = 10;
		
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		
		$this->_column_headers = array( $columns, $hidden, $sortable );
		
		$data = lbcb_get_kulers( $criteria );
		
		$current_page = $this->get_pagenum();
		
		$total_items = count($data);
		
		$data = array_slice($data,(($current_page-1)*$per_page),$per_page);
		
		$this->items = $data;
		
		$this->set_pagination_args( array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
			'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
		) );
	}
	
	/**
	 * Accessor function to set Kuler type
	 *
	 * @param string $k_type The type of kuler we're currently dealing with
	 */
	function set_kuler_type( $k_type ){
		$this->kuler_type = $k_type;
	}
}