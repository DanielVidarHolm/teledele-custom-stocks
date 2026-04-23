<?php

namespace TeledeleCustomStocks;

defined('ABSPATH') || exit;

class Product {

	public function __construct() {
		$this->hooks();
	}

	private function hooks(): void {

		// Override WC stock html (single + places that use wc_get_stock_html)
		add_filter( 'woocommerce_get_stock_html', [ $this, 'override_stock_html' ], 20, 2 );

		// Archive fallback: print our stock line in the loop
		add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'print_archive_stock' ], 12 );

		// Add a class to archive product card if custom stock text exists
		add_filter( 'woocommerce_post_class', [ $this, 'add_custom_stock_class' ], 20, 2 );
	}

	public function override_stock_html( string $html, $product ): string {
		if ( ! $product instanceof \WC_Product ) {
			return $html;
		}

		$custom = '';

		if ($product->get_meta( '_custom_stock_information_category' )) {
			echo "<script>console.log('custom stock info found" . $product->get_meta("_custom_stock_information_category") . " ') </script>";
			$custom = trim( (string) $product->get_meta( '_custom_stock_information_category' ) );
		}

		if ($product->get_meta( '_custom_stock_information' )) {
			echo "<script>console.log('custom stock info found" . $product->get_meta("_custom_stock_information") . " ') </script>";
			$custom = trim( (string) $product->get_meta( '_custom_stock_information' ) );
		}

		if ( $custom === '' ) {
			return $html;
		}

		$status_class = $product->is_in_stock() ? 'in-stock' : 'out-of-stock';

		return sprintf(
			'<p class="stock %s woo-custom-stock-status wd-style-default tcs-stock">%s</p>',
			esc_attr( $status_class ),
			esc_html( $custom )
		);
	}

	public function print_archive_stock(): void {
		if ( is_admin() ) {
			return;
		}

		if ( ! ( is_shop() || is_product_category() || is_product_tag() ) ) {
			return;
		}

		global $product;

		if ( ! $product instanceof \WC_Product ) {
			return;
		}

		$custom = trim( (string) $product->get_meta( '_custom_stock_information' ) );

		// Only output extra stock line when custom text exists
		if ( $custom === '' ) {
			return;
		}

		echo wc_get_stock_html( $product );
	}

	public function add_custom_stock_class( array $classes, $product ): array {
		if ( ! $product instanceof \WC_Product ) {
			return $classes;
		}

		$custom = trim( (string) $product->get_meta( '_custom_stock_information' ) );

		if ( $custom !== '' ) {
			$classes[] = 'tcs-has-custom-stock';
		}

		return $classes;
	}
}
