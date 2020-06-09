/*!
 * ==============================================================
 *  TEXT EDITOR 3.1.8
 * ==============================================================
 * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
 * License: MIT
 * --------------------------------------------------------------
 */
!function(t,e,n){var r,u="replace";t.setTimeout;function o(t){return t.length}function s(t){return t instanceof Array}function i(t){return"function"==typeof t}function c(t){return t instanceof RegExp&&(t.source||!0)}function l(t){return void 0!==t}function a(e){if(s(e)){var r,o=[];for(r in e)o[r]=a(e[r]);return o}return e[u](f("["+t[n].x[u](/./g,"\\$&")+"]","g"),"\\$&")}function f(t,e){return c(t)||new RegExp(t,e)}function v(t,e){return t["trim"+(-1===e?"Left":1===e?"Right":"")]()}(r=t[n]=function(r,p){if(r){var $=this,b=t[n],g=/^([\s\S]*?)$/,d=e.body,h=d.parentNode,m=Object.assign({},b.state,"string"==typeof p?{tab:p}:p||{});if(r[n])return $;if(!($ instanceof b))return new b(r,m);b.instances[r.id||r.name||o(Object.keys(b.instances))]=$,$.self=$.source=r,$.value=y(),$.get=function(){return!r.disabled&&v(r.value)||null},$.let=function(){return r.value=$.value,$},$.set=function(t){return r.disabled||r.readOnly?$:(r.value=t,$)},$.$=function(){return new b.Selection(r.selectionStart,r.selectionEnd,y())},$.focus=function(t){var e,n;return-1===t?e=n=0:1===t&&(e=o(y()),n=r.scrollHeight),l(e)&&l(n)&&(r.selectionStart=r.selectionEnd=e,r.scrollTop=n),r.focus(),$},$.blur=function(){return r.blur(),$},$.select=function(){if(r.disabled||r.readOnly)return r.focus(),$;var e,n,u,s=arguments,i=o(s),c=$.$();if(e=t.pageXOffset||h.scrollLeft||d.scrollLeft,n=t.pageYOffset||h.scrollTop||d.scrollTop,u=r.scrollTop,0===i)s[0]=c.start,s[1]=c.end;else if(1===i){if(!0===s[0])return r.focus(),r.select(),$;s[1]=s[0]}return r.focus(),r.selectionStart=s[0],r.selectionEnd=s[1],r.scrollTop=u,t.scroll(e,n),$},$.match=function(t,e){if(s(t)){var n=$.$(),r=[n.before.match(t[0]),n.value.match(t[1]),n.after.match(t[2])];return i(e)?e.call($,r[0]||[],r[1]||[],r[2]||[]):[!!r[0],!!r[1],!!r[2]]}r=$.$().value.match(t);return i(e)?e.call($,r||[]):!!r},$[u]=function(t,e,n){var r=$.$(),s=r.before,i=r.after,c=r.value;return-1===n?s=s[u](t,e):1===n?i=i[u](t,e):c=c[u](t,e),$.set(s+c+i).select(s=o(s),s+o(c))},$.insert=function(t,e,n){var r=g;return n&&$[u](r,""),-1===e?r=/$/:1===e&&(r=/^/),$[u](r,t,e)},$.wrap=function(t,e,n){var r=$.$(),s=r.before,i=r.after,c=r.value;return n?$[u](g,t+"$1"+e):$.set(s+t+c+e+i).select(s=o(s+t),s+o(c))},$.peel=function(t,e,n){var r=$.$(),s=r.before,i=r.after,l=r.value;t=c(t)||a(t),e=c(e)||a(e);var v=f(t+"$"),p=f("^"+e);return n?$[u](f("^"+t+"([\\s\\S]*?)"+e+"$"),"$1"):v.test(s)&&p.test(i)?(s=s[u](v,""),i=i[u](p,""),$.set(s+l+i).select(s=o(s),s+o(l))):$.select()},$.pull=function(t,e){var n=$.$();return t=c(t=l(t)?t:m.tab)||a(t),l(e)||(e=!0),o(n)?e?$[u](f("^"+t,"gm"),""):$.insert(n.value.split("\n").map(function(e){return f("^("+t+")*$").test(e)?e:e[u](f("^"+t),"")}).join("\n")):$[u](f(t+"$"),"",-1)},$.push=function(t,e){var n=$.$();return t=l(t)?t:m.tab,l(e)||(e=!1),o(n)?$[u](f("^"+(e?"":"(?!$)"),"gm"),t):$.insert(t,-1)},$.trim=function(t,e,n,r,u){l(u)||(u=!0),null!==t&&!1!==t&&(t=t||""),null!==e&&!1!==e&&(e=e||""),null!==n&&!1!==n&&(n=n||""),null!==r&&!1!==r&&(r=r||"");var s=$.$(),i=s.before,c=s.after,a=s.value,f=v(i,1),p=v(c,-1);return i=!1!==t?v(i,1)+(f||!u?t:""):i,c=!1!==e?(p||!u?e:"")+v(c,-1):c,!1!==n&&(a=v(a,-1)),!1!==r&&(a=v(a,1)),$.set(i+a+c).select(i=o(i),i+o(a))},$.pop=function(){return delete r[n],$},$.state=m}function y(){return r.value[u](/\r/g,"")}}).version="3.1.8",r.state={tab:"\t"},r.instances={},r.x="!$^*()-=+[]{}\\|:<>,./?",r.esc=a,r.Selection=function(t,e,n){var r,u=this;u.start=t,u.end=e,u.value=r=n.substring(t,e),u.before=n.substring(0,t),u.after=n.substring(e),u.length=o(r),u.toString=function(){return r}},r._=r.prototype}(this,this.document,"TE");
/*!
 * ==============================================================
 *  TEXT EDITOR HISTORY 1.1.2
 * ==============================================================
 * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
 * License: MIT
 * --------------------------------------------------------------
 */
!function(t,n,e){function r(t){return void 0!==t}function i(t,n){return r(n[0])&&t<n[0]?n[0]:r(n[1])&&t>n[1]?n[1]:t}var u=t[e],o=u._,c="_history",a=c+"State";o[c]=[],o[a]=-1,o.history=function(t){var n=this;return r(t)?r(n[c][t])?n[c][t]:null:n[c]},o.record=function(t){var n=this,e=n.$(),i=n[c][n[a]]||[],u=[n.self.value,e.start,e.end];return u[0]===i[0]&&u[1]===i[1]&&u[2]===i[2]?n:(++n[a],n[c][r(t)?t:n[a]]=u,n)},o.loss=function(t){var n,e=this;return!0===t?(e[c]=[],e[a]=-1,[]):(n=e[c].splice(r(t)?t:e[a],1),e[a]=i(e[a]-1,[-1]),n)},o.undo=function(){var t,n=this;return n[a]=i(n[a]-1,[0,n[c].length-1]),t=n[c][n[a]],n.set(t[0]).select(t[1],t[2])},o.redo=function(){var t,n=this;return n[a]=i(n[a]+1,[0,n[c].length-1]),t=n[c][n[a]],n.set(t[0]).select(t[1],t[2])}}(this,this.document,"TE");
/*!
 * ==============================================================
 *  TEXT EDITOR SOURCE 1.1.6
 * ==============================================================
 * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
 * License: MIT
 * --------------------------------------------------------------
 */
!function(e,t,n){var r,o,s=e.TE,u=e.setTimeout,c=s.esc,i="blur",a="call",f="close",l="ctrlKey",d="disabled",p="focus",v="fromCharCode",y="indexOf",h="lastIndexOf",m="length",k="match",w="pull",b="push",C="readOnly",$="record",g="redo",E="replace",K="select",O="shiftKey",x="toLowerCase",L="undo";function T(e,t,n){e.removeEventListener(t,n)}function _(e,t,n){e.addEventListener(t,n,!1)}function j(e){e&&e.preventDefault()}function z(e,t){return new RegExp(e,t)}for(o in r=function(e,t){var n=this,r=n.pop,o=L in s._;s[a](n,e,t);var D,I,R={},S=!("source"in(t=n.state))||t.source;R[f]={"(":")","{":"}","[":"]",'"':'"',"'":"'","<":">"},R[w]=function(e){var t=e.ctrlKey,n=e.key,r=e.keyCode;return t&&(n&&"["===n||r&&219===r)},R[b]=function(e){var t=e.ctrlKey,n=e.key,r=e.keyCode;return t&&(n&&"]"===n||r&&221===r)},R[K]=!0,S&&(t.source=(D=R,I=!0===t.source?{}:t.source,Object.assign(D,I)));var W,q=t.source||{};function A(){e[d]||e[C]||u(function(){var e=n.$(),t=/\W/g,r=e.before[E](t,"|")[h]("|"),o=e.after[E](t,"|")[y]("|");e.value;r=r<0?0:r+1,o=o<0?e.after[m]:o,W!==e.start&&n[K](r,e.end+o)},0)}function B(){W=n.$().start}function F(r){if(!e[d]&&!e[C]){var o=q[f],s=t.tab,y=r.keyCode,h=(r.key||String[v](y))[x](),m=r[l],$="enter"===h||13===y,E=(r[O],n.$()),T=E.before,_=E.value,D=E.after,I=T.slice(-1),R=D.slice(0,1),S=T[k](z("(?:^|\\n)("+c(s)+"+).*$")),W=S?S[1]:"",A=o[h];if(q[b]&&q[b][a](n,r))n[b](s),G(),j(r);else if(q[w]&&q[w][a](n,r))n[w](s),G(),j(r);else if(m)"z"===h||90===y?(n[L](),G(),j(r)):"y"!==h&&89!==y||(n[g](),G(),j(r));else if("\\"!==I&&h===R)n[K](E.end+1),G(),j(r);else if("\\"!==I&&A)G(),n.wrap(h,A),G(),j(r);else if("backspace"===h||8===y){var B="",F="";for(var H in o)B+=H,F+=o[H];B="(["+c(B)+"])",F="(["+c(F)+"])";var J=T[k](z(B+"\\n(?:"+c(W)+")$")),M=D[k](z("^\\n(?:"+c(W)+")"+F));!_&&J&&M&&M[1]===o[J[1]]?(n.trim("",""),G(),j(r)):!_&&T[k](z(c(s)+"$"))?(n[w](s),G(),j(r)):(A=o[I])&&A===R&&(n.peel(I,R),j(r)),G()}else"delete"===h||46===y?((A=o[I])&&A===R&&(n.peel(I,R),j(r)),G()):$?((A=o[I])&&A===R?(n.wrap("\n"+s+W,"\n"+W)[i]()[p](),j(r)):(_||W)&&(n.insert("\n",-1,!0)[b](W)[i]()[p](),j(r)),G()):u(G,0)}}function G(){o&&n[$]()}S&&(_(e,"keydown",F),q[K]&&(_(e,"mousedown",A),_(e,"mouseup",B),_(e,"touchend",B),_(e,"touchstart",A)),G()),n.pop=function(){return r&&r[a](n),T(e,"keydown",F),T(e,"mousedown",A),T(e,"mouseup",B),T(e,"touchend",B),T(e,"touchstart",A),o&&n.loss(!0),n},n.state=t},s)r[o]=s[o];r.prototype=r._=s._,e.TE=r}(this,this.document);


(function(doc, _) {
    function onChange() {
        for (let k in TE.instances) {
            TE.instances[k].pop(); // Destroy!
            delete TE.instances[k];
        }
        let source = doc.querySelectorAll('.field\\:source .textarea'), $$;
        source.length && source.forEach(function($) {
            $$ = new TE($, JSON.parse($.getAttribute('data-state') || '{}'));
        });
    } onChange();
    _.on('change', onChange);
})(document, _);
