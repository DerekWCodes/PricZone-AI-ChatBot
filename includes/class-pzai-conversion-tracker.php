<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

class Conversion_Tracker {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_data'], 30);
        add_action('woocommerce_checkout_create_order', [$this, 'attach_order_meta'], 20, 2);
        add_action('woocommerce_thankyou', [$this, 'log_order_conversion'], 20, 1);
        add_action('wp_footer', [$this, 'output_clarity_purchase_event'], 40);
    }

    public function enqueue_frontend_data() {
        if (!wp_script_is('pzai-chat', 'registered') && !wp_script_is('pzai-chat', 'enqueued')) return;
        wp_localize_script('pzai-chat', 'pzaiConversionData', [
            'enabled' => true,
            'ajaxAddToCartSelector' => '.add_to_cart_button, .single_add_to_cart_button',
        ]);
    }

    private function read_cookie($key) {
        return isset($_COOKIE[$key]) ? sanitize_text_field(wp_unslash((string) $_COOKIE[$key])) : '';
    }

    public function attach_order_meta($order, $data) {
        if (!$order || !is_a($order, 'WC_Order')) return;
        $session_id = $this->read_cookie('pzai_session_id');
        if ($session_id === '') return;
        $last_query = $this->read_cookie('pzai_last_query');
        $last_product_id = absint($this->read_cookie('pzai_last_product_id'));
        $last_product_name = $this->read_cookie('pzai_last_product_name');
        $order->update_meta_data('_pzai_assisted', 'yes');
        $order->update_meta_data('_pzai_session_id', $session_id);
        if ($last_query !== '') $order->update_meta_data('_pzai_last_chat_query', $last_query);
        if ($last_product_id > 0) $order->update_meta_data('_pzai_last_clicked_product_id', $last_product_id);
        if ($last_product_name !== '') $order->update_meta_data('_pzai_last_clicked_product_name', $last_product_name);
    }

    public function log_order_conversion($order_id) {
        if (!$order_id || !function_exists('wc_get_order')) return;
        $order = wc_get_order($order_id);
        if (!$order) return;
        if ($order->get_meta('_pzai_conversion_logged') === 'yes') return;
        if ($order->get_meta('_pzai_assisted') !== 'yes') return;
        Logger::add_event('order_completed_after_chat', [
            'label' => 'Order #' . $order->get_order_number(),
            'session_id' => (string) $order->get_meta('_pzai_session_id'),
            'order_id' => $order->get_id(),
            'order_total' => (float) $order->get_total(),
            'query' => (string) $order->get_meta('_pzai_last_chat_query'),
            'product_id' => absint($order->get_meta('_pzai_last_clicked_product_id')),
        ]);
        $order->update_meta_data('_pzai_conversion_logged', 'yes');
        $order->save();
    }

    public function output_clarity_purchase_event() {
        if (!function_exists('is_order_received_page') || !is_order_received_page()) return;
        if (!function_exists('wc_get_order')) return;
        $order_id = absint(get_query_var('order-received'));
        if ($order_id <= 0 && isset($_GET['order-received'])) $order_id = absint($_GET['order-received']);
        if ($order_id <= 0) return;
        $order = wc_get_order($order_id);
        if (!$order) return;
        if ($order->get_meta('_pzai_assisted') !== 'yes') return;
        echo "<script>(function(){try{if(window.clarity){window.clarity('event','pzai_order_after_chat');}}catch(e){}})();</script>";
    }
}
