# PricZone AI Concierge

Version: 5.4.0e

## What this plugin does
PricZone AI Concierge adds an Ask AI shopping assistant to WooCommerce. It helps visitors discover products, browse categories, compare options, and get approved store-help answers without exposing raw product payload data in chat.

## Current focus
This build keeps the plugin Ollama-friendly while still using WooCommerce and the plugin itself as the source of truth for product cards, stock, prices, links, and approved support answers.

## Changed in 5.4.0d
- Added Groq as an optional hosted AI provider while keeping the existing grounded reply, tool-driven store actions, approved knowledge answers, and rules-first safeguards in place.
- Added Groq API key and Groq model fields inside the existing AI Integration section instead of creating a second AI system.
- Kept Ollama Local and None (rules only) as the recommended setup, with Groq available when you want a faster hosted provider.

## Changed in 5.4.0a
- Added a Refresh button to the Analytics Overview header so you can pull in new analytics entries without refreshing the whole admin page.
- The refresh button now stays hidden while the Analytics Overview accordion is closed and appears only when that section is open.
- Updated the existing analytics panel to reload over AJAX so new query and debug entries can show up in place while you stay on the same spot in the admin page.

## Changed in 5.4.0
- Added session memory so Ask AI can remember the current shopper session context like the last product type, category, budget, and in-stock preference during the active chat session.
- Added response controls to turn session memory on or off and choose how many recent turns should be remembered in the current session.
- Updated the existing Ask AI routing layer so short follow-up prompts like cheaper options, only in stock, similar items, compare these, or under a new budget can reuse the current session context instead of acting like a brand new search every time.

## Changed in 5.3.1b
- Upgraded the existing follow-up chip system into smarter suggestion chips so Ask AI can show better next-step buttons like cheaper, in-stock, compare, similar, shipping, returns, and support based on the current result.
- Added new response controls for smart suggestion chips and chip count instead of adding a second suggestion system.
- Updated structured chip routing so support and policy chips can call the existing knowledge answers directly, while product chips keep using the existing grounded WooCommerce pipeline.

## Changed in 5.3.0
- Added a new Safety & Performance section so you can control local-only AI mode, Ollama timeout, external AI timeout, AI reply length caps, and lightweight AI debug logging without replacing the existing Ask AI workflow.
- Updated the existing AI rewrite layer to respect those safety controls, including optional local-only blocking of legacy external providers and configurable timeout behavior.
- Added AI debug event visibility in Analytics Overview so you can review recent provider outcomes, timeouts, and local-only blocks while testing.

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


## Changelog

### 5.4.0e
- Added hosted AI connection tests for Groq, OpenAI, OpenRouter, and GitHub Models.
- Each probe sends a tiny test prompt only and does not send product data or save settings.
- Added clearer probe feedback with elapsed time and a reminder when Local-only AI mode is still enabled.
