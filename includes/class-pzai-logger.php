<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

class Logger {
    const OPTION_KEY = 'pzai_query_logs';
    const EVENT_OPTION_KEY = 'pzai_event_logs';

    public function __construct() {}

    public static function add($query, $response_type = 'unknown', $meta = []) {
        $query = sanitize_text_field((string) $query);
        $response_type = sanitize_text_field((string) $response_type);
        if ($query === '') return;
        $logs = get_option(self::OPTION_KEY, []);
        if (!is_array($logs)) $logs = [];
        $logs[] = [
            'time' => current_time('mysql'),
            'query' => $query,
            'response_type' => $response_type,
            'result_count' => isset($meta['result_count']) ? absint($meta['result_count']) : 0,
            'top_suggestion' => sanitize_text_field((string) ($meta['top_suggestion'] ?? '')),
            'session_id' => sanitize_text_field((string) ($meta['session_id'] ?? '')),
        ];
        if (count($logs) > 1000) $logs = array_slice($logs, -1000);
        update_option(self::OPTION_KEY, array_values($logs), false);
    }

    public static function add_event($event_type, $meta = []) {
        $event_type = sanitize_text_field((string) $event_type);
        if ($event_type === '') return;
        $events = get_option(self::EVENT_OPTION_KEY, []);
        if (!is_array($events)) $events = [];
        $events[] = [
            'time' => current_time('mysql'),
            'event_type' => $event_type,
            'label' => sanitize_text_field((string) ($meta['label'] ?? '')),
            'product_id' => absint($meta['product_id'] ?? 0),
            'session_id' => sanitize_text_field((string) ($meta['session_id'] ?? '')),
            'order_id' => absint($meta['order_id'] ?? 0),
            'order_total' => isset($meta['order_total']) ? round((float) $meta['order_total'], 2) : 0,
            'query' => sanitize_text_field((string) ($meta['query'] ?? '')),
        ];
        if (count($events) > 2500) $events = array_slice($events, -2500);
        update_option(self::EVENT_OPTION_KEY, array_values($events), false);
    }

    public static function get_all() {
        $logs = get_option(self::OPTION_KEY, []);
        return is_array($logs) ? array_values($logs) : [];
    }

    public static function get_events() {
        $events = get_option(self::EVENT_OPTION_KEY, []);
        return is_array($events) ? array_values($events) : [];
    }

    public static function clear() {
        update_option(self::OPTION_KEY, [], false);
        update_option(self::EVENT_OPTION_KEY, [], false);
    }

    public static function summary() {
        $logs = self::get_all();
        $events = self::get_events();
        $summary = [
            'total_queries' => count($logs),
            'zero_result_queries' => 0,
            'avg_results' => 0,
            'response_types' => [],
            'top_queries' => [],
            'top_zero_result_queries' => [],
            'events' => [],
            'ai_assisted_add_to_carts' => 0,
            'ai_assisted_orders' => 0,
            'ai_assisted_revenue' => 0,
            'top_converting_queries' => [],
        ];
        $query_counts = [];
        $zero_counts = [];
        $conversion_queries = [];
        $result_total = 0;
        foreach ($logs as $row) {
            $query = trim((string) ($row['query'] ?? ''));
            $type = trim((string) ($row['response_type'] ?? 'unknown')) ?: 'unknown';
            $count = absint($row['result_count'] ?? 0);
            $summary['response_types'][$type] = ($summary['response_types'][$type] ?? 0) + 1;
            $result_total += $count;
            if ($query !== '') $query_counts[$query] = ($query_counts[$query] ?? 0) + 1;
            if ($count === 0) {
                $summary['zero_result_queries']++;
                if ($query !== '') $zero_counts[$query] = ($zero_counts[$query] ?? 0) + 1;
            }
        }
        if ($summary['total_queries'] > 0) $summary['avg_results'] = round($result_total / $summary['total_queries'], 2);
        foreach ($events as $row) {
            $type = trim((string) ($row['event_type'] ?? 'unknown')) ?: 'unknown';
            $summary['events'][$type] = ($summary['events'][$type] ?? 0) + 1;
            if ($type === 'added_to_cart_after_chat') $summary['ai_assisted_add_to_carts']++;
            if ($type === 'order_completed_after_chat') {
                $summary['ai_assisted_orders']++;
                $summary['ai_assisted_revenue'] += (float) ($row['order_total'] ?? 0);
                $query = trim((string) ($row['query'] ?? ''));
                if ($query !== '') $conversion_queries[$query] = ($conversion_queries[$query] ?? 0) + 1;
            }
        }
        arsort($query_counts);
        arsort($zero_counts);
        arsort($conversion_queries);
        arsort($summary['response_types']);
        arsort($summary['events']);
        $summary['top_queries'] = array_slice($query_counts, 0, 8, true);
        $summary['top_zero_result_queries'] = array_slice($zero_counts, 0, 8, true);
        $summary['top_converting_queries'] = array_slice($conversion_queries, 0, 8, true);
        $summary['ai_assisted_revenue'] = round((float) $summary['ai_assisted_revenue'], 2);
        return $summary;
    }

    public static function csv() {
        $rows = self::get_all();
        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, ['time', 'query', 'response_type', 'result_count', 'top_suggestion', 'session_id']);
        foreach ($rows as $row) {
            fputcsv($fh, [
                $row['time'] ?? '',
                $row['query'] ?? '',
                $row['response_type'] ?? '',
                $row['result_count'] ?? 0,
                $row['top_suggestion'] ?? '',
                $row['session_id'] ?? '',
            ]);
        }
        rewind($fh);
        return stream_get_contents($fh);
    }
}
