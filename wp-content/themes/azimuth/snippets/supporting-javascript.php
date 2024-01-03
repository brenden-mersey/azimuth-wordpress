<script async src="https://cdn.jsdelivr.net/npm/lazysizes@5.3.2/lazysizes.min.js" integrity="sha256-PZEg+mIdptYTwWmLcBTsa99GIDZujyt7VHBZ9Lb2Jys=" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js" integrity="sha256-pQBbLkFHcP1cy0C8IhoSdxlm0CtcH5yJ2ki9jjgR03c=" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha256-m81NDyncZVbr7v9E6qCWXwx/cwjuWDlHCMzi9pjMobA=" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/glide.min.js" integrity="sha256-g3ppCcO2K1k7ISyQxKL2vMFul0JknSZfnwdMS0Ijw7g=" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/axios@1.3.4/dist/axios.min.js" integrity="sha256-EIyuZ2Lbxr6vgKrEt8W2waS6D3ReLf9aeoYPZ/maJPI=" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/validator@13.9.0/validator.min.js" integrity="sha256-FwIvpbXCs5jmYYy4G0/pLEV4mkLI4Y++GVG1tSr6dTw=" crossorigin="anonymous"></script>

<script>

  document.documentElement.style.setProperty( "--theme-viewport-height", window.innerHeight + "px" );

  window.lazySizesConfig = window.lazySizesConfig || {};
  window.lazySizesConfig.lazyClass = "lazyload";
  lazySizesConfig.loadMode = 1;

  document.addEventListener("lazybeforeunveil", function(e){
    let bg = e.target.getAttribute("data-bg");
    if ( bg ) e.target.style.backgroundImage = "url(" + bg + ")";
  });

  document.addEventListener("lazyloaded", function(e) {
    e.target.parentNode.classList.add("lazyloaded");
  });

  WebFontConfig = {
    google: {
      families: [
        "Halant:300,400,500,600,700",
        "Space Grotesk:300,400,500,600,700"
      ]
    }
  };

  (function(d) {
    var wf = d.createElement("script"), s = d.scripts[0];
    wf.src = "https://cdn.jsdelivr.net/npm/webfontloader@1.6.28/webfontloader.min.js";
    wf.async = true;
    s.parentNode.insertBefore(wf, s);
  })(document);

</script>
