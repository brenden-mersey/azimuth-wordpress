import Credits from './modules/credits';
import Forms from './modules/forms';
import Gliders from './modules/gliders';
import InstagramFeed from './modules/instagramFeed';
import MobileMenu from './modules/mobileMenu';
import Scrolling from './modules/scrolling';
import Sizing from './modules/sizing';
import Tools from './modules/tools';

AOS.init();
Credits.init();

window.addEventListener( 'load', (event) => {
  AOS.refresh();
  Forms.init();
  Gliders.init();
  InstagramFeed.init();
  MobileMenu.init();
  Scrolling.init();
  Sizing.init();
});

window.addEventListener( 'resize', Tools.throttle(() => {
  Sizing.init();
}, 300));

window.addEventListener( 'scroll', Tools.throttle(() => {
  Scrolling.init();
}, 300));


