/*!
 * ==============================================================
 *  TEXT EDITOR 3.1.4
 * ==============================================================
 * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
 * License: MIT
 * --------------------------------------------------------------
 */
!function(n,t,e){function r(n){return n.length}function u(n){return n instanceof Array}function i(n){return"function"==typeof n}function f(n){return n instanceof RegExp?n.source||!0:!1}function o(n){return void 0!==n}function a(n){return"string"==typeof n}function c(t){if(u(t)){var r,i=[];for(r in t)i[r]=c(t[r]);return i}return t[w](l("["+n[e].x[w](/./g,"\\$&")+"]","g"),"\\$&")}function l(n,t){return f(n)||RegExp(n,t)}function s(n,t){return n["trim"+(-1===t?"Left":1===t?"Right":"")]()}var v="__instance__",p="Selection",g="blur",b="disabled",d="focus",h="insert",m="match",y="parentNode",S="readOnly",w="replace",_="scroll",x=_+"Left",E=_+"Top",O="select",R=O+"ion",j=R+"End",T=R+"Start",L="substring",k=setTimeout;!function(e){e._=e.prototype,e.version="3.1.4",e[v]={},e.each=function(n,t){var r,u;return k(function(){u=$[v];for(r in u)n.call(u[r],r)},0===t?0:t||1),e},e.x="!$^*()-=+[]{}\\|:<>,./?",e.esc=c,e[p]=function(n,t,e){var u,i=this;i.start=n,i.end=t,i.value=u=e[L](n,t),i.before=e[L](0,n),i.after=e[L](t),i.length=r(u),i.toString=function(){return u}};var u=t.currentScript;e.path=(u&&u.src||n.location.href).split("/").slice(0,-1).join("/")}(n[e]=function($,R){function L(){return $.value[w](/\r/g,"")}if($){var k=this,A=n[e],H=/^([\s\S]*?)$/,N=t.body,X=N[y];if($[e])return k;if(R=R||{},a(R)&&(R={tab:R}),o(R.tab)||(R.tab="	"),k.state=R,!(k instanceof A))return new A($,R);A[v][$.id||$.name||r(Object.keys(A[v]))]=k,k.self=k.source=$,k.value=L(),k.get=function(){return!$[b]&&s($.value)||null},k.let=function(){return $.value=k.value,k},k.set=function(n){return $[b]||$[S]?k:($.value=n,k)},k.$=function(){var n=new A[p]($[T],$[j],L());return n},k[d]=function(n){var t,e;return-1===n?t=e=0:1===n&&(t=r(L()),e=$[_+"Height"]),o(t)&&o(e)&&($[T]=$[j]=t,$[E]=e),$[d](),k},k[g]=function(){return $[g](),k},k[O]=function(){if($[b]||$[S])return $[d](),k;var t,e,u,i=arguments,f=r(i),o=k.$();if(t=n.pageXOffset||X[x]||N[x],e=n.pageYOffset||X[E]||N[E],u=$[E],0===f)i[0]=o.start,i[1]=o.end;else if(1===f){if(!0===i[0])return $[d](),$[O](),k;i[1]=i[0]}return $[d](),$.setSelectionRange(i[0],i[1]),$[E]=u,n.scroll(t,e),k},k[m]=function(n,t){if(u(n)){var e=k.$(),r=[e.before[m](n[0]),e.value[m](n[1]),e.after[m](n[2])];return i(t)?t.call(k,r[0]||[],r[1]||[],r[2]||[]):[!!r[0],!!r[1],!!r[2]]}var r=k.$().value[m](n);return i(t)?t.call(k,r||[]):!!r},k[w]=function(n,t,e){var u=k.$(),i=u.before,f=u.after,o=u.value;return-1===e?i=i[w](n,t):1===e?f=f[w](n,t):o=o[w](n,t),k.set(i+o+f)[O](i=r(i),i+r(o))},k[h]=function(n,t,e){var r=H;return e&&k[w](r,""),-1===t?r=/$/:1===t&&(r=/^/),k[w](r,n,t)},k.wrap=function(n,t,e){var u=k.$(),i=u.before,f=u.after,o=u.value;return e?k[w](H,n+"$1"+t):k.set(i+n+o+t+f)[O](i=r(i+n),i+r(o))},k.peel=function(n,t,e){var u=k.$(),i=u.before,o=u.after,a=u.value;n=f(n)||c(n),t=f(t)||c(t);var s=l(n+"$"),v=l("^"+t);return e?k[w](l("^"+n+"([\\s\\S]*?)"+t+"$"),"$1"):s.test(i)&&v.test(o)?(i=i[w](s,""),o=o[w](v,""),k.set(i+a+o)[O](i=r(i),i+r(a))):k[O]()},k.pull=function(n,t){var e=k.$();return n=o(n)?n:R.tab,n=f(n)||c(n),o(t)||(t=!0),r(e)?t?k[w](l("^"+n,"gm"),""):k[h](e.value.split("\n").map(function(t){return l("^("+n+")*$").test(t)?t:t[w](l("^"+n),"")}).join("\n")):k[w](l(n+"$"),"",-1)},k.push=function(n,t){var e=k.$();return n=o(n)?n:R.tab,o(t)||(t=!1),r(e)?k[w](l("^"+(t?"":"(?!$)"),"gm"),n):k[h](n,-1)},k.trim=function(n,t,e,u,i){o(i)||(i=!0),null!==n&&!1!==n&&(n=n||""),null!==t&&!1!==t&&(t=t||""),null!==e&&!1!==e&&(e=e||""),null!==u&&!1!==u&&(u=u||"");var f=k.$(),a=f.before,c=f.after,l=f.value,v=s(a,1),$=s(c,-1);return a=!1!==n?s(a,1)+(v||!i?n:""):a,c=!1!==t?($||!i?t:"")+s(c,-1):c,!1!==e&&(l=s(l,-1)),!1!==u&&(l=s(l,1)),k.set(a+l+c)[O](a=r(a),a+r(l))},k.pop=function(){return delete $[e],k}}})}(window,document,"TE");
/*!
 * ==============================================================
 *  TEXT EDITOR HISTORY 1.1.0
 * ==============================================================
 * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
 * License: MIT
 * --------------------------------------------------------------
 */
!function(t,n,e){function r(t,n,e){return u(n)&&n>t?n:u(e)&&t>e?e:t}function u(t){return void 0!==t}var i=t[e],a=i._,o="_history",c=o+"State";a[o]=[],a[c]=-1,a.history=function(t){var n=this;return u(t)?u(n[o][t])?n[o][t]:null:n[o]},a.record=function(t){var n=this,e=n.$(),r=n[o][n[c]]||[],i=[n.self.value,e.start,e.end];return i[0]===r[0]&&i[1]===r[1]&&i[2]===r[2]?n:(++n[c],n[o][u(t)?t:n[c]]=i,n)},a.loss=function(t){var n,e=this;return!0===t?(e[o]=[],e[c]=-1,[]):(n=e[o].splice(u(t)?t:e[c],1),e[c]=r(e[c]-1,-1),n)},a.undo=function(){var t,n=this;return n[c]=r(n[c]-1,0,n[o].length-1),t=n[o][n[c]],n.set(t[0]).select(t[1],t[2])},a.redo=function(){var t,n=this;return n[c]=r(n[c]+1,0,n[o].length-1),t=n[o][n[c]],n.set(t[0]).select(t[1],t[2])}}(window,document,"TE");
/*!
 * ==============================================================
 *  TEXT EDITOR SOURCE 1.1.4
 * ==============================================================
 * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
 * License: MIT
 * --------------------------------------------------------------
 */
!function(t,e,n){function o(t,e,n){t.removeEventListener(e,n)}function i(t,e,n){t.addEventListener(e,n,!1)}function r(t,e){return Object.assign(t,e)}function s(t){t&&t.preventDefault()}var a,c,l=t[n],u=setTimeout,f=l.esc,d="blur",h="close",p="ctrlKey",m="disabled",v="focus",g="fromCharCode",w="indexOf",b="lastIndexOf",y="keydown",x="match",E="mousedown",L="mouseup",S="pull",T="push",k="readOnly",A="record",N="redo",j="replace",q="select",R="shiftKey",C="toLowerCase",O="touch",P=O+"end",_=O+"start",$="undo";a=function(t,e){function n(){t[m]||t[k]||u(function(){{var t=M.$(),e=/\W/g,n=".",o=t.before[j](e,n)[b](n),i=t.after[j](e,n)[w](n);t.value}o=0>o?0:o+1,i=0>i?t.after.length:i,F!==t.start&&M[q](o,t.end+i)},0)}function a(){F=M.$().start}function c(n){if(!t[m]&&!t[k]){var o=B[h],i=e.tab,r=n.keyCode,a=(n.key||String[g](r))[C](),c=n[p],l="enter"===a||13===r,w=(n[R],M.$()),b=w.before,y=w.value,E=w.after,L=b.slice(-1),A=E.slice(0,1),j=b[x](RegExp("(?:^|\\n)("+f(i)+"+).*$")),P=j?j[1]:"",_=o[a];c?"z"===a||90===r?(M[$](),O(),s(n)):"y"===a||89===r?(M[N](),O(),s(n)):"]"===a||221===r?(M[T](i),O(),s(n)):("["===a||219===r)&&(M[S](i),O(),s(n)):"\\"!==L&&a===A?(M[q](w.end+1),O(),s(n)):"\\"!==L&&_?(O(),M.wrap(a,_),O(),s(n)):"backspace"===a||8===r?(!y&&b[x](RegExp(f(i)+"$"))?(M[S](i),O(),s(n)):(_=o[L],_&&_===A&&(M.peel(L,A),s(n))),O()):"delete"===a||46===r?(_=o[L],_&&_===A&&(M.peel(L,A),s(n)),O()):l?(_=o[L],_&&_===A?(M.wrap("\n"+i+P,"\n"+P)[d]()[v](),s(n)):(y||P)&&(M.insert("\n",-1,!0)[T](P)[d]()[v](),s(n)),O()):u(O,0)}}function O(){U&&M[A]()}var M=this,H=M.pop,U=$ in l._;l.call(M,t,e);var D="source",e=M.state,X={},K=!(D in e)||e[D];X[h]={"(":")","{":"}","[":"]",'"':'"',"'":"'","<":">"},X[q]=!0,K&&(e[D]=r(X,!0===e[D]?{}:e[D]));var F,B=e[D]||{};K&&(i(t,y,c),B[q]&&(i(t,E,n),i(t,L,a),i(t,P,a),i(t,_,n)),O()),M.pop=function(){return H&&H.call(M),o(t,y,c),o(t,E,n),o(t,L,a),o(t,P,a),o(t,_,n),U&&M.loss(!0),M},M.state=e};for(c in l)a[c]=l[c];a.prototype=a._=l._,t[n]=a}(window,document,"TE");


(function(doc, _) {
    function onChange() {
        for (var k in TE.__instance__) {
            TE.__instance__[k].pop(); // Destroy!
            delete TE.__instance__[k];
        }
        var source = doc.querySelectorAll('.field\\:source .textarea'), $$;
        source.length && source.forEach(function($) {
            $$ = new TE($, JSON.parse($.getAttribute('data-state') || '{}'));
        });
    } onChange();
    _.on('change', onChange);
})(document, _);
