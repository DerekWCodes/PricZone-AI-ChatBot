<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

class Engine {
    private $catalog;
    private $knowledge;
    private $settings;

    public function __construct($catalog, $knowledge, $settings) {
        $this->catalog = $catalog;
        $this->knowledge = $knowledge;
        $this->settings = $settings;
    }

    private function clean_text($text) {
        $text = wp_strip_all_tags((string) $text, true);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        return trim((string) $text);
    }

    private function truncate($text, $limit) {
        $text = $this->clean_text($text);
        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            return mb_strlen($text) > $limit ? rtrim(mb_substr($text, 0, $limit - 1)) . '…' : $text;
        }
        return strlen($text) > $limit ? rtrim(substr($text, 0, $limit - 1)) . '…' : $text;
    }

    private function local_only_mode_enabled($settings) {
        return !empty($settings['ai_local_only_mode']);
    }

    private function ai_debug_logging_enabled($settings) {
        return !empty($settings['ai_debug_logging']);
    }

    private function ai_reply_char_limit($settings) {
        $limit = (int) ($settings['ai_reply_char_limit'] ?? 240);
        return max(80, min(600, $limit));
    }

    private function ai_timeout_for_provider($provider, $settings) {
        if ($provider === 'ollama_local') {
            $timeout = (int) ($settings['ollama_timeout'] ?? 8);
            return max(3, min(30, $timeout));
        }
        $timeout = (int) ($settings['external_ai_timeout'] ?? 20);
        return max(5, min(60, $timeout));
    }

    private function log_ai_debug($settings, $label, $query = '') {
        if (!$this->ai_debug_logging_enabled($settings)) return;
        Logger::add_event('ai_debug', [
            'label' => $this->truncate((string) $label, 180),
            'query' => $this->truncate((string) $query, 120),
        ]);
    }


private function normalize_phrase($text) {
    $text = strtolower($this->clean_text($text));
    $text = str_replace(['whats', "whats", "what is", "tell me", "lemme", "pls", "plz", "abt", "prodcut", "prdouct", "descripton", "descripton", "differntly"], ['what is', 'what is', 'what is', 'tell me', 'let me', 'please', 'please', 'about', 'product', 'product', 'description', 'description', 'differently'], $text);
    return trim(preg_replace('/\s+/', ' ', $text));
}

private function is_product_context_query($query) {
    $q = $this->normalize_phrase($query);
    if ($q === '') return false;
    $patterns = [
        'about this product','about this item','about this','tell me about this','tell me about this product','what is this',
        'what is this product','what is this item','product details','details about this','details on this',
        'more information','more info','more information about this product','describe this product','product description',
        'description of this product','what should i know about this','info on this','information on this','learn more about this',
        'about the product','about product','tell me more about this','explain this product'
    ];
    foreach ($patterns as $phrase) {
        if (strpos($q, $phrase) !== false) return true;
    }
    $tokens = preg_split('/[^a-z0-9]+/', $q);
    $tokens = array_values(array_filter((array) $tokens));
    $has_about = in_array('about', $tokens, true) || in_array('info', $tokens, true) || in_array('information', $tokens, true) || in_array('details', $tokens, true) || in_array('description', $tokens, true) || in_array('describe', $tokens, true) || in_array('explain', $tokens, true);
    $has_product_ref = in_array('product', $tokens, true) || in_array('item', $tokens, true) || in_array('this', $tokens, true);
    return $has_about && $has_product_ref;
}


private function get_product_attribute_bits($page_context, $limit = 2, $value_limit = 40) {
    $all_bits = [];
    if (!empty($page_context['attributes']) && is_array($page_context['attributes'])) {
        foreach ($page_context['attributes'] as $attr) {
            $label = $this->clean_text(isset($attr['label']) ? $attr['label'] : '');
            $value = $this->truncate(isset($attr['value']) ? $attr['value'] : '', $value_limit);
            if ($label !== '' && $value !== '') $all_bits[] = ['label' => $label, 'value' => $value, 'text' => $label . ': ' . $value];
        }
    }

    $priority_order = [
        'Weight', 'Dimensions', 'Brand Name', 'Material', 'Characters', 'Gender', 'Item Type',
        'Components', 'Source Type', 'Special Use', 'Department-Name', 'Department Name',
        'Craft of Weaving', 'Source Countries', 'Hign-concerned Chemical'
    ];

    $picked = [];
    $used = [];
    foreach ($priority_order as $wanted) {
        foreach ($all_bits as $bit) {
            if (isset($used[$bit['label']])) continue;
            if (strcasecmp($bit['label'], $wanted) === 0) {
                $picked[] = $bit['text'];
                $used[$bit['label']] = true;
                if (count($picked) >= $limit) return $picked;
                break;
            }
        }
    }

    foreach ($all_bits as $bit) {
        if (isset($used[$bit['label']])) continue;
        $picked[] = $bit['text'];
        if (count($picked) >= $limit) break;
    }
    return $picked;
}

private function is_key_details_query($query) {
    $q = $this->normalize_phrase($query);
    if ($q === '') return false;
    foreach (['key details', 'additional information', 'additional info', 'specs', 'specifications', 'materials', 'sizes', 'size info', 'dimensions', 'weight', 'brand name'] as $phrase) {
        if (strpos($q, $phrase) !== false) return true;
    }
    return false;
}

private function is_more_information_query($query) {
    $q = $this->normalize_phrase($query);
    if ($q === '') return false;
    foreach (['more information about this product', 'more information', 'more info', 'tell me more about this product', 'product description', 'description of this product', 'describe this product', 'about this product', 'about this item', 'tell me about this product', 'learn more about this'] as $phrase) {
        if (strpos($q, $phrase) !== false) return true;
    }
    return false;
}

private function build_promotional_product_blurb($page_context) {
    $name = $this->clean_text(isset($page_context['name']) ? $page_context['name'] : 'This product');
    $attr_bits = $this->get_product_attribute_bits($page_context, 3, 40);
    if ($attr_bits) {
        return $name . ' stands out with ' . implode(', ', $attr_bits) . '. It is a strong pick if you want something eye-catching and ready to impress.';
    }
    return $name . ' is a solid choice if you want something that looks great, feels purposeful, and adds a little extra appeal to your setup. It is the kind of item that can quickly turn interest into a confident purchase.';
}

private function build_short_product_context_message($page_context, $query = '') {
    $name = $this->clean_text(isset($page_context['name']) ? $page_context['name'] : '');
    if ($name === '') return '';

    $short = $this->truncate(isset($page_context['short_description']) ? $page_context['short_description'] : '', 170);
    $desc = $this->truncate(isset($page_context['description']) ? $page_context['description'] : '', 170);
    $attr_bits = $this->get_product_attribute_bits($page_context, 5, 50);
    $details_first = $this->is_key_details_query($query);
    $more_info = $this->is_more_information_query($query);

    if ($details_first) {
        if (!$attr_bits) {
            return 'I could not find key details in the Additional Information section for this product.';
        }
        $primary = array_slice($attr_bits, 0, 3);
        $secondary = array_slice($attr_bits, 3, 2);
        $sentences = [];
        $sentences[] = 'Key details for ' . $name . ': ' . implode(' | ', $primary) . '.';
        if ($secondary) {
            $sentences[] = 'Also noted: ' . implode(' | ', $secondary) . '.';
        }
        return implode(' ', array_slice($sentences, 0, 2));
    }

    if ($more_info) {
        if ($desc !== '') {
            return $desc;
        }
        if ($short !== '') {
            return $short;
        }
        return $this->build_promotional_product_blurb($page_context);
    }

    $sentences = [];
    $sentences[] = $name . ' is a standout pick if you want a quick feel for what this item offers.';
    if ($desc !== '') {
        $sentences[] = $desc;
    } elseif ($short !== '') {
        $sentences[] = $short;
    } elseif ($attr_bits) {
        $sentences[] = 'More information: ' . implode(' | ', array_slice($attr_bits, 0, 2)) . '.';
    } else {
        $sentences[] = $this->build_promotional_product_blurb($page_context);
    }

    return implode(' ', array_slice($sentences, 0, 2));
}

    private function build_product_context_answer($query, $page_context) {
        if (empty($page_context['product_id']) && empty($page_context['name'])) return [];
        $name = $this->clean_text(isset($page_context['name']) ? $page_context['name'] : '');
        if ($name === '') return [];

        if ($this->is_product_context_query($query) || $this->is_key_details_query($query) || $this->is_more_information_query($query)) {
            $message = $this->build_short_product_context_message($page_context, $query);
            $message = $this->maybe_ai_rewrite_product_context_message($query, $page_context, $this->settings->get_all(), $message);
            return [
                'type' => 'product_context',
                'message' => $message,
                'products' => [],
                'suggestions' => ['More information about this product', 'What are the key details?', 'Show similar items'],
            ];
        }

        $parts = [];
        $parts[] = 'You are viewing ' . $name . '.';

        $short = $this->truncate(isset($page_context['short_description']) ? $page_context['short_description'] : '', 220);
        $desc = $this->truncate(isset($page_context['description']) ? $page_context['description'] : '', 260);
        if ($short !== '') {
            $parts[] = $short;
        } elseif ($desc !== '') {
            $parts[] = $desc;
        }

        $attr_bits = [];
        if (!empty($page_context['attributes']) && is_array($page_context['attributes'])) {
            foreach ($page_context['attributes'] as $attr) {
                $label = $this->clean_text(isset($attr['label']) ? $attr['label'] : '');
                $value = $this->truncate(isset($attr['value']) ? $attr['value'] : '', 80);
                if ($label !== '' && $value !== '') $attr_bits[] = $label . ': ' . $value;
                if (count($attr_bits) >= 4) break;
            }
        }
        if ($attr_bits) $parts[] = 'Key details: ' . implode('; ', $attr_bits) . '.';

        $categories = [];
        if (!empty($page_context['categories']) && is_array($page_context['categories'])) {
            foreach ($page_context['categories'] as $cat) {
                $cat = $this->clean_text($cat);
                if ($cat !== '') $categories[] = $cat;
            }
        }
        if ($categories) $parts[] = 'This item is in: ' . implode(', ', array_slice($categories, 0, 3)) . '.';

        $stock = $this->clean_text(isset($page_context['stock_status']) ? $page_context['stock_status'] : '');
        if ($stock === 'instock') $parts[] = 'It currently shows as in stock.';
        if ($stock === 'outofstock') $parts[] = 'It currently shows as out of stock.';

        $suggestions = [];
        $products = [];
        if (!empty($page_context['complementary_products']) && is_array($page_context['complementary_products'])) {
            foreach ($page_context['complementary_products'] as $item) {
                if (empty($item['name']) || empty($item['permalink'])) continue;
                $products[] = $item;
                $suggestions[] = $this->clean_text($item['name']);
                if (count($suggestions) >= 3) break;
            }
        }
        if ($suggestions) $parts[] = 'Good companion options from the store include ' . implode(', ', $suggestions) . '.';

        $message = implode(' ', $parts);
        $message = $this->maybe_ai_rewrite_product_context_message($query, $page_context, $this->settings->get_all(), $message);
        return [
            'type' => 'product_context',
            'message' => $message,
            'products' => array_slice($products, 0, max(1, (int) $this->settings->get('max_results', 6))),
            'suggestions' => ['Show similar items', 'Show cheaper options', 'Only show in-stock options'],
        ];
    }

    private function build_match_reason($query, $product, $matched_categories = []) {
        if (!$product || !is_object($product)) return '';
        $tokens = preg_split('/[^a-z0-9]+/i', strtolower((string) $query));
        $tokens = array_values(array_filter(array_unique(array_map('trim', (array) $tokens)), function($token){
            return $token !== '' && strlen($token) >= 4 && !in_array($token, ['show','with','that','this','have','want','need','best','find','more','only','than','into','from']);
        }));
        $name = strtolower($this->clean_text($product->get_name()));
        $reasons = [];
        foreach ($tokens as $token) {
            if (strpos($name, $token) !== false) {
                $reasons[] = 'Name match';
                break;
            }
        }
        $category_names = [];
        $terms = get_the_terms($product->get_id(), 'product_cat');
        if (!is_wp_error($terms) && is_array($terms)) {
            foreach ($terms as $term) {
                if (!empty($term->name)) $category_names[] = $this->clean_text($term->name);
            }
        }
        if ($matched_categories) {
            foreach ($matched_categories as $cat) {
                $cat_name = isset($cat['name']) ? $this->clean_text($cat['name']) : '';
                if ($cat_name !== '' && in_array($cat_name, $category_names, true)) {
                    $reasons[] = 'Category match';
                    break;
                }
            }
        }
        if ($product->is_in_stock()) $reasons[] = 'In stock';
        $price = (float) $product->get_price();
        if ($price > 0 && (strpos($query, 'cheap') !== false || strpos($query, 'budget') !== false || strpos($query, 'cheaper') !== false || strpos($query, 'affordable') !== false)) {
            $reasons[] = 'Budget-friendly option';
        }
        $reasons = array_values(array_unique(array_filter($reasons)));
        return implode(' • ', array_slice($reasons, 0, 2));
    }

    private function build_suggestions($query, $products = [], $matched_categories = []) {
        $query = trim((string) $query);
        $ql = strtolower($query);
        $primary_category = !empty($matched_categories[0]) ? $matched_categories[0] : [];
        $category_name = !empty($primary_category['name']) ? $this->clean_text($primary_category['name']) : '';
        $category_id = !empty($primary_category['id']) ? absint($primary_category['id']) : 0;

        $suggestions = [];
        if ($query !== '' && strpos($ql, 'cheaper') === false && strpos($ql, 'budget') === false && strpos($ql, 'cheap') === false) {
            $suggestions[] = [
                'label' => $query . ' cheaper',
                'query' => $query . ' cheaper',
                'base_query' => $query,
                'mode' => 'cheaper',
                'category_name' => $category_name,
                'category_id' => $category_id,
            ];
        }
        if ($query !== '' && strpos($ql, 'in stock') === false) {
            $suggestions[] = [
                'label' => $query . ' in stock',
                'query' => $query . ' in stock',
                'base_query' => $query,
                'mode' => 'in_stock',
                'category_name' => $category_name,
                'category_id' => $category_id,
            ];
        }
        if ($query !== '' && strpos($ql, 'compare') === false) {
            $suggestions[] = [
                'label' => 'Compare ' . $query . ' options',
                'query' => 'Compare ' . $query . ' options',
                'base_query' => $query,
                'mode' => 'compare',
                'category_name' => $category_name,
                'category_id' => $category_id,
            ];
        }
        if ($category_name !== '') {
            $suggestions[] = [
                'label' => $category_name,
                'query' => $category_name,
                'base_query' => $query,
                'mode' => 'category',
                'category_name' => $category_name,
                'category_id' => $category_id,
            ];
        }
        if (!$suggestions && !empty($products)) {
            $suggestions[] = [
                'label' => 'Show similar items',
                'query' => 'Show similar items',
                'base_query' => $query,
                'mode' => 'compare',
                'category_name' => $category_name,
                'category_id' => $category_id,
            ];
        }

        $out = [];
        $seen = [];
        foreach ($suggestions as $item) {
            $label = trim(preg_replace('/\s+/', ' ', (string) ($item['label'] ?? '')));
            if ($label === '' || isset($seen[strtolower($label)])) continue;
            $item['label'] = $label;
            $item['query'] = trim(preg_replace('/\s+/', ' ', (string) ($item['query'] ?? $label)));
            $item['base_query'] = trim((string) ($item['base_query'] ?? $query));
            $seen[strtolower($label)] = true;
            $out[] = $item;
            if (count($out) >= 4) break;
        }
        return $out;
    }

    private function normalize_suggestion_meta($meta) {
        if (!is_array($meta)) return [];
        $clean = [];
        $clean['label'] = $this->clean_text(isset($meta['label']) ? (string) $meta['label'] : '');
        $clean['query'] = $this->clean_text(isset($meta['query']) ? (string) $meta['query'] : '');
        $clean['mode'] = strtolower($this->clean_text(isset($meta['mode']) ? (string) $meta['mode'] : ''));
        $clean['base_query'] = $this->clean_text(isset($meta['base_query']) ? (string) $meta['base_query'] : '');
        $clean['category_name'] = $this->clean_text(isset($meta['category_name']) ? (string) $meta['category_name'] : '');
        $clean['category_id'] = absint(isset($meta['category_id']) ? $meta['category_id'] : 0);
        return $clean;
    }

    private function resolve_view_all_url($matched_categories = [], $exact_category = [], $payload = []) {
        if (!empty($exact_category['id'])) {
            $link = get_term_link((int) $exact_category['id'], 'product_cat');
            if (!is_wp_error($link) && !empty($link)) return (string) $link;
        }
        if (!empty($matched_categories) && is_array($matched_categories)) {
            foreach ($matched_categories as $cat) {
                if (empty($cat['id'])) continue;
                $link = get_term_link((int) $cat['id'], 'product_cat');
                if (!is_wp_error($link) && !empty($link)) return (string) $link;
            }
        }
        if (!empty($payload) && is_array($payload)) {
            $first = $payload[0];
            if (!empty($first['view_all_url'])) return (string) $first['view_all_url'];
            if (!empty($first['deepest_category_url'])) return (string) $first['deepest_category_url'];
            if (!empty($first['grandchild_category_url'])) return (string) $first['grandchild_category_url'];
            if (!empty($first['child_category_url'])) return (string) $first['child_category_url'];
            if (!empty($first['category_url'])) return (string) $first['category_url'];
        }
        return '';
    }

    private function attach_view_all_url($payload = [], $view_all_url = '') {
        if (empty($payload) || !is_array($payload)) return $payload;
        foreach ($payload as &$item) {
            if (!is_array($item)) continue;
            if (empty($item['view_all_url']) && !empty($view_all_url)) $item['view_all_url'] = (string) $view_all_url;
        }
        unset($item);
        return $payload;
    }

    private function apply_structured_suggestion($meta, $matched_categories, $settings, $synonyms = []) {
        if (empty($meta['mode'])) return null;
        $limit = max(8, (int) $settings['max_results']);
        $category_ids = [];
        if (!empty($meta['category_id'])) $category_ids[] = (int) $meta['category_id'];
        if (!$category_ids && !empty($matched_categories)) $category_ids = wp_list_pluck(array_slice($matched_categories, 0, 6), 'id');
        $base_query = trim((string) ($meta['base_query'] ?: $meta['query'] ?: ''));
        $search_query = $base_query;
        if ($search_query === '' && !empty($meta['category_name'])) $search_query = $meta['category_name'];
        $exact_category = $this->catalog->find_best_category_match($search_query !== '' ? $search_query : $meta['category_name'], $synonyms);
        if (!empty($exact_category['id'])) $category_ids = [(int) $exact_category['id']];

        $args = ['limit' => $limit];
        if (!empty($category_ids)) $args['category_ids'] = $category_ids;
        $products = $this->catalog->search_products($search_query, $args);
        if ((!$products || count($products) < 2) && !empty($exact_category['id'])) {
            $exact_only = $this->catalog->search_products('', ['limit' => $limit, 'category_ids' => [(int) $exact_category['id']]]);
            if ($exact_only) $products = array_merge($products ? $products : [], $exact_only);
        }
        if ((!$products || count($products) < 2) && !empty($exact_category['parent'])) {
            $parent_only = $this->catalog->search_products('', ['limit' => $limit, 'category_ids' => [(int) $exact_category['parent']]]);
            if ($parent_only) $products = array_merge($products ? $products : [], $parent_only);
        }
        if ((!$products || count($products) < 2) && !empty($category_ids)) {
            $fallback_products = $this->catalog->search_products('', $args);
            if ($fallback_products) $products = array_merge($products ? $products : [], $fallback_products);
        }
        if (!$products && $search_query !== '') $products = $this->catalog->search_products($search_query, ['limit' => $limit]);
        if (!$products && !empty($meta['category_name'])) $products = $this->catalog->search_products($meta['category_name'], ['limit' => $limit]);

        $seen = [];
        $deduped = [];
        foreach ((array) $products as $product) {
            if (!$product || isset($seen[$product->get_id()])) continue;
            $seen[$product->get_id()] = true;
            $deduped[] = $product;
        }
        if (!$deduped) return null;

        $scored = $this->catalog->score_products($deduped, $search_query !== '' ? $search_query : $meta['label'], $matched_categories);
        $payload = [];
        foreach ($scored as $row) {
            $item = $this->catalog->product_payload($row['product']);
            $item['match_reason'] = $this->build_match_reason(strtolower($search_query !== '' ? $search_query : $meta['label']), $row['product'], $matched_categories);
            $payload[] = $item;
        }
        if (!$payload) return null;

        $mode = $meta['mode'];
        if ($mode === 'in_stock') {
            $payload = array_values(array_filter($payload, function($p){
                return isset($p['stock_status']) && $p['stock_status'] === 'instock';
            }));
            if (!$payload) return [
                'type' => 'fallback',
                'message' => $settings['fallback_message'],
                'products' => [],
                'suggestions' => $this->build_suggestions($search_query !== '' ? $search_query : $meta['label'], [], $matched_categories),
            ];
        }
        if ($mode === 'cheaper') {
            usort($payload, function($a, $b){ return (float)($a['price'] ?? 0) <=> (float)($b['price'] ?? 0); });
        }
        if ($mode === 'compare') {
            usort($payload, function($a, $b){
                $aStock = isset($a['stock_status']) && $a['stock_status'] === 'instock' ? 0 : 1;
                $bStock = isset($b['stock_status']) && $b['stock_status'] === 'instock' ? 0 : 1;
                if ($aStock !== $bStock) return $aStock <=> $bStock;
                return (float)($a['price'] ?? 0) <=> (float)($b['price'] ?? 0);
            });
        }

        $base_for_message = $search_query !== '' ? $search_query : ($meta['category_name'] ?: $meta['label']);
        $message = $this->build_message(strtolower($base_for_message), $payload, $matched_categories, $settings);
        if ($mode === 'cheaper') {
            $message = 'Here are cheaper matching options I found.';
        } elseif ($mode === 'in_stock') {
            $message = 'Here are matching items that are currently in stock.';
        } elseif ($mode === 'compare') {
            $message = 'Here are comparable options so shoppers can compare price and availability.';
        } elseif ($mode === 'category') {
            $message = 'Here are matching items from that category.';
        }
        $message = $this->maybe_ai_rewrite_message($base_for_message, $payload, $settings, $message);

        $view_all_url = $this->resolve_view_all_url($matched_categories, $exact_category, $payload);
        $payload = $this->attach_view_all_url($payload, $view_all_url);
        return [
            'type' => 'products',
            'message' => $message,
            'products' => array_slice($payload, 0, (int) $settings['max_results']),
            'view_all_url' => $view_all_url,
            'suggestions' => $this->build_suggestions($base_for_message, $payload, $matched_categories),
        ];
    }



    private function merge_category_matches($primary = [], $secondary = []) {
        $merged = [];
        $seen = [];
        foreach (array_merge((array) $primary, (array) $secondary) as $cat) {
            if (!is_array($cat) || empty($cat['id'])) continue;
            $id = (int) $cat['id'];
            if (isset($seen[$id])) continue;
            $seen[$id] = true;
            $merged[] = $cat;
        }
        usort($merged, function($a, $b){
            return (int) ($b['score'] ?? 0) <=> (int) ($a['score'] ?? 0);
        });
        return $merged;
    }

    private function extract_budget_max($query) {
        $query = strtolower($this->clean_text($query));
        if ($query === '') return 0.0;
        $patterns = [
            '/\b(?:under|below|less than|up to|max(?:imum)?(?:\s+price)?|within)\s*\$?\s*([0-9]+(?:\.[0-9]{1,2})?)/i',
            '/\$\s*([0-9]+(?:\.[0-9]{1,2})?)\s*(?:or less|and under|max)?/i',
            '/\b([0-9]+(?:\.[0-9]{1,2})?)\s*(?:dollars|bucks)\s*(?:or less|and under|max)?/i',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $query, $match)) {
                $amount = isset($match[1]) ? (float) $match[1] : 0.0;
                if ($amount > 0) return $amount;
            }
        }
        return 0.0;
    }

    private function extract_base_search_query($query) {
        $query = strtolower($this->clean_text($query));
        if ($query === '') return '';

        $query = str_replace('&', ' and ', $query);
        $query = preg_replace('/\b(can you|could you|please|kindly|just|maybe)\b/', ' ', $query);
        $query = preg_replace('/^(?:show me|find me|find|show|browse|looking for|look for|i want|i need|give me|help me find|can you show me|can you find me)\s+/i', '', $query);
        $query = preg_replace('/\b(?:compare|comparison|similar|similar items|similar products|like this|like these|comparable|cheaper|cheap|budget|affordable|lowest price|best price)\b/', ' ', $query);
        $query = preg_replace('/\b(?:only\s+)?in stock\b/', ' ', $query);
        $query = preg_replace('/\b(?:under|below|less than|up to|max(?:imum)?(?:\s+price)?|within)\s*\$?\s*[0-9]+(?:\.[0-9]{1,2})?/i', ' ', $query);
        $query = preg_replace('/\$\s*[0-9]+(?:\.[0-9]{1,2})?\s*(?:or less|and under|max)?/i', ' ', $query);
        $query = preg_replace('/\b[0-9]+(?:\.[0-9]{1,2})?\s*(?:dollars|bucks)\s*(?:or less|and under|max)?/i', ' ', $query);
        $query = preg_replace('/\b(?:products?|items?|options?)\b/', ' ', $query);
        $query = preg_replace('/\b(?:for me|please|thanks)\b/', ' ', $query);
        $query = preg_replace('/\s+/', ' ', $query);
        return trim($query);
    }

    private function detect_local_intent($query, $page_context = [], $matched_categories = [], $exact_category = null) {
        $normalized = strtolower($this->clean_text($query));
        $budget_max = $this->extract_budget_max($normalized);
        $base_query = $this->extract_base_search_query($normalized);

        $is_compare = strpos($normalized, 'compare') !== false || strpos($normalized, 'comparison') !== false;
        $is_similar = !$is_compare && (strpos($normalized, 'similar') !== false || strpos($normalized, 'like this') !== false || strpos($normalized, 'like these') !== false || strpos($normalized, 'comparable') !== false);
        $require_in_stock = strpos($normalized, 'in stock') !== false;
        $prefer_price_asc = (strpos($normalized, 'cheaper') !== false || strpos($normalized, 'cheap') !== false || strpos($normalized, 'budget') !== false || strpos($normalized, 'affordable') !== false || strpos($normalized, 'lowest') !== false || $budget_max > 0);
        $looks_like_browse = preg_match('/^(show me|find me|find|show|browse|looking for|look for|i want|i need|give me)\b/i', $normalized) === 1;

        if ($base_query === '') {
            if (!empty($exact_category['name'])) {
                $base_query = strtolower($this->clean_text($exact_category['name']));
            } elseif (!empty($matched_categories[0]['name'])) {
                $base_query = strtolower($this->clean_text($matched_categories[0]['name']));
            }
        }

        if ($is_similar && $base_query === '' && !empty($page_context['categories']) && is_array($page_context['categories'])) {
            $base_query = strtolower($this->clean_text($page_context['categories'][0] ?? ''));
        }
        if ($is_similar && $base_query === '' && !empty($page_context['name'])) {
            $base_query = strtolower($this->clean_text($page_context['name']));
        }

        return [
            'normalized_query' => $normalized,
            'search_query' => $base_query !== '' ? $base_query : $normalized,
            'base_query' => $base_query,
            'looks_like_browse' => $looks_like_browse,
            'is_compare' => $is_compare,
            'is_similar' => $is_similar,
            'require_in_stock' => $require_in_stock,
            'prefer_price_asc' => $prefer_price_asc,
            'budget_max' => $budget_max,
        ];
    }

    private function tool_driven_actions_enabled($settings) {
        return !isset($settings['tool_driven_store_actions']) || (int) $settings['tool_driven_store_actions'] === 1;
    }

    private function semantic_query_assist_enabled($settings) {
        return !isset($settings['semantic_query_assist']) || (int) $settings['semantic_query_assist'] === 1;
    }

    private function get_merged_faq_items($settings) {
        return $this->knowledge->get_merged_faq_items($settings);
    }

    private function has_faq_match($query, $settings) {
        return $this->knowledge->detect_knowledge_topic($query, $settings) === 'faq_answer';
    }

    private function knowledge_priority_enabled($settings) {
        return !isset($settings['knowledge_answer_priority']) || (int) $settings['knowledge_answer_priority'] === 1;
    }

    private function detect_store_action($query, $intent, $page_context, $matched_categories, $exact_category, $settings) {
        $q = strtolower($this->clean_text($query));
        if ($q === '') return 'search_products';

        $knowledge_topic = $this->knowledge->detect_knowledge_topic($query, $settings);
        if ($knowledge_topic !== '') {
            return $knowledge_topic;
        }
        if (!empty($intent['is_compare'])) {
            return 'compare_products';
        }
        if (!empty($intent['is_similar'])) {
            return 'similar_products';
        }
        if (!empty($exact_category['id']) || !empty($matched_categories)) {
            return 'browse_category';
        }
        if (!empty($page_context['product_id']) && preg_match('/\\b(related|similar|match|go with|pair with|accessories?)\\b/i', $q)) {
            return 'similar_products';
        }
        return 'search_products';
    }

    private function build_action_suggestions($action) {
        switch ($action) {
            case 'support_contact':
                return ['Track my order', 'Shipping info', 'Returns and refunds'];
            case 'shipping_policy':
                return ['Track my order', 'Returns and refunds', 'Contact support'];
            case 'returns_policy':
                return ['Shipping info', 'Track my order', 'Contact support'];
            case 'track_order':
                return ['Contact support', 'Shipping info', 'Returns and refunds'];
            case 'membership_policy':
                return ['Contact support', 'Shipping info', 'Returns and refunds'];
            case 'ask_ai_usage':
                return ['Privacy and unsubscribe', 'Contact support', 'Shipping info'];
            case 'privacy_policy':
                return ['Ask AI information use', 'Contact support', 'Track my order'];
            case 'faq_answer':
                return ['Track my order', 'Shipping info', 'Returns and refunds'];
        }
        return [];
    }

    private function execute_knowledge_action($action, $query, $settings) {
        $message = '';
        switch ($action) {
            case 'support_contact':
                $message = $this->knowledge->answer_store_question('contact support', $settings);
                break;
            case 'shipping_policy':
                $message = $this->knowledge->answer_store_question('shipping info', $settings);
                break;
            case 'returns_policy':
                $message = $this->knowledge->answer_store_question('returns and refunds', $settings);
                break;
            case 'track_order':
                $message = $this->knowledge->answer_store_question('track my order', $settings);
                if ($message === '') {
                    $message = $this->knowledge->answer_store_question('contact support', $settings);
                }
                break;
            case 'membership_policy':
                $message = $this->knowledge->answer_store_question('membership policy', $settings);
                break;
            case 'ask_ai_usage':
                $message = $this->knowledge->answer_store_question('what do you do with my information for ask ai', $settings);
                break;
            case 'privacy_policy':
                $message = $this->knowledge->answer_store_question('privacy and unsubscribe', $settings);
                break;
            case 'faq_answer':
                $message = $this->knowledge->answer_store_question($query, $settings);
                break;
        }
        if ($message === '') return [];
        return [
            'type' => 'knowledge',
            'action' => $action,
            'message' => $message,
            'products' => [],
            'suggestions' => $this->build_action_suggestions($action),
        ];
    }

    private function product_numeric_price($product) {
        $price = isset($product['price']) ? (float) $product['price'] : 0.0;
        if ($price > 0) return $price;
        $plain = preg_replace('/[^0-9.]+/', '', (string) ($product['price_html'] ?? ''));
        return $plain !== '' ? (float) $plain : 0.0;
    }

    private function build_filtered_no_results_message($intent, $base_query, $settings) {
        $label = $this->clean_text($base_query);
        $budget_label = !empty($intent['budget_max']) ? wp_strip_all_tags(wc_price((float) $intent['budget_max'])) : '';
        if (!empty($intent['budget_max']) && !empty($intent['require_in_stock'])) {
            return 'I found possible matches, but none are currently in stock at or below ' . $budget_label . '. Try a higher budget or remove the in-stock filter.';
        }
        if (!empty($intent['budget_max'])) {
            return 'I found possible matches, but none are at or below ' . $budget_label . '. Try a higher budget or ask for cheaper options.';
        }
        if (!empty($intent['require_in_stock'])) {
            return $label !== ''
                ? 'I found possible matches for ' . $label . ', but none are currently showing as in stock. Try a broader search or remove the in-stock filter.'
                : 'I found possible matches, but none are currently showing as in stock. Try a broader search or remove the in-stock filter.';
        }
        return $settings['fallback_message'];
    }

    private function build_intent_message($intent, $query, $products, $categories, $settings) {
        $base = $this->clean_text(!empty($intent['base_query']) ? $intent['base_query'] : $query);
        $budget_label = !empty($intent['budget_max']) ? wp_strip_all_tags(wc_price((float) $intent['budget_max'])) : '';

        if (!empty($intent['is_compare']) && count($products) >= 2) {
            $names = array_slice(array_map(function($p){ return $p['name']; }, $products), 0, 3);
            return 'Here are a few options to compare' . ($base !== '' ? ' for ' . $base : '') . ': ' . implode(', ', $names) . '.';
        }
        if (!empty($intent['is_similar'])) {
            return $base !== ''
                ? 'Here are similar items I found for ' . $base . '.'
                : 'Here are similar items I found.';
        }
        if (!empty($intent['budget_max']) && !empty($intent['require_in_stock'])) {
            return 'Here are matching items currently in stock at or below ' . $budget_label . '.';
        }
        if (!empty($intent['budget_max'])) {
            return 'Here are matching items at or below ' . $budget_label . '.';
        }
        if (!empty($intent['require_in_stock'])) {
            return 'Here are matching items that are currently in stock.';
        }
        if (!empty($intent['prefer_price_asc'])) {
            return 'I sorted these toward lower-priced options first so you can review the more budget-friendly matches.';
        }

        return $this->build_message($query, $products, $categories, $settings);
    }

    public function handle_query($query, $page_context = [], $suggestion_meta = []) {
        $settings = $this->settings->get_all();
        $normalized = strtolower(trim((string) $query));
        $suggestion_meta = $this->normalize_suggestion_meta($suggestion_meta);
        $synonyms = $this->merged_synonyms($settings);
        $matched_categories = $this->catalog->find_matching_categories($query, $synonyms);
        $exact_category = $this->catalog->find_best_category_match($query, $synonyms);
        if (!empty($suggestion_meta['category_name'])) {
            $meta_categories = $this->catalog->find_matching_categories($suggestion_meta['category_name'], $synonyms);
            if (!empty($meta_categories)) $matched_categories = $meta_categories;
            $meta_exact_category = $this->catalog->find_best_category_match($suggestion_meta['category_name'], $synonyms);
            if (!empty($meta_exact_category)) $exact_category = $meta_exact_category;
        }
        if (!empty($suggestion_meta['mode'])) {
            $structured_result = $this->apply_structured_suggestion($suggestion_meta, $matched_categories, $settings, $synonyms);
            if (!empty($structured_result)) return $structured_result;
        }

        $product_answer = $this->build_product_context_answer($query, $page_context);
        if (!empty($product_answer) && ($this->is_product_context_query($query) || $this->is_key_details_query($query) || $this->is_more_information_query($query))) return $product_answer;

        if (!empty($product_answer)) return $product_answer;

        $intent = $this->detect_local_intent($query, $page_context, $matched_categories, $exact_category);
        $store_action = $this->tool_driven_actions_enabled($settings)
            ? $this->detect_store_action($query, $intent, $page_context, $matched_categories, $exact_category, $settings)
            : '';

        if ($this->knowledge_priority_enabled($settings) && $store_action !== '' && in_array($store_action, ['support_contact', 'shipping_policy', 'returns_policy', 'track_order', 'membership_policy', 'ask_ai_usage', 'privacy_policy', 'faq_answer'], true)) {
            $knowledge_result = $this->execute_knowledge_action($store_action, $query, $settings);
            if (!empty($knowledge_result)) return $knowledge_result;
        }

        $store_answer = $this->knowledge->answer_store_question($query, $settings);
        if ($store_answer) {
            return [ 'type' => 'knowledge', 'action' => $store_action !== '' ? $store_action : 'knowledge', 'message' => $store_answer, 'products' => [], 'suggestions' => $this->build_action_suggestions($store_action !== '' ? $store_action : 'faq_answer') ];
        }

        $effective_query = !empty($intent['search_query']) ? $intent['search_query'] : $query;
        if ($effective_query !== '' && strtolower($this->clean_text($effective_query)) !== strtolower($this->clean_text($query))) {
            $intent_categories = $this->catalog->find_matching_categories($effective_query, $synonyms);
            $matched_categories = $this->merge_category_matches($intent_categories, $matched_categories);
            $intent_exact_category = $this->catalog->find_best_category_match($effective_query, $synonyms);
            if (!empty($intent_exact_category)) $exact_category = $intent_exact_category;
        }

        if ($store_action === 'similar_products' && !empty($page_context['categories']) && is_array($page_context['categories'])) {
            $context_category_query = implode(' ', array_map(function($category_name){ return (string) $category_name; }, $page_context['categories']));
            if ($context_category_query !== '') {
                $context_categories = $this->catalog->find_matching_categories($context_category_query, $synonyms);
                $matched_categories = $this->merge_category_matches($context_categories, $matched_categories);
                if (empty($exact_category)) {
                    $context_exact_category = $this->catalog->find_best_category_match($context_category_query, $synonyms);
                    if (!empty($context_exact_category)) $exact_category = $context_exact_category;
                }
            }
        }

        if ($store_action === 'browse_category' && !empty($exact_category['id'])) {
            $effective_query = '';
        }

        $limit = max(8, (int) $settings['max_results']);
        $candidate_limit = max($limit, (int) ($settings['semantic_candidate_pool'] ?? 24));
        $primary_category_ids = !empty($exact_category['id']) ? [absint($exact_category['id'])] : wp_list_pluck(array_slice($matched_categories, 0, 6), 'id');
        $args = [ 'limit' => $limit, 'candidate_limit' => $candidate_limit, 'category_ids' => $primary_category_ids ];

        if ($this->semantic_query_assist_enabled($settings)) {
            $products = $this->catalog->search_products_semantic($effective_query, $args, $synonyms, $matched_categories);
        } else {
            $products = $this->catalog->search_products($effective_query, $args);
        }

        if ((!$products || count($products) < 2) && !empty($exact_category['id'])) {
            $exact_only_products = $this->catalog->search_products('', [ 'limit' => $candidate_limit, 'category_ids' => [absint($exact_category['id'])] ]);
            if ($exact_only_products) $products = array_merge($products ? $products : [], $exact_only_products);
        }
        if ((!$products || count($products) < 2) && !empty($exact_category['parent'])) {
            $parent_products = $this->catalog->search_products('', [ 'limit' => $candidate_limit, 'category_ids' => [absint($exact_category['parent'])] ]);
            if ($parent_products) $products = array_merge($products ? $products : [], $parent_products);
        }
        if ((!$products || count($products) < 2) && !empty($matched_categories)) {
            $broader_args = [ 'limit' => $candidate_limit, 'category_ids' => wp_list_pluck(array_slice($matched_categories, 0, 6), 'id') ];
            $fallback_products = $this->catalog->search_products('', $broader_args);
            if ($fallback_products) $products = array_merge($products ? $products : [], $fallback_products);
        }
        if (!$products) $products = $this->catalog->search_products($effective_query, [ 'limit' => $candidate_limit ]);

        $seen = [];
        $deduped = [];
        foreach ((array) $products as $product) {
            if (!$product || isset($seen[$product->get_id()])) continue;
            $seen[$product->get_id()] = true;
            $deduped[] = $product;
        }

        $scored = $this->catalog->score_products($deduped, $effective_query, $matched_categories);
        $payload = [];
        foreach ($scored as $row) {
            $item = $this->catalog->product_payload($row['product']);
            $item['match_reason'] = $this->build_match_reason(strtolower($effective_query), $row['product'], $matched_categories);
            $payload[] = $item;
        }

        $unfiltered_payload = $payload;
        if (!empty($intent['require_in_stock'])) {
            $payload = array_values(array_filter($payload, function($p){ return ($p['stock_status'] ?? '') === 'instock'; }));
        }
        if (!empty($intent['budget_max'])) {
            $budget_cap = (float) $intent['budget_max'];
            $payload = array_values(array_filter($payload, function($p) use ($budget_cap){
                $price = $this->product_numeric_price($p);
                return $price > 0 && $price <= $budget_cap;
            }));
        }
        if (!empty($intent['prefer_price_asc'])) {
            usort($payload, function($a,$b){ return $this->product_numeric_price($a) <=> $this->product_numeric_price($b); });
        }
        if (!empty($intent['is_compare'])) {
            usort($payload, function($a, $b){
                $aStock = (($a['stock_status'] ?? '') === 'instock') ? 0 : 1;
                $bStock = (($b['stock_status'] ?? '') === 'instock') ? 0 : 1;
                if ($aStock !== $bStock) return $aStock <=> $bStock;
                return $this->product_numeric_price($a) <=> $this->product_numeric_price($b);
            });
        }

        if (!$payload) {
            $message = !empty($unfiltered_payload) ? $this->build_filtered_no_results_message($intent, $effective_query, $settings) : $settings['fallback_message'];
            $suggestion_base = !empty($intent['base_query']) ? $intent['base_query'] : $effective_query;
            return [ 'type' => 'fallback', 'action' => $store_action !== '' ? $store_action : 'search_products', 'message' => $message, 'products' => [], 'suggestions' => $this->build_suggestions($suggestion_base, [], $matched_categories) ];
        }

        $message = $this->build_intent_message($intent, strtolower($effective_query), $payload, $matched_categories, $settings);
        $message = $this->maybe_ai_rewrite_message($effective_query, $payload, $settings, $message);
        $view_all_url = $this->resolve_view_all_url($matched_categories, $exact_category, $payload);
        $payload = $this->attach_view_all_url($payload, $view_all_url);
        $suggestion_base = !empty($intent['base_query']) ? $intent['base_query'] : $effective_query;
        return [ 'type' => 'products', 'action' => $store_action !== '' ? $store_action : 'search_products', 'message' => $message, 'products' => array_slice($payload, 0, (int)$settings['max_results']), 'view_all_url' => $view_all_url, 'suggestions' => $this->build_suggestions($suggestion_base, $payload, $matched_categories) ];
    }

    private function merged_synonyms($settings) {
        $base = $this->knowledge->parse_json($settings['category_synonyms_json'], []);
        $visual = $this->knowledge->parse_json($settings['synonym_items'], []);
        if (is_array($visual)) {
            foreach ($visual as $row) {
                $category_key = '';
                if (!empty($row['category_id'])) {
                    $category_key = trim((string) $row['category_id']);
                } elseif (!empty($row['category'])) {
                    $category_key = trim((string) $row['category']);
                }
                $phrase = isset($row['phrase']) ? trim((string) $row['phrase']) : '';
                if ($category_key === '' || $phrase === '') continue;
                if (!isset($base[$category_key]) || !is_array($base[$category_key])) $base[$category_key] = [];
                $base[$category_key][] = $phrase;
            }
        }
        return $base;
    }

    private function extract_ai_content($content) {
        if (is_string($content)) {
            return trim($content);
        }

        if (is_array($content)) {
            $parts = [];
            foreach ($content as $part) {
                if (is_string($part) && trim($part) !== '') {
                    $parts[] = trim($part);
                    continue;
                }
                if (!is_array($part)) {
                    continue;
                }
                if (isset($part['text']) && is_string($part['text']) && trim($part['text']) !== '') {
                    $parts[] = trim($part['text']);
                    continue;
                }
                if (isset($part['content']) && is_string($part['content']) && trim($part['content']) !== '') {
                    $parts[] = trim($part['content']);
                }
            }
            return trim(implode("\n", $parts));
        }

        return '';
    }

    private function ai_output_looks_structured($text) {
        $text = trim((string) $text);
        if ($text === '') return false;

        $lower = strtolower($text);
        $signals = [
            '**name:**', '**price:**', '**link:**', '**image:**', '**stock status:**', '**match reason:**',
            '"name":', '"price":', '"link":', '"image":', '"stock_status":', '"match_reason":',
            '[view product]', 'price range:', 'stock status:', 'match reason:', 'permalink:', 'image url:', 'category url:'
        ];
        foreach ($signals as $signal) {
            if (strpos($lower, $signal) !== false) return true;
        }

        if (preg_match('#https?://[^\s)]+#i', $text)) return true;
        if (preg_match('/\b(name|price|link|image|permalink|match reason)\s*:/i', $text) && preg_match('/\b(stock status|price range|category url)\s*:/i', $text)) return true;
        if (preg_match('/^[\[{].*[\]}]$/s', $text)) return true;
        if (substr_count($text, "\n") >= 3 && preg_match('/[:\[\]{}]/', $text)) return true;

        return false;
    }

    private function readable_stock_label($status) {
        $status = strtolower($this->clean_text($status));
        if ($status === 'instock') return 'In stock';
        if ($status === 'outofstock') return 'Out of stock';
        if ($status === 'onbackorder') return 'Backorder';
        return $status !== '' ? ucfirst($status) : '';
    }

    private function product_summary_for_ai($product) {
        if (!is_array($product)) return [];
        $summary = [
            'name' => $this->clean_text($product['name'] ?? ''),
            'price' => $this->clean_text($product['price_html'] ?? ($product['price'] ?? '')),
            'stock' => $this->readable_stock_label($product['stock_status'] ?? ''),
            'categories' => [],
            'short_summary' => $this->truncate($product['short_description'] ?? '', 160),
            'match_reason' => $this->clean_text($product['match_reason'] ?? ''),
        ];

        if (!empty($product['categories']) && is_array($product['categories'])) {
            foreach ($product['categories'] as $category) {
                $category = $this->clean_text($category);
                if ($category !== '') $summary['categories'][] = $category;
                if (count($summary['categories']) >= 3) break;
            }
        }

        $summary['categories'] = array_values(array_unique($summary['categories']));
        return array_filter($summary, function($value) {
            if (is_array($value)) return !empty($value);
            return $value !== '' && $value !== null;
        });
    }

    private function grounded_products_for_ai($products, $settings) {
        $limit = max(1, min(6, (int) ($settings['ai_grounded_context_limit'] ?? 3)));
        if (($settings['ai_provider'] ?? '') === 'ollama_local') {
            $limit = min($limit, 2);
        }
        $items = [];
        foreach (array_slice((array) $products, 0, $limit) as $product) {
            $summary = $this->product_summary_for_ai($product);
            if (!empty($summary)) $items[] = $summary;
        }
        return $items;
    }

    private function build_ai_rewrite_messages($query, $products, $settings, $fallback) {
        $grounded_mode = !empty($settings['ai_grounded_reply_mode']);
        $product_context = $grounded_mode ? $this->grounded_products_for_ai($products, $settings) : array_slice((array) $products, 0, 4);
        $store_facts = [];
        foreach (['support_email', 'contact_url', 'shipping_policy', 'shipping_url', 'returns_policy', 'returns_url', 'tracking_url', 'membership_policy', 'membership_url', 'ask_ai_usage_policy', 'privacy_summary'] as $fact_key) {
            $fact_value = $this->clean_text($settings[$fact_key] ?? '');
            if ($fact_value !== '') $store_facts[$fact_key] = $this->truncate($fact_value, 220);
        }

        $system_parts = [
            'You are PricZone AI Concierge.',
            'Write one short plain-text shopper-friendly reply.',
            'Use only the supplied store facts and grounded product summaries.',
            'Do not invent policies, prices, stock, links, or product details.',
            'Do not output JSON, markdown, bullet lists, raw field names, image URLs, product URLs, or payload dumps.',
            'Mention at most three product names.',
            'Keep the answer tight and natural for a shopper chat.',
        ];
        if ($grounded_mode) {
            $system_parts[] = 'The plugin renders the actual product cards separately, so your job is only the short shopper-facing message.';
        }

        return [
            ['role' => 'system', 'content' => implode(' ', $system_parts)],
            ['role' => 'user', 'content' => wp_json_encode([
                'query' => $this->clean_text($query),
                'grounded_products' => $product_context,
                'store_facts' => $store_facts,
                'fallback' => $this->clean_text($fallback),
            ])],
        ];
    }

    private function normalize_ai_rewrite_message($text, $fallback, $settings = []) {
        $text = $this->clean_text($text);
        $text = str_replace(['**', '__', '```'], '', $text);
        $text = preg_replace('/\[(.*?)\]\((https?:\/\/[^)]+)\)/i', '$1', $text);
        $text = trim(preg_replace('/\s+/', ' ', (string) $text));
        if ($text === '') return '';
        if ($this->ai_output_looks_structured($text)) return $this->clean_text($fallback);
        $text = $this->truncate($text, $this->ai_reply_char_limit($settings));
        return $text;
    }

    private function maybe_ai_rewrite_message($query, $products, $settings, $fallback_message) {
        if ($this->should_skip_ai_rewrite($query, $products, $settings)) {
            return $fallback_message;
        }
        $rewrite = $this->ai_rewrite($query, $products, $settings, $fallback_message);
        return $rewrite !== '' ? $rewrite : $fallback_message;
    }

    private function maybe_ai_rewrite_product_context_message($query, $page_context, $settings, $fallback_message) {
        if (empty($page_context) || !is_array($page_context)) return $fallback_message;
        $product = [
            'name' => $page_context['name'] ?? '',
            'price_html' => $page_context['price_html'] ?? '',
            'stock_status' => $page_context['stock_status'] ?? '',
            'short_description' => $page_context['short_description'] ?? ($page_context['description'] ?? ''),
            'categories' => $page_context['categories'] ?? [],
        ];
        $rewrite = $this->ai_rewrite($query, [$product], $settings, $fallback_message);
        return $rewrite !== '' ? $rewrite : $fallback_message;
    }


    private function is_fast_listing_query($query) {
        $q = strtolower($this->clean_text($query));
        if ($q === '') return false;

        foreach (['shipping', 'return', 'refund', 'policy', 'track', 'order', 'where is', 'what is', 'how do', 'why', 'about this product', 'about this item', 'details', 'description', 'more information'] as $phrase) {
            if (strpos($q, $phrase) !== false) return false;
        }

        if (strpos($q, 'compare') !== false) return false;

        $starts = ['show me', 'find me', 'find', 'show', 'looking for', 'i want', 'i need', 'browse'];
        foreach ($starts as $start) {
            if (strpos($q, $start) === 0) return true;
        }

        return preg_match('/^[a-z0-9\s&\-]{2,60}$/', $q) === 1;
    }

    private function should_skip_ai_rewrite($query, $products, $settings) {
        if (($settings['ai_provider'] ?? '') !== 'ollama_local') return false;
        if (empty($settings['ai_grounded_reply_mode'])) return false;
        if (empty($products) || !is_array($products)) return false;
        return $this->is_fast_listing_query($query);
    }

    private function build_message($query, $products, $categories, $settings) {
        if (strpos($query, 'compare') !== false && count($products) >= 2) {
            $top = array_slice($products, 0, 3);
            return 'Here are a few products to compare: ' . implode(', ', array_map(function($p){ return $p['name']; }, $top)) . '. I selected them based on your request and store relevance.';
        } elseif (strpos($query, 'cheaper') !== false || strpos($query, 'budget') !== false) {
            return 'I sorted these toward lower-priced options first so you can review the more budget-friendly matches.';
        } else {
            $names = array_slice(array_map(function($p){ return $p['name']; }, $products), 0, 3);
            $cat_text = $categories ? ' I also used matching store categories to narrow the results.' : '';
            return 'I found some products that may fit “' . wp_strip_all_tags($query) . '”, including ' . implode(', ', $names) . '.' . $cat_text;
        }
    }

    private function normalize_ollama_endpoint($endpoint) {
        $endpoint = trim((string) $endpoint);
        if ($endpoint === '') {
            $endpoint = 'http://127.0.0.1:11434';
        }
        return rtrim($endpoint, '/');
    }

    private function ai_rewrite($query, $products, $settings, $fallback) {
        if (empty($settings['ai_provider']) || $settings['ai_provider'] === 'none') return '';

        $provider = (string) $settings['ai_provider'];
        if ($this->local_only_mode_enabled($settings) && in_array($provider, ['openai', 'openrouter', 'github_models'], true)) {
            $this->log_ai_debug($settings, 'provider=' . $provider . ' status=blocked_local_only', $query);
            return '';
        }
        $url = '';
        $model = '';
        $api_key = '';
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'model' => '',
            'messages' => $this->build_ai_rewrite_messages($query, $products, $settings, $fallback),
            'temperature' => 0.2,
        ];

        if ($provider === 'openai') {
            $api_key = trim((string) ($settings['openai_api_key'] ?? ''));
            $model = trim((string) ($settings['openai_model'] ?? 'gpt-4o-mini'));
            $url = 'https://api.openai.com/v1/chat/completions';
        } elseif ($provider === 'openrouter') {
            $api_key = trim((string) ($settings['openrouter_api_key'] ?? ''));
            $model = trim((string) ($settings['openrouter_model'] ?? 'openai/gpt-4o-mini'));
            $url = 'https://openrouter.ai/api/v1/chat/completions';
            $headers['HTTP-Referer'] = home_url('/');
            $headers['X-Title'] = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
        } elseif ($provider === 'github_models') {
            $api_key = trim((string) ($settings['github_models_api_key'] ?? ''));
            $model = trim((string) ($settings['github_models_model'] ?? 'openai/gpt-4o-mini'));
            $url = 'https://models.github.ai/inference/chat/completions';
            $headers['Accept'] = 'application/vnd.github+json';
            $headers['X-GitHub-Api-Version'] = '2026-03-10';
        } elseif ($provider === 'ollama_local') {
            $model = trim((string) ($settings['ollama_model'] ?? ''));
            $url = $this->normalize_ollama_endpoint($settings['ollama_endpoint'] ?? '') . '/api/chat';
            $body['stream'] = false;
        } else {
            return '';
        }

        if ($model === '' || $url === '') {
            return '';
        }

        $body['model'] = $model;

        if ($provider !== 'ollama_local') {
            if ($api_key === '') {
                return '';
            }
            $headers['Authorization'] = 'Bearer ' . $api_key;
        }

        $timeout = $this->ai_timeout_for_provider($provider, $settings);
        $request_started = microtime(true);
        $response = wp_remote_post($url, [
            'timeout' => $timeout,
            'headers' => $headers,
            'body' => wp_json_encode($body),
        ]);

        $elapsed_ms = (int) round((microtime(true) - $request_started) * 1000);
        if (is_wp_error($response)) {
            $this->log_ai_debug($settings, 'provider=' . $provider . ' status=wp_error timeout=' . $timeout . 's ms=' . $elapsed_ms, $query);
            return '';
        }

        $status = (int) wp_remote_retrieve_response_code($response);
        if ($status < 200 || $status >= 300) {
            $this->log_ai_debug($settings, 'provider=' . $provider . ' status=http_' . $status . ' timeout=' . $timeout . 's ms=' . $elapsed_ms, $query);
            return '';
        }

        $json = json_decode(wp_remote_retrieve_body($response), true);
        if (!is_array($json)) {
            $this->log_ai_debug($settings, 'provider=' . $provider . ' status=invalid_json timeout=' . $timeout . 's ms=' . $elapsed_ms, $query);
            return '';
        }

        if ($provider === 'ollama_local') {
            $content = $json['message']['content'] ?? '';
        } else {
            $content = $json['choices'][0]['message']['content'] ?? ($json['choices'][0]['text'] ?? '');
        }
        $this->log_ai_debug($settings, 'provider=' . $provider . ' status=ok timeout=' . $timeout . 's ms=' . $elapsed_ms, $query);
        return $this->normalize_ai_rewrite_message($this->extract_ai_content($content), $fallback, $settings);
    }
}
