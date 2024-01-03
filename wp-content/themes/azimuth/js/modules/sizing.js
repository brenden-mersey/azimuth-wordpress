const config = { debug: false, name: 'sizing.js', version: '1.0' };

const init = () => {
  if ( config.debug ) console.log(`[ ${config.name} v.${config.version} initialized ]`);
    document.documentElement.style.setProperty( "--theme-header-height--total", document.getElementById("header").offsetHeight + "px" );
    document.documentElement.style.setProperty( "--theme-viewport-height--total", window.innerHeight + "px" );
  if ( config.debug ) console.log(`[ ${config.name} v.${config.version} complete ]`);
};

export default { init };
