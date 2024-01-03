// @codekit-prepend "./modules/_credits.js";
// @codekit-prepend "./modules/_breakpoints.js";
// @codekit-prepend "./modules/_forms.js";
// @codekit-prepend "./modules/_gliders.js";
// @codekit-prepend "./modules/_instagramFeed.js";
// @codekit-prepend "./modules/_mobileMenu.js";
// @codekit-prepend "./modules/_sizing.js";
// @codekit-prepend "./modules/_scrolling.js";
// @codekit-prepend "./modules/_theme.js";
// @codekit-prepend "./modules/_tools.js";

let credits = new Credits();
let forms = new Forms();
let gliders = new Gliders();
let instagramFeed = new InstagramFeed();
let mobileMenu = new MobileMenu();
let sizing = new Sizing();
let scrolling = new Scrolling();

Theme.init([
  credits,
  forms,
  gliders,
  instagramFeed,
  mobileMenu,
  sizing,
  scrolling,
]);

AOS.init({
  offset: 150,         // offset (in px) from the original trigger point
  delay: 0,           // values from 0 to 3000, with step 50ms
  duration: 550,      // values from 0 to 3000, with step 50ms
  easing: 'ease-in-out',     // default easing for AOS animations
});

window.addEventListener( 'DOMContentLoaded', event => {
  onPageLoadScrollTo();
});

( document.querySelectorAll('.js--scroll-to') || [] ).forEach( item => {
  item.addEventListener( 'click', event => {

    console.log( 'clicked!' );

    let current = {
      path: location.pathname,
      host: location.hostname
    };
    let link = {
      path: item.pathname,
      href: item.href,
      host: item.hostname
    };
    let lastIndexOfHash = link.href.lastIndexOf('#');
    let hash = '';

    if ( current.path.replace(/^\//, '') == link.path.replace(/^\//, '') && current.host == link.host ) {
      event.preventDefault();
      hash = link.href.slice(lastIndexOfHash + 1) || '';
      console.log( 'on page :: ' + hash );
      mobileMenu.toggle('remove');
      scrollToTheShit( '#' + hash );
      document.location.hash = hash;
    } else {
      console.log( 'not on page' );
    }

  });
});

function scrollToTheShit( $id ) {

  const target = document.querySelector(`[data-scroll-to-target="${$id}"]`) || false;
  const targetOffset = target.offsetTop;
  console.log({ $id, target, targetOffset });

  if ( targetOffset ) {
    setTimeout(() => {
      scroll({
        top: targetOffset,
        behavior: "smooth"
      });
    }, 250);
  }

}

function onPageLoadScrollTo() {
  let hash = location.hash;
  scrollToTheShit( hash );
}

//
// // see about adding items to timeline
// let myPath = anime.path('#custom-path path');
// let animation = anime({
//   targets: '#custom-marker',
//   translateX: myPath('x'),
//   translateY: myPath('y'),
//   easing: 'linear',
//   autoplay: false,
//   loop: false,
// });
//
// window.addEventListener('scroll', () => {
//
// 	//const persentage = getScrollPercent()
//   let container = document.querySelector('.flexible-content__section.section.section--svg');
//   let viewportHeight = window.innerHeight;
//   let containerHeight = container.offsetHeight;
//   let containerDistanceFromTop = container.offsetTop;
//   let seekPosition = (window.pageYOffset - containerDistanceFromTop) + (viewportHeight/2);
//   let abc = seekPosition/containerHeight * 1000;
//
//   console.log({
//     abc,
//     viewportHeight,
//     containerHeight,
//     containerDistanceFromTop,
//     seekPosition
//   });
//
//   //console.log( window.pageYOffset, container.offsetTop );
//   //console.log( (window.pageYOffset - container.offsetTop) + window.innerHeight );
//
//   animation.seek( abc );
//
// });

console.log(wp.hooks);

// // Define our filter callback.
// function myPluginGettextFilter( translation, text, domain ) {
//     if ( text === 'Write an excerpt (optional)' ) {
//         return 'Write an excerpt';
//     }
//
//     return translation;
// }
//
// // Adding the filter
// wp.hooks.addFilter(
//     'i18n.gettext',
//     'my-plugin/override-write-an-excerpt-label',
//     myPluginGettextFilter
// );

