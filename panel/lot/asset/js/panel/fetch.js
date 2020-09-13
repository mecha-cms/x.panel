/*!
 * ==============================================================
 *  F3H 1.0.15
 * ==============================================================
 * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
 * License: MIT
 * --------------------------------------------------------------
 */
!function(e,t,n){var r,o,u,i="GET",s="POST",c="document",f="text",a="replace",l="search",p="test",h=e.history,d=e.location,v="//"+d.hostname,m=t.documentElement,g=t.currentScript;function y(e,t){return e.getAttribute(t)}function b(e,t){return e.hasAttribute(t)}function L(e,t,n){return e.setAttribute(t,n)}function k(e){return e.innerHTML}function w(e){return"false"!==e&&(""===e||"null"===e?null:"true"===e||(/^-?(\d*\.)?\d+$/[p](e)?+e:e))}function S(e){return H(e)?"submit":"click"}function E(e,t,n){e.removeEventListener(t,n)}function T(e,t,n){e.addEventListener(t,n,!1)}function C(e){return e.split("#")[1]||""}function j(e){return e.split("#")[0]}function x(e){var t,n=0,r=e.length;if(0===r)return n;for(t=0;t<r;++t)n=(n<<5)-n+e.charCodeAt(t),n&=n;return n<1?-1*n:n}function A(e){return"function"==typeof e}function H(e){return"form"===X(e.nodeName)}function N(e){return void 0!==e}function O(e){if(e.src&&g.src===e.src)return 1;var t=X(n);return b(e,"data-"+t)||b(e,t)?1:new RegExp("\\b"+n+"\\b").test(k(e)||"")?1:0}function R(e){for(var t,r,o,u,i={},s=D("link[rel=dns-prefetch],link[rel=preconnect],link[rel=prefetch],link[rel=preload],link[rel=prerender]",e),c=0,f=s.length;c<f;++c)o=r=s[c],void 0,u=X(n),b(o,"data-"+u)||b(o,u)||(r.id=t=r.id||n+":"+x(y(r,"href")||k(r)),i[t]=P(r),i[t][2].href=r.href);return i}function q(e,n){return(n||t).querySelector(e)}function D(e,n){return(n||t).querySelectorAll(e)}function M(e,t,n){n.insertBefore(e,t&&n===t.parentNode?t:null)}function B(e){if(e){var t=e.parentNode;t&&t.removeChild(e)}}function F(e){var n=t.createElement(e[0]);for(var r in n.innerHTML=e[1],e[2])L(n,r,Q(e[2][r]));return n}function P(e){for(var t=e.attributes,n=[X(e.nodeName),k(e),{}],r=0,o=t.length;r<o;++r)n[2][t[r].name]=w(t[r].value);return n}function U(e){return e[a](/\/+$/,"")}function $(e){e.preventDefault()}function J(){return d.href}function _(e){for(var t,r,o={},u=D("script",e),i=0,s=u.length;i<s;++i)O(r=u[i])||(r.id=t=r.id||n+":"+x(y(r,"src")||k(r)),o[t]=P(r));return o}function G(e){for(var t,r,o,u,i={},s=D("link[rel=stylesheet],style",e),c=0,f=s.length;c<f;++c)o=r=s[c],void 0,u=X(n),b(o,"data-"+u)||b(o,u)||(r.id=t=r.id||n+":"+x(y(r,"href")||k(r)),i[t]=P(r));return i}function I(e,n){return e?t.getElementById(e)||(n?t.getElementsByName(e)[0]:null):null}function X(e){return e.toLowerCase()}function z(e){return e.toUpperCase()}function K(e){var t,n,r,o,u={},i=e.getAllResponseHeaders().trim().split(/[\r\n]+/);for(t in i)r=X((n=i[t].split(": ")).shift()),X(o=n.join(": ")),u[r]=w(o);return new Proxy(u,{get:function(e,t){return e[X(t)]||null},set:function(e,t,n){e[X(t)]=n}})}function Q(e){return!1===e?"false":null===e?"null":!0===e?"true":e+""}(u=e[n]=function(u){var p,d,v,g,y=this,b=e[n],L={},k={},w=J(),x={},O=Object.assign({},b.state,!0===u?{cache:u}:u||{}),P=X(O.sources);if(O.turbo&&(O.cache=!0),!(y instanceof b))return new b(u);function X(e,t){var n=D(e,t),r=J();if(A(O.is)){for(var o=[],u=0,i=n.length;u<i;++u)O.is.call(y,n[u],r)&&o.push(n[u]);return o}return n}function Q(e){var n=t.createElement("input"),r=D("[name][type=submit][value]",e);n.type="hidden",M(n,0,e);for(var o=0,u=r.length;o<u;++o)T(r[o],"click",function(){n.name=this.name,n.value=this.value})}function V(n,r,o){var u=n===e,s=O.history;if(i!==r||n!==g||u){if(g=n,w=y.ref=o,se("exit",[t,n]),O.cache){var f=L[U(j(o))];if(f)return y.lot=f[2],y.status=f[0],f[3]&&!u&&s&&ne(m),te(o),a=[f[1],n],f[3]&&(p=oe(a[0])),f[3]&&(v=ie(a[0])),se("success",a),se(f[0],a),P=X(O.sources),f[3]&&(d=ue(a[0])),he(a),void se("enter",a)}var a,h,b,k,S,E=Z(n,r,o,O.lot),C=c===E.responseType,x=E.upload;return T(E,"abort",function(){A(),se("abort",[E.response,n])}),T(E,"error",h=function(){A(),C&&!u&&s&&ne(m),a=[E.response,n],C&&(p=oe(a[0])),C&&(v=ie(a[0])),se("error",a),P=X(O.sources),C&&(d=ue(a[0])),he(a),se("enter",a)}),T(x,"error",h),T(E,"load",h=function(){if(A(),a=[E.response,n],k=E.responseURL,S>=300&&S<400){var t=U(k);return L[t]&&delete L[t],se("success",a),se(S,a),void V(g=e,i,k||o)}te(-1===o[l]("#")&&k||o),C&&(v=ie(a[0])),se("success",a),se(S,a),C&&s&&ne(m),P=X(O.sources),C&&(d=ue(a[0])),he(a),se("enter",a)}),T(x,"load",h),T(E,"progress",function(e){A(),se("pull",e.lengthComputable?[e.loaded,e.total]:[0,-1])}),T(x,"progress",function(e){A(),se("push",e.lengthComputable?[e.loaded,e.total]:[0,-1])}),E}function A(){b=K(E),S=E.status,i===r&&O.cache&&S&&(L[U(j(o))]=[S,E.response,b,C]),y.lot=b,y.status=S}}function W(e){x[e]&&x[e][0]&&(x[e][0].abort(),delete x[e])}function Y(){for(var e in x)W(e)}function Z(e,t,n,r){n=A(O.ref)?O.ref.call(y,e,n):n;var o,u=new XMLHttpRequest,i=z(n.split(/[?&#]/)[0].split("/").pop().split(".")[1]||""),c=O.types[i]||O.type||f;if(A(c)&&(c=c.call(y,n)),u.responseType=c,u.open(t,n,!0),function(e){return"object"==typeof e}(r))for(o in r)u.setRequestHeader(o,r[o]);return u.send(s===t?new FormData(e):null),u}function ee(e,t){var n,r=Z(e,i,t);T(r,"load",function(){200===(n=r.status)&&(L[U(j(t))]=[n,r.response,K(r),c===r.responseType])})}function te(e){e!==J()&&O.history&&h.pushState({},"",e)}function ne(e){e&&(m.scrollLeft=o.scrollLeft=e.offsetLeft,m.scrollTop=o.scrollTop=e.offsetTop)}function re(e,t,n,r){var o,u,i,s=n(e),c={};for(o in t)(u=q("#"+o[a](/[:.]/g,"\\$&")))&&(c[o]=u.nextElementSibling),s[o]||(delete t[o],B(I(o)));for(o in s)t[o]||(t[o]=i=s[o],M(F(i),c[o],r));return t}function oe(e){return re(e,p,R,r)}function ue(e){return re(e,d,_,o)}function ie(e){return re(e,v,G,r)}function se(e,t){if(!N(k[e]))return y;for(var n=0,r=k[e].length;n<r;++n)k[e][n].apply(y,t);return y}function ce(){o=t.body,r=t.head,y.links=p=R(),y.scripts=d=_(),y.styles=v=G(),he([t,e]),O.cache&&ee(e,J())}function fe(e){Y();var t,n=this,r=n.href,o=n.action,u=r||o,s=z(n.method||i);i===s&&(H(n)&&(t=new URLSearchParams(new FormData(n))+"",u=U(u.split(/[?&#]/)[0])+(t?"?"+t:"")),O.turbo&&te(u)),x[u]=[V(n,s,u),n],$(e)}function ae(e){ne(I(C(J()),1)),$(e)}function le(){var e=this,t=e.href;L[U(j(t))]||ee(e,t),E(e,"mousemove",le)}function pe(t){Y();var n=J();C(n)&&j(w)===j(n)||(x[n]=[V(e,i,n),e])}function he(e){for(var t=O.turbo,n=0,r=P.length;n<r;++n)T(P[n],S(P[n]),fe),H(P[n])?Q(P[n]):t&&T(P[n],"mousemove",le);!function(e){if(k.focus)se("focus",e);else{var t=q("[autofocus]");t&&t.focus()}}(e),function(e){k.scroll?se("scroll",e):ne(I(C(J()),1))}(e)}return b.instances[Object.keys(b.instances).length]=y,y.abort=function(e){return e?x[e]&&W(e):Y(),y},y.pop=function(){return function(){for(var e=0,t=P.length;e<t;++e)E(P[e],S(P[e]),fe)}(),E(e,"DOMContentLoaded",ce),E(e,"hashchange",ae),E(e,"popstate",pe),se("pop",[t,e]),y.abort()},y.caches=L,y.fetch=function(e,t,n){return Z(n,t,e)},y.fire=se,y.hooks=k,y.links={},y.lot={},y.off=function(e,t){if(!N(e))return k={},y;if(N(k[e]))if(N(t)){for(var n=0,r=k[e].length;n<r;++n)t===k[e][n]&&k[e].splice(n,1);0===r&&delete k[e]}else delete k[e];return y},y.on=function(e,t){return N(k[e])||(k[e]=[]),N(t)&&k[e].push(t),y},y.ref=null,y.scripts={},y.state=O,y.status=null,y.styles={},T(e,"DOMContentLoaded",ce),T(e,"hashchange",ae),T(e,"popstate",pe),y}).version="1.0.15",u.state={cache:!1,history:!0,is:function(e,t){var n=e.target,r=y(e,"href")||y(e,"action")||"",o=e.href||e.action||"";return!(n&&"_self"!==n||"#"===r[0]||/^(data|javascript|mailto):/[p](r)||C(o)&&j(t)===j(o)||""!==r&&0!==r[l](/[.\/?]/)&&0!==r[l](v)&&0!==r[l](d.protocol+v)&&-1!==r[l]("://"))},lot:{"x-requested-with":n},ref:function(e,t){return t},sources:"a[href],form",turbo:!1,type:c,types:{"":c,CSS:f,JS:f,JSON:"json"}},u.instances={},u._=u.prototype}(window,document,"F3H");


(function(win, doc, _) {
    function $$(query, context) {
        return (context || doc).querySelectorAll(query);
    }
    // Get the default F3H element(s) filter
    let f = F3H.state.is;
    // Ignore navigation link(s) that has sub-menu(s) in it
    F3H.state.is = function(source, refNow) {
        return f(source, refNow) && !source.parentNode.classList.contains('has:menu');
    };
    let root = doc.documentElement,
        selectors = 'body>div,body>svg',
        elements = $$(selectors),
        f3h = new F3H(false);
    _.on('error', function() {
        win.location.reload();
    });
    // Force response type as `document`
    delete F3H.state.types.CSS;
    delete F3H.state.types.JS;
    delete F3H.state.types.JSON;
    f3h.on('error', function() {
        _.fire('error');
    });
    f3h.on('exit', function(response, target) {
        let title = doc.querySelector('title');
        if (title) {
            if (target && target.nodeName && 'form' === target.nodeName.toLowerCase()) {
                title.setAttribute('data-is', 'search' === target.name ? 'search' : 'push');
            } else {
                title.removeAttribute('data-is');
            }
        }
        _.fire('let');
    });
    f3h.on('success', function(response, target) {
        let status = this.status;
        if (200 === status || 404 === status) {
            let responseElements = $$(selectors, response),
                responseRoot = response.documentElement;
            doc.title = response.title;
            responseRoot && (root.className = responseRoot.className + ' can:fetch');
            elements.forEach(function(element, index) {
                if (responseElements[index]) {
                    element.className = responseElements[index].className;
                    element.innerHTML = responseElements[index].innerHTML;
                }
            });
            _.fire('change');
        }
    });
    _.f3h = f3h;
})(window, document, _);
