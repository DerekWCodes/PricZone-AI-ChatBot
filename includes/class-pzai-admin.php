<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

class Admin {
    private $settings;
    private $logger;
    private $catalog;

    public function __construct($settings, $logger = null, $catalog = null) {
        $this->settings = $settings;
        $this->logger = $logger;
        $this->catalog = $catalog;

        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_enqueue_scripts', [$this, 'assets']);
        add_action('admin_post_pzai_save_settings', [$this, 'save_settings']);
        add_action('admin_post_pzai_clear_logs', [$this, 'clear_logs']);
        add_action('admin_post_pzai_export_logs', [$this, 'export_logs']);

        add_action('wp_ajax_pzai_get_visitor_leads', [$this, 'ajax_get_visitor_leads']);
        add_action('wp_ajax_pzai_delete_visitor_lead', [$this, 'ajax_delete_visitor_lead']);
        add_action('wp_ajax_pzai_clear_visitor_leads', [$this, 'ajax_clear_visitor_leads']);
        add_action('wp_ajax_pzai_probe_ollama', [$this, 'ajax_probe_ollama']);
        add_action('wp_ajax_pzai_get_ollama_models', [$this, 'ajax_get_ollama_models']);
        add_action('wp_ajax_pzai_save_settings', [$this, 'ajax_save_settings']);
    }

    public function menu() {
        add_menu_page('PricZone AI Concierge', 'PricZone AI', 'manage_options', 'pzai-settings', [$this, 'page'], 'dashicons-format-chat', 56);
    }

    public function assets($hook) {
        if ($hook !== 'toplevel_page_pzai-settings') return;

        $css = '.pzai-wrap{max-width:none;width:auto;margin-right:20px}.pzai-wrap .pzai-notice-inline{max-width:none}.pzai-hero{background:linear-gradient(135deg,#06435A,#0b6a87);color:#fff;padding:28px;border-radius:18px;margin:18px 0}.pzai-hero h1{color:#fff;margin:0 0 10px;font-size:28px}.pzai-badge{display:inline-block;background:#16d6a6;color:#05384a;padding:6px 10px;border-radius:999px;font-weight:700;font-size:12px;margin-bottom:12px}.pzai-notice-inline{margin:14px 0 0;background:#fff;border-left:4px solid #16d6a6;color:#17313d;padding:12px 14px;border-radius:8px;max-width:1020px;opacity:1;transform:translateY(0);transition:opacity .45s ease,transform .45s ease}.pzai-notice-inline.is-fading{opacity:0;transform:translateY(-6px)}.pzai-stack{display:flex;flex-direction:column;gap:18px}.pzai-card{background:#fff;border:1px solid #d9e3e7;border-radius:16px;box-shadow:0 10px 30px rgba(6,67,90,.05)}.pzai-card.pzai-accordion-card{padding:0;overflow:hidden}.pzai-card.pzai-accordion-card>summary{list-style:none}.pzai-card.pzai-accordion-card>summary::-webkit-details-marker{display:none}.pzai-accordion-summary{display:flex!important;align-items:center;gap:10px;min-height:54px;padding:22px;cursor:pointer}.pzai-accordion-caret{width:18px;height:18px;min-width:18px;flex:0 0 18px;display:inline-flex;align-items:center;justify-content:center;border:1px solid #d0d7de;border-radius:999px;background:#f6f7f7;color:#1d2327;box-shadow:none;padding:0}.pzai-accordion-caret::before{content:"▸";display:block;margin-left:1px;font-size:9px;line-height:1}.pzai-card.pzai-accordion-card[open] .pzai-accordion-caret::before{content:"▾";margin-left:0;margin-top:-1px}.pzai-accordion-title-wrap{min-width:0;flex:1 1 auto}.pzai-accordion-title{display:block;margin:0;color:#1d2327;font-size:22px;font-weight:600;line-height:1.3}.pzai-accordion-subtitle{display:block;margin-top:4px;color:#50636d;font-size:13px;line-height:1.5}.pzai-accordion-status{display:inline-flex;align-items:center;justify-content:center;min-width:64px;padding:5px 10px;margin-left:auto;border:1px solid #d0d7de;border-radius:999px;background:#f6f7f7;color:#50575e;font-size:12px;font-weight:500;line-height:1.2;white-space:nowrap}.pzai-accordion-status.is-open{background:#fff;color:#1d2327}.pzai-accordion-status.is-closed{background:#f6f7f7;color:#50575e}.pzai-accordion-body{padding:0 22px 22px}.pzai-card.pzai-accordion-card:not([open]) .pzai-accordion-summary{border-bottom:0}.pzai-card h2{margin:0 0 14px}.pzai-form-table{width:100%}.pzai-form-table th{width:260px;text-align:left;padding:14px 16px 14px 0;vertical-align:top}.pzai-form-table td{padding:14px 0}.pzai-form-table textarea.code{font-family:Consolas,Monaco,monospace}.pzai-builder{margin-top:18px;padding-top:18px;border-top:1px solid #e5ecef}.pzai-builder table{width:100%;border-collapse:collapse}.pzai-builder th,.pzai-builder td{padding:8px;border-bottom:1px solid #eef3f5;vertical-align:top}.pzai-builder input{width:100%}.pzai-section-note{color:#50636d;margin:-4px 0 10px}.pzai-metrics{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin:12px 0 18px}.pzai-metric{background:#f7fbfc;border:1px solid #dde8ec;border-radius:14px;padding:14px}.pzai-metric strong{display:block;font-size:24px;color:#06435A}.pzai-mini-table{width:100%;border-collapse:collapse;margin-top:10px}.pzai-mini-table th,.pzai-mini-table td{padding:8px 10px;border-bottom:1px solid #edf2f4;text-align:left}.pzai-actions{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:14px}.pzai-directory-feedback,.pzai-probe-feedback,.pzai-live-save-feedback{display:none;margin:0 0 12px;padding:10px 12px;border-radius:10px;background:#f7fbfc;border:1px solid #d9e3e7;color:#17313d;opacity:1;transform:translateY(0);transition:opacity .45s ease,transform .45s ease}.pzai-directory-feedback.is-visible,.pzai-probe-feedback.is-visible,.pzai-live-save-feedback.is-visible{display:block}.pzai-live-save-feedback{margin-top:12px}.pzai-live-save-feedback.is-error{background:#fff5f5;border-color:#fecaca;color:#9b1c1c}.pzai-live-save-feedback.is-fading{opacity:0;transform:translateY(-6px)}.pzai-submit-row{display:flex;flex-direction:column;align-items:flex-start}.pzai-submit-row .button.is-saving{pointer-events:none;opacity:.7}.pzai-model-actions{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:8px}.pzai-probe-feedback.is-error{background:#fff5f5;border-color:#fecaca;color:#9b1c1c}.pzai-directory-shell{min-height:120px}.pzai-directory-shell.is-loading{opacity:.68}.pzai-visitor-summary{display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:10px}.pzai-visitor-status{display:inline-block;padding:4px 8px;border-radius:999px;font-size:11px;font-weight:700;letter-spacing:.02em}.pzai-visitor-status.is-active{background:#ecfffa;color:#05503f;border:1px solid #bcefe1}.pzai-visitor-status.is-unsubscribed{background:#fff5f5;color:#9b1c1c;border:1px solid #fecaca}.pzai-legacy-ai-note{margin:0 0 14px;padding:12px 14px;border-radius:12px;background:#f7fbfc;border:1px solid #d9e3e7;color:#17313d}.pzai-visitor-pager{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-top:12px}.pzai-visitor-pager .button[disabled]{opacity:.55;cursor:not-allowed}.pzai-visitor-source{max-width:340px;overflow-wrap:anywhere}.pzai-visitor-table-actions{width:110px}.pzai-visitor-stats{color:#50636d}.pzai-reset-note{color:#50636d;margin-top:10px}.pzai-quick-nav{position:fixed;top:50%;right:18px;z-index:10010;display:grid;gap:8px;padding:6px;border-radius:999px;background:rgba(255,255,255,.72);box-shadow:0 10px 24px rgba(0,0,0,.14);opacity:.18;transform:translateY(-50%);transition:opacity .16s ease,transform .16s ease,background .16s ease}.pzai-quick-nav:hover,.pzai-quick-nav:focus-within{opacity:1;background:rgba(255,255,255,.96);transform:translateY(-50%) translateX(-2px)}.pzai-quick-nav-btn{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border:0;border-radius:999px;background:#050505;color:#fff;cursor:pointer;font-size:18px;font-weight:800;line-height:1;box-shadow:0 4px 10px rgba(0,0,0,.22);transition:background .16s ease,transform .16s ease,box-shadow .16s ease;padding:0}.pzai-quick-nav-btn:hover,.pzai-quick-nav-btn:focus{background:#2271b1;color:#fff;outline:none;transform:translateY(-1px);box-shadow:0 6px 14px rgba(0,0,0,.28)}.pzai-quick-nav-icon{display:block;width:16px;height:16px;overflow:visible;stroke:currentColor;stroke-width:2.6;stroke-linecap:round;stroke-linejoin:round;fill:none;flex:0 0 auto}.pzai-quick-nav-icon-center{stroke:none;fill:currentColor}.pzai-scroll-label{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}@media (max-width:782px){.pzai-form-table th,.pzai-form-table td{display:block;width:100%;padding-right:0}.pzai-mini-table{display:block;overflow:auto}.pzai-visitor-summary{align-items:flex-start}.pzai-quick-nav{right:8px;gap:6px;padding:5px;opacity:.3}.pzai-quick-nav-btn{width:30px;height:30px}.pzai-quick-nav-icon{width:14px;height:14px}.pzai-accordion-summary{padding:18px}.pzai-accordion-status{min-width:58px;padding:5px 8px}}@media (max-width:480px){.pzai-quick-nav{right:5px}.pzai-quick-nav-btn{width:28px;height:28px}}';
        wp_register_style('pzai-admin-inline', false, [], PZAI_VERSION);
        wp_enqueue_style('pzai-admin-inline');
        wp_add_inline_style('pzai-admin-inline', $css);

        wp_register_script('pzai-admin-inline-js', '', [], PZAI_VERSION, true);
        wp_enqueue_script('pzai-admin-inline-js');
        wp_add_inline_script('pzai-admin-inline-js', 'window.pzaiAdminData=' . wp_json_encode([
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pzai_admin_nonce'),
            'perPage' => 25,
            'saveSuccessMessage' => 'Settings saved without refreshing the page.',
            'saveErrorMessage' => 'Could not save settings right now.',
        ]) . ';', 'before');

        $js = <<<'JS'
(function(){
  function syncFaq(){
    var rows=[];
    document.querySelectorAll('.pzai-faq-row').forEach(function(row){
      var q=(row.querySelector('.pzai-faq-q')||{}).value||'';
      var a=(row.querySelector('.pzai-faq-a')||{}).value||'';
      q=q.trim(); a=a.trim();
      if(q && a) rows.push({q:q,a:a});
    });
    var target=document.getElementById('pzai_faq_items');
    if(target) target.value=JSON.stringify(rows, null, 2);
  }
  function syncSyn(){
    var rows=[];
    document.querySelectorAll('.pzai-syn-row').forEach(function(row){
      var c=(row.querySelector('.pzai-syn-c')||{}).value||'';
      var p=(row.querySelector('.pzai-syn-p')||{}).value||'';
      c=c.trim(); p=p.trim();
      if(c && p) rows.push({category_id:c,phrase:p});
    });
    var target=document.getElementById('pzai_synonym_items');
    if(target) target.value=JSON.stringify(rows, null, 2);
  }
  function showDirectoryMessage(message){
    var box=document.querySelector('.pzai-directory-feedback');
    if(!box) return;
    box.textContent=message||'';
    box.classList.toggle('is-visible', !!message);
  }
  function showProbeMessage(message, isError){
    var box=document.querySelector('.pzai-probe-feedback');
    if(!box) return;
    box.textContent=message||'';
    box.classList.toggle('is-visible', !!message);
    box.classList.toggle('is-error', !!isError);
  }
  function getOllamaModelSelect(){
    return document.getElementById('pzai_ollama_model');
  }
  function fillOllamaModelSelect(models){
    var select=getOllamaModelSelect();
    if(!select) return;
    var currentValue=(select.getAttribute('data-current-value') || select.value || '').trim();
    select.innerHTML='';
    var placeholder=document.createElement('option');
    placeholder.value='';
    placeholder.textContent=models && models.length ? 'Select an Ollama model' : 'No models detected';
    select.appendChild(placeholder);
    var seen={};
    (models||[]).forEach(function(model){
      var value=(model || '').trim();
      if(!value || seen[value]) return;
      seen[value]=true;
      var option=document.createElement('option');
      option.value=value;
      option.textContent=value;
      if(currentValue && currentValue===value){
        option.selected=true;
      }
      select.appendChild(option);
    });
    if(currentValue && !seen[currentValue]){
      var currentOption=document.createElement('option');
      currentOption.value=currentValue;
      currentOption.textContent=currentValue + ' (saved)';
      currentOption.selected=true;
      select.appendChild(currentOption);
    }
    select.setAttribute('data-loaded','1');
  }
  function requestOllamaModels(forceRefresh){
    var endpointField=document.getElementById('pzai_ollama_endpoint');
    var endpoint=(endpointField && endpointField.value ? endpointField.value : '').trim();
    var select=getOllamaModelSelect();
    if(!select) return Promise.resolve(null);
    if(!endpoint){
      fillOllamaModelSelect([]);
      showProbeMessage('Enter the Ollama endpoint first.', true);
      return Promise.resolve(null);
    }
    if(!forceRefresh && select.getAttribute('data-loaded')==='1' && select.options.length > 1){
      return Promise.resolve(null);
    }
    showProbeMessage(forceRefresh ? 'Refreshing the Ollama model list...' : 'Loading Ollama models from the server...', false);
    return postAdmin({action:'pzai_get_ollama_models', nonce:window.pzaiAdminData.nonce, endpoint:endpoint})
      .then(function(response){
        if(!response || !response.success){
          showProbeMessage((response && response.data && response.data.message) || 'Could not load Ollama models.', true);
          return response;
        }
        fillOllamaModelSelect((response.data && response.data.models) || []);
        showProbeMessage((response.data && response.data.message) || 'Ollama models loaded.', false);
        return response;
      })
      .catch(function(){
        showProbeMessage('Could not load Ollama models.', true);
        return null;
      });
  }
  function postAdmin(data){
    var params=new URLSearchParams();
    Object.keys(data||{}).forEach(function(key){
      params.append(key, data[key]);
    });
    return fetch((window.pzaiAdminData||{}).ajaxUrl || '', {
      method:'POST',
      credentials:'same-origin',
      headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'},
      body:params.toString()
    }).then(function(res){ return res.json(); });
  }
  var liveSaveMessageTimer=null;
  var liveSaveMessageRemoveTimer=null;
  function showLiveSaveMessage(message, isError){
    var box=document.querySelector('.pzai-live-save-feedback');
    if(!box) return;
    if(liveSaveMessageTimer){
      window.clearTimeout(liveSaveMessageTimer);
      liveSaveMessageTimer=null;
    }
    if(liveSaveMessageRemoveTimer){
      window.clearTimeout(liveSaveMessageRemoveTimer);
      liveSaveMessageRemoveTimer=null;
    }
    box.classList.remove('is-fading');
    box.textContent=message||'';
    box.classList.toggle('is-visible', !!message);
    box.classList.toggle('is-error', !!isError);
    if(message && !isError){
      liveSaveMessageTimer=window.setTimeout(function(){
        box.classList.add('is-fading');
        liveSaveMessageRemoveTimer=window.setTimeout(function(){
          box.classList.remove('is-visible');
          box.classList.remove('is-fading');
          box.textContent='';
          liveSaveMessageRemoveTimer=null;
        }, 500);
        liveSaveMessageTimer=null;
      }, 2200);
    }
  }
  function clearLiveSaveMessage(){
    showLiveSaveMessage('', false);
  }
  function parseProviderOptions(select, attr){
    if(!select) return {};
    try{
      var raw=select.getAttribute(attr)||'{}';
      var parsed=JSON.parse(raw);
      return parsed && typeof parsed==='object' ? parsed : {};
    }catch(err){
      return {};
    }
  }
  function syncAiProviderOptions(){
    var providerSelect=document.getElementById('pzai_ai_provider');
    if(!providerSelect) return;
    var legacyToggle=document.getElementById('pzai_show_legacy_ai_providers');
    var showLegacy=!!(legacyToggle && legacyToggle.checked);
    var fullOptions=parseProviderOptions(providerSelect, 'data-full-options');
    var simpleOptions=parseProviderOptions(providerSelect, 'data-simple-options');
    var options=showLegacy ? fullOptions : simpleOptions;
    var current=providerSelect.value||'';
    var allowedValues=Object.keys(options||{});
    if(!allowedValues.length) return;
    if(allowedValues.indexOf(current)===-1){
      current=showLegacy && fullOptions[current] ? current : (options.ollama_local ? 'ollama_local' : allowedValues[0]);
    }
    providerSelect.innerHTML='';
    allowedValues.forEach(function(value){
      var option=document.createElement('option');
      option.value=value;
      option.textContent=options[value];
      if(value===current) option.selected=true;
      providerSelect.appendChild(option);
    });
  }
  function syncVisualBuilders(){
    syncFaq();
    syncSyn();
  }
  function saveSettingsForm(form){
    if(!form || form.getAttribute('data-pzai-saving')==='1') return;
    syncVisualBuilders();
    var submitButton=form.querySelector('.pzai-save-settings-button');
    var originalText=submitButton ? (submitButton.getAttribute('data-original-text') || submitButton.textContent || 'Save Settings') : '';
    var formData=new FormData(form);
    formData.set('action','pzai_save_settings');
    form.setAttribute('data-pzai-saving','1');
    clearLiveSaveMessage();
    if(submitButton){
      submitButton.disabled=true;
      submitButton.classList.add('is-saving');
      submitButton.setAttribute('data-original-text', originalText);
      submitButton.textContent='Saving...';
    }
    fetch((window.pzaiAdminData||{}).ajaxUrl || form.action || '', {
      method:'POST',
      credentials:'same-origin',
      body:formData
    }).then(function(res){ return res.json(); })
      .then(function(response){
        if(!response || !response.success){
          showLiveSaveMessage((response && response.data && response.data.message) || ((window.pzaiAdminData||{}).saveErrorMessage || 'Could not save settings right now.'), true);
          return;
        }
        showLiveSaveMessage((response.data && response.data.message) || ((window.pzaiAdminData||{}).saveSuccessMessage || 'Settings saved without refreshing the page.'), false);
      })
      .catch(function(){
        showLiveSaveMessage(((window.pzaiAdminData||{}).saveErrorMessage || 'Could not save settings right now.'), true);
      })
      .finally(function(){
        form.setAttribute('data-pzai-saving','0');
        if(submitButton){
          submitButton.disabled=false;
          submitButton.classList.remove('is-saving');
          submitButton.textContent=submitButton.getAttribute('data-original-text') || originalText || 'Save Settings';
        }
      });
  }

  function syncProviderFields(){
    var providerSelect=document.getElementById('pzai_ai_provider');
    if(!providerSelect) return;
    syncAiProviderOptions();
    var provider=providerSelect.value||'none';
    var legacyToggle=document.getElementById('pzai_show_legacy_ai_providers');
    var showLegacy=!!(legacyToggle && legacyToggle.checked);
    document.querySelectorAll('[data-pzai-provider-field]').forEach(function(row){
      var rowProvider=row.getAttribute('data-pzai-provider-field');
      var isLegacy=row.getAttribute('data-pzai-legacy-provider')==='1';
      var shouldShow=rowProvider===provider;
      if(isLegacy && !showLegacy){
        shouldShow=false;
      }
      row.style.display=shouldShow ? '' : 'none';
    });
    document.querySelectorAll('[data-pzai-legacy-provider-wrapper]').forEach(function(box){
      box.style.display=showLegacy ? '' : 'none';
    });
    if(provider==='ollama_local'){
      requestOllamaModels(false);
    }
  }
  function scrollAdminTo(position){
    var pageHeight=Math.max(
      document.body ? document.body.scrollHeight : 0,
      document.documentElement ? document.documentElement.scrollHeight : 0
    );
    var viewportHeight=window.innerHeight || (document.documentElement ? document.documentElement.clientHeight : 0) || 0;
    var max=Math.max(0, pageHeight - viewportHeight);
    var target=0;
    if(position==='center'){
      target=Math.max(0, Math.floor(max / 2));
    } else if(position==='bottom'){
      target=max;
    }
    window.scrollTo({top:target, behavior:'smooth'});
  }

  function initSectionAccordions(){
    var cards=document.querySelectorAll('.pzai-card.pzai-accordion-card[data-pzai-accordion-key]');
    cards.forEach(function(card){
      if(card.getAttribute('data-pzai-accordion-ready')==='1') return;
      card.setAttribute('data-pzai-accordion-ready','1');
      var key=card.getAttribute('data-pzai-accordion-key') || '';
      var status=card.querySelector('.pzai-accordion-status');
      var fallbackOpen=card.hasAttribute('open');
      var open=fallbackOpen;
      try{
        var saved=window.localStorage.getItem('pzai_accordion_' + key);
        if(saved==='open') open=true;
        else if(saved==='closed') open=false;
      }catch(err){}
      card.open=open;
      function updateState(){
        var isOpen=!!card.open;
        card.classList.toggle('is-open', isOpen);
        card.classList.toggle('is-collapsed', !isOpen);
        if(status){
          status.textContent=isOpen ? 'Open' : 'Closed';
          status.classList.toggle('is-open', isOpen);
          status.classList.toggle('is-closed', !isOpen);
        }
        try{
          window.localStorage.setItem('pzai_accordion_' + key, isOpen ? 'open' : 'closed');
        }catch(err){}
      }
      card.addEventListener('toggle', updateState);
      updateState();
    });
  }

  function loadVisitorDirectory(page){
    var shell=document.querySelector('[data-pzai-visitor-directory]');
    if(!shell || !window.pzaiAdminData) return;
    shell.classList.add('is-loading');
    shell.innerHTML='<p>Loading visitor directory...</p>';
    postAdmin({action:'pzai_get_visitor_leads', nonce:window.pzaiAdminData.nonce, page:page || 1})
      .then(function(response){
        shell.classList.remove('is-loading');
        if(!response || !response.success || !response.data){
          shell.innerHTML='<p>Could not load the visitor directory.</p>';
          return;
        }
        shell.innerHTML=response.data.html || '<p>No saved visitors found.</p>';
      })
      .catch(function(){
        shell.classList.remove('is-loading');
        shell.innerHTML='<p>Could not load the visitor directory.</p>';
      });
  }
  document.addEventListener('input', function(e){
    if(e.target.closest('.pzai-faq-row')) syncFaq();
    if(e.target.closest('.pzai-syn-row')) syncSyn();
  });
  document.addEventListener('change', function(e){
    if(e.target && (e.target.id==='pzai_ai_provider' || e.target.id==='pzai_show_legacy_ai_providers')) syncProviderFields();
    if(e.target && e.target.id==='pzai_ollama_endpoint'){
      var select=getOllamaModelSelect();
      if(select){
        select.setAttribute('data-loaded','0');
      }
      requestOllamaModels(true);
    }
    if(e.target && e.target.id==='pzai_ollama_model'){
      e.target.setAttribute('data-current-value', e.target.value || '');
    }
  });
  document.addEventListener('click', function(e){
    if(e.target.matches('.pzai-add-faq')){
      e.preventDefault();
      var tbody=document.querySelector('.pzai-faq-body');
      if(!tbody) return;
      tbody.insertAdjacentHTML('beforeend','<tr class="pzai-faq-row"><td><input class="pzai-faq-q" type="text"></td><td><input class="pzai-faq-a" type="text"></td></tr>');
      syncFaq();
      return;
    }
    if(e.target.matches('.pzai-add-syn')){
      e.preventDefault();
      var tbody=document.querySelector('.pzai-syn-body');
      if(!tbody) return;
      tbody.insertAdjacentHTML('beforeend','<tr class="pzai-syn-row"><td><input class="pzai-syn-c" type="text"></td><td><input class="pzai-syn-p" type="text"></td></tr>');
      syncSyn();
      return;
    }
    if(e.target.matches('.pzai-refresh-ollama-models')){
      e.preventDefault();
      var refreshButton=e.target;
      refreshButton.disabled=true;
      requestOllamaModels(true).finally(function(){
        refreshButton.disabled=false;
      });
      return;
    }
    if(e.target.matches('.pzai-probe-ollama')){
      e.preventDefault();
      var endpointField=document.getElementById('pzai_ollama_endpoint');
      var endpoint=(endpointField && endpointField.value ? endpointField.value : '').trim();
      var button=e.target;
      if(!endpoint){
        showProbeMessage('Enter the Ollama endpoint first.', true);
        return;
      }
      showProbeMessage('Checking the local Ollama endpoint...', false);
      button.disabled=true;
      postAdmin({action:'pzai_probe_ollama', nonce:window.pzaiAdminData.nonce, endpoint:endpoint})
        .then(function(response){
          button.disabled=false;
          if(!response || !response.success){
            showProbeMessage((response && response.data && response.data.message) || 'Could not reach the Ollama endpoint.', true);
            return;
          }
          if(response.data && response.data.models){
            fillOllamaModelSelect(response.data.models);
          }
          showProbeMessage((response.data && response.data.message) || 'Ollama endpoint reached successfully.', false);
        })
        .catch(function(){
          button.disabled=false;
          showProbeMessage('Could not reach the Ollama endpoint.', true);
        });
      return;
    }
    var scrollBtn=e.target.closest('.pzai-quick-nav-btn');
    if(scrollBtn){
      e.preventDefault();
      var position=scrollBtn.getAttribute('data-pzai-scroll') || 'top';
      scrollAdminTo(position);
      return;
    }
    if(e.target.matches('.pzai-visitor-page')){
      e.preventDefault();
      var targetPage=parseInt(e.target.getAttribute('data-page') || '1', 10) || 1;
      loadVisitorDirectory(targetPage);
      return;
    }
    if(e.target.matches('.pzai-delete-visitor')){
      e.preventDefault();
      var leadKey=e.target.getAttribute('data-lead-key') || '';
      if(!leadKey) return;
      if(!window.confirm('Delete this saved visitor entry and allow that email to sign up again?')) return;
      postAdmin({action:'pzai_delete_visitor_lead', nonce:window.pzaiAdminData.nonce, lead_key:leadKey})
        .then(function(response){
          if(!response || !response.success){
            showDirectoryMessage((response && response.data && response.data.message) || 'Could not delete the visitor entry.');
            return;
          }
          showDirectoryMessage((response.data && response.data.message) || 'Visitor entry deleted.');
          loadVisitorDirectory((response.data && response.data.page) || 1);
        })
        .catch(function(){
          showDirectoryMessage('Could not delete the visitor entry.');
        });
      return;
    }
    if(e.target.matches('.pzai-clear-visitors')){
      e.preventDefault();
      if(!window.confirm('Clear every saved visitor email and reset the Ask AI form for visitors?')) return;
      postAdmin({action:'pzai_clear_visitor_leads', nonce:window.pzaiAdminData.nonce})
        .then(function(response){
          if(!response || !response.success){
            showDirectoryMessage((response && response.data && response.data.message) || 'Could not clear the visitor directory.');
            return;
          }
          showDirectoryMessage((response.data && response.data.message) || 'Visitor directory cleared.');
          loadVisitorDirectory(1);
        })
        .catch(function(){
          showDirectoryMessage('Could not clear the visitor directory.');
        });
      return;
    }
  });

  var notice=document.querySelector('.pzai-notice-inline[data-clean-url]');
  if(notice){
    var cleanUrl=notice.getAttribute('data-clean-url') || '';
    window.setTimeout(function(){
      notice.classList.add('is-fading');
      window.setTimeout(function(){
        if(notice && notice.parentNode) notice.parentNode.removeChild(notice);
        if(cleanUrl && window.history && window.history.replaceState){
          window.history.replaceState({}, document.title, cleanUrl);
        }
      }, 500);
    }, 2400);
  }

  document.addEventListener('submit', function(e){
    var form=e.target;
    if(!form || !form.matches('form[data-pzai-settings-form]')) return;
    e.preventDefault();
    saveSettingsForm(form);
  });

  syncProviderFields();
  initSectionAccordions();
  if(document.querySelector('[data-pzai-visitor-directory]')) loadVisitorDirectory(1);
})();
JS;
        wp_add_inline_script('pzai-admin-inline-js', $js);
    }

    private function visitor_defaults() {
        return [
            'visitor_gate_enabled' => 1,
            'visitor_gate_days' => 30,
            'visitor_gate_recipient_email' => 'customerservice@priczone.com',
            'visitor_terms_page_id' => 0,
            'visitor_unsubscribe_page_id' => 0,
            'visitor_gate_thank_you_message' => 'Thank you for using our ASK AI at PricZone, enjoy.',
            'visitor_terms_tooltip' => '',
            'visitor_welcome_email_subject' => "Welcome to {site_name}'s Ask AI",
            'visitor_welcome_email_html' => '<p>Hi {first_name},</p><p>Welcome to {site_name} Ask AI.</p><p>You now have access to chat with our shopping assistant as a visitor.</p><p>{thank_you_message}</p><p><a href="{unsubscribe_url}">Unsubscribe from Ask AI emails</a></p>',
            'visitor_unsubscribe_email_subject' => 'Sorry to see you leave {site_name} Ask AI',
            'visitor_unsubscribe_email_html' => '<p>Hi {first_name},</p><p>Sorry to see you leave {site_name} Ask AI.</p><p>You have been unsubscribed from future Ask AI emails for {email}.</p>',
            'visitor_recaptcha_site_key' => '',
            'visitor_recaptcha_secret_key' => '',
        ];
    }

    private function visitor_settings() {
        $saved = get_option('pzai_visitor_settings', []);
        if (!is_array($saved)) $saved = [];
        return wp_parse_args($saved, $this->visitor_defaults());
    }

    private function sanitize_visitor_settings($raw) {
        $current = $this->visitor_settings();
        if (!is_array($raw)) $raw = [];
        $clean = [];
        $clean['visitor_gate_enabled'] = !empty($raw['visitor_gate_enabled']) ? 1 : 0;
        $clean['visitor_gate_days'] = max(1, absint($raw['visitor_gate_days'] ?? $current['visitor_gate_days']));
        $clean['visitor_gate_recipient_email'] = sanitize_email(wp_unslash((string) ($raw['visitor_gate_recipient_email'] ?? $current['visitor_gate_recipient_email'])));
        if (!$clean['visitor_gate_recipient_email']) $clean['visitor_gate_recipient_email'] = 'customerservice@priczone.com';
        $clean['visitor_terms_page_id'] = absint($raw['visitor_terms_page_id'] ?? $current['visitor_terms_page_id']);
        $clean['visitor_unsubscribe_page_id'] = absint($raw['visitor_unsubscribe_page_id'] ?? $current['visitor_unsubscribe_page_id']);
        $clean['visitor_gate_thank_you_message'] = sanitize_textarea_field(wp_unslash((string) ($raw['visitor_gate_thank_you_message'] ?? $current['visitor_gate_thank_you_message'])));
        $clean['visitor_terms_tooltip'] = sanitize_textarea_field(wp_unslash((string) ($raw['visitor_terms_tooltip'] ?? $current['visitor_terms_tooltip'])));
        $clean['visitor_welcome_email_subject'] = sanitize_text_field(wp_unslash((string) ($raw['visitor_welcome_email_subject'] ?? $current['visitor_welcome_email_subject'])));
        $clean['visitor_welcome_email_html'] = wp_kses_post(wp_unslash((string) ($raw['visitor_welcome_email_html'] ?? $current['visitor_welcome_email_html'])));
        $clean['visitor_unsubscribe_email_subject'] = sanitize_text_field(wp_unslash((string) ($raw['visitor_unsubscribe_email_subject'] ?? $current['visitor_unsubscribe_email_subject'])));
        $clean['visitor_unsubscribe_email_html'] = wp_kses_post(wp_unslash((string) ($raw['visitor_unsubscribe_email_html'] ?? $current['visitor_unsubscribe_email_html'])));
        $clean['visitor_recaptcha_site_key'] = sanitize_text_field(wp_unslash((string) ($raw['visitor_recaptcha_site_key'] ?? $current['visitor_recaptcha_site_key'])));
        $clean['visitor_recaptcha_secret_key'] = sanitize_text_field(wp_unslash((string) ($raw['visitor_recaptcha_secret_key'] ?? $current['visitor_recaptcha_secret_key'])));
        return $clean;
    }

    private function render_visitor_access_settings() {
        $v = $this->visitor_settings();
        echo '<table class="pzai-form-table"><tbody>';
        echo '<tr><th><label for="pzai_visitor_gate_enabled">Enable visitor access form</label></th><td><label><input id="pzai_visitor_gate_enabled" type="checkbox" name="pzai_visitor_settings[visitor_gate_enabled]" value="1" ' . checked((int) $v['visitor_gate_enabled'], 1, false) . '> Require logged-out visitors to submit the Ask AI form before they can chat.</label></td></tr>';
        echo '<tr><th><label for="pzai_visitor_gate_days">Remember visitor for days</label></th><td><select id="pzai_visitor_gate_days" name="pzai_visitor_settings[visitor_gate_days]">';
        foreach ([30, 60, 90, 120, 180, 365] as $days) {
            echo '<option value="' . esc_attr((string) $days) . '" ' . selected((int) $v['visitor_gate_days'], $days, false) . '>' . esc_html((string) $days) . ' days</option>';
        }
        echo '</select><p class="description">Controls how long the visitor can keep using Ask AI before the form appears again.</p></td></tr>';
        echo '<tr><th><label for="pzai_visitor_gate_recipient_email">Admin notification email</label></th><td><input id="pzai_visitor_gate_recipient_email" type="email" class="regular-text" name="pzai_visitor_settings[visitor_gate_recipient_email]" value="' . esc_attr((string) $v['visitor_gate_recipient_email']) . '"><p class="description">New visitor submissions are sent here. Unsubscribe alerts are also sent to customerservice@priczone.com.</p></td></tr>';
        echo '<tr><th><label for="pzai_visitor_recaptcha_site_key">reCAPTCHA v2 site key</label></th><td><input id="pzai_visitor_recaptcha_site_key" type="text" class="large-text" name="pzai_visitor_settings[visitor_recaptcha_site_key]" value="' . esc_attr((string) $v['visitor_recaptcha_site_key']) . '"><p class="description">Paste your Google reCAPTCHA v2 site key here.</p></td></tr>';
        echo '<tr><th><label for="pzai_visitor_recaptcha_secret_key">reCAPTCHA v2 secret key</label></th><td><input id="pzai_visitor_recaptcha_secret_key" type="text" class="large-text" name="pzai_visitor_settings[visitor_recaptcha_secret_key]" value="' . esc_attr((string) $v['visitor_recaptcha_secret_key']) . '"><p class="description">Paste your Google reCAPTCHA v2 secret key here so the plugin can verify submissions server-side.</p></td></tr>';
        echo '<tr><th><label for="pzai_visitor_terms_page_id">Agreement page ID</label></th><td><input id="pzai_visitor_terms_page_id" type="number" class="small-text" name="pzai_visitor_settings[visitor_terms_page_id]" value="' . esc_attr((string) $v['visitor_terms_page_id']) . '"><p class="description">Used for the clickable agreement of usage of information link inside the chat form.</p></td></tr>';
        echo '<tr><th><label for="pzai_visitor_unsubscribe_page_id">Sorry-to-leave page ID</label></th><td><input id="pzai_visitor_unsubscribe_page_id" type="number" class="small-text" name="pzai_visitor_settings[visitor_unsubscribe_page_id]" value="' . esc_attr((string) $v['visitor_unsubscribe_page_id']) . '"><p class="description">Visitors who unsubscribe from the welcome email are redirected to this page.</p></td></tr>';
        echo '<tr><th><label for="pzai_visitor_gate_thank_you_message">Thank-you chat message</label></th><td><textarea id="pzai_visitor_gate_thank_you_message" name="pzai_visitor_settings[visitor_gate_thank_you_message]" rows="3" class="large-text">' . esc_textarea((string) $v['visitor_gate_thank_you_message']) . '</textarea></td></tr>';
        echo '<tr><th><label for="pzai_visitor_welcome_email_subject">Welcome email subject</label></th><td><input id="pzai_visitor_welcome_email_subject" type="text" class="large-text" name="pzai_visitor_settings[visitor_welcome_email_subject]" value="' . esc_attr(wp_unslash((string) $v['visitor_welcome_email_subject'])) . '"><p class="description">Available tokens: {first_name}, {email}, {site_name}, {unsubscribe_url}, {thank_you_message}, {source_url}</p></td></tr>';
        echo '<tr><th><label for="pzai_visitor_welcome_email_html">Welcome email HTML</label></th><td><textarea id="pzai_visitor_welcome_email_html" name="pzai_visitor_settings[visitor_welcome_email_html]" rows="10" class="large-text code">' . esc_textarea(wp_unslash((string) $v['visitor_welcome_email_html'])) . '</textarea></td></tr>';
        echo '<tr><th><label for="pzai_visitor_unsubscribe_email_subject">Sorry-to-see-you-leave subject</label></th><td><input id="pzai_visitor_unsubscribe_email_subject" type="text" class="large-text" name="pzai_visitor_settings[visitor_unsubscribe_email_subject]" value="' . esc_attr(wp_unslash((string) $v['visitor_unsubscribe_email_subject'])) . '"></td></tr>';
        echo '<tr><th><label for="pzai_visitor_unsubscribe_email_html">Sorry-to-see-you-leave HTML</label></th><td><textarea id="pzai_visitor_unsubscribe_email_html" name="pzai_visitor_settings[visitor_unsubscribe_email_html]" rows="10" class="large-text code">' . esc_textarea(wp_unslash((string) $v['visitor_unsubscribe_email_html'])) . '</textarea></td></tr>';
        echo '</tbody></table>';
    }

    private function get_visitor_leads_rows() {
        $leads = get_option('pzai_visitor_leads', []);
        if (!is_array($leads)) $leads = [];
        $rows = [];
        foreach ($leads as $lead_key => $lead) {
            if (!is_array($lead)) continue;
            $lead['lead_key'] = sanitize_key((string) $lead_key);
            $rows[] = $lead;
        }
        usort($rows, function($a, $b) {
            $a_time = isset($a['consent_at']) ? strtotime((string) $a['consent_at']) : 0;
            $b_time = isset($b['consent_at']) ? strtotime((string) $b['consent_at']) : 0;
            if ($a_time === $b_time) return 0;
            return ($a_time < $b_time) ? 1 : -1;
        });
        return $rows;
    }

    private function render_visitor_directory_markup($page = 1) {
        $rows = $this->get_visitor_leads_rows();
        $per_page = 25;
        $total_items = count($rows);
        $total_pages = max(1, (int) ceil($total_items / $per_page));
        $page = max(1, min($total_pages, absint($page)));
        $offset = ($page - 1) * $per_page;
        $items = array_slice($rows, $offset, $per_page);
        $from = $total_items ? ($offset + 1) : 0;
        $to = min($offset + $per_page, $total_items);

        ob_start();
        echo '<div class="pzai-visitor-summary">';
        echo '<div><strong>Saved visitor directory</strong><div class="pzai-visitor-stats">Showing ' . esc_html((string) $from) . ' to ' . esc_html((string) $to) . ' of ' . esc_html((string) $total_items) . ' saved visitor entries.</div></div>';
        echo '<div class="pzai-actions">';
        echo '<button type="button" class="button button-secondary pzai-clear-visitors">Clear all saved visitors and reset form</button>';
        echo '</div>';
        echo '</div>';

        if (!$items) {
            echo '<p>No saved visitor names and emails are currently stored.</p>';
        } else {
            echo '<table class="pzai-mini-table"><thead><tr><th>First name</th><th>Email</th><th>Submitted</th><th>Status</th><th>Source</th><th class="pzai-visitor-table-actions">Action</th></tr></thead><tbody>';
            foreach ($items as $item) {
                $first_name = sanitize_text_field((string) ($item['first_name'] ?? ''));
                $email = sanitize_email((string) ($item['email'] ?? ''));
                $submitted = sanitize_text_field((string) ($item['consent_at'] ?? ''));
                $unsubscribed_at = sanitize_text_field((string) ($item['unsubscribed_at'] ?? ''));
                $source_url = esc_url_raw((string) ($item['source_url'] ?? ''));
                $lead_key = sanitize_key((string) ($item['lead_key'] ?? ''));
                $status_html = $unsubscribed_at
                    ? '<span class="pzai-visitor-status is-unsubscribed">Unsubscribed</span>'
                    : '<span class="pzai-visitor-status is-active">Active</span>';

                echo '<tr>';
                echo '<td>' . esc_html($first_name ?: '—') . '</td>';
                echo '<td>' . esc_html($email ?: '—') . '</td>';
                echo '<td>' . esc_html($submitted ?: '—') . '</td>';
                echo '<td>' . $status_html;
                if ($unsubscribed_at) echo '<div style="margin-top:6px;color:#50636d;font-size:12px">' . esc_html($unsubscribed_at) . '</div>';
                echo '</td>';
                echo '<td class="pzai-visitor-source">';
                if ($source_url) {
                    echo '<a href="' . esc_url($source_url) . '" target="_blank" rel="noopener noreferrer">' . esc_html($source_url) . '</a>';
                } else {
                    echo '—';
                }
                echo '</td>';
                echo '<td><button type="button" class="button button-small pzai-delete-visitor" data-lead-key="' . esc_attr($lead_key) . '">Delete</button></td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }

        echo '<div class="pzai-visitor-pager">';
        echo '<button type="button" class="button pzai-visitor-page" data-page="' . esc_attr((string) max(1, $page - 1)) . '" ' . disabled($page <= 1, true, false) . '>Previous 25</button>';
        echo '<span>Page ' . esc_html((string) $page) . ' of ' . esc_html((string) $total_pages) . '</span>';
        echo '<button type="button" class="button pzai-visitor-page" data-page="' . esc_attr((string) min($total_pages, $page + 1)) . '" ' . disabled($page >= $total_pages, true, false) . '>Next 25</button>';
        echo '</div>';
        echo '<p class="pzai-reset-note">Deleting an entry lets that email submit the Ask AI visitor form again. Clearing all saved visitors removes every stored email and forces the form to show again for visitors whose saved access no longer exists.</p>';
        return (string) ob_get_clean();
    }

    private function verify_ajax_request() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized.'], 403);
        }
        check_ajax_referer('pzai_admin_nonce', 'nonce');
    }

    public function ajax_get_visitor_leads() {
        $this->verify_ajax_request();
        $page = max(1, absint($_POST['page'] ?? 1));
        wp_send_json_success([
            'html' => $this->render_visitor_directory_markup($page),
        ]);
    }

    public function ajax_delete_visitor_lead() {
        $this->verify_ajax_request();
        $lead_key = sanitize_key((string) ($_POST['lead_key'] ?? ''));
        if ($lead_key === '') {
            wp_send_json_error(['message' => 'Missing visitor entry key.'], 400);
        }

        $leads = get_option('pzai_visitor_leads', []);
        if (!is_array($leads)) $leads = [];
        if (!isset($leads[$lead_key])) {
            wp_send_json_error(['message' => 'That visitor entry could not be found.'], 404);
        }

        $email = sanitize_email((string) ($leads[$lead_key]['email'] ?? ''));
        unset($leads[$lead_key]);
        update_option('pzai_visitor_leads', $leads, false);

        $unsubs = get_option('pzai_visitor_unsubscribed', []);
        if (!is_array($unsubs)) $unsubs = [];
        if ($email) {
            $unsub_key = md5(strtolower(trim($email)));
            if (isset($unsubs[$unsub_key])) {
                unset($unsubs[$unsub_key]);
                update_option('pzai_visitor_unsubscribed', $unsubs, false);
            }
        }

        $remaining = count($this->get_visitor_leads_rows());
        $page = max(1, (int) ceil(max(1, $remaining) / 25));
        wp_send_json_success([
            'message' => 'Visitor entry deleted. That email can now be submitted again.',
            'page' => $page,
        ]);
    }

    public function ajax_clear_visitor_leads() {
        $this->verify_ajax_request();
        update_option('pzai_visitor_leads', [], false);
        update_option('pzai_visitor_unsubscribed', [], false);
        update_option('pzai_visitor_reset_version', (string) time(), false);
        wp_send_json_success([
            'message' => 'All saved visitors were cleared, the visitor form can be shown again, and previous Ask AI visitor chat activity will be reset.',
        ]);
    }

    private function normalize_ollama_endpoint($endpoint) {
        $endpoint = esc_url_raw(trim((string) $endpoint));
        if ($endpoint === '') return '';
        $endpoint = rtrim($endpoint, '/');
        if (!preg_match('#^https?://#i', $endpoint)) return '';
        return $endpoint;
    }

    private function fetch_ollama_models($endpoint) {
        $endpoint = $this->normalize_ollama_endpoint($endpoint);
        if ($endpoint === '') {
            return new \WP_Error('pzai_invalid_ollama_endpoint', 'Use a full Ollama endpoint such as http://127.0.0.1:11434');
        }

        $response = wp_remote_get($endpoint . '/api/tags', [
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if (is_wp_error($response)) {
            return new \WP_Error('pzai_ollama_unreachable', 'WordPress could not reach the Ollama endpoint: ' . $response->get_error_message());
        }

        $status = (int) wp_remote_retrieve_response_code($response);
        if ($status < 200 || $status >= 300) {
            return new \WP_Error('pzai_ollama_http', 'Ollama returned HTTP ' . $status . ' when checking /api/tags.');
        }

        $json = json_decode((string) wp_remote_retrieve_body($response), true);
        $model_names = [];
        if (is_array($json) && !empty($json['models']) && is_array($json['models'])) {
            foreach ($json['models'] as $model) {
                if (!is_array($model)) continue;
                $name = sanitize_text_field((string) ($model['name'] ?? ''));
                if ($name !== '') $model_names[] = $name;
            }
        }

        $model_names = array_values(array_unique($model_names));
        update_option('pzai_ollama_model_cache', $model_names, false);

        return [
            'endpoint' => $endpoint,
            'models' => $model_names,
        ];
    }

    public function ajax_get_ollama_models() {
        $this->verify_ajax_request();

        $raw_endpoint = isset($_POST['endpoint']) ? wp_unslash((string) $_POST['endpoint']) : '';
        $result = $this->fetch_ollama_models($raw_endpoint);
        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()], 400);
        }

        $message = empty($result['models'])
            ? 'WordPress reached the Ollama endpoint but no installed models were returned by /api/tags.'
            : 'Loaded ' . count($result['models']) . ' Ollama model(s) from the local server.';

        wp_send_json_success([
            'message' => $message,
            'endpoint' => $result['endpoint'],
            'models' => $result['models'],
        ]);
    }

    public function ajax_probe_ollama() {
        $this->verify_ajax_request();

        $raw_endpoint = isset($_POST['endpoint']) ? wp_unslash((string) $_POST['endpoint']) : '';
        $result = $this->fetch_ollama_models($raw_endpoint);
        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()], 400);
        }

        $message = 'WordPress reached the Ollama endpoint successfully.';
        if (!empty($result['models'])) {
            $message .= ' Available models: ' . implode(', ', array_slice($result['models'], 0, 8));
        } else {
            $message .= ' No models were returned by /api/tags.';
        }

        wp_send_json_success([
            'message' => $message,
            'endpoint' => $result['endpoint'],
            'models' => $result['models'],
        ]);
    }

    public function clear_logs() {
        if (!current_user_can('manage_options')) wp_die('Unauthorized');
        check_admin_referer('pzai_clear_logs');
        Logger::clear();
        wp_safe_redirect(admin_url('admin.php?page=pzai-settings&logs_cleared=1'));
        exit;
    }

    public function export_logs() {
        if (!current_user_can('manage_options')) wp_die('Unauthorized');
        check_admin_referer('pzai_export_logs');
        nocache_headers();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=pzai-query-logs.csv');
        echo Logger::csv();
        exit;
    }

    private function persist_settings_from_request() {
        $raw = $_POST[Settings::OPTION_KEY] ?? [];
        if (!is_array($raw)) $raw = [];
        $clean = $this->settings->sanitize($raw);
        update_option(Settings::OPTION_KEY, $clean, false);

        $visitor_raw = $_POST['pzai_visitor_settings'] ?? [];
        update_option('pzai_visitor_settings', $this->sanitize_visitor_settings($visitor_raw), false);
    }

    public function ajax_save_settings() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized.'], 403);
        }
        check_ajax_referer('pzai_save_settings');
        $this->persist_settings_from_request();
        wp_send_json_success([
            'message' => 'Settings saved without refreshing the page.',
        ]);
    }

    public function save_settings() {
        if (!current_user_can('manage_options')) wp_die('Unauthorized');
        check_admin_referer('pzai_save_settings');
        $this->persist_settings_from_request();
        wp_safe_redirect(admin_url('admin.php?page=pzai-settings&updated=1'));
        exit;
    }

    private function section($title, $desc, $callback, $open = true) {
        $key = sanitize_title($title);
        echo '<details class="pzai-card pzai-accordion-card" data-pzai-accordion-key="' . esc_attr($key) . '" ' . ($open ? 'open' : '') . '>';
        echo '<summary class="pzai-accordion-summary">';
        echo '<span class="pzai-accordion-caret" aria-hidden="true"></span>';
        echo '<span class="pzai-accordion-title-wrap">';
        echo '<span class="pzai-accordion-title">' . esc_html($title) . '</span>';
        if ($desc) echo '<span class="pzai-accordion-subtitle">' . esc_html($desc) . '</span>';
        echo '</span>';
        echo '<span class="pzai-accordion-status is-open">Open</span>';
        echo '</summary>';
        echo '<div class="pzai-accordion-body">';
        call_user_func($callback);
        echo '</div>';
        echo '</details>';
    }

    private function render_analytics() {
        $summary = Logger::summary();
        $logs = array_reverse(array_slice(Logger::get_all(), -12));
        echo '<div class="pzai-metrics">';
        echo '<div class="pzai-metric"><span>Total queries</span><strong>' . esc_html((string) ($summary['total_queries'] ?? 0)) . '</strong></div>';
        echo '<div class="pzai-metric"><span>Zero-result queries</span><strong>' . esc_html((string) ($summary['zero_result_queries'] ?? 0)) . '</strong></div>';
        echo '<div class="pzai-metric"><span>Average results</span><strong>' . esc_html((string) ($summary['avg_results'] ?? 0)) . '</strong></div>';
        echo '<div class="pzai-metric"><span>Product clicks</span><strong>' . esc_html((string) (($summary['events']['product_click'] ?? 0))) . '</strong></div>';
        echo '<div class="pzai-metric"><span>AI add-to-carts</span><strong>' . esc_html((string) ($summary['ai_assisted_add_to_carts'] ?? 0)) . '</strong></div>';
        echo '<div class="pzai-metric"><span>AI assisted orders</span><strong>' . esc_html((string) ($summary['ai_assisted_orders'] ?? 0)) . '</strong></div>';
        echo '<div class="pzai-metric"><span>AI assisted revenue</span><strong>' . (function_exists('wc_price') ? wp_kses_post(wc_price((float) ($summary['ai_assisted_revenue'] ?? 0))) : esc_html('$' . number_format((float) ($summary['ai_assisted_revenue'] ?? 0), 2))) . '</strong></div>';
        echo '</div>';

        echo '<h3>Top queries</h3><table class="pzai-mini-table"><thead><tr><th>Query</th><th>Count</th></tr></thead><tbody>';
        if (!empty($summary['top_queries'])) {
            foreach ($summary['top_queries'] as $query => $count) {
                echo '<tr><td>' . esc_html($query) . '</td><td>' . esc_html((string) $count) . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="2">No query data yet.</td></tr>';
        }
        echo '</tbody></table>';

        echo '<h3>Top zero-result queries</h3><table class="pzai-mini-table"><thead><tr><th>Query</th><th>Count</th></tr></thead><tbody>';
        if (!empty($summary['top_zero_result_queries'])) {
            foreach ($summary['top_zero_result_queries'] as $query => $count) {
                echo '<tr><td>' . esc_html($query) . '</td><td>' . esc_html((string) $count) . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="2">No zero-result queries yet.</td></tr>';
        }
        echo '</tbody></table>';

        echo '<h3>Top converting queries</h3><table class="pzai-mini-table"><thead><tr><th>Query</th><th>Orders</th></tr></thead><tbody>';
        if (!empty($summary['top_converting_queries'])) {
            foreach ($summary['top_converting_queries'] as $query => $count) {
                echo '<tr><td>' . esc_html($query) . '</td><td>' . esc_html((string) $count) . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="2">No AI-assisted orders yet.</td></tr>';
        }
        echo '</tbody></table>';

        echo '<h3>Recent query log</h3><table class="pzai-mini-table"><thead><tr><th>Time</th><th>Query</th><th>Type</th><th>Results</th></tr></thead><tbody>';
        if ($logs) {
            foreach ($logs as $row) {
                echo '<tr><td>' . esc_html((string) ($row['time'] ?? '')) . '</td><td>' . esc_html((string) ($row['query'] ?? '')) . '</td><td>' . esc_html((string) ($row['response_type'] ?? '')) . '</td><td>' . esc_html((string) ($row['result_count'] ?? 0)) . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="4">No logs recorded yet.</td></tr>';
        }
        echo '</tbody></table>';

        echo '<div class="pzai-actions">';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('pzai_export_logs');
        echo '<input type="hidden" name="action" value="pzai_export_logs">';
        echo '<button class="button" type="submit">Export CSV</button>';
        echo '</form>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('pzai_clear_logs');
        echo '<input type="hidden" name="action" value="pzai_clear_logs">';
        echo '<button class="button button-secondary" type="submit">Clear analytics logs</button>';
        echo '</form>';
        echo '</div>';
    }

    public function page() {
        if (!current_user_can('manage_options')) return;

        $faq_visual = json_decode($this->settings->get('faq_items', '[]'), true);
        if (!is_array($faq_visual)) $faq_visual = [];

        $syn_visual = json_decode($this->settings->get('synonym_items', '[]'), true);
        if (!is_array($syn_visual)) $syn_visual = [];

        $clean_url = admin_url('admin.php?page=pzai-settings');

        echo '<div class="wrap pzai-wrap">';
        echo '<div class="pzai-hero"><span class="pzai-badge">Version ' . esc_html(PZAI_VERSION) . '</span><h1>PricZone AI Concierge</h1><p>Storefront AI shopping assistant for WooCommerce.</p>';
        if (!empty($_GET['updated'])) echo '<div class="pzai-notice-inline" data-clean-url="' . esc_url($clean_url) . '">Settings saved.</div>';
        if (!empty($_GET['logs_cleared'])) echo '<div class="pzai-notice-inline" data-clean-url="' . esc_url($clean_url) . '">Analytics logs cleared.</div>';
        echo '</div>';

        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" data-pzai-settings-form="1">';
        wp_nonce_field('pzai_save_settings');
        echo '<input type="hidden" name="action" value="pzai_save_settings">';
        echo '<div class="pzai-stack">';

        $self = $this;
        $this->section('General', 'Core behavior and storefront visibility settings.', function() use ($self) {
            $self->settings->render_fields_for_tab('general');
        });
        $this->section('Display', 'Widget labels and mobile visibility controls.', function() use ($self) {
            $self->settings->render_fields_for_tab('display');
        });
        $this->section('Visitor Access', 'Require logged-out visitors to submit a consent form before using Ask AI, store visitor entries, and control reCAPTCHA for the form.', function() use ($self) {
            $self->render_visitor_access_settings();
        });
        $this->section('Store Knowledge', 'Support information, policies, and FAQ answers the bot can trust.', function() use ($self, $faq_visual) {
            $self->settings->render_fields_for_tab('store');
            echo '<div class="pzai-builder"><h3>Visual FAQ manager</h3><p>Add common questions without editing raw JSON.</p><table><thead><tr><th>Question</th><th>Answer</th></tr></thead><tbody class="pzai-faq-body">';
            foreach ($faq_visual as $row) {
                echo '<tr class="pzai-faq-row"><td><input class="pzai-faq-q" type="text" value="' . esc_attr($row['q'] ?? '') . '"></td><td><input class="pzai-faq-a" type="text" value="' . esc_attr($row['a'] ?? '') . '"></td></tr>';
            }
            if (!$faq_visual) echo '<tr class="pzai-faq-row"><td><input class="pzai-faq-q" type="text"></td><td><input class="pzai-faq-a" type="text"></td></tr>';
            echo '</tbody></table><p><button type="button" class="button pzai-add-faq">Add FAQ Row</button></p></div>';
        });
        $this->section('Catalog Intelligence', 'Category matching, search language, and synonym management.', function() use ($self, $syn_visual) {
            $self->settings->render_fields_for_tab('catalog');
            echo '<div class="pzai-builder"><h3>Visual synonym editor</h3><p>Map shopper phrases to your actual WooCommerce category IDs.</p><table><thead><tr><th>Category ID</th><th>Shopper phrase</th></tr></thead><tbody class="pzai-syn-body">';
            foreach ($syn_visual as $row) {
                echo '<tr class="pzai-syn-row"><td><input class="pzai-syn-c" type="text" value="' . esc_attr($row['category_id'] ?? ($row['category'] ?? '')) . '"></td><td><input class="pzai-syn-p" type="text" value="' . esc_attr($row['phrase'] ?? '') . '"></td></tr>';
            }
            if (!$syn_visual) echo '<tr class="pzai-syn-row"><td><input class="pzai-syn-c" type="text"></td><td><input class="pzai-syn-p" type="text"></td></tr>';
            echo '</tbody></table><p><button type="button" class="button pzai-add-syn">Add Synonym Row</button></p></div>';
        });
        $this->section('Response Controls', 'Fallback replies and answer behavior.', function() use ($self) {
            $self->settings->render_fields_for_tab('responses');
        });
        $this->section('AI Integration', 'Ollama-first provider controls with an optional legacy external provider view.', function() use ($self) {
            echo '<div class="pzai-legacy-ai-note">Recommended setup: <strong>Ollama Local</strong> for AI replies, plus <strong>None (rules only)</strong> as the safe fallback. Legacy external providers can stay hidden unless you still need them.</div>';
            $self->settings->render_fields_for_tab('integrations');
            echo '<div class="pzai-builder" data-pzai-provider-field="ollama_local"><h3>Optional Local AI Probe</h3><p>This checks whether WordPress can reach the Ollama endpoint and can also refresh the installed model list for the selector below. It does not send product data or save settings.</p><div class="pzai-probe-feedback"></div><div class="pzai-model-actions"><button type="button" class="button button-secondary pzai-probe-ollama">Test Ollama Connection</button><button type="button" class="button pzai-refresh-ollama-models">Refresh Model List</button></div></div>';
        });

        echo '<p class="submit pzai-submit-row"><button type="submit" class="button button-primary button-large pzai-save-settings-button">Save Settings</button><span class="pzai-live-save-feedback" aria-live="polite"></span></p>';
        echo '</div></form>';

        echo '<div class="pzai-stack" style="margin-top:20px">';
        $this->section('Saved Visitor Directory', 'Review stored visitor names and emails, delete individual entries, or clear all saved emails without reloading the admin page.', function() {
            echo '<div class="pzai-directory-feedback"></div>';
            echo '<div class="pzai-directory-shell" data-pzai-visitor-directory><p>Loading visitor directory...</p></div>';
        });
        $this->section('Analytics Overview', 'Track what shoppers ask, where results fail, and what they click after the AI responds.', function() use ($self) {
            $self->render_analytics();
        });
        echo '</div>';
        echo '<div class="pzai-quick-nav" aria-label="Admin page quick scroll">';
        echo '<button type="button" class="pzai-quick-nav-btn" data-pzai-scroll="top" aria-label="Back to top" title="Back to top"><svg class="pzai-quick-nav-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 14l6-6 6 6"></path></svg><span class="pzai-scroll-label">Back to top</span></button>';
        echo '<button type="button" class="pzai-quick-nav-btn" data-pzai-scroll="center" aria-label="Jump to center" title="Jump to center"><svg class="pzai-quick-nav-icon pzai-quick-nav-icon-center" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="4.25"></circle></svg><span class="pzai-scroll-label">Jump to center</span></button>';
        echo '<button type="button" class="pzai-quick-nav-btn" data-pzai-scroll="bottom" aria-label="Back to bottom" title="Back to bottom"><svg class="pzai-quick-nav-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 10l6 6 6-6"></path></svg><span class="pzai-scroll-label">Back to bottom</span></button>';
        echo '</div>';
        echo '</div>';
    }
}
