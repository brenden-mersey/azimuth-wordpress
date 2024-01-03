const config = { debug: false, name: 'gliders.js', version: '1.0' };

const events = [ "build.after", "run.after" ];
const elements = document.querySelectorAll( '.js--glide' ) || [];
const gliders = {};

const UpdateNavigationStateBasedOnGlideSize = ( Glide, Components, Events ) => {

  var updateButtons = {
    mount () {},
    method () {

      let length = Components.Sizes.length;
      let index0 = Glide.index;
      let index = Glide.index + 1;
      let next = document.querySelectorAll( `[data-glide-navigation="${Glide.selector}"].next, [data-target="${Glide.selector}"].next` ) || [];
      let prev = document.querySelectorAll( `[data-glide-navigation="${Glide.selector}"].prev, [data-target="${Glide.selector}"].prev` ) || [];

      if ( 1 === index ) {
        prev.forEach( button => {
          button.classList.add('disabled');
        });
        next.forEach( button => {
          button.classList.remove('disabled');
        });
      }

      if ( length === index ) {
        prev.forEach( button => {
          button.classList.remove('disabled');
        });
        next.forEach( button => {
          button.classList.add('disabled');
        });
      }

      if ( length > 2 && ( index > 1 ) && ( index < length ) ) {
        prev.forEach( button => {
          button.classList.remove('disabled');
        });
        next.forEach( button => {
          button.classList.remove('disabled');
        });
      }

    }
  }

  Events.on( events, (event) => {
    updateButtons.method()
  })

  return updateButtons;

}

const createGliderFromElement = ( element = {} ) => {

  let element_id = element?.id ?? '';
  let animationDuration = parseInt( element.dataset?.glideAnimationDuration ?? 450 );
  let autoplay = parseInt( element.dataset?.glideAutoplay ?? 3500 );
  let direction = element.dataset?.glideDirection ?? 'ltr';
  let gap = parseInt( element.dataset?.glideGap ?? 0 );
  let style = element.dataset?.glideStyle ?? '';

  let options = getOptions({
    animationDuration,
    autoplay,
    direction,
    gap
  });

  switch ( style ) {
    case 'community': {
      options.focusAt = 0;
      options.gap = 20;
      options.peek = { before: 0, after: 100 };
      break;
    }
    case 'instagram-feed': {
      options.breakpoints = {
        575: {
          peek: { before: 35, after: 35 },
          perView: 3,
        },
        767: {
          peek: { before: 65, after: 65 },
          perView: 3,
        },
        991: {
          peek: { before: 35, after: 35 },
          perView: 4,
        },
        1199: {
          peek: { before: 65, after: 65 },
          perView: 4,
        },
        1399: {
          peek: { before: 35, after: 35 },
          perView: 5,
        },
        9999: {
          peek: { before: 65, after: 65 },
          perView: 6,
        }
      };
      options.gap = 12;
      break;
    }
    case 'products': {
      options.breakpoints = {
        575: {
          gap: 10,
          peek: {
            before: 0,
            after: 50
          },
          perView: 1
        },
        767: {
          gap: 10,
          peek: {
            before: 0,
            after: 150
          },
          perView: 1
        },
        991: {
          gap: 10,
          peek: {
            before: 0,
            after: 250
          },
          perView: 1
        },
        1199: {
          gap: 10,
          peek: {
            before: 0,
            after: 0
          },
          perView: 2
        },
        1399: {
          gap: 10,
          peek: {
            before: 0,
            after: 150
          },
          perView: 2
        },
        9999: {
          gap: 10,
          peek: {
            before: 0,
            after: 0
          },
          perView: 3
        }
      };
      break;
    }
  }

  let glide = new Glide( "#" + element_id, options );

  glide.on( events, (event) => {
    setTimeout( () => updateGlideTrackHeight( element ), 175 );
  });

  ( document.querySelectorAll( `[data-glide-navigation="#${element_id}"].next, [data-target="#${element_id}"].next` ) || [] ).forEach( button => {
    button.addEventListener("click", function () {
      glide.go(">");
    });
  });

  ( document.querySelectorAll( `[data-glide-navigation="#${element_id}"].prev, [data-target="#${element_id}"].prev` ) || [] ).forEach( button => {
    button.addEventListener("click", function () {
      glide.go("<");
    });
  });

  // glide.mount({ UpdateNavigationStateBasedOnGlideSize });
  glide.mount();

  // FIX for when single slide does not fill 100% of glider
  setTimeout( () => {
    glide.update();
  }, 175 );

  gliders[element_id] = { element_id, glide };

};

const getOptions = ( custom = {} ) => {

  let standard = {
    animationTimingFunc: "ease-in-out",
    animationDuration: 450,
    autoHeight: true,
    autoplay: 3250,
    direction: 'rtl',
    dragThreshold: 35,
    hoverpause: true,
    perView: 1,
    swipeThreshold: 35,
    type: "carousel",
    rewind: true,
    throttle: 50,
    gap: 10,
  };

  return { ...standard, ...custom };

};

const updateGlideTrackHeight = ( element = false ) => {
  if ( element ) {
    let active_slide = element.querySelector( '.glide__slide--active' ) || false;
    let glide_track = element.querySelector( '.glide__track' ) || false;
    if ( active_slide && glide_track ) {
      let active_slide_height = active_slide.offsetHeight;
      let glide_track_height = glide_track.offsetHeight;
      if ( glide_track_height != active_slide_height ) glide_track.style.height = active_slide_height + 'px';
      AOS.refresh();
    }
  }
};

const init = () => {
  if ( config.debug ) console.log(`[ ${config.name} v.${config.version} initialized ]`);
    elements.forEach( element => createGliderFromElement( element ) );
  if ( config.debug ) console.log(`[ ${config.name} v.${config.version} complete ]`);
};

export default { gliders, init };
