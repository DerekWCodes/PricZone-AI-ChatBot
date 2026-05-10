<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

class Widget {
    private $settings;

    public function __construct($settings) {
        $this->settings = $settings;
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        add_action('wp_footer', [$this, 'render']);
        add_filter('body_class', [$this, 'body_class']);
    }

    public function should_render() {
        if (!(int) $this->settings->get('enabled')) return false;

        if (function_exists('is_product') && is_product()) {
            return (int) $this->settings->get('show_on_product') === 1;
        }

        if ((function_exists('is_shop') && is_shop()) || (function_exists('is_product_taxonomy') && is_product_taxonomy())) {
            return (int) $this->settings->get('show_on_shop') === 1;
        }

        if (function_exists('is_cart') && is_cart()) {
            return (int) $this->settings->get('show_on_cart') === 1;
        }

        return false;
    }

    public function body_class($classes) {
        if ((int) $this->settings->get('hide_on_mobile')) $classes[] = 'pzai-hide-mobile';
        return $classes;
    }

    private function clean_text($text) {
        $text = wp_strip_all_tags((string) $text, true);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim((string) $text);
    }

    private function get_product_context() {
        if (!function_exists('is_product') || !is_product()) return [];
        if (!function_exists('wc_get_product')) return [];
        $product = wc_get_product(get_the_ID());
        if (!$product) return [];

        $attributes = [];
        foreach ($product->get_attributes() as $attribute) {
            $label = wc_attribute_label($attribute->get_name());
            if ($attribute->is_taxonomy()) {
                $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
                $value = implode(', ', array_filter(array_map('trim', (array) $values)));
            } else {
                $value = implode(', ', array_filter(array_map('trim', (array) $attribute->get_options())));
            }
            if ($label && $value) $attributes[] = ['label' => $label, 'value' => $value];
        }

        $categories = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
        $complementary = [];
        $related_ids = function_exists('wc_get_related_products') ? wc_get_related_products($product->get_id(), 3) : [];
        foreach ((array) $related_ids as $related_id) {
            $related = wc_get_product($related_id);
            if (!$related) continue;
            $image = wp_get_attachment_image_url($related->get_image_id(), 'woocommerce_thumbnail');
            $complementary[] = [
                'id' => $related->get_id(),
                'name' => $related->get_name(),
                'permalink' => get_permalink($related->get_id()),
                'price_html' => html_entity_decode(wp_strip_all_tags($related->get_price_html(), true)),
                'image' => $image ? $image : wc_placeholder_img_src(),
            ];
        }

        return [
            'product_id' => $product->get_id(),
            'name' => $product->get_name(),
            'permalink' => get_permalink($product->get_id()),
            'price_html' => html_entity_decode(wp_strip_all_tags($product->get_price_html(), true)),
            'short_description' => $this->clean_text($product->get_short_description()),
            'description' => $this->clean_text($product->get_description()),
            'categories' => array_values(array_filter(array_map('strval', (array) $categories))),
            'attributes' => $attributes,
            'stock_status' => $product->get_stock_status(),
            'complementary_products' => $complementary,
        ];
    }

    public function enqueue() {
        if (!$this->should_render()) return;

        wp_enqueue_style('pzai-chat', PZAI_PLUGIN_URL . 'assets/chat.css', [], PZAI_VERSION);
        wp_enqueue_script('pzai-chat', PZAI_PLUGIN_URL . 'assets/chat.js', [], PZAI_VERSION, true);

        $visitor_settings = get_option('pzai_visitor_settings', []);
        if (!is_array($visitor_settings)) $visitor_settings = [];

        $visitor_terms_page_id = absint($visitor_settings['visitor_terms_page_id'] ?? 0);
        $visitor_terms_url = $visitor_terms_page_id ? get_permalink($visitor_terms_page_id) : '';
        $recaptcha_site_key = sanitize_text_field((string) ($visitor_settings['visitor_recaptcha_site_key'] ?? ''));
        $recaptcha_secret_key = sanitize_text_field((string) ($visitor_settings['visitor_recaptcha_secret_key'] ?? ''));
        $recaptcha_enabled = ($recaptcha_site_key !== '' && $recaptcha_secret_key !== '');

        if (!is_user_logged_in() && !empty($visitor_settings['visitor_gate_enabled']) && $recaptcha_enabled) {
            wp_enqueue_script('google-recaptcha-v2', 'https://www.google.com/recaptcha/api.js', [], null, true);
        }

        wp_localize_script('pzai-chat', 'pzaiData', [
            'endpoint' => esc_url_raw(rest_url('pzai/v1/chat')),
            'title' => $this->settings->get('widget_title'),
            'welcome' => $this->settings->get('welcome_message'),
            'primary' => $this->settings->get('brand_primary'),
            'dark' => $this->settings->get('brand_dark'),
            'light' => $this->settings->get('brand_light'),
            'shape' => 'round',
            'hideOnMobile' => (int) $this->settings->get('hide_on_mobile'),
            'productLinkTarget' => '_self',
            'pageContext' => $this->get_product_context(),
            'visitorLeadEndpoint' => esc_url_raw(rest_url('pzai/v1/visitor-lead')),
            'visitorStatusEndpoint' => esc_url_raw(rest_url('pzai/v1/visitor-status')),
            'visitorGateEnabled' => (int) ($visitor_settings['visitor_gate_enabled'] ?? 1),
            'visitorRememberDays' => max(1, absint($visitor_settings['visitor_gate_days'] ?? 30)),
            'visitorThankYouMessage' => (string) ($visitor_settings['visitor_gate_thank_you_message'] ?? 'Thank you for using our ASK AI at PricZone, enjoy.'),
            'visitorTermsUrl' => $visitor_terms_url,
            'isLoggedIn' => is_user_logged_in() ? 1 : 0,
            'restNonce' => wp_create_nonce('wp_rest'),
            'recaptchaEnabled' => $recaptcha_enabled ? 1 : 0,
            'visitorResetVersion' => (string) get_option('pzai_visitor_reset_version', '0'),
        ]);
    }

    public function render() {
        if (!$this->should_render()) return;
        include PZAI_PLUGIN_PATH . 'templates/widget.php';
    }
}
