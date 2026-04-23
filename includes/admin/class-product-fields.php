<?php

namespace TeledeleCustomStocks\Admin;

defined('ABSPATH') || exit;

class ProductFields {

	public function __construct() {
		$this->hooks();
	}

	private function hooks() {
		add_action('woocommerce_product_options_inventory_product_data', array($this, 'add_field'),5);
		add_action('woocommerce_process_product_meta', array($this, 'save_field'), 10, 2);
	}

	public function add_field(){
		global $product_object;
		?>
		<div class="inventory_custom_stock_information options_group show_if_simple show_if_variable">
			<?php
			woocommerce_wp_text_input(
				array(
					'id' => '_custom_stock_information',
					'label' => __('Custom Stock Message','teledele_custom_stocks'),
					'description' => __('Information shown in store', 'teledele_custom_stocks'),
					'desc_tip' => true,
					'value' => $product_object->get_meta('_custom_stock_information')
				)
			);
			?>
		</div>
		<?php
	}

	public function save_field($post_id, $post){
		if(isset($_POST['_custom_stock_information'])){
			$product = wc_get_product(intval($post_id));
			$product->update_meta_data('_custom_stock_information', sanitize_text_field($_POST['_custom_stock_information']));
			$product->save_meta_data();
		}
	}
}
