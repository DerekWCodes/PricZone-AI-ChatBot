<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

class Rest {
    private $engine;
    private $settings;

    public function __construct($engine, $settings) {
        $this->engine = $engine;
        $this->settings = $settings;
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('pzai/v1', '/chat', [
            'methods' => 'POST',
            'callback' => [$this, 'chat'],
            'permission_callback' => '__return_true',
        ]);
        register_rest_route('pzai/v1', '/event', [
            'methods' => 'POST',
            'callback' => [$this, 'event'],
            'permission_callback' => '__return_true',
        ]);
    }

    private function visitor_gate_required() {
        $settings = get_option('pzai_visitor_settings', []);
        if (!is_array($settings)) $settings = [];
        if (empty($settings['visitor_gate_enabled']) || is_user_logged_in()) return false;

        $gate_cookie = sanitize_text_field((string) ($_COOKIE['pzai_visitor_gate'] ?? ''));
        $email_cookie = strtolower(trim((string) sanitize_email(rawurldecode((string) ($_COOKIE['pzai_visitor_email'] ?? '')))));
        if ($gate_cookie !== '1' || $email_cookie === '' || !is_email($email_cookie)) return true;

        $leads = get_option('pzai_visitor_leads', []);
        if (!is_array($leads)) $leads = [];
        $lead_key = md5($email_cookie);
        return empty($leads[$lead_key]) || !is_array($leads[$lead_key]);
    }

    private function sanitize_page_context($context) {
        if (!is_array($context)) return [];
        $clean = [];
        $clean['product_id'] = isset($context['product_id']) ? absint($context['product_id']) : 0;
        foreach (['name','permalink','price_html','short_description','description','stock_status'] as $key) {
            $clean[$key] = isset($context[$key]) ? sanitize_text_field(wp_strip_all_tags((string) $context[$key], true)) : '';
        }
        $clean['categories'] = [];
        if (!empty($context['categories']) && is_array($context['categories'])) {
            foreach ($context['categories'] as $item) $clean['categories'][] = sanitize_text_field((string) $item);
        }
        $clean['attributes'] = [];
        if (!empty($context['attributes']) && is_array($context['attributes'])) {
            foreach ($context['attributes'] as $item) {
                if (!is_array($item)) continue;
                $clean['attributes'][] = [
                    'label' => sanitize_text_field(isset($item['label']) ? (string) $item['label'] : ''),
                    'value' => sanitize_text_field(isset($item['value']) ? (string) $item['value'] : ''),
                ];
            }
        }
        $clean['complementary_products'] = [];
        if (!empty($context['complementary_products']) && is_array($context['complementary_products'])) {
            foreach ($context['complementary_products'] as $item) {
                if (!is_array($item)) continue;
                $clean['complementary_products'][] = [
                    'id' => absint(isset($item['id']) ? $item['id'] : 0),
                    'name' => sanitize_text_field(isset($item['name']) ? (string) $item['name'] : ''),
                    'permalink' => esc_url_raw(isset($item['permalink']) ? (string) $item['permalink'] : ''),
                    'price_html' => sanitize_text_field(isset($item['price_html']) ? (string) $item['price_html'] : ''),
                    'image' => esc_url_raw(isset($item['image']) ? (string) $item['image'] : ''),
                ];
            }
        }
        return $clean;
    }

    public function chat($request) {
        if ($this->visitor_gate_required()) {
            return new \WP_REST_Response([
                'error' => 'visitor_gate_required',
                'message' => 'Please complete the visitor form before using Ask AI.',
                'reset_version' => (string) get_option('pzai_visitor_reset_version', '0'),
            ], 403);
        }
        $message = sanitize_text_field((string) $request->get_param('message'));
        if (!$message) return new \WP_REST_Response(['error' => 'Missing message'], 400);
        $page_context = $this->sanitize_page_context($request->get_param('page_context'));
        $session_id = sanitize_text_field((string) $request->get_param('session_id'));
        $suggestion_meta = $request->get_param('suggestion_meta');
        if (!is_array($suggestion_meta)) $suggestion_meta = [];
        $result = $this->engine->handle_query($message, $page_context, $suggestion_meta);
        Logger::add($message, $result['type'] ?? 'unknown', [
            'result_count' => is_array($result['products'] ?? null) ? count($result['products']) : 0,
            'top_suggestion' => is_array($result['suggestions'] ?? null) && !empty($result['suggestions'][0]) ? $result['suggestions'][0] : '',
            'session_id' => $session_id,
        ]);
        return new \WP_REST_Response($result, 200);
    }

    public function event($request) {
        $event_type = sanitize_text_field((string) $request->get_param('event_type'));
        if (!$event_type) return new \WP_REST_Response(['error' => 'Missing event_type'], 400);
        Logger::add_event($event_type, [
            'label' => sanitize_text_field((string) $request->get_param('label')),
            'product_id' => absint($request->get_param('product_id')),
            'session_id' => sanitize_text_field((string) $request->get_param('session_id')),
            'order_id' => absint($request->get_param('order_id')),
            'order_total' => (float) $request->get_param('order_total'),
            'query' => sanitize_text_field((string) $request->get_param('query')),
        ]);
        return new \WP_REST_Response(['ok' => true], 200);
    }
}
