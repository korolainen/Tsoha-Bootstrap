<?php
class CssClass{

	public static function visibility(){
		$visibility = array('add' => 'add-toggle-block',
				'add_product' => 'add-toggle-hidden',
				'list_products' => 'edit-toggle-block',
				'edit' => 'edit-toggle-hidden',
				'editbutton' => true,
				'is_add' => false);
		if(array_key_exists('edit', $_GET)){
			$visibility = array('add' => 'add-toggle-block',
					'add_product' => 'add-toggle-hidden',
					'list_products' => 'edit-toggle-hidden',
					'edit' => 'edit-toggle-block',
					'editbutton' => true,
					'is_add' => false);
		}else if(array_key_exists('add', $_GET)){
			$visibility = array('add' => 'add-toggle-hidden',
					'add_product' => 'add-toggle-block',
					'list_products' => 'edit-toggle-block',
					'edit' => 'edit-toggle-hidden',
					'editbutton' => false,
					'is_add' => true);
		}
		return $visibility;
	}
	
}