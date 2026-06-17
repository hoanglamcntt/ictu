<?php defined( 'ABSPATH' ) || exit;

use \Elementor\Controls_Manager as Controls_Manager;

class Elementor_Ovic_Documentsupdate extends Ovic_Widget_Elementor {

	public function get_name() {
		return 'ovic_documentsupdate';
	}

	public function get_title() {
		return esc_html__( 'Ovic Document List Update', 'ictu' );
	}

	public function get_icon() {
		return ' eicon-check-circle';
	}

	public function get_categories() {
		return array( 'ovic' );
	}

	protected function _register_controls() {

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo ovic_do_shortcode( $this->get_name(), $settings );
	}

}