== Version 5.2.2 ==
- Expanded the existing Store Knowledge layer so Ask AI now answers approved support, shipping, returns, tracking, membership, Ask AI usage, privacy, and unsubscribe-style questions from stored knowledge first.
- Added new Store Knowledge fields for approved shipping, returns, and tracking URLs plus membership, Ask AI usage, and privacy summaries.
- Improved FAQ matching so the plugin can answer common support questions from the approved FAQ set more reliably without relying on loose AI phrasing.

== Version 5.2.1 ==
- Added semantic query assist on top of the existing Ask AI search pipeline so broader natural-language prompts can gather stronger candidate products before ranking and trimming the final cards.
- Added a response control to enable or disable semantic query assist and a candidate-pool setting so you can tune how many products are gathered before scoring.
- Added semantic candidate retrieval using cleaned search terms, category names, matched category paths, and synonym-linked phrases without replacing the grounded WooCommerce product pipeline.

== Version 5.2.0b ==
- Added an Ollama-first simplify layer in AI Integration.
- Added a Show legacy external AI providers toggle so OpenAI, OpenRouter, and GitHub Models stay hidden unless needed.
- Simplified the AI provider selector to Ollama Local and None by default while keeping legacy compatibility available.

== Version 5.2.0 ==
- Updated the existing Ask AI routing layer into controlled tool-driven store actions so requests now route through internal actions like browse category, compare products, similar items, shipping, returns, tracking, and support before falling back to looser search behavior.
- Added a response control for tool-driven store actions and tightened category-browse behavior so category-led requests can load cleaner category results without over-restricting the WooCommerce query.
- Improved support-style handling so store-help questions return action-specific responses and follow-up suggestions while product requests continue through the existing catalog pipeline.

== Version 5.1.2 ==
- Updated the existing Ask AI request routing with local intent detection so the plugin now parses shopper filters like compare, similar, in stock, cheaper, and budget before searching WooCommerce.
- Broad requests now get a cleaned internal search query, filter-aware product sorting, and clearer no-match messaging instead of relying only on the raw typed phrase.

== Version 5.1.1c ==
* Fixed the live Save Settings AJAX hook so it attaches to the main Ask AI settings form again instead of the analytics clear-logs form, preventing the admin page refresh regression.

=== PricZone AI Concierge ===
Contributors: PricZone
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 5.2.2
License: GPLv2 or later

WooCommerce AI shopping concierge with category-aware retrieval, admin tabs, trusted store answers, logs, and configurable widget positioning.

== Version 5.1.1a ==
- Fixed category-based product retrieval for Ask AI so category matches use real WooCommerce product category slugs instead of numeric IDs.
- This improves broad Ask AI requests like Mini PCs, headphones, and other category-led queries that were falling back too early.

== Version 5.1.1 ==
- Upgraded the existing AI rewrite layer into a grounded reply mode so Ask AI now sends a compact shopper-safe summary of matched WooCommerce products to the model instead of the raw product payload.
- Added response controls for grounded AI mode and for how many matched products are included in the AI context.
- Keeps product cards, links, images, and actual store data rendered by the plugin while the AI only rewrites the short shopper-facing chat message.
- Tightened AI output filtering to reject raw field dumps, markdown-like payloads, image links, and other structured responses more aggressively before they can show in chat.

== Version 5.1.0d ==
- Converted the admin settings cards below the version header into accordions with persistent open and closed memory states saved per section.
- Matched the accordion header style to the other plugin pattern with the caret control and Open or Closed pill on the right.
- Replaced the Ask AI admin quick scroll rail styling and fade behavior so it matches the right-side top, center, and bottom control style used in your other plugin.
- Corrected the main settings form hook so live save stays attached to the actual settings form without affecting the analytics export form.

== Version 5.1.0c ==
- Updated the existing main settings save flow to save live over AJAX without refreshing the admin page or kicking you back up the screen.
- Keeps the old admin-post save path as a fallback, so the current settings feature was upgraded instead of replaced with a duplicate save system.
- Added in-place save feedback and a temporary Saving button state on the main Save Settings button.

== Version 5.1.0b ==
- Updated the Ask AI plugin admin page to use the full available WordPress admin width instead of capping the layout with an unnecessary right-side gap.

== Version 5.1.0a ==
* Updated the existing Ollama Local settings so the model field now works as a real dropdown selector instead of a manual-only text field.
* Added AJAX model discovery from /api/tags and a Refresh Model List button so the plugin can pull installed local models from the server without reloading the admin page.
* Cached the returned Ollama model names so the saved selector still shows known models after reloads.

== Version 5.1.0 ==
* Added Ollama Local as a new AI provider in the existing AI Integration section instead of creating a separate duplicate AI workflow.
* Added Ollama endpoint and model settings plus an optional Local AI Probe button that checks whether WordPress can reach the Ollama endpoint without sending product data or saving settings.
* Extended the existing provider rewrite engine so Ask AI can use a local Ollama model through the /api/chat endpoint.

== Version 5.0.8 ==
* Replaced the single bottom back-to-top button with a right-side quick-scroll rail for top, center, and bottom navigation on the plugin admin page.

== Version 5.0.7 ==
- Added a bottom-centered Back to top arrow on the plugin admin screen
- Arrow appears after scrolling and smooth-scrolls the admin page back to the top
- Keeps the long PricZone AI settings screen easier to navigate without changing existing sections

== Version 5.0.6 ==
- Email unsubscribe now removes the saved visitor entry entirely instead of leaving an unsubscribed record in the admin directory
- Unsubscribe now clears front end visitor gate and chat state keys so the Ask AI signup form can reappear for that visitor
- Adds a visitor form Submitting notification while the first name and email request is processing
- Disables the visitor form briefly during submission and restores it after the request completes

== Features ==
- Floating storefront chat widget
- Square or round widget style
- Adjustable right/bottom positioning
- WooCommerce product search and recommendations via wc_get_products()
- Category-aware matching using product_cat taxonomy
- Trusted store knowledge for contact, shipping, and returns answers
- WordPress admin settings tabs
- Query logs in admin analytics

== Installation ==
1. Upload the ZIP in WordPress Plugins > Add New > Upload Plugin.
2. Activate the plugin.
3. Go to PricZone AI in wp-admin.
4. Configure store info, FAQ JSON, category synonyms, and widget style.

- Square launcher button
- Optional hide on mobile devices via wp_is_mobile()
- Optional hide below configured browser width

== Version 3 ==
- Cleaner multi-tab admin UI
- Seeded shipping, returns, FAQ, and category synonym values for PricZone
- Better field descriptions so setup is easier

== Version 4 ==
- Author updated to Derek Williams
- Better compare, cheaper, and in-stock request handling
- Improved storefront prompt language

== Version 4.1 ==
- Query logging dashboard
- Visual FAQ manager
- Visual category synonym editor
- Optional OpenAI/OpenRouter response rewrite support

== Version 4.5.6 ==
- Fix for allowed options list error by ensuring settings are registered on admin_init before options.php saves

== Version 4.2 ==
- Hard fix for settings saving by switching to custom admin-post save handler
- Export query logs as CSV
- Clear logs action
- Improved compare text with price and stock context
- Suggested synonym phrases from fallback searches

== Version 4.3 ==
- Stable plugin root folder corrected to priczone-ai-concierge for in-place overwrite installs
- Plugin header version updated to 4.3.0
- Carries forward 4.2 save-handler and analytics improvements

== Version 4.5.6 ==
- Corrects stale plugin header metadata so the Plugins screen shows the actual current version
- Keeps stable root folder name for overwrite installs

== Version 4.4 ==
- Save notice moved below the version badge area instead of floating in the hero header
- Admin tab navigation now loads panels over AJAX without a full page refresh
- Stable root folder and synchronized version metadata updated to 4.4.0

== Version 4.5.6 ==
- Emergency rollback-safe build after critical error
- Restores the previously working admin controller to get the plugin loading again
- Keeps stable plugin folder and synchronized version metadata

== Version 4.5.6 ==
- Moves the save notice below the version badge in the admin hero
- Uses lightweight in-page tab switching with JavaScript to avoid full page reloads
- Keeps stable plugin folder and synchronized version metadata

== Version 4.5.6 ==
- Ultra-safe recovery build after activation fatal error
- Keeps success notices below the version badge
- Returns to stable full-page tab navigation to avoid activation/runtime issues

== Version 4.5.6 ==
- Removes the hide-below-width display setting from admin
- Stops frontend JavaScript from hiding the chat widget based on browser width
- Keeps stable plugin folder and synchronized version metadata

== Version 4.5.6 ==
- Fixes checkbox saving for enabled and display toggles
- Ensures the widget can render on WooCommerce storefront pages when enabled
- Keeps stable plugin folder and synchronized version metadata

== Version 4.5.6 ==
- Fixes settings persistence across tabs using one unified save form
- Fixes checkbox storage for enabled/display options
- Separates query-log export/clear forms from the main save button
- Client-side tab switching avoids page reload until Save Settings is clicked

== Version 4.5.6 ==
- Emergency recovery build for admin critical error
- Removes risky inline action handlers from the analytics tab
- Keeps single-page tab switching and settings persistence improvements

== Version 4.5 ==
- Floating launcher moved to the vertical middle on the right edge
- Chat panel now slides out to the left and toggles closed on repeat click
- Added admin setting for launcher button text

== Version 4.5.6 ==
- Fixes launcher tab position when the chat panel opens
- Docks the Ask AI tab to the outside-right edge of the open panel
- Keeps the slide-out panel aligned with the desired storefront behavior

== Version 4.5.6 ==
- Fixes Hide AI Chat so it hides on mobile devices and resized mobile-width browser windows
- Restores a configurable mobile breakpoint setting in the admin panel
- Re-checks widget visibility on resize and orientation change

== Version 4.5.6 ==
- Removes Query Logs from the plugin for now
- Removes log admin actions and log settings references
- Stops writing query log entries from the chat engine
- Keeps mobile hiding and same-tab product links intact

== Version 4.5.6 ==
- Fixes Hide on mobile devices by applying a direct @media rule to the widget wrapper
- Hides the entire chat tool at 768px and below when the admin setting is enabled
- Removes reliance on server-side mobile detection for frontend resizing behavior

== Version 4.5.6.2 ==
- Rebuilt from the known-good 4.5.6-clean package
- Removes the admin color customization feature
- Sets the widget header, ASK AI button, and Send button background to #232F3E
- Sets ASK AI button text to white

== Version 4.5.6.4 ==
- Rebuilt from 4.5.6.2 simple color fix to remove the unrelated 4.5.6.3-only render patch as a separate layer
- Adds exact product-page detection and product context handoff to the chat request
- Uses the current product name, descriptions, attributes, categories, stock, and related products in answers on single product pages
- Keeps the simple #232F3E color changes and avoids the broken admin color feature

== Version 4.5.6.5 ==
- Fixes raw JSON fields so saving settings no longer adds escaped backslashes repeatedly
- Changes the closed Ask AI indicator dot to white
- Keeps single-product page context answers from 4.5.6.4

== Version 4.5.6.6 ==
- Fixes the real cause of JSON slash buildup by bypassing wp_kses_post for raw JSON and visual-manager JSON fields during settings sanitization
- Raw FAQ JSON and category synonym JSON now save as plain JSON text instead of escaped JSON strings
- Keeps the white Ask AI closed-state dot and product-context chat behavior

== Version 4.5.6.7 ==
- Fixes the Ask AI indicator dot in the open chat state so it stays small
- Forces the dot to remain white instead of red when the chat is opened
- Keeps the 4.5.6.6 JSON save fix and product-context chat behavior

== Version 4.5.6.8 ==
- Restores the widget header layout so the title stays white and aligned correctly
- Moves the close X back to the far right side of the chat header
- Keeps the small white Ask AI dot in both closed and open states
- Keeps the JSON save fix and product-context chat behavior

== Version 4.5.6.9 ==
- Removes the outdated admin hero build note about starting from 4.5.5 and Query Logs
- Updates the admin version badge to use the full live plugin version dynamically, such as 4.5.6.9
- Keeps the header, dot, JSON save, and product-context fixes from earlier builds

== Version 4.5.7.0 ==
- Adds GitHub Models as a new AI Integration provider
- Supports GitHub personal access token authentication and a configurable GitHub Models model name
- Defaults GitHub Models to openai/gpt-4o-mini for lightweight chat responses
- Keeps the admin version badge synced to the full plugin version

== Version 4.5.7.1 ==
- Fixes the admin Integrations tab so GitHub Models token and model fields actually appear
- Keeps GitHub Models provider support and GPT-4o mini default

== Version 4.5.7.2 ==
- Restores the Thinking indicator in the chat while requests are in progress
- Removes the Thinking message after success or error responses

== Version 4.5.7.3 ==
- Stops AI rewriting for product-result messages so chat shows clean result text and product cards instead of raw Markdown lists
- Keeps structured product payload rendering in the storefront widget

== Version 4.5.8.0 ==
- Adds quick-reply suggestion chips to guide shoppers to cheaper, in-stock, compare, and category follow-up queries
- Adds a Why this matches line on product cards using name, category, stock, and budget cues
- Adds suggestion chips to knowledge and product-context responses for a more guided chat flow

== Version 4.5.8.1 ==
- Adds analytics overview cards in admin for total queries, zero-result queries, average results, and product clicks
- Adds recent query logs, top queries, zero-result query reporting, CSV export, and clear-log action
- Logs chat sessions with result counts and tracks suggestion clicks and product clicks from the storefront widget

== Version 4.5.8.2 ==
- Fixes the analytics admin page crash by defining the admin instance before rendering the Analytics Overview section
- Restores CSV export handling for analytics logs from the settings page

== Version 4.5.8.3 ==
- Fixes analytics CSV export by moving it to a proper admin-post download action with nonce validation
- Replaces the export link with a real export form so clicking Export CSV downloads the file instead of refreshing the settings page

== Version 4.5.8.4 ==
- Moves Analytics Overview outside the settings form so Save Settings no longer triggers CSV export
- Keeps Export CSV and Clear analytics logs as completely separate admin-post forms

== Version 4.5.9.0 ==
- Adds AI-assisted conversion tracking for chat start, product click, add-to-cart after chat, and completed order attribution
- Persists chat session data in first-party cookies and writes AI attribution meta to WooCommerce orders
- Adds admin conversion metrics for AI add-to-carts, AI-assisted orders, AI-assisted revenue, and top converting queries
- Sends optional Microsoft Clarity custom events for product click and add-to-cart after chat when Clarity is present

== Version 4.5.9.1 ==
- Fixes AI assisted revenue metric rendering in the admin analytics card
- Uses safe WooCommerce price HTML rendering instead of escaped raw markup

== Version 4.5.9.2 ==
- Adds the final Microsoft Clarity purchase event for AI-assisted completed orders on the WooCommerce thank-you page
- Completes Clarity event coverage for product click, add-to-cart, and purchase after chat

== Version 4.5.9.3 ==
- Fixes the 4.5.9.2 crash by moving the Clarity purchase-event method back inside the Conversion_Tracker class
- Keeps the completed-order Clarity event for AI-assisted purchases without breaking plugin activation

== Version 4.5.9.7 ==
- Restores frontend chat design and styling from 4.5.8.3 while preserving the 4.5.9.3 feature and tracking baseline

== Version 4.6.0.4 ==
- Restores the frontend chat UI baseline from the 4.5.8.3 design/styling package again
- Updates Clear chat to reset immediately with no confirmation prompt
- Preserves full chat history, including text, product cards, suggestion buttons, draft text, and open/closed state across page loads

== Version 4.6.0.5 ==
- Restores the frontend chat design and styling layer from 4.5.8.3 again
- Keeps the 4.6.0.4 feature and function JS baseline intact
- Retains clear chat, welcome message, persistent chat history, product cards, suggestion buttons, draft text, and open/closed state behavior

== Version 4.6.0.6 ==
- Restores suggestion buttons to the original chip-style rendering again
- Updates chat.js to render suggestion buttons with the legacy pzai-chip class
- Adds a CSS alias fallback for pzai-suggestion so cached markup still matches the older visual design

== Version 4.6.0.7 ==
- Fixes product cards in chat so clicking reliably navigates in the same tab
- Adds broader product URL fallback support for url, permalink, link, product_url, and product_permalink fields
- Forces navigation with window.location.assign on click and prevents dead-card behavior when nested content is clicked

== Version 4.6.0.8 ==
- Patches suggestion buttons to send structured intent metadata instead of acting like plain text only
- Makes cheaper, in-stock, compare, and category suggestion buttons return real product results when possible
- Keeps fallback responses for true no-match cases and non-answerable queries only

== Version 4.6.0.9 ==
- Prioritizes exact subcategory matches before broader category matches
- Falls back from exact subcategory to its parent category only when needed
- Keeps broader multi-category expansion as a later fallback instead of the first search path

== Version 4.6.1.0 ==
- Enforces live WooCommerce product_cat hierarchy matching using actual category names, slugs, parents, and full path strings
- Excludes Uncategorized and Funding & Subscriptions from category matching, taxonomy expansion, and suggestion paths
- Improves exact child and grandchild category prioritization before parent or broad fallback expansion

== Version 4.6.1.1 ==
- Uses the provided product category ID hierarchy as the primary taxonomy map for category resolution
- Keeps live WooCommerce product_cat data as a fallback and slug enrichment source
- Hard-excludes Funding & Subscriptions (ID 5017) and uncategorized (ID 18) from matching and expansion

== Version 4.6.1.1.1 ==
- Stable hotfix built from 4.6.1.1 seeded taxonomy package
- Category synonyms JSON can now match by numeric product category ID keys while keeping legacy name-key support
- Includes a separate minified, hierarchy-ordered ID-keyed synonym JSON file for manual paste into settings

== Version 4.6.1.1.2 ==
- Added a single right-aligned underlined View all link below the product card list
- View all opens in the same frame using target _self like product cards
- View all prefers the deepest available child or grandchild product category URL

== Version 4.6.1.1.3 ==
- Added explicit top-level view_all_url in chat responses
- Attached view_all_url to product items so the View all link renders even when category fields are incomplete
- Restored View all link from saved history entries by persisting view_all_url

== Version 4.6.1.1.4 ==
- Added explicit top-level view_all_url in chat responses
- Updated chat.js to render View all from response-level view_all_url
- Persisted view_all_url in chat history so restored product results still show the link
== Version 4.6.1.1.6 ==
- Added product card CSS for separated title and metadata rows
- Added spaced divider styling for price and price range text
- Keeps price metadata from appearing smashed against product titles
== Version 4.6.1.1.7 ==
- Reworked product meta rendering so the divider is output as an explicit visible element
- Product cards now render: price | Price range text when both values exist
- Added stronger divider styling for clarity
== Version 4.6.1.1.8 ==
- Added browser console diagnostics for chat response payloads and rendered product card metadata
- Logs first product, raw product fields, resolved view_all_url, and final rendered meta row
- Adds data attributes on product cards for easier DOM inspection
== Version 4.6.1.1.9 ==
- Split variable-product pricing into separate price_html and price_range_text fields in the product payload
- Updated chat renderer to output price | Price range text as separate spans
- Added frontend fallback to split flattened price strings that already contain Price range text
- Removed temporary diagnostic logging from the chat widget
== Version 4.6.1.2.0 ==
- Fixed chat history restore so the panel stays pinned to the latest response after refreshes and page changes
- Added deferred auto-scroll after product images load, preventing the chat from jumping back to the top
- Re-applies bottom scroll when the widget opens and after restore completes
== Version 4.6.1.2.1 ==
- Added single-product page context passing from the frontend to the chat endpoint
- Added product-page intent recognition for phrases like about this product, more information, product details, and similar wording variations
- Added short product-specific replies capped to about three sentences using the product name, description, and attribute data
- Prioritizes product-context replies on single product pages before generic store FAQ answers
== Version 4.6.1.2.2 ==
- Removed the single-product suggestion that asked if the item is in stock
- Updated short product-context replies so key details and additional information queries prioritize product attributes from the Additional Information tab
- Product-page replies now pass the user query into the short reply builder so wording like key details, specs, materials, and additional information can shape the response
== Version 4.6.1.2.3 ==
- Fixed single-product "What are the key details?" prompts so they are treated as product-context requests instead of product search queries
- Updated the product-context gate to include key-details and additional-information style wording
- Keeps the reply focused on the current product attributes instead of returning other products
== Version 4.6.1.2.4 ==
- Updated single-product key-details replies to use only Additional Information attributes and not the product description
- Prioritizes important fields like Weight, Dimensions, Brand Name, Material, Characters, Gender, Item Type, and related product details
- Keeps the key-details reply short by returning only a few important attributes instead of the full attribute list
== Version 4.6.1.2.5 ==
- Updated the single-product suggestion "More information about this product" to pull from the product description first and the short description second
- Key-details prompts now stay dedicated to Additional Information attributes instead of sharing the same wording path as more-information prompts
- Added a short promotional fallback reply when neither the description nor short description has usable text
== Version 4.7.0 ==
- Added fading admin success notices for Settings saved and Analytics logs cleared
- After the notice fades, the settings page URL is cleaned back to admin.php?page=pzai-settings using history.replaceState
- Prevents the logs_cleared query string from lingering in the URL after clearing analytics logs
== Version 4.7.1 ==
- Fixed admin success notice fade script loading on the settings page
- Enqueued a dedicated admin inline script handle instead of attaching the notice logic to jquery-core
- Restores automatic notice fade-out and URL cleanup after saving settings or clearing analytics logs
== Version 4.7.2 ==
- Updated the Visual synonym editor to use WooCommerce category IDs instead of category names
- Synonym rows now save category_id values while keeping shopper phrases unchanged
- Added backward-compatible merging so existing name-based synonym rows still load until they are replaced with IDs

== Version 4.8.0 ==
- Added a logged-out visitor Ask AI access form with required first name, email, and consent checkbox
- Blocks the Ask AI input and Send button for visitors until the form is submitted
- Sends admin notification and visitor welcome emails through WordPress mail, with unsubscribe link support
- Added configurable visitor remember-days, agreement page ID, unsubscribe page ID, thank-you message, tooltip text, and editable HTML email templates in the admin panel

== Version 4.8.0.2 ==
- Fixed the visitor gate UI so the chat form input and Send button are the elements that are disabled until visitor submission succeeds
- Replaced the gate helper text with "Complete this form below to use ASK AI" and added wrapping-friendly styling
- Removed the consent tooltip and kept only the agreement link
- Ensured first name is shown above email in the visitor form and corrected checkbox checkmark alignment

== Version 4.8.0.3 ==
- Fixed the consent checkbox stretching by preventing generic text-input styles from applying to the checkbox input
- Tightened the consent row layout so the checkbox stays square and aligned with the text block
- Removed the obsolete Consent tooltip text setting from plugin code and admin settings
- Added cleanup to remove the legacy visitor_terms_tooltip key from the saved pzai_visitor_settings option

== Version 4.8.0.4 ==
- Hardened the visitor consent checkbox CSS against theme checkbox pseudo-elements and background-image overrides
- Replaced the checkbox tick rendering with an inline SVG background on the checked state to prevent duplicate theme checkmarks

== Version 4.8.0.5 ==
- Fixed logged-in users being treated like visitors on REST chat requests by adding a WordPress REST nonce to localized widget data
- Updated chat, event, and visitor lead AJAX requests to send same-origin credentials and the X-WP-Nonce header


== Version 5.0.0 ==
- Adds a saved visitor directory in admin with AJAX pagination in groups of 25
- Lets admins delete individual visitor entries or clear all saved visitor emails without a page refresh
- Blocks duplicate visitor form submissions while an email remains in the saved visitor directory
- Sends unsubscribe notifications to customerservice@priczone.com and preserves the entry until an admin removes it
- Adds Google reCAPTCHA v2 site key and secret key settings with frontend and backend verification
- Revalidates visitor access against saved email records so clearing entries makes the form appear again


== Version 5.1.1b ==
- Speeds up simple product browsing requests for Ollama Local by reusing the plugin's existing deterministic shopper reply instead of waiting on an AI rewrite
- Trims the grounded product context sent to Ollama Local so fewer product summaries are passed on broad browse queries
- Lowers the Ollama Local rewrite timeout so the plugin falls back faster instead of leaving shoppers waiting on slow local model responses


= 5.2.0b =
* Updated the live save success message so it fades away automatically after saving without a page refresh.
