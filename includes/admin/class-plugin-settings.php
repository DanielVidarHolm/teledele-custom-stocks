<?php

namespace TeledeleCustomStocks\Admin;

defined("ABSPATH") || exit;
class ClassPluginSettings{
	public function __construct(){
		$this->hooks();
	}

	private function hooks(){

	}

	public function add_menu(){
		add_options_page(
			'Teledele Custom Stocks Settings',
			'Teledele Custom Stocks',
			'manage_options',
			'tcs-settings',
			$this->render_page()
		);
	}

	public function render_page(){
		?>

		<div class="wrap">
			<form method="post" action="options.php">

				<?php
				settings_fields('tcs_settings_group');
				do_settings_sections('tcs-settings');
				submit_button();
				?>

			</form>
		</div>

		<?php
	}

	public function register_settings(){
		register_settings('tcs_settings_group');

		add_settings_section(
			'tcs_main_section',
			'Main Settings',
			'',
			'tcs-settings'
		);


	}
}
