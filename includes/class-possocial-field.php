<?php
/**
 * This class defines new "location picker" field type for Meta Box class
 * 
 * @author Tran Ngoc Tuan Anh <rilwis@gmail.com>
 * @package Meta Box
 * @see http://metabox.io/?post_type=docs&p=390
 */
if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Possocial_Field extends RWMB_Field
	{

		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 *
		 * @return string
		 */
		static function html( $meta, $field )
		{
			return sprintf(
				'%s <input type="text" class="rwmb-text" name="%s" id="%s" value="%s" placeholder="%s" size="%s" %s/> %s',				
				self::pos_fonticonpicker_html( $field ),
				$field['field_name'],
				$field['id'],
				$meta,
				$field['placeholder'],
				$field['size'],
				$field['datalist'] ? "list='{$field['datalist']['id']}'" : '',
				self::datalist_html( $field )			
			);
		}

		/**
		 * Normalize parameters for field
		 *
		 * @param array $field
		 *
		 * @return array
		 */
		static function normalize_field( $field )
		{
			$field = wp_parse_args( $field, array(
				'size'        => 30,
				'datalist'    => false,
				'placeholder' => '',
				'icon' => '',
			) );

			return $field;
		}

		/**
		 * Create datalist, if any
		 *
		 * @param array $field
		 *
		 * @return array
		 */
		static function datalist_html( $field )
		{
			if ( ! $field['datalist'] )
				return '';

			$datalist = $field['datalist'];
			$html     = sprintf(
				'<datalist id="%s">',
				$datalist['id']
			);

			foreach ( $datalist['options'] as $option )
			{
				$html .= sprintf( '<option value="%s"></option>', $option );
			}

			$html .= '</datalist>';

			return $html;
		}


		/**
		 * ICON
		 *
		 * @param array $field
		 *
		 * @return array
		 */
		static function pos_fonticonpicker_html( $field )
		{

			$html = '<input type="text" class="pos_fonticonpicker" />';		

			return $html;
		}
	}
}