<?php

class WP_Audit_Tool_Health_Check_WP_Cron
{
	public static function results($info)
	{
		$crons = self::get_cron_events();
		$fields[] = [
            'label' => __('Hook', 'wp-audit-tool'),
            'value' => __('Schedule / Next Run', 'wp-audit-tool'),
        ];
        foreach($crons as $cron) {
            $fields[] = [
                'label' => $cron['hook'],
                'value' => ($cron['schedule'] ?: 'n/a')  . ' / ' . date('Y-m-d H:i:s', $cron['time']),
                'debug' => $cron,
            ];
        }

		$info['wp-cron'] = [
			'label' => __('WordPress Cron', 'wp-audit-tool'),
			'fields' => $fields,
		];

		return $info;
	}

	protected static function get_cron_events()
	{
		$crons = _get_cron_array();
		$events = [];

		foreach ($crons as $time => $hooks) {
			foreach ($hooks as $hook => $hook_events) {
				foreach ($hook_events as $sig => $data) {
					$events[] = [
						'hook' => $hook,
						'time' => $time,
						'sig' => $sig,
						'args' => $data['args'],
						'schedule' => $data['schedule'],
						'interval' => isset($data['interval']) ? $data['interval'] : null,
					];
				}
			}
		}

		return $events;
	}
}
