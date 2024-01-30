<?php

class WP_Audit_Tool_Health_Check_ACF
{
	public static function results($info)
	{
		if (! function_exists('acf_get_store')) {
			$fields = [
				[
					'label' => '',
					'value' => __('ACF is not installed', 'wp-audi-tool'),
				]
			];
		} else {
			$fields = [
				[
					'label' => __('Number of ACF Field Groups', 'wp-audi-tool'),
					'value' => ! function_exists('acf_get_store') ? __('ACF is not installed', 'wp-audi-tool') : self::get_acf_field_group_count(),
					'debug' => self::get_acf_field_group_count(),
				],
				[
					'label' => __('Number of ACF Fields', 'wp-audi-tool'),
					'value' => ! function_exists('acf_get_store') ? __('ACF is not installed', 'wp-audi-tool') : self::get_acf_fields_count(),
					'debug' => self::get_acf_fields_count(),
				],
			];
		}

		$info['acf'] = [
			'label' => __('Advanced Custom Fields', 'wp-audit-tool'),
			'fields' => $fields,
		];

		return $info;
	}

	public static function get_acf_field_group_count() {
		if (!function_exists('acf_get_store')) {
			return null;
		} 

		return acf_get_store( 'fields' )->count() + acf_get_local_store('fields')->count();
	}

	public static function get_acf_fields_count() {
		if (!function_exists('acf_get_store')) {
			return null;
		} 

		return acf_get_store( 'field-groups' )->count();
	}
}
