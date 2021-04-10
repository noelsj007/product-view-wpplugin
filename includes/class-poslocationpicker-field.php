<?php
/**
 * This class defines new "phone" field type for Meta Box class
 * 
 * @author Tran Ngoc Tuan Anh <rilwis@gmail.com>
 * @package Meta Box
 * @see http://metabox.io/?post_type=docs&p=390
 */
if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Poslocationpicker_Field extends RWMB_Field
	{
		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 *
		 * @return string
		 */
		static public function html( $meta, $field )
		{		
	        
	        //Display the map
	        $output = '<input type="text" name="%s" id="pos_search_address" class="controls" placeholder="' . __('Pesquisar endereço...','points-of-sale') . '">';
	        
	        $output .= '<div id="pos_locationpicker"></div>';
	        
	        return sprintf( $output, $field['field_name'],	$meta );

	    }
	}
}