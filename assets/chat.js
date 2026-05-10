document.addEventListener('DOMContentLoaded', function(){
  const root=document.getElementById('pzai-widget');
  if(!root || typeof pzaiData==='undefined') return;

  const toggle=root.querySelector('.pzai-toggle');
  const closeBtn=root.querySelector('.pzai-close');
  const clearBtn=root.querySelector('.pzai-clear');
  const panel=root.querySelector('.pzai-panel');
  const form=root.querySelector('.pzai-chat-form');
  const input=root.querySelector('.pzai-chat-input');
  const messages=root.querySelector('.pzai-messages');
  const title=root.querySelector('.pzai-title');
  const sendBtn=root.querySelector('.pzai-chat-send');
  const gateWrap=root.querySelector('.pzai-gate');
  const gateForm=root.querySelector('.pzai-gate-form');
  const gateName=root.querySelector('.pzai-gate-name');
  const gateEmail=root.querySelector('.pzai-gate-email');
  const gateAgree=root.querySelector('.pzai-gate-agree');
  const gateErrorBox=root.querySelector('.pzai-gate-error');
  const gateSubmitBtn=gateForm ? gateForm.querySelector('button[type="submit"]') : null;
  const defaultGateSubmitText=gateSubmitBtn ? (gateSubmitBtn.textContent || 'Submit') : 'Submit';
  const defaultPlaceholder=input ? (input.getAttribute('placeholder') || '') : '';
  const STORAGE_KEY='pzai_chat_state_v3';
  const GATE_STORAGE_KEY='pzai_visitor_gate_v2';
  const RESET_STORAGE_KEY='pzai_visitor_reset_version';
  const GATE_SYNC_STORAGE_KEY='pzai_visitor_gate_sync';
  const MAX_HISTORY=60;
  const DAY_MS=86400000;

  function keepPanelOpenOnReset(options){
    if(options && typeof options.keepOpen !== 'undefined') return !!options.keepOpen;
    return !!(state && state.open);
  }

  function updatePanelMetrics(){
    if(!root) return;
    var viewportWidth=Math.max(window.innerWidth || 0, 0);
    var panelWidth=Math.min(360, Math.max(220, viewportWidth - 24));
    if(!viewportWidth) panelWidth=360;
    root.style.setProperty('--pzai-panel-width', panelWidth + 'px');
  }

  function genSessionId(){ return 'pzai-'+Math.random().toString(36).slice(2,10); }
  function defaultState(){ return { sessionId: genSessionId(), open:false, draft:'', history:[], updatedAt:Date.now() }; }
  function safeState(raw){
    const base=defaultState();
    try{
      const data=JSON.parse(raw||'{}');
      if(data && typeof data==='object'){
        if(typeof data.sessionId==='string' && data.sessionId) base.sessionId=data.sessionId;
        if(typeof data.open==='boolean') base.open=data.open;
        if(typeof data.draft==='string') base.draft=data.draft;
        if(Array.isArray(data.history)) base.history=data.history.slice(-MAX_HISTORY);
        base.updatedAt=Number(data.updatedAt||Date.now()) || Date.now();
      }
    }catch(e){}
    return base;
  }

  let state;
  try{ state=safeState(localStorage.getItem(STORAGE_KEY)); }catch(e){ state=defaultState(); }
  let sessionId=state.sessionId || genSessionId();
  state.sessionId=sessionId;
  let gateState={allowed:!isGateRequired(),expiresAt:0,firstName:'',email:''};

  function saveState(){
    state.updatedAt=Date.now();
    state.sessionId=sessionId;
    state.history=state.history.slice(-MAX_HISTORY);
    try{ localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); }catch(e){}
  }

  function shouldHideForViewport(){
    if(!pzaiData.hideOnMobile) return false;
    const maxWidth=parseInt(pzaiData.hideBelowWidth || 768, 10);
    return window.innerWidth <= maxWidth;
  }

  function syncVisibility(){
    const hidden=shouldHideForViewport();
    root.style.display = hidden ? 'none' : 'flex';
    if(hidden){
      root.classList.remove('pzai-open');
      if(panel) panel.hidden=true;
      return;
    }
    root.classList.toggle('pzai-open', !!state.open);
    if(panel) panel.hidden=!state.open;
    if(state.open){ scrollToLatest(); }
  }

  function writeCookie(name, value){
    const days=Math.max(parseInt((pzaiData && pzaiData.visitorRememberDays) || 30,10) || 30,1);
    try{ document.cookie = name + '=' + encodeURIComponent(value || '') + '; path=/; max-age=' + (60*60*24*days) + '; SameSite=Lax'; }catch(e){}
  }

  function eraseCookie(name){
    try{ document.cookie = name + '=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT; SameSite=Lax'; }catch(e){}
  }

  function readCookie(name){
    try{
      const found=document.cookie.split('; ').find(function(part){ return part.indexOf(name + '=')===0; });
      return found ? decodeURIComponent(found.split('=').slice(1).join('=')) : '';
    }catch(e){ return ''; }
  }

  function isGateRequired(){
    return !!Number((pzaiData && pzaiData.visitorGateEnabled) || 0) && !Number((pzaiData && pzaiData.isLoggedIn) || 0);
  }

  function clearGateError(){
    if(gateErrorBox){ gateErrorBox.hidden=true; gateErrorBox.textContent=''; }
  }

  function showGateError(message){
    if(!gateErrorBox) return;
    gateErrorBox.textContent=String(message||'Please complete the visitor form to continue.');
    gateErrorBox.hidden=false;
  }

  function saveGateState(){
    try{ localStorage.setItem(GATE_STORAGE_KEY, JSON.stringify(gateState)); }catch(e){}
  }

  function clearGateState(){
    gateState={allowed:!isGateRequired(),expiresAt:0,firstName:'',email:''};
    try{ localStorage.removeItem(GATE_STORAGE_KEY); }catch(e){}
    eraseCookie('pzai_visitor_gate');
    eraseCookie('pzai_visitor_email');
    eraseCookie('pzai_session_id');
    eraseCookie('pzai_assistant_used');
    eraseCookie('pzai_last_query');
    eraseCookie('pzai_last_product_id');
    eraseCookie('pzai_last_product_name');
  }

  function currentResetVersion(){
    return String((pzaiData && pzaiData.visitorResetVersion) || '0');
  }

  function readStoredResetVersion(){
    try{ return String(localStorage.getItem(RESET_STORAGE_KEY) || '0'); }catch(e){ return '0'; }
  }

  function writeStoredResetVersion(value){
    try{ localStorage.setItem(RESET_STORAGE_KEY, String(value || '0')); }catch(e){}
  }

  function hasChatActivity(){
    return !!((state && Array.isArray(state.history) && state.history.length) || readCookie('pzai_visitor_gate') || readCookie('pzai_visitor_email'));
  }

  function applyVisitorReset(resetVersion){
    clearGateState();
    clearChatState({silent:true, keepOpen:keepPanelOpenOnReset(), skipFocus:true});
    writeStoredResetVersion(resetVersion || currentResetVersion());
    syncGateUI();
    clearGateError();
  }

  function maybeApplyStoredReset(){
    var current=currentResetVersion();
    var stored=readStoredResetVersion();
    if(current !== stored){
      if(current !== '0' && hasChatActivity()){
        applyVisitorReset(current);
        return;
      }
      writeStoredResetVersion(current);
    }
  }


  function syncVisitorGateResetAcrossTabs(storageKey){
    if(storageKey && storageKey !== GATE_STORAGE_KEY && storageKey !== GATE_SYNC_STORAGE_KEY && storageKey !== RESET_STORAGE_KEY) return;
    clearGateState();
    clearChatState({silent:true, keepOpen:keepPanelOpenOnReset(), skipFocus:true});
    syncGateUI();
    clearGateError();
  }

  function hydrateGateState(){
    if(!isGateRequired()){ gateState={allowed:true,expiresAt:Date.now()+DAY_MS,firstName:'',email:''}; return; }
    let stored={};
    try{ stored=JSON.parse(localStorage.getItem(GATE_STORAGE_KEY)||'{}')||{}; }catch(e){ stored={}; }
    const cookieAllowed=readCookie('pzai_visitor_gate')==='1';
    const expiresAt=Number(stored.expiresAt||0)||0;
    const notExpired=expiresAt > Date.now();
    gateState={
      allowed: !!(cookieAllowed || (stored.allowed && notExpired)),
      expiresAt: cookieAllowed ? (Date.now() + (((parseInt((pzaiData && pzaiData.visitorRememberDays) || 30,10) || 30) * DAY_MS))) : expiresAt,
      firstName: typeof stored.firstName==='string' ? stored.firstName : '',
      email: typeof stored.email==='string' ? stored.email : ''
    };
    if(!gateState.allowed){ clearGateState(); } else { saveGateState(); }
  }

  function isRecaptchaEnabled(){
    return !!Number((pzaiData && pzaiData.recaptchaEnabled) || 0);
  }

  function getRecaptchaResponse(){
    if(!isRecaptchaEnabled()) return '';
    try{
      if(window.grecaptcha && typeof window.grecaptcha.getResponse==='function'){
        return window.grecaptcha.getResponse() || '';
      }
    }catch(e){}
    return '';
  }

  function resetRecaptcha(){
    if(!isRecaptchaEnabled()) return;
    try{
      if(window.grecaptcha && typeof window.grecaptcha.reset==='function'){
        window.grecaptcha.reset();
      }
    }catch(e){}
  }

  async function syncGateAccessFromServer(){
    if(!isGateRequired() || !pzaiData.visitorStatusEndpoint) return;
    const shouldCheck = !!(readCookie('pzai_visitor_gate') || readCookie('pzai_visitor_email') || gateState.allowed || hasChatActivity());
    if(!shouldCheck) return;
    try{
      const res=await fetch(pzaiData.visitorStatusEndpoint,{method:'GET',credentials:'same-origin',headers:restHeaders()});
      const data=await res.json();
      const serverResetVersion=(data && typeof data.reset_version !== 'undefined') ? String(data.reset_version) : currentResetVersion();
      if(serverResetVersion !== readStoredResetVersion()){
        applyVisitorReset(serverResetVersion);
        return;
      }
      if(res.ok && data && data.allowed){
        gateState.allowed=true;
        gateState.expiresAt=Date.now()+((parseInt((data.remember_days || pzaiData.visitorRememberDays || 30),10) || 30) * DAY_MS);
        gateState.email=typeof data.email==='string' ? data.email : gateState.email;
        saveGateState();
      }else{
        if(hasChatActivity()) clearChatState({silent:true, keepOpen:keepPanelOpenOnReset(), skipFocus:true});
        clearGateState();
      }
      syncGateUI();
    }catch(e){}
  }

  function isGateAllowed(){
    return !isGateRequired() || (gateState.allowed && Number(gateState.expiresAt||0) > Date.now());
  }

  function setChatLocked(locked){
    if(form) form.classList.toggle('pzai-disabled', !!locked);
    if(input){
      input.disabled=!!locked;
      input.placeholder=locked ? 'Complete this form below to use ASK AI' : defaultPlaceholder;
      input.setAttribute('aria-disabled', locked ? 'true' : 'false');
    }
    if(sendBtn){
      sendBtn.disabled=!!locked;
      sendBtn.setAttribute('aria-disabled', locked ? 'true' : 'false');
    }
  }

  function syncGateUI(){
    const blocked=!isGateAllowed();
    if(gateWrap) gateWrap.hidden=!blocked;
    setChatLocked(blocked);
  }

  function restHeaders(){
    const headers={'Content-Type':'application/json'};
    if(typeof pzaiData!=='undefined' && pzaiData && pzaiData.restNonce){ headers['X-WP-Nonce']=pzaiData.restNonce; }
    return headers;
  }

  function trackEvent(eventType, payload){
    const body=Object.assign({event_type:eventType,session_id:sessionId}, payload||{});
    try{ fetch((pzaiData.endpoint||'').replace('/chat','/event'),{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)}); }catch(e){}
  }

  function getWelcomeText(){
    return pzaiData.welcome || pzaiData.welcomeMessage || pzaiData.greeting || pzaiData.initialMessage || 'Hi! How can I help you today?';
  }

  function scrollToBottom(){ messages.scrollTop=messages.scrollHeight; }
  function scrollToLatest(){
    let tries=0;
    function apply(){
      if(!messages) return;
      messages.scrollTop=messages.scrollHeight;
      tries++;
      if(tries < 8) window.requestAnimationFrame(apply);
    }
    window.requestAnimationFrame(apply);
    setTimeout(function(){ if(messages) messages.scrollTop=messages.scrollHeight; }, 60);
    setTimeout(function(){ if(messages) messages.scrollTop=messages.scrollHeight; }, 180);
    setTimeout(function(){ if(messages) messages.scrollTop=messages.scrollHeight; }, 420);
  }
  function bindAutoScrollForMedia(scope){
    const rootEl=scope || messages;
    if(!rootEl || !rootEl.querySelectorAll) return;
    rootEl.querySelectorAll('img').forEach(function(img){
      if(img.dataset.pzaiScrollBound==='1') return;
      img.dataset.pzaiScrollBound='1';
      if(!img.complete){
        img.addEventListener('load', scrollToLatest, {once:true});
        img.addEventListener('error', scrollToLatest, {once:true});
      }
    });
  }
  function remember(entry){ state.history.push(entry); state.history=state.history.slice(-MAX_HISTORY); saveState(); }

  function decodeEntities(value){
    const str=String(value==null ? '' : value);
    if(!str || str.indexOf('&')===-1) return str;
    const el=document.createElement('textarea');
    el.innerHTML=str;
    return el.value;
  }

  function normalizeDisplayText(value){
    return decodeEntities(value).replace(/\s+/g,' ').trim();
  }

  function renderText(text, cls){
    const div=document.createElement('div');
    div.className=cls;
    div.textContent=normalizeDisplayText(text);
    messages.appendChild(div);
    scrollToBottom();
  }

  function appendMessage(text, cls, rememberIt){
    renderText(text, cls);
    if(rememberIt!==false){
      remember({kind:'text', role: cls.indexOf('pzai-user-message')!==-1 ? 'user' : 'bot', text:String(text||'')});
    }
  }

  function renderSuggestions(items){
    if(!Array.isArray(items) || !items.length) return;
    const wrap=document.createElement('div');
    wrap.className='pzai-suggestions';
    items.forEach(function(item){
      if(!item) return;
      const btn=document.createElement('button');
      const label=normalizeDisplayText((typeof item==='string') ? item : String((item && (item.label || item.text || item.query)) || '').trim());
      if(!label) return;
      btn.type='button';
      btn.className='pzai-chip';
      btn.textContent=label;
      btn.addEventListener('click', function(){
        trackEvent('suggestion_click',{label:label});
        sendMessage(label, item && typeof item==='object' ? item : null);
      });
      wrap.appendChild(btn);
    });
    messages.appendChild(wrap);
    scrollToLatest();
  }

  function appendSuggestions(items, rememberIt){
    const clean=(items||[]).filter(Boolean).slice(0,8);
    if(!clean.length) return;
    renderSuggestions(clean);
    if(rememberIt!==false) remember({kind:'suggestions', items:clean});
  }

  function resolveProductUrl(p){
    if(!p || typeof p!=='object') return '';
    return p.url || p.permalink || p.link || p.product_url || p.product_permalink || p.slug_url || '';
  }

  function resolveCategoryUrl(items, forcedUrl){
    if(forcedUrl) return forcedUrl;
    if(!Array.isArray(items) || !items.length) return '';
    const p=items[0] || {};
    if(p.view_all_url) return p.view_all_url;
    if(p.deepest_category_url) return p.deepest_category_url;
    if(p.grandchild_category_url) return p.grandchild_category_url;
    if(p.child_category_url) return p.child_category_url;
    if(Array.isArray(p.categories) && p.categories.length){
      const sorted=p.categories.filter(function(cat){ return cat && cat.url; }).sort(function(a,b){ return (Number(b.depth)||0) - (Number(a.depth)||0); });
      if(sorted.length && sorted[0].url) return sorted[0].url;
    }
    return p.category_url || '';
  }

  function renderViewAllLink(items, forcedUrl){
    const categoryUrl=resolveCategoryUrl(items, forcedUrl);
    if(!categoryUrl) return;
    const wrap=document.createElement('div');
    wrap.className='pzai-view-all-wrap';
    const a=document.createElement('a');
    a.className='pzai-view-all-link';
    a.href=categoryUrl;
    a.target='_self';
    a.rel='noopener noreferrer';
    a.textContent='View all';
    a.addEventListener('click', function(e){
      writeCookie('pzai_assistant_used','1');
      writeCookie('pzai_session_id', sessionId);
      trackEvent('category_view_all_click',{category_url:categoryUrl});
      e.preventDefault();
      window.location.assign(categoryUrl);
    });
    wrap.appendChild(a);
    messages.appendChild(wrap);
    scrollToBottom();
  }

  function renderProducts(items, meta){
    if(!Array.isArray(items) || !items.length) return;
    const wrap=document.createElement('div');
    wrap.className='pzai-products';
    items.forEach(function(p){
      if(!p) return;
      const a=document.createElement('a');
      const productUrl=resolveProductUrl(p);
      a.className='pzai-product';
      a.href=productUrl || '#';
      a.target='_self';
      a.rel='noopener noreferrer';
      a.addEventListener('click', function(e){
        writeCookie('pzai_assistant_used','1');
        writeCookie('pzai_session_id', sessionId);
        if(p.id) writeCookie('pzai_last_product_id', String(p.id));
        if(p.name) writeCookie('pzai_last_product_name', normalizeDisplayText(p.name));
        trackEvent('product_click',{label:normalizeDisplayText(p.name||''),product_id:p.id||0});
        if(window.clarity){ try{ window.clarity('event','pzai_product_click_after_chat'); }catch(e){} }
        if(!productUrl){
          e.preventDefault();
          return;
        }
        e.preventDefault();
        window.location.assign(productUrl);
      });

      let price=normalizeDisplayText((p.price_html || p.price || '').toString().trim());
      let metaText=normalizeDisplayText((p.price_range_text || p.meta || '').toString().trim());
      if(!metaText && price.indexOf('Price range:') !== -1){
        const parts=price.split('Price range:');
        price=normalizeDisplayText((parts.shift() || '').trim());
        metaText=normalizeDisplayText(('Price range:' + parts.join('Price range:')).trim());
      }

      if(p.image){
        const img=document.createElement('img');
        img.src=p.image;
        img.alt=normalizeDisplayText(p.name||'Product');
        a.appendChild(img);
      }

      const info=document.createElement('div');
      info.className='pzai-product-info';

      const title=document.createElement('strong');
      title.textContent=normalizeDisplayText(p.name||'Product');
      info.appendChild(title);

      if(price || metaText){
        const metaRow=document.createElement('span');
        metaRow.className='pzai-product-meta-row';
        if(price){
          const priceSpan=document.createElement('span');
          priceSpan.className='pzai-product-price';
          priceSpan.textContent=price;
          metaRow.appendChild(priceSpan);
        }
        if(price && metaText){
          const divider=document.createElement('span');
          divider.className='pzai-meta-divider';
          divider.setAttribute('aria-hidden','true');
          divider.textContent=' | ';
          metaRow.appendChild(divider);
        }
        if(metaText){
          const metaSpan=document.createElement('span');
          metaSpan.className='pzai-product-meta';
          metaSpan.textContent=metaText;
          metaRow.appendChild(metaSpan);
        }
        info.appendChild(metaRow);
      }

      a.appendChild(info);
      wrap.appendChild(a);
    });
    messages.appendChild(wrap);
    bindAutoScrollForMedia(wrap);
    renderViewAllLink(items, meta && meta.view_all_url ? meta.view_all_url : '');
    scrollToLatest();
  }

  function appendProducts(items, rememberIt, meta){
    const clean=(items||[]).slice(0,8).map(function(p){
      return {
        id:p && p.id ? p.id : 0,
        name:p && p.name ? normalizeDisplayText(p.name) : '',
        url:p && (p.url || p.permalink || p.link || p.product_url || p.product_permalink || p.slug_url) ? (p.url || p.permalink || p.link || p.product_url || p.product_permalink || p.slug_url) : '',
        image:p && p.image ? p.image : '',
        meta:p && p.meta ? p.meta : '',
        price:p && p.price ? p.price : '',
        price_html:p && p.price_html ? normalizeDisplayText(p.price_html) : '',
        price_range_text:p && p.price_range_text ? normalizeDisplayText(p.price_range_text) : '',
        category_url:p && p.category_url ? p.category_url : '',
        child_category_url:p && p.child_category_url ? p.child_category_url : '',
        grandchild_category_url:p && p.grandchild_category_url ? p.grandchild_category_url : '',
        deepest_category_url:p && p.deepest_category_url ? p.deepest_category_url : '',
        view_all_url:p && p.view_all_url ? p.view_all_url : '',
        categories:p && Array.isArray(p.categories) ? p.categories : []
      };
    }).filter(function(p){ return p.name || p.url || p.image || p.price || p.price_html; });
    if(!clean.length) return;
    const viewAllUrl=(meta && meta.view_all_url) || (clean[0] && clean[0].view_all_url) || '';
    renderProducts(clean, {view_all_url:viewAllUrl});
    if(rememberIt!==false) remember({kind:'products', items:clean, view_all_url:viewAllUrl});
  }

  function setStatusNotice(key, label, show){
    if(!messages) return;
    const selector='.pzai-status-notice[data-status-key="' + key + '"]';
    let el=messages.querySelector(selector);
    if(show){
      if(!el){
        el=document.createElement('div');
        el.className='pzai-bot-message pzai-thinking pzai-status-notice';
        el.setAttribute('data-status-key', key);
        messages.appendChild(el);
      }
      el.textContent=String(label || '');
      scrollToLatest();
      return;
    }
    if(el) el.remove();
  }

  function setThinking(show){
    setStatusNotice('thinking','Thinking...',show);
  }

  function setGateSubmitting(show){
    setStatusNotice('gate-submit','Submitting...',show);
    if(gateSubmitBtn){
      gateSubmitBtn.disabled=!!show;
      gateSubmitBtn.textContent=show ? 'Submitting...' : defaultGateSubmitText;
      gateSubmitBtn.setAttribute('aria-busy', show ? 'true' : 'false');
    }
    [gateName, gateEmail, gateAgree].forEach(function(field){
      if(field){
        field.disabled=!!show;
        field.setAttribute('aria-disabled', show ? 'true' : 'false');
      }
    });
  }

  function restoreHistory(){
    messages.innerHTML='';
    (state.history||[]).forEach(function(entry){
      if(!entry || typeof entry!=='object') return;
      if(entry.kind==='text'){
        renderText(entry.text||'', entry.role==='user' ? 'pzai-user-message' : 'pzai-bot-message');
      }else if(entry.kind==='products'){
        renderProducts(entry.items||[], {view_all_url: entry.view_all_url || ''});
      }else if(entry.kind==='suggestions'){
        renderSuggestions(entry.items||[]);
      }
    });
    bindAutoScrollForMedia(messages);
    scrollToLatest();
  }

  function ensureWelcomeMessage(){
    if(state.history && state.history.length) return;
    appendMessage(getWelcomeText(),'pzai-bot-message');
  }

  function clearChatState(options){
    options=options||{};
    sessionId=genSessionId();
    const nextOpen = keepPanelOpenOnReset(options);
    state={sessionId:sessionId, open:nextOpen, draft:'', history:[], updatedAt:Date.now()};
    if(input) input.value='';
    writeCookie('pzai_session_id', sessionId);
    writeCookie('pzai_last_query','');
    writeCookie('pzai_last_product_id','');
    writeCookie('pzai_last_product_name','');
    saveState();
    updatePanelMetrics();
    restoreHistory();
    ensureWelcomeMessage();
    syncVisibility();
    if(!options.skipFocus && input) input.focus();
  }

  async function sendMessage(message, metadata){
    const value=String(message||'').trim();
    if(!value) return;
    if(!isGateAllowed()){ syncGateUI(); showGateError('Please complete the visitor form before using Ask AI.'); return; }
    writeCookie('pzai_assistant_used','1');
    writeCookie('pzai_session_id', sessionId);
    writeCookie('pzai_last_query', value);
    appendMessage(value,'pzai-user-message');
    state.draft='';
    saveState();
    if(input) input.value='';
    setThinking(true);
    try{
      const payload={message:value,session_id:sessionId};
      if(pzaiData.pageContext && typeof pzaiData.pageContext==='object'){ payload.page_context=pzaiData.pageContext; }
      if(metadata && typeof metadata==='object'){ payload.suggestion_meta=metadata; }
      const res=await fetch(pzaiData.endpoint,{method:'POST',credentials:'same-origin',headers:restHeaders(),body:JSON.stringify(payload)});
      const data=await res.json();
      setThinking(false);
      if(!res.ok && data && data.error==='visitor_gate_required'){
        if(data && typeof data.reset_version !== 'undefined' && String(data.reset_version) !== readStoredResetVersion()){
          applyVisitorReset(String(data.reset_version));
        }else{
          clearChatState({silent:true, keepOpen:keepPanelOpenOnReset(), skipFocus:true});
          clearGateState();
        }
        syncGateUI();
        showGateError(data.message || 'Please complete the visitor form before using Ask AI.');
        appendMessage(data.message || 'Please complete the visitor form before using Ask AI.','pzai-bot-message');
        return;
      }
      appendMessage(data.message || data.reply || 'Sorry, something went wrong.','pzai-bot-message');
      appendProducts(data.products || [], true, {view_all_url: data.view_all_url || ''});
      appendSuggestions(data.suggestions || data.followups || []);
    }catch(e){
      setThinking(false);
      appendMessage('Sorry, I could not reach the assistant right now.','pzai-bot-message');
    }
  }

  function registerAddToCartTracking(){
    function sendAddToCart(payload){
      writeCookie('pzai_assistant_used','1');
      writeCookie('pzai_session_id', sessionId);
      if(payload && payload.product_id) writeCookie('pzai_last_product_id', String(payload.product_id));
      if(payload && payload.label) writeCookie('pzai_last_product_name', normalizeDisplayText(payload.label));
      trackEvent('added_to_cart_after_chat', Object.assign({query:''}, payload || {}));
      if(window.clarity){ try{ window.clarity('event','pzai_add_to_cart_after_chat'); }catch(e){} }
    }
    document.addEventListener('click', function(e){
      const btn=e.target.closest('.add_to_cart_button, .single_add_to_cart_button');
      if(!btn) return;
      const pid=btn.getAttribute('data-product_id') || btn.value || 0;
      const label=btn.getAttribute('aria-label') || btn.textContent || 'Product';
      sendAddToCart({product_id:Number(pid)||0,label:String(label).trim()});
    }, true);
    if(window.jQuery){
      window.jQuery(document.body).on('added_to_cart', function(event, fragments, cart_hash, button){
        const $btn=window.jQuery(button);
        const pid=($btn && ($btn.data('product_id') || $btn.val())) || 0;
        const label=($btn && ($btn.attr('aria-label') || $btn.text())) || 'Product';
        sendAddToCart({product_id:Number(pid)||0,label:String(label).trim()});
      });
    }
  }

  if(title && pzaiData.title) title.textContent=pzaiData.title;
  if(input && state.draft) input.value=state.draft;
  writeCookie('pzai_session_id', sessionId);
  updatePanelMetrics();
  restoreHistory();
  ensureWelcomeMessage();
  hydrateGateState();
  maybeApplyStoredReset();
  syncGateUI();
  syncVisibility();
  syncGateAccessFromServer();
  if(isGateRequired() && pzaiData.visitorStatusEndpoint){
    window.setInterval(syncGateAccessFromServer, 45000);
    document.addEventListener('visibilitychange', function(){ if(!document.hidden) syncGateAccessFromServer(); });
    window.addEventListener('storage', function(event){
      if(!event) return;
      if(event.key === GATE_STORAGE_KEY && event.newValue === null){
        syncVisitorGateResetAcrossTabs(event.key);
        return;
      }
      if(event.key === GATE_SYNC_STORAGE_KEY || event.key === RESET_STORAGE_KEY){
        syncVisitorGateResetAcrossTabs(event.key);
      }
    });
  }
  registerAddToCartTracking();

  if(panel){
    panel.addEventListener('click', function(e){ e.stopPropagation(); });
  }

  if(toggle) toggle.addEventListener('click', function(e){
    e.preventDefault();
    e.stopPropagation();
    const opening=!state.open;
    state.open=opening;
    saveState();
    syncVisibility();
    if(opening){
      writeCookie('pzai_assistant_used','1');
      writeCookie('pzai_session_id', sessionId);
      trackEvent('chat_started', {});
      if(isGateRequired()) syncGateAccessFromServer();
      if(!isGateAllowed() && gateName) gateName.focus(); else if(input) input.focus();
    }
  });

  if(closeBtn) closeBtn.addEventListener('click', function(e){
    e.preventDefault();
    e.stopPropagation();
    state.open=false;
    saveState();
    syncVisibility();
  });

  if(clearBtn) clearBtn.addEventListener('click', function(e){
    e.preventDefault();
    e.stopPropagation();
    clearChatState();
  });

  if(input) input.addEventListener('input', function(){
    state.draft=input.value || '';
    saveState();
  });

  if(gateForm) gateForm.addEventListener('submit', async function(e){
    e.preventDefault();
    clearGateError();
    const firstName=((gateName && gateName.value) || '').trim();
    const email=((gateEmail && gateEmail.value) || '').trim();
    const consent=!!(gateAgree && gateAgree.checked);
    if(!firstName || !email){ showGateError('Please enter your first name and email address.'); return; }
    if(!consent){ showGateError('Please agree to the PricZone terms before using Ask AI.'); return; }
    setGateSubmitting(true);
    try{
      if(isRecaptchaEnabled()){
        if(typeof window.grecaptcha==='undefined'){
          setGateSubmitting(false);
          showGateError('reCAPTCHA is still loading. Please wait a moment and try again.');
          return;
        }
        if(!getRecaptchaResponse()){
          setGateSubmitting(false);
          showGateError('Please confirm the reCAPTCHA before submitting.');
          return;
        }
      }
      const leadPayload={first_name:firstName,email:email,consent:1,session_id:sessionId,source_url:window.location.href};
      if(isRecaptchaEnabled()) leadPayload.recaptcha_token=getRecaptchaResponse();
      const res=await fetch(pzaiData.visitorLeadEndpoint,{method:'POST',credentials:'same-origin',headers:restHeaders(),body:JSON.stringify(leadPayload)});
      const data=await res.json();
      setGateSubmitting(false);
      if(!res.ok || !data || data.ok===false){
        resetRecaptcha();
        if(data && typeof data.reset_version !== 'undefined' && String(data.reset_version) !== readStoredResetVersion()){
          applyVisitorReset(String(data.reset_version));
        }
        showGateError((data && data.message) || 'Sorry, we could not process your request right now.');
        return;
      }
      gateState={allowed:true,expiresAt:Date.now()+((parseInt((data.remember_days || pzaiData.visitorRememberDays || 30),10) || 30) * DAY_MS),firstName:firstName,email:email};
      saveGateState();
      syncGateUI();
      appendMessage((data && data.message) || pzaiData.visitorThankYouMessage || 'Thank you for using our ASK AI at PricZone, enjoy.','pzai-bot-message');
      if(gateName) gateName.value='';
      if(gateEmail) gateEmail.value='';
      if(gateAgree) gateAgree.checked=false;
      clearGateError();
      resetRecaptcha();
      if(input) input.focus();
    }catch(err){
      setGateSubmitting(false);
      resetRecaptcha();
      showGateError('Sorry, we could not process your request right now.');
    }
  });

  if(form) form.addEventListener('submit', function(e){
    e.preventDefault();
    if(!isGateAllowed()){ syncGateUI(); showGateError('Please complete the visitor form before using Ask AI.'); return; }
    const value=((input && input.value) || '').trim();
    if(!value) return;
    sendMessage(value);
  });

  window.addEventListener('resize', function(){
    updatePanelMetrics();
    syncVisibility();
  });
  window.addEventListener('orientationchange', function(){
    updatePanelMetrics();
    syncVisibility();
  });
  window.addEventListener('load', function(){
    updatePanelMetrics();
    scrollToLatest();
  });
});
