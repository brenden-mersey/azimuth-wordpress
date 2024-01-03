const config = { debug: false, name: 'render.js', version: '1.0' };

const instagramFeed = ( account = "", items = [] ) => {
  if ( account && items.length ) {
    ( document.querySelectorAll( `[data-instagram-feed-account="${account}"]` ) || [] ).forEach( element => {

      let elementLimit = element.dataset?.instagramFeedLimit || 20;
      let elementItemCount = 0;

      for ( let i = 0; i < items.length; ++i ) {

        let {
          block_name = "instagram-feed",
          media_type,
          media_url = "",
          permalink = "",
        } = items[i];
        let allowedMediaType = [ "CAROUSEL_ALBUM", "IMAGE" ].includes(media_type);

        if ( allowedMediaType ) {

          ( element.querySelectorAll(`.${block_name}__item[data-index="${elementItemCount}"] .${block_name}__link`) || [] ).forEach( el => {
            el.href = permalink;
          });

          ( element.querySelectorAll(`.${block_name}__item[data-index="${elementItemCount}"] .${block_name}__image`) || [] ).forEach( el => {
            el.dataset.bg = media_url;
            el.classList.add("lazyload");
          });

          elementItemCount++;

        }

        if ( elementItemCount >= elementLimit ) {
          break;
        }

      }
    });
  }
};

export default { instagramFeed };
