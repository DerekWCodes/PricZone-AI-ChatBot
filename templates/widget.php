<?php
$pzai_visitor_settings = get_option('pzai_visitor_settings', []);
if (!is_array($pzai_visitor_settings)) $pzai_visitor_settings = [];
$pzai_terms_page_id = absint($pzai_visitor_settings['visitor_terms_page_id'] ?? 0);
$pzai_terms_url = $pzai_terms_page_id ? get_permalink($pzai_terms_page_id) : '#';
$pzai_recaptcha_site_key = sanitize_text_field((string) ($pzai_visitor_settings['visitor_recaptcha_site_key'] ?? ''));
$pzai_recaptcha_secret_key = sanitize_text_field((string) ($pzai_visitor_settings['visitor_recaptcha_secret_key'] ?? ''));
$pzai_recaptcha_enabled = ($pzai_recaptcha_site_key !== '' && $pzai_recaptcha_secret_key !== '');
?>
<div id="pzai-widget" class="pzai-widget pzai-shape-<?php echo esc_attr($this->settings->get('widget_shape')); ?> <?php echo ((int) $this->settings->get('hide_on_mobile')) ? 'pzai-hide-mobile-enabled' : ''; ?>" data-hide-mobile="<?php echo ((int) $this->settings->get('hide_on_mobile')); ?>" aria-live="polite">
  <button class="pzai-toggle" type="button" aria-label="Open AI assistant"><span class="pzai-toggle-dot"></span><span class="pzai-toggle-label">ASK AI</span></button>
  <div class="pzai-panel" hidden>
    <div class="pzai-header">
      <strong class="pzai-title"><?php echo esc_html($this->settings->get('widget_title')); ?></strong>
      <div class="pzai-header-actions">
        <button class="pzai-clear" type="button" aria-label="Clear chat" title="Clear chat">Clear</button>
        <button class="pzai-close" type="button" aria-label="Close">×</button>
      </div>
    </div>
    <div class="pzai-messages">
      <div class="pzai-bot-message"><?php echo esc_html($this->settings->get('welcome_message')); ?></div>
    </div>
    <div class="pzai-gate" hidden>
      <div class="pzai-gate-note">Complete this form below to use ASK AI</div>
      <form class="pzai-form pzai-gate-form">
        <div class="pzai-gate-row">
          <input class="pzai-input pzai-gate-name" type="text" name="first_name" placeholder="First name" autocomplete="given-name" />
          <input class="pzai-input pzai-gate-email" type="email" name="email" placeholder="Email" autocomplete="email" />
        </div>
        <label class="pzai-gate-consent">
          <input class="pzai-gate-agree" type="checkbox" name="agree" value="1" />
          <span>I agree that PricZone may use my information to send Ask AI access emails, site notifications, and feedback requests. <a class="pzai-gate-terms" href="<?php echo esc_url($pzai_terms_url); ?>" target="_blank" rel="noopener noreferrer">Agreement of usage of information</a>.</span>
        </label>
        <?php if ($pzai_recaptcha_enabled) : ?>
          <div class="pzai-gate-recaptcha"><div class="g-recaptcha" data-sitekey="<?php echo esc_attr($pzai_recaptcha_site_key); ?>"></div></div>
        <?php endif; ?>
        <button type="submit">Submit</button>
      </form>
      <div class="pzai-bot-message pzai-gate-error" hidden></div>
    </div>
    <form class="pzai-form pzai-chat-form">
      <input class="pzai-input pzai-chat-input" type="text" name="message" placeholder="Ask for product recommendations, compare items, or find a category..." />
      <button class="pzai-chat-send" type="submit">Send</button>
    </form>
  </div>
</div>
