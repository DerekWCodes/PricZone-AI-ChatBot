<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

class Knowledge {
    public function parse_json($json, $default = []) {
        $decoded = json_decode((string)$json, true);
        return is_array($decoded) ? $decoded : $default;
    }

    private function clean_text($text) {
        $text = wp_strip_all_tags((string) $text, true);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        return trim((string) $text);
    }

    private function normalize_query($query) {
        $query = strtolower($this->clean_text($query));
        return trim((string) preg_replace('/\s+/', ' ', $query));
    }

    private function unique_parts($parts) {
        $seen = [];
        $out = [];
        foreach ((array) $parts as $part) {
            $part = $this->clean_text($part);
            if ($part === '' || isset($seen[$part])) continue;
            $seen[$part] = true;
            $out[] = $part;
        }
        return $out;
    }

    public function get_merged_faq_items($settings) {
        $faq = $this->parse_json($settings['faq_json'] ?? '[]', []);
        $visual = $this->parse_json($settings['faq_items'] ?? '[]', []);
        if (is_array($visual)) {
            foreach ($visual as $row) {
                if (!empty($row['q']) && !empty($row['a'])) {
                    $faq[] = ['q' => $row['q'], 'a' => $row['a']];
                }
            }
        }
        $merged = [];
        foreach ((array) $faq as $item) {
            $question = $this->clean_text($item['q'] ?? '');
            $answer = $this->clean_text($item['a'] ?? '');
            if ($question === '' || $answer === '') continue;
            $merged[] = ['q' => $question, 'a' => $answer];
        }
        return $merged;
    }

    private function topic_patterns() {
        return [
            'support_contact' => '/\b(contact|customer service|support email|support phone|phone number|call support|email support|contact support|help desk|how do i contact)\b/i',
            'shipping_policy' => '/\b(ship|shipping|delivery|deliveries|delivered|arrival|when will it arrive|how long does shipping take|shipping info|shipping information)\b/i',
            'returns_policy' => '/\b(return|returns|refund|refunds|exchange|exchanges|money back|cancel order|cancel my order|cancelation|cancellation|return policy|refund policy)\b/i',
            'track_order' => '/\b(track( my)? order|tracking|order status|where is my order|where\'s my order)\b/i',
            'membership_policy' => '/\b(membership|member benefits|subscription|subscribe|priczone prime|prime membership|membership plan)\b/i',
            'ask_ai_usage' => '/\b(ask ai|ai chat|what do you do with my information|use my information|why do you need my email|why do you need my name|agreement|usage of information|how is my information used)\b/i',
            'privacy_policy' => '/\b(privacy|personal information|my information|my data|data use|data usage|unsubscribe|remove my email|delete my information|opt out|opt-out)\b/i',
        ];
    }

    public function detect_knowledge_topic($query, $settings = []) {
        $q = $this->normalize_query($query);
        if ($q === '') return '';

        foreach ($this->topic_patterns() as $topic => $pattern) {
            if (preg_match($pattern, $q)) return $topic;
        }

        foreach ($this->get_merged_faq_items($settings) as $item) {
            $question = strtolower($item['q']);
            if ($question !== '' && (strpos($q, $question) !== false || strpos($question, $q) !== false)) {
                return 'faq_answer';
            }
        }

        return '';
    }

    private function answer_support_contact($settings) {
        $parts = [];
        if (!empty($settings['support_email'])) $parts[] = 'You can reach us by email at ' . $this->clean_text($settings['support_email']) . '.';
        if (!empty($settings['support_phone'])) $parts[] = 'Phone: ' . $this->clean_text($settings['support_phone']) . '.';
        if (!empty($settings['contact_url'])) $parts[] = 'Contact page: ' . $this->clean_text($settings['contact_url']) . '.';
        return implode(' ', $this->unique_parts($parts));
    }

    private function answer_shipping_policy($settings) {
        $parts = [];
        if (!empty($settings['shipping_policy'])) $parts[] = $settings['shipping_policy'];
        if (!empty($settings['shipping_url'])) $parts[] = 'Shipping details: ' . $this->clean_text($settings['shipping_url']) . '.';
        if (!empty($settings['contact_url'])) $parts[] = 'For order-specific shipping help, contact support here: ' . $this->clean_text($settings['contact_url']) . '.';
        return implode(' ', $this->unique_parts($parts));
    }

    private function answer_returns_policy($settings) {
        $parts = [];
        if (!empty($settings['returns_policy'])) $parts[] = $settings['returns_policy'];
        if (!empty($settings['returns_url'])) $parts[] = 'Returns and refunds details: ' . $this->clean_text($settings['returns_url']) . '.';
        if (!empty($settings['contact_url'])) $parts[] = 'If you need order-specific help, contact support here: ' . $this->clean_text($settings['contact_url']) . '.';
        return implode(' ', $this->unique_parts($parts));
    }

    private function answer_track_order($settings) {
        $parts = [];
        if (!empty($settings['tracking_url'])) {
            $parts[] = 'You can track your order here: ' . $this->clean_text($settings['tracking_url']) . '.';
        }
        if (!empty($settings['contact_url'])) {
            $parts[] = 'If you need help with tracking, contact support here: ' . $this->clean_text($settings['contact_url']) . '.';
        } elseif (!empty($settings['support_email'])) {
            $parts[] = 'If you need help with tracking, email us at ' . $this->clean_text($settings['support_email']) . '.';
        }
        return implode(' ', $this->unique_parts($parts));
    }

    private function answer_membership_policy($settings) {
        $parts = [];
        if (!empty($settings['membership_policy'])) $parts[] = $settings['membership_policy'];
        if (!empty($settings['membership_url'])) $parts[] = 'Membership details: ' . $this->clean_text($settings['membership_url']) . '.';
        if (!empty($settings['contact_url'])) $parts[] = 'If you need help with membership questions, contact support here: ' . $this->clean_text($settings['contact_url']) . '.';
        return implode(' ', $this->unique_parts($parts));
    }

    private function answer_ask_ai_usage($settings) {
        $parts = [];
        if (!empty($settings['ask_ai_usage_policy'])) $parts[] = $settings['ask_ai_usage_policy'];
        if (!empty($settings['privacy_summary'])) $parts[] = $settings['privacy_summary'];
        if (!empty($settings['contact_url'])) $parts[] = 'For more help, contact support here: ' . $this->clean_text($settings['contact_url']) . '.';
        return implode(' ', $this->unique_parts($parts));
    }

    private function answer_privacy_policy($settings) {
        $parts = [];
        if (!empty($settings['privacy_summary'])) $parts[] = $settings['privacy_summary'];
        if (!empty($settings['ask_ai_usage_policy'])) $parts[] = $settings['ask_ai_usage_policy'];
        if (!empty($settings['contact_url'])) $parts[] = 'If you need help with removal or privacy questions, contact support here: ' . $this->clean_text($settings['contact_url']) . '.';
        return implode(' ', $this->unique_parts($parts));
    }

    private function answer_faq($query, $settings) {
        $q = $this->normalize_query($query);
        if ($q === '') return '';

        $query_tokens = array_values(array_filter(array_unique(preg_split('/[^a-z0-9]+/i', $q)), function($token) {
            return is_string($token) && strlen($token) >= 3;
        }));

        $best_answer = '';
        $best_score = 0.0;
        foreach ($this->get_merged_faq_items($settings) as $item) {
            $question = strtolower($item['q']);
            $answer = $item['a'];
            if ($question === '') continue;
            if (strpos($q, $question) !== false || strpos($question, $q) !== false) {
                return $answer;
            }
            $question_tokens = array_values(array_filter(array_unique(preg_split('/[^a-z0-9]+/i', $question)), function($token) {
                return is_string($token) && strlen($token) >= 3;
            }));
            if (!$query_tokens || !$question_tokens) continue;
            $overlap = array_intersect($query_tokens, $question_tokens);
            $score = count($overlap) / max(1, count($question_tokens));
            if (count($overlap) >= 2) $score += 0.35;
            if ($score > $best_score) {
                $best_score = $score;
                $best_answer = $answer;
            }
        }

        return $best_score >= 0.45 ? $best_answer : '';
    }

    public function answer_store_question($query, $settings) {
        $topic = $this->detect_knowledge_topic($query, $settings);
        switch ($topic) {
            case 'support_contact':
                return $this->answer_support_contact($settings);
            case 'shipping_policy':
                return $this->answer_shipping_policy($settings);
            case 'returns_policy':
                return $this->answer_returns_policy($settings);
            case 'track_order':
                return $this->answer_track_order($settings);
            case 'membership_policy':
                return $this->answer_membership_policy($settings);
            case 'ask_ai_usage':
                return $this->answer_ask_ai_usage($settings);
            case 'privacy_policy':
                return $this->answer_privacy_policy($settings);
            case 'faq_answer':
                return $this->answer_faq($query, $settings);
        }

        $faq_answer = $this->answer_faq($query, $settings);
        if ($faq_answer !== '') return $faq_answer;

        return '';
    }
}
