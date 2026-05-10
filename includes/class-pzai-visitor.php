<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

class Visitor {
    const OPTION_KEY = 'pzai_visitor_settings';
    const LEADS_OPTION = 'pzai_visitor_leads';
    const UNSUB_OPTION = 'pzai_visitor_unsubscribed';
    const RESET_OPTION = 'pzai_visitor_reset_version';

    private $settings;

    public function __construct($settings) {
        $this->settings = $settings;
        add_action('rest_api_init', [$this, 'register_routes']);
        add_action('template_redirect', [$this, 'handle_unsubscribe']);
    }

    private function defaults() {
        return [
            'visitor_gate_enabled' => 1,
            'visitor_gate_days' => 30,
            'visitor_gate_recipient_email' => 'customerservice@priczone.com',
            'visitor_terms_page_id' => 0,
            'visitor_unsubscribe_page_id' => 0,
            'visitor_gate_thank_you_message' => 'Thank you for using our ASK AI at PricZone, enjoy.',
            'visitor_welcome_email_subject' => "Welcome to {site_name}'s Ask AI",
            'visitor_welcome_email_html' => '<p>Hi {first_name},</p><p>Welcome to {site_name} Ask AI.</p><p>You now have access to chat with our shopping assistant as a visitor.</p><p>{thank_you_message}</p><p><a href="{unsubscribe_url}">Unsubscribe from Ask AI emails</a></p>',
            'visitor_unsubscribe_email_subject' => 'Sorry to see you leave {site_name} Ask AI',
            'visitor_unsubscribe_email_html' => '<p>Hi {first_name},</p><p>Sorry to see you leave {site_name} Ask AI.</p><p>You have been unsubscribed from future Ask AI emails for {email}.</p>',
            'visitor_recaptcha_site_key' => '',
            'visitor_recaptcha_secret_key' => '',
        ];
    }

    private function cfg($key, $default = '') {
        $saved = get_option(self::OPTION_KEY, []);
        if (!is_array($saved)) $saved = [];
        $merged = wp_parse_args($saved, $this->defaults());
        $value = array_key_exists($key, $merged) ? $merged[$key] : $default;
        return is_string($value) ? wp_unslash($value) : $value;
    }

    private function remember_days() {
        return max(1, absint($this->cfg('visitor_gate_days', 30)));
    }

    private function option_array($key) {
        $value = get_option($key, []);
        return is_array($value) ? $value : [];
    }

    private function save_option_array($key, $value) {
        update_option($key, is_array($value) ? $value : [], false);
    }

    private function normalize_email($email) {
        return strtolower(trim((string) sanitize_email($email)));
    }

    private function lead_key($email) {
        return md5($this->normalize_email($email));
    }

    private function sign_email($email) {
        return hash_hmac('sha256', $this->normalize_email($email), wp_salt('auth') . '|pzai-visitor-unsubscribe');
    }

    private function b64url_encode($value) {
        return rtrim(strtr(base64_encode((string) $value), '+/', '-_'), '=');
    }

    private function b64url_decode($value) {
        $value = strtr((string) $value, '-_', '+/');
        $pad = strlen($value) % 4;
        if ($pad) $value .= str_repeat('=', 4 - $pad);
        return base64_decode($value);
    }

    private function unsubscribe_url($email) {
        return add_query_arg([
            'pzai_unsubscribe' => rawurlencode($this->b64url_encode($this->normalize_email($email))),
            'sig' => $this->sign_email($email),
        ], home_url('/'));
    }

    private function replace_tokens($html, $tokens) {
        $pairs = [];
        foreach ((array) $tokens as $key => $value) {
            $pairs['{' . $key . '}'] = (string) $value;
        }
        return strtr((string) $html, $pairs);
    }

    private function mail_html($to, $subject, $html) {
        $to = sanitize_email((string) $to);
        if (!$to || !is_email($to)) return false;

        $subject = wp_specialchars_decode((string) $subject);
        $html = (string) $html;

        if (function_exists('WC') && function_exists('wc_mail') && WC() && method_exists(WC(), 'mailer')) {
            $mailer = WC()->mailer();
            if ($mailer) {
                $heading = wp_strip_all_tags($subject);
                $wrapped = $html;
                if (method_exists($mailer, 'wrap_message')) {
                    $wrapped = $mailer->wrap_message($heading, $html);
                }
                if (method_exists($mailer, 'style_inline')) {
                    $wrapped = $mailer->style_inline($wrapped);
                }
                return wc_mail($to, $subject, $wrapped);
            }
        }

        return wp_mail($to, $subject, $html, ['Content-Type: text/html; charset=UTF-8']);
    }

    private function notification_recipients() {
        $recipients = ['customerservice@priczone.com'];
        $configured = sanitize_email((string) $this->cfg('visitor_gate_recipient_email', 'customerservice@priczone.com'));
        if ($configured && is_email($configured)) $recipients[] = $configured;
        $recipients = array_unique(array_filter(array_map('strtolower', $recipients)));
        return $recipients;
    }

    private function clear_visitor_cookies() {
        $params = [
            'expires' => time() - HOUR_IN_SECONDS,
            'path' => defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/',
            'domain' => defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '',
            'secure' => is_ssl(),
            'httponly' => false,
            'samesite' => 'Lax',
        ];
        @setcookie('pzai_visitor_gate', '', $params);
        @setcookie('pzai_visitor_email', '', $params);
    }

    private function is_recaptcha_enabled() {
        return (trim((string) $this->cfg('visitor_recaptcha_site_key', '')) !== '' && trim((string) $this->cfg('visitor_recaptcha_secret_key', '')) !== '');
    }

    private function verify_recaptcha($token) {
        if (!$this->is_recaptcha_enabled()) {
            return ['success' => true, 'message' => ''];
        }

        $token = sanitize_text_field((string) $token);
        if ($token === '') {
            return ['success' => false, 'message' => 'Please confirm the reCAPTCHA before submitting.'];
        }

        $secret = trim((string) $this->cfg('visitor_recaptcha_secret_key', ''));
        if ($secret === '') {
            return ['success' => false, 'message' => 'reCAPTCHA is not configured correctly.'];
        }

        $body = [
            'secret' => $secret,
            'response' => $token,
        ];
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $body['remoteip'] = sanitize_text_field((string) $_SERVER['REMOTE_ADDR']);
        }

        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'timeout' => 15,
            'body' => $body,
        ]);

        if (is_wp_error($response)) {
            return ['success' => false, 'message' => 'Could not verify reCAPTCHA right now. Please try again.'];
        }

        $json = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($json['success'])) {
            return ['success' => false, 'message' => 'Please confirm the reCAPTCHA before submitting.'];
        }

        return ['success' => true, 'message' => ''];
    }

    private function has_saved_lead($email) {
        $email = $this->normalize_email($email);
        if (!$email || !is_email($email)) return false;
        $leads = $this->option_array(self::LEADS_OPTION);
        return !empty($leads[$this->lead_key($email)]) && is_array($leads[$this->lead_key($email)]);
    }

    private function request_has_valid_access() {
        if (!(int) $this->cfg('visitor_gate_enabled', 1) || is_user_logged_in()) {
            return true;
        }

        $gate_cookie = sanitize_text_field((string) ($_COOKIE['pzai_visitor_gate'] ?? ''));
        $email_cookie = $this->normalize_email(rawurldecode((string) ($_COOKIE['pzai_visitor_email'] ?? '')));

        if ($gate_cookie !== '1' || !$email_cookie || !is_email($email_cookie)) {
            return false;
        }

        return $this->has_saved_lead($email_cookie);
    }

    public function register_routes() {
        register_rest_route('pzai/v1', '/visitor-lead', [
            'methods' => 'POST',
            'callback' => [$this, 'submit_lead'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('pzai/v1', '/visitor-status', [
            'methods' => 'GET',
            'callback' => [$this, 'visitor_status'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function visitor_status($request) {
        $allowed = $this->request_has_valid_access();
        $email_cookie = $this->normalize_email(rawurldecode((string) ($_COOKIE['pzai_visitor_email'] ?? '')));
        return new \WP_REST_Response([
            'ok' => true,
            'allowed' => $allowed,
            'gate_required' => !$allowed,
            'remember_days' => $this->remember_days(),
            'email' => $allowed && $email_cookie ? $email_cookie : '',
            'reset_version' => (string) get_option(self::RESET_OPTION, '0'),
        ], 200);
    }

    public function submit_lead($request) {
        if (!(int) $this->cfg('visitor_gate_enabled', 1)) {
            return new \WP_REST_Response(['ok' => true, 'message' => 'Visitor access form is disabled.'], 200);
        }

        $first_name = sanitize_text_field((string) $request->get_param('first_name'));
        $email = $this->normalize_email($request->get_param('email'));
        $consent = (int) !!$request->get_param('consent');
        $source_url = esc_url_raw((string) $request->get_param('source_url'));
        $session_id = sanitize_text_field((string) $request->get_param('session_id'));
        $recaptcha_token = sanitize_text_field((string) $request->get_param('recaptcha_token'));

        if ($first_name === '' || $email === '' || !is_email($email)) {
            return new \WP_REST_Response(['error' => 'missing_fields', 'message' => 'Please enter your first name and a valid email address.', 'reset_version' => (string) get_option(self::RESET_OPTION, '0')], 400);
        }
        if (!$consent) {
            return new \WP_REST_Response(['error' => 'consent_required', 'message' => 'Please agree to the PricZone terms before using Ask AI.', 'reset_version' => (string) get_option(self::RESET_OPTION, '0')], 400);
        }

        $recaptcha = $this->verify_recaptcha($recaptcha_token);
        if (empty($recaptcha['success'])) {
            return new \WP_REST_Response(['error' => 'recaptcha_failed', 'message' => $recaptcha['message'], 'reset_version' => (string) get_option(self::RESET_OPTION, '0')], 400);
        }

        $lead_key = $this->lead_key($email);
        $leads = $this->option_array(self::LEADS_OPTION);
        if (!empty($leads[$lead_key]) && is_array($leads[$lead_key])) {
            return new \WP_REST_Response([
                'error' => 'duplicate_email',
                'message' => 'That email is already saved in the Ask AI visitor directory. Please wait for an admin to delete it before submitting again.',
                'reset_version' => (string) get_option(self::RESET_OPTION, '0'),
            ], 409);
        }

        $remember_days = $this->remember_days();
        $thank_you = trim((string) $this->cfg('visitor_gate_thank_you_message', ''));
        if ($thank_you === '') $thank_you = 'Thank you for using our ASK AI at PricZone, enjoy.';

        $leads[$lead_key] = [
            'first_name' => $first_name,
            'email' => $email,
            'consent' => 1,
            'consent_at' => current_time('mysql'),
            'source_url' => $source_url,
            'session_id' => $session_id,
            'ip' => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field((string) $_SERVER['REMOTE_ADDR']) : '',
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field((string) $_SERVER['HTTP_USER_AGENT']) : '',
            'unsubscribed_at' => '',
        ];
        $this->save_option_array(self::LEADS_OPTION, $leads);

        $unsubs = $this->option_array(self::UNSUB_OPTION);
        if (isset($unsubs[$lead_key])) {
            unset($unsubs[$lead_key]);
            $this->save_option_array(self::UNSUB_OPTION, $unsubs);
        }

        $site_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
        $unsubscribe_url = $this->unsubscribe_url($email);
        $tokens = [
            'first_name' => esc_html($first_name),
            'email' => esc_html($email),
            'site_name' => esc_html($site_name),
            'unsubscribe_url' => esc_url($unsubscribe_url),
            'thank_you_message' => esc_html($thank_you),
            'source_url' => esc_url($source_url),
        ];

        $admin_html = '<p>A visitor submitted the PricZone Ask AI access form.</p>'
            . '<p><strong>First name:</strong> ' . esc_html($first_name) . '<br>'
            . '<strong>Email:</strong> ' . esc_html($email) . '<br>'
            . '<strong>Consent:</strong> Yes<br>'
            . '<strong>Source URL:</strong> ' . ($source_url ? '<a href="' . esc_url($source_url) . '">' . esc_html($source_url) . '</a>' : 'N/A') . '<br>'
            . '<strong>Session ID:</strong> ' . esc_html($session_id) . '</p>';

        foreach ($this->notification_recipients() as $recipient) {
            $this->mail_html($recipient, 'New PricZone Ask AI visitor access submission', $admin_html);
        }

        $welcome_subject = trim((string) $this->cfg('visitor_welcome_email_subject', ''));
        if ($welcome_subject === '') $welcome_subject = "Welcome to {site_name}'s Ask AI";
        $welcome_html = trim((string) $this->cfg('visitor_welcome_email_html', ''));
        if ($welcome_html === '') $welcome_html = '<p>Hi {first_name},</p><p>Welcome to {site_name} Ask AI.</p><p>You now have access to chat with our shopping assistant as a visitor.</p><p>{thank_you_message}</p><p><a href="{unsubscribe_url}">Unsubscribe from Ask AI emails</a></p>';
        $this->mail_html($email, $this->replace_tokens($welcome_subject, $tokens), $this->replace_tokens($welcome_html, $tokens));

        $params = [
            'expires' => time() + ($remember_days * DAY_IN_SECONDS),
            'path' => defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/',
            'domain' => defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '',
            'secure' => is_ssl(),
            'httponly' => false,
            'samesite' => 'Lax',
        ];
        @setcookie('pzai_visitor_gate', '1', $params);
        @setcookie('pzai_visitor_email', rawurlencode($email), $params);

        return new \WP_REST_Response([
            'ok' => true,
            'message' => $thank_you,
            'remember_days' => $remember_days,
            'reset_version' => (string) get_option(self::RESET_OPTION, '0'),
        ], 200);
    }

    public function handle_unsubscribe() {
        if (empty($_GET['pzai_unsubscribe']) || empty($_GET['sig'])) return;

        $encoded = sanitize_text_field((string) $_GET['pzai_unsubscribe']);
        $sig = sanitize_text_field((string) $_GET['sig']);
        $email = $this->normalize_email($this->b64url_decode(rawurldecode($encoded)));
        if ($email === '' || !hash_equals($this->sign_email($email), $sig)) {
            wp_safe_redirect(home_url('/'));
            exit;
        }

        $lead_key = $this->lead_key($email);
        $leads = $this->option_array(self::LEADS_OPTION);
        $first_name = 'there';
        if (!empty($leads[$lead_key]) && is_array($leads[$lead_key])) {
            $first_name = sanitize_text_field((string) ($leads[$lead_key]['first_name'] ?? 'there'));
            unset($leads[$lead_key]);
            $this->save_option_array(self::LEADS_OPTION, $leads);
        }

        $unsubs = $this->option_array(self::UNSUB_OPTION);
        if (isset($unsubs[$lead_key])) {
            unset($unsubs[$lead_key]);
            $this->save_option_array(self::UNSUB_OPTION, $unsubs);
        }

        $site_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
        $tokens = [
            'first_name' => esc_html($first_name),
            'email' => esc_html($email),
            'site_name' => esc_html($site_name),
            'unsubscribe_url' => '',
            'thank_you_message' => '',
            'source_url' => '',
        ];

        $admin_html = '<p>A visitor unsubscribed from the PricZone Ask AI visitor list.</p>'
            . '<p><strong>First name:</strong> ' . esc_html($first_name) . '<br>'
            . '<strong>Email:</strong> ' . esc_html($email) . '<br>'
            . '<strong>Unsubscribed at:</strong> ' . esc_html(current_time('mysql')) . '</p>'
            . '<p>The saved Ask AI visitor entry for this email was removed automatically, and the visitor can now submit the form again whenever needed.</p>';
        foreach ($this->notification_recipients() as $recipient) {
            $this->mail_html($recipient, 'PricZone Ask AI visitor unsubscribe request', $admin_html);
        }

        $goodbye_subject = trim((string) $this->cfg('visitor_unsubscribe_email_subject', ''));
        if ($goodbye_subject === '') $goodbye_subject = 'Sorry to see you leave {site_name} Ask AI';
        $goodbye_html = trim((string) $this->cfg('visitor_unsubscribe_email_html', ''));
        if ($goodbye_html === '') $goodbye_html = '<p>Hi {first_name},</p><p>Sorry to see you leave {site_name} Ask AI.</p><p>You have been unsubscribed from future Ask AI emails for {email}.</p>';
        $this->mail_html($email, $this->replace_tokens($goodbye_subject, $tokens), $this->replace_tokens($goodbye_html, $tokens));

        $page_id = absint($this->cfg('visitor_unsubscribe_page_id', 0));
        $redirect_url = $page_id ? get_permalink($page_id) : home_url('/');
        if (!$redirect_url) $redirect_url = home_url('/');
        $redirect_url = add_query_arg('pzai_unsubscribed', '1', $redirect_url);

        $this->clear_visitor_cookies();
        nocache_headers();
        echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Unsubscribing…</title></head><body>';
        echo '<script>'
            . 'try{localStorage.removeItem("pzai_visitor_gate_v1");localStorage.removeItem("pzai_visitor_gate_v2");localStorage.removeItem("pzai_visitor_access_v1");localStorage.removeItem("pzai_chat_state_v3");localStorage.setItem("pzai_visitor_gate_sync", String(Date.now()));}catch(e){};'
            . 'document.cookie="pzai_visitor_gate=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax";'
            . 'document.cookie="pzai_visitor_email=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax";'
            . 'document.cookie="pzai_session_id=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax";'
            . 'document.cookie="pzai_assistant_used=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax";'
            . 'document.cookie="pzai_last_query=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax";'
            . 'document.cookie="pzai_last_product_id=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax";'
            . 'document.cookie="pzai_last_product_name=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax";'
            . 'window.location.replace(' . wp_json_encode(esc_url_raw($redirect_url)) . ');'
            . '</script>';
        echo '<p>Redirecting…</p></body></html>';
        exit;
    }
}
