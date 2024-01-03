import Tools from "tools";

const config = { debug: true, name: "mobileMenu.js", version: "1.0" };
const elements = Tools.getArrayOfElementsByTag();

const onClickToggleMobileMenu = () => {
  ( document.querySelectorAll( ".js--mobile-menu-toggle-trigger" ) || [] ).forEach( trigger => {
    trigger.addEventListener( "click", event => {

      elements.forEach( element => {
        element.classList.toggle( "mobile-menu--active" );
      });

      triggers.forEach( element => {
        element.classList.toggle( "is-active" );
      });

    });
  });
};

const onClickHideMobileMenu = () => {
  ( document.querySelectorAll( ".mobile-menu__navigation-link" ) || [] ).forEach( trigger => {
    trigger.addEventListener( "click", event => {

      elements.forEach( element => {
        element.classList.remove( "mobile-menu--active" );
      });

      triggers.forEach( element => {
        element.classList.remove( "is-active" );
      });

    });
  });
};

const init = () => {
  if ( config.debug ) console.log(`[ ${config.name} v.${config.version} initialized ]`);
    onClickToggleMobileMenu();
    onClickHideMobileMenu();
  if ( config.debug ) console.log(`[ ${config.name} v.${config.version} complete ]`);
};

export default { init };
