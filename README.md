# PricZone AI Concierge

Version: 5.2.2

## What this plugin does
PricZone AI Concierge adds an Ask AI shopping assistant to WooCommerce. It helps visitors discover products, browse categories, compare options, and get approved store-help answers without exposing raw product payload data in chat.

## Current focus
This build keeps the plugin Ollama-friendly while still using WooCommerce and the plugin itself as the source of truth for product cards, stock, prices, links, and approved support answers.

## Changed in 5.2.2
- Expanded the existing FAQ and policy knowledge layer instead of adding a duplicate answer system.
- Added approved knowledge fields for shipping info URL, returns URL, tracking URL, membership policy, membership URL, Ask AI usage policy, and privacy summary.
- Improved support and FAQ matching so Ask AI can answer approved store-help questions first.
- Added a response control to prefer approved knowledge answers before any AI phrasing is considered.

## Main plugin areas
- Ask AI storefront widget
- WooCommerce product discovery and category-aware search
- Grounded AI rewrite mode
- Tool-driven store actions
- Semantic query assist
- Store knowledge and FAQ answers
- Visitor gate and unsubscribe handling
- Conversion tracking

## Installation
1. Upload the overwrite-ready ZIP in WordPress.
2. Replace the existing `priczone-ai-concierge` plugin folder.
3. Activate or keep the plugin active.
4. Open the Ask AI plugin settings page.
5. Review the Store Knowledge tab and fill in approved policy URLs and summaries.

## Recommended setup
- AI provider: Ollama Local
- Fallback: None (rules only)
- Keep grounded AI reply mode enabled
- Keep tool-driven store actions enabled
- Keep approved knowledge answers enabled

## Notes
This plugin should keep using WooCommerce and approved store knowledge as the source of truth. AI should improve phrasing and routing, not invent store facts.
