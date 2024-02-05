<?php

class WP_Audit_Tool_Health_Check_Sys_Cron
{
	public static function results($info)
	{
		$fields = self::get_cron_fields();
		$info['sys-cron'] = [
			'label' => __('System Cron', 'wp-audit-tool'),
			'fields' => $fields,
		];

		return $info;
	}

	protected static function get_cron_fields()
	{
		if (!function_exists('exec')) {
			return [
				[
					'label' => __('Unable to exec to fetch crontab on this server', 'wp-audit-tool'),
				]
			];
		}

		try {
			exec('crontab -l', $crontab);
		} catch (Exception $e) {
			return [
				[
					'label' => __('Unable to exec to fetch crontab on this server', 'wp-audit-tool'),
				]
			];
		}

		if (empty($crontab)) {
			return [
				[
					'label' => __('No syscron detected', 'wp-audit-tool'),
				]
			];
		}

		$fields = [];
		$fields[] = [
			'label' => __('Cron Expression', 'wp-audit-tool'),
			'value' => __('Command', 'wp-audit-tool'),
		];

		foreach ($crontab as $line) {
			if ($line[0] === '#') {
				continue;
			}

			preg_match("/(@(annually|yearly|monthly|weekly|daily|hourly|reboot))|(@every (\d+(ns|us|µs|ms|s|m|h))+)|((((\d+,)+\d+|(\d+(\/|-)\d+)|\d+|\*) ?){5,7})/", $line, $matches);
			$time = $matches[0] ?? '';
			$task = str_replace($time, '', $line);

			$fields[] = [
				'label' => $time,
				'value' => $task,
				'debug' => $line,
			];
		}

		return $fields;
	}
}
