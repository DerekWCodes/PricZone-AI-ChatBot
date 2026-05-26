<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

class Settings {
    const OPTION_KEY = 'pzai_settings';

    public function defaults() {
        return [
            'enabled' => 1,
            'widget_title' => 'PricZone AI Concierge',
            'welcome_message' => 'Hi! I can help you find products, compare options, discover categories, and answer common store questions.',
            'brand_primary' => '#16d6a6',
            'brand_dark' => '#232F3E',
            'brand_light' => '#FFFFFF',
            'support_email' => 'support@priczone.com',
            'support_phone' => '',
            'contact_url' => 'https://priczone.com/contact-us-customer-support/',
            'shipping_policy' => 'PricZone offers fast shipping for online orders. Shipping times can vary by product, fulfillment source, and destination, so customers should review the shipping information page or contact support for item-specific timing.',
            'shipping_url' => 'https://priczone.com/shipping-info/',
            'returns_policy' => 'If you need help with a return, refund, or order issue, please contact PricZone support first through the customer support page so the team can review the order and next steps.',
            'returns_url' => 'https://priczone.com/returns-refunds/',
            'tracking_url' => 'https://priczone.com/tracking-order/',
            'membership_policy' => 'PricZone membership, subscription, or member-only benefits can vary by offer. Review the active membership page for current details or contact support for help.',
            'membership_url' => '',
            'ask_ai_usage_policy' => 'PricZone may use the information submitted through Ask AI to send Ask AI access emails, site notifications, and feedback requests related to this tool.',
            'privacy_summary' => 'PricZone does not sell Ask AI visitor information and does not use it for unrelated marketing unless separately agreed. You can unsubscribe or request help through support if needed.',
            'faq_json' => '[
  {"q":"What is your support email?","a":"You can contact PricZone at support@priczone.com."},
  {"q":"Do you offer customer support?","a":"Yes. Visit https://priczone.com/contact-us-customer-support/ for help."},
  {"q":"Where can I track my order?","a":"You can use the tracking page at https://priczone.com/tracking-order/."},
  {"q":"Where can I read shipping information?","a":"Shipping details are available at https://priczone.com/shipping-info/."},
  {"q":"Where can I read about returns and refunds?","a":"Return and refund details are available at https://priczone.com/returns-refunds/."}
]',
            'category_synonyms_json' => '{
  "Men\'s Clothing": ["mens clothing", "men clothing", "mens fashion", "men apparel", "mens wear", "male clothing", "guy clothes"],
  "Women\'s Clothing": ["womens clothing", "women clothing", "womens fashion", "women apparel", "ladies clothing", "female clothing", "girl clothes"],
  "Kid\'s Clothing": ["kids clothing", "kid clothing", "children clothing", "boys clothing", "girls clothing", "baby clothes", "kids wear"],
  "Men\'s Underwear": ["mens underwear", "men underwear", "boxers", "briefs", "undershirts", "thermal underwear", "long johns", "sleepwear"],
  "Luggage, Bags & Shoes": ["luggage", "bags", "shoes", "backpack", "travel bag", "footwear", "sneakers", "boots", "sandals"],
  "Jewelry, Watches & Accessories": ["jewelry", "watches", "accessories", "bracelets", "rings", "necklaces", "fashion accessories", "sunglasses"],
  "Electronics": ["electronics", "tech", "gadgets", "devices", "headphones", "monitor", "earbuds", "speaker", "charger"],
  "Phones and Telecommunications": ["phones", "phone accessories", "telecommunications", "mobile", "smartphone", "iphone", "android", "phone case"],
  "Computer, Office & Education": ["computer", "office", "education", "workspace", "school supplies", "desk setup", "laptop", "keyboard", "mouse", "printer"],
  "Furniture": ["furniture", "home furniture", "office furniture", "desk", "chair", "table", "cabinet", "shelf"],
  "Beauty & Health": ["beauty", "health", "skincare", "makeup", "cosmetics", "wellness", "personal care"],
  "Home, Garden & Tools": ["home", "garden", "tools", "kitchen", "household", "home improvement", "decor"],
  "Toys & Games": ["toys", "games", "kids toys", "play", "fun products", "board games", "puzzles"],
  "Sports & Outdoors": ["sports", "outdoors", "fitness", "exercise", "camping", "hiking", "gym"],
  "Pets": ["pets", "pet supplies", "dog", "cat", "pet accessories"],
  "Automotive": ["automotive", "car accessories", "vehicle", "truck", "auto parts"],
  "Cosplay & Costumes": ["cosplay", "costumes", "anime costume", "dress up", "roleplay outfits"],
  "Weddings & Events": ["wedding", "event", "party supplies", "bridal", "celebration"]
}',
            'fallback_message' => 'I could not find an exact match yet, but I can help if you tell me the category, your budget, the type of product, or whether you want cheaper or similar options.',
            'show_on_shop' => 1,
            'show_on_product' => 1,
            'show_on_cart' => 0,
            'max_results' => 6,
            'hide_on_mobile' => 0,
            'hide_below_width' => 768,
            'ai_provider' => 'none',
            'openai_api_key' => '',
            'openai_model' => 'gpt-4o-mini',
            'openrouter_api_key' => '',
            'openrouter_model' => 'openai/gpt-4o-mini',
            'groq_api_key' => '',
            'groq_model' => 'llama-3.1-8b-instant',
            'github_models_api_key' => '',
            'github_models_model' => 'openai/gpt-4o-mini',
            'ollama_endpoint' => 'http://127.0.0.1:11434',
            'ollama_model' => '',
            'ai_grounded_reply_mode' => 1,
            'ai_grounded_context_limit' => 3,
            'tool_driven_store_actions' => 1,
            'semantic_query_assist' => 1,
            'semantic_candidate_pool' => 24,
            'knowledge_answer_priority' => 1,
            'show_legacy_ai_providers' => 0,
            'ai_local_only_mode' => 0,
            'ollama_timeout' => 8,
            'external_ai_timeout' => 20,
            'ai_reply_char_limit' => 240,
            'ai_debug_logging' => 0,
            'smart_suggestion_chips' => 1,
            'smart_suggestion_chip_limit' => 4,
            'session_memory_enabled' => 1,
            'session_memory_turn_limit' => 6,
            'faq_items' => '[]',
            'synonym_items' => '[
  {"category":"Men\'s Clothing","phrase":"mens clothing"},
  {"category":"Women\'s Clothing","phrase":"womens clothing"},
  {"category":"Kid\'s Clothing","phrase":"kids clothing"},
  {"category":"Men\'s Underwear","phrase":"boxers"},
  {"category":"Men\'s Underwear","phrase":"briefs"},
  {"category":"Men\'s Underwear","phrase":"undershirts"},
  {"category_id":"0","phrase":"gadgets"},
  {"category_id":"0","phrase":"phone accessories"},
  {"category_id":"0","phrase":"office supplies"},
  {"category_id":"0","phrase":"desk"},
  {"category_id":"0","phrase":"shoes"},
  {"category_id":"0","phrase":"watches"}
]',
        ];
    }

    public function get_all() {
        return wp_parse_args(get_option(self::OPTION_KEY, []), $this->defaults());
    }

    public function get($key, $default = null) {
        $settings = $this->get_all();
        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    public function get_tabs() {
        return [
            'general' => 'General',
            'display' => 'Display',
            'store' => 'Store Knowledge',
            'catalog' => 'Catalog Intelligence',
            'responses' => 'Response Controls',
            'safety' => 'Safety & Performance',
            'integrations' => 'AI Integration',
        ];
    }

    public function get_field_schema() {
        return [
            'general' => [
                ['key'=>'enabled','label'=>'Enable bot','type'=>'checkbox','help'=>'Turn the storefront AI assistant on or off.'],
                ['key'=>'widget_title','label'=>'Widget title','type'=>'text','help'=>'Shown in the widget header.'],
                ['key'=>'welcome_message','label'=>'Welcome message','type'=>'textarea','help'=>'Recommended: greet users and mention product discovery, comparisons, and support help.'],
            ],
            'display' => [
                ['key'=>'show_on_shop','label'=>'Show on shop/archive pages','type'=>'checkbox','help'=>'Recommended: enabled for category and catalog browsing.'],
                ['key'=>'show_on_product','label'=>'Show on single product pages','type'=>'checkbox','help'=>'Recommended: enabled so shoppers can ask about the current item.'],
                ['key'=>'show_on_cart','label'=>'Show on cart page','type'=>'checkbox','help'=>'Optional; leave off if you want a less distracting checkout flow.'],
                ['key'=>'hide_on_mobile','label'=>'Hide on mobile devices','type'=>'checkbox','help'=>'Uses a CSS media query to hide the entire chat widget at 768px and below, including mobile devices and scaled-down browsers.'],
                ['key'=>'hide_below_width','label'=>'Mobile breakpoint width','type'=>'number','help'=>'Widget hides at or below this browser width when Hide AI Chat is enabled.'],
                ['key'=>'brand_primary','label'=>'Primary color','type'=>'text','help'=>'PricZone teal default: #16d6a6'],
                ['key'=>'brand_dark','label'=>'Dark color','type'=>'text','help'=>'PricZone dark default: #06435A'],
                ['key'=>'brand_light','label'=>'Light color','type'=>'text','help'=>'Use #FFFFFF for a clean card background.'],
            ],
            'store' => [
                ['key'=>'support_email','label'=>'Support email','type'=>'text','help'=>'Use your real support inbox so the bot never guesses contact details.'],
                ['key'=>'support_phone','label'=>'Support phone','type'=>'text','help'=>'Optional. Leave blank if email/support page is your main support channel.'],
                ['key'=>'contact_url','label'=>'Contact URL','type'=>'text','help'=>'Recommended: https://priczone.com/contact-us-customer-support/'],
                ['key'=>'shipping_policy','label'=>'Shipping policy','type'=>'textarea','help'=>'Short summary the bot can quote directly.'],
                ['key'=>'shipping_url','label'=>'Shipping info URL','type'=>'text','help'=>'Optional. Approved page URL for shipping details.'],
                ['key'=>'returns_policy','label'=>'Returns policy','type'=>'textarea','help'=>'Short summary the bot can quote directly.'],
                ['key'=>'returns_url','label'=>'Returns and refunds URL','type'=>'text','help'=>'Optional. Approved page URL for returns and refunds details.'],
                ['key'=>'tracking_url','label'=>'Tracking URL','type'=>'text','help'=>'Optional. Approved tracking page URL the bot can share directly.'],
                ['key'=>'membership_policy','label'=>'Membership policy','type'=>'textarea','help'=>'Short approved summary for membership, subscription, or member-benefit questions.'],
                ['key'=>'membership_url','label'=>'Membership URL','type'=>'text','help'=>'Optional. Approved membership page URL.'],
                ['key'=>'ask_ai_usage_policy','label'=>'Ask AI usage policy','type'=>'textarea','help'=>'Approved summary for what Ask AI uses visitor information for.'],
                ['key'=>'privacy_summary','label'=>'Privacy summary','type'=>'textarea','help'=>'Approved summary for privacy, unsubscribe, and information-use questions.'],
                ['key'=>'faq_json','label'=>'FAQ JSON','type'=>'textarea','help'=>'Raw FAQ JSON.'],
            ],
            'catalog' => [
                ['key'=>'category_synonyms_json','label'=>'Category synonyms JSON','type'=>'textarea','help'=>'Category ID and shopper-phrase matching configuration.'],
                ['key'=>'max_results','label'=>'Max product results','type'=>'number','help'=>'Recommended range: 4 to 8.'],
                ['key'=>'synonym_items','label'=>'Saved synonym rows JSON','type'=>'hidden','help'=>'Managed by the visual synonym tool below.'],
            ],
            'responses' => [
                ['key'=>'fallback_message','label'=>'Fallback message','type'=>'textarea','help'=>'Shown when no strong product match is found.'],
                ['key'=>'ai_grounded_reply_mode','label'=>'Use grounded AI reply mode','type'=>'checkbox','help'=>'Keeps AI replies tied to WooCommerce and store facts instead of sending raw product payloads to the model.'],
                ['key'=>'ai_grounded_context_limit','label'=>'Grounded AI product context limit','type'=>'number','help'=>'How many matched products are summarized and sent to the AI rewrite layer. Recommended: 2 to 4.'],
                ['key'=>'tool_driven_store_actions','label'=>'Use tool-driven store actions','type'=>'checkbox','help'=>'Routes shopper prompts into controlled internal actions like browse category, compare products, similar items, shipping, returns, and support before falling back to loose search behavior.'],
                ['key'=>'semantic_query_assist','label'=>'Use semantic query assist','type'=>'checkbox','help'=>'Expands natural-language shopper prompts into broader candidate retrieval using category names, synonym phrases, and cleaned search terms before ranking results.'],
                ['key'=>'semantic_candidate_pool','label'=>'Semantic candidate pool','type'=>'number','help'=>'How many candidate products are gathered before scoring and trimming the final result list. Recommended: 18 to 36.'],
                ['key'=>'knowledge_answer_priority','label'=>'Use approved knowledge answers first','type'=>'checkbox','help'=>'Keeps store-help answers tied to your approved support, policy, privacy, and FAQ content before any AI phrasing is considered.'],
                ['key'=>'smart_suggestion_chips','label'=>'Use smart suggestion chips','type'=>'checkbox','help'=>'Upgrades the existing follow-up chips so Ask AI shows more useful next-step buttons like cheaper, in stock, compare, similar, support, and policy help based on the current result.'],
                ['key'=>'smart_suggestion_chip_limit','label'=>'Smart suggestion chip limit','type'=>'number','help'=>'How many smart suggestion chips Ask AI should show at one time. Recommended: 3 to 5.'],
                ['key'=>'session_memory_enabled','label'=>'Use session memory','type'=>'checkbox','help'=>'Lets Ask AI remember the current shopper session context like the last product type, category, budget, and in-stock preference so follow-up questions feel more natural without storing long-term customer memory.'],
                ['key'=>'session_memory_turn_limit','label'=>'Session memory turn limit','type'=>'number','help'=>'How many recent shopper turns Ask AI should keep for the current session memory. Recommended: 4 to 8.'],
            ],
            'safety' => [
                ['key'=>'ai_local_only_mode','label'=>'Local-only AI mode','type'=>'checkbox','help'=>'When enabled, Ask AI only allows Ollama Local or None (rules only), even if legacy provider controls are visible.'],
                ['key'=>'ollama_timeout','label'=>'Ollama timeout (seconds)','type'=>'number','help'=>'How long Ask AI waits for the local Ollama rewrite request before falling back. Recommended: 5 to 10 seconds.'],
                ['key'=>'external_ai_timeout','label'=>'External AI timeout (seconds)','type'=>'number','help'=>'How long Ask AI waits for external provider rewrites if you still use legacy providers. Recommended: 10 to 30 seconds.'],
                ['key'=>'ai_reply_char_limit','label'=>'AI reply character limit','type'=>'number','help'=>'Hard cap for the shopper-facing AI rewrite reply after cleanup. Recommended: 160 to 320 characters.'],
                ['key'=>'ai_debug_logging','label'=>'Enable AI debug logging','type'=>'checkbox','help'=>'Stores lightweight AI rewrite debug events in Analytics Overview so you can see local-only blocks, timeouts, and provider response outcomes.'],
            ],
            'integrations' => [
                ['key'=>'show_legacy_ai_providers','label'=>'Show legacy external AI providers','type'=>'checkbox','help'=>'Off by default for a cleaner setup. Turn this on only if you still want to use OpenAI, OpenRouter, or GitHub Models.'],
                ['key'=>'ai_provider','label'=>'AI provider','type'=>'select','options'=>['none'=>'None (rules only)','groq'=>'Groq','openai'=>'OpenAI','openrouter'=>'OpenRouter','github_models'=>'GitHub Models','ollama_local'=>'Ollama Local'],'help'=>'Recommended: Ollama Local or None (rules only). Groq is available directly here as an optional fast hosted provider.'],
                ['key'=>'groq_api_key','label'=>'Groq API key','type'=>'password','help'=>'Only required if provider = Groq. Groq now shows directly in the AI provider list; Local-only AI mode must be turned off to actually use it.'],
                ['key'=>'groq_model','label'=>'Groq model','type'=>'text','help'=>'Recommended: llama-3.1-8b-instant'],
                ['key'=>'openai_api_key','label'=>'OpenAI API key','type'=>'password','help'=>'Only required if provider = OpenAI.'],
                ['key'=>'openai_model','label'=>'OpenAI model','type'=>'text','help'=>'Recommended: gpt-4o-mini'],
                ['key'=>'openrouter_api_key','label'=>'OpenRouter API key','type'=>'password','help'=>'Only required if provider = OpenRouter.'],
                ['key'=>'openrouter_model','label'=>'OpenRouter model','type'=>'text','help'=>'Example: openai/gpt-4o-mini'],
                ['key'=>'github_models_api_key','label'=>'GitHub personal access token','type'=>'password','help'=>'Only required if provider = GitHub Models.'],
                ['key'=>'github_models_model','label'=>'GitHub Models model','type'=>'text','help'=>'Recommended: openai/gpt-4o-mini'],
                ['key'=>'ollama_endpoint','label'=>'Ollama endpoint','type'=>'text','help'=>'Use the local Ollama server endpoint. Example: http://127.0.0.1:11434'],
                ['key'=>'ollama_model','label'=>'Ollama model','type'=>'select','options'=>[],'help'=>'Choose from the installed local Ollama models returned by your server. Use Refresh Model List if this is empty.'],
            ],
        ];
    }


    private function legacy_ai_providers() {
        return ['openai', 'openrouter', 'github_models'];
    }

    private function hosted_ai_providers() {
        return ['groq', 'openai', 'openrouter', 'github_models'];
    }

    private function is_legacy_ai_provider($provider) {
        return in_array((string) $provider, $this->legacy_ai_providers(), true);
    }

    private function is_hosted_ai_provider($provider) {
        return in_array((string) $provider, $this->hosted_ai_providers(), true);
    }

    public function sanitize($input) {
        $defaults = $this->defaults();
        $schema = $this->get_field_schema();
        $fields = [];
        foreach ($schema as $group) foreach ($group as $field) $fields[$field['key']] = $field;
        $out = $defaults;
        foreach ($fields as $key => $field) {
            $type = $field['type'];
            $value = $input[$key] ?? null;
            switch ($type) {
                case 'checkbox':
                    $out[$key] = $value ? 1 : 0;
                    break;
                case 'number':
                    $out[$key] = is_numeric($value) ? (int) $value : (int) ($defaults[$key] ?? 0);
                    break;
                case 'textarea':
                case 'hidden':
                    if (in_array($key, ['faq_json', 'category_synonyms_json', 'faq_items', 'synonym_items'], true)) {
                        $out[$key] = is_string($value) ? wp_unslash($value) : (string) ($defaults[$key] ?? '');
                    } else {
                        $out[$key] = is_string($value) ? wp_kses_post(wp_unslash($value)) : (string) ($defaults[$key] ?? '');
                    }
                    break;
                case 'password':
                case 'text':
                case 'select':
                default:
                    $out[$key] = is_string($value) ? wp_kses_post(wp_unslash($value)) : (string) ($defaults[$key] ?? '');
                    break;
            }
        }

        if (empty($out['show_legacy_ai_providers']) && $this->is_legacy_ai_provider($out['ai_provider'] ?? '')) {
            $out['ai_provider'] = !empty($out['ollama_model']) || !empty($out['ollama_endpoint']) ? 'ollama_local' : 'none';
        }

        if (!empty($out['ai_local_only_mode']) && $this->is_hosted_ai_provider($out['ai_provider'] ?? '')) {
            $out['ai_provider'] = !empty($out['ollama_model']) || !empty($out['ollama_endpoint']) ? 'ollama_local' : 'none';
        }

        return $out;
    }

    public function render_fields_for_tab($tab) {
        $settings = $this->get_all();
        $schema = $this->get_field_schema();
        if (empty($schema[$tab])) return;
        echo '<table class="form-table pzai-form-table" role="presentation"><tbody>';
        foreach ($schema[$tab] as $field) {
            $key = $field['key'];
            $label = $field['label'];
            $type = $field['type'];
            $help = $field['help'] ?? '';
            $value = $settings[$key] ?? '';
            if (is_string($value)) $value = wp_unslash($value);
            $row_attrs = '';
            if ($tab === 'integrations' && $key !== 'ai_provider' && $key !== 'show_legacy_ai_providers') {
                $provider_map = [
                    'groq_api_key' => 'groq',
                    'groq_model' => 'groq',
                    'openai_api_key' => 'openai',
                    'openai_model' => 'openai',
                    'openrouter_api_key' => 'openrouter',
                    'openrouter_model' => 'openrouter',
                    'github_models_api_key' => 'github_models',
                    'github_models_model' => 'github_models',
                    'ollama_endpoint' => 'ollama_local',
                    'ollama_model' => 'ollama_local',
                ];
                if (!empty($provider_map[$key])) {
                    $row_attrs = ' data-pzai-provider-field="' . esc_attr($provider_map[$key]) . '"';
                    if ($this->is_legacy_ai_provider($provider_map[$key])) {
                        $row_attrs .= ' data-pzai-legacy-provider="1"';
                    }
                }
            }
            echo '<tr' . $row_attrs . '>';
            echo '<th scope="row"><label for="pzai_' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
            echo '<td>';
            $name = self::OPTION_KEY . '[' . $key . ']';
            if ($type === 'checkbox') {
                echo '<label><input id="pzai_' . esc_attr($key) . '" type="checkbox" name="' . esc_attr($name) . '" value="1" ' . checked((int)$value, 1, false) . '> ' . esc_html($label) . '</label>';
            } elseif ($type === 'textarea') {
                echo '<textarea id="pzai_' . esc_attr($key) . '" class="large-text code" rows="8" name="' . esc_attr($name) . '">' . esc_textarea($value) . '</textarea>';
            } elseif ($type === 'select' && $key === 'ollama_model') {
                $cached_models = get_option('pzai_ollama_model_cache', []);
                if (!is_array($cached_models)) $cached_models = [];
                echo '<select id="pzai_' . esc_attr($key) . '" name="' . esc_attr($name) . '" data-current-value="' . esc_attr((string) $value) . '" data-loaded="' . (!empty($cached_models) ? '1' : '0') . '">';
                echo '<option value="">' . esc_html(!empty($cached_models) ? 'Select an Ollama model' : 'No models detected') . '</option>';
                $rendered = [];
                foreach ($cached_models as $model_name) {
                    $model_name = sanitize_text_field((string) $model_name);
                    if ($model_name === '' || isset($rendered[$model_name])) continue;
                    $rendered[$model_name] = true;
                    echo '<option value="' . esc_attr($model_name) . '" ' . selected($value, $model_name, false) . '>' . esc_html($model_name) . '</option>';
                }
                if ($value !== '' && !isset($rendered[(string) $value])) {
                    echo '<option value="' . esc_attr((string) $value) . '" selected>' . esc_html((string) $value . ' (saved)') . '</option>';
                }
                echo '</select>';
            } elseif ($type === 'select') {
                $options = (array) ($field['options'] ?? []);
                if ($key === 'ai_provider') {
                    $simple_options = [
                        'none' => $options['none'] ?? 'None (rules only)',
                        'ollama_local' => $options['ollama_local'] ?? 'Ollama Local',
                        'groq' => $options['groq'] ?? 'Groq',
                    ];
                    $show_legacy = !empty($settings['show_legacy_ai_providers']);
                    $local_only = !empty($settings['ai_local_only_mode']);
                    if ($local_only) {
                        $options = $simple_options;
                    } elseif (!$show_legacy) {
                        if ($this->is_legacy_ai_provider($value) && isset($options[$value])) {
                            $simple_options = [$value => $options[$value] . ' (legacy saved)'] + $simple_options;
                        }
                        $options = $simple_options;
                    }
                    echo '<select id="pzai_' . esc_attr($key) . '" name="' . esc_attr($name) . '" data-full-options="' . esc_attr(wp_json_encode($field['options'] ?? [])) . '" data-simple-options="' . esc_attr(wp_json_encode($simple_options)) . '">';
                } else {
                    echo '<select id="pzai_' . esc_attr($key) . '" name="' . esc_attr($name) . '">';
                }
                foreach ($options as $opt_value => $opt_label) {
                    echo '<option value="' . esc_attr($opt_value) . '" ' . selected($value, $opt_value, false) . '>' . esc_html($opt_label) . '</option>';
                }
                echo '</select>';
            } else {
                $input_type = in_array($type, ['text','number','password','hidden'], true) ? $type : 'text';
                $class = $input_type === 'hidden' ? '' : 'regular-text';
                echo '<input id="pzai_' . esc_attr($key) . '" class="' . esc_attr($class) . '" type="' . esc_attr($input_type) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '">';
            }
            if ($help && $type !== 'hidden') echo '<p class="description">' . esc_html($help) . '</p>';
            echo '</td></tr>';
        }
        echo '</tbody></table>';
    }
}
