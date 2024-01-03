<?php

class CustomThemeTemplates extends CustomThemeBase {

  /*
  //////////////////////////////////////////////////////////
  ////  Properties
  //////////////////////////////////////////////////////////
  */

  private $name = 'Custom Theme Templates';
  private $version = '2.0';

  /*
  //////////////////////////////////////////////////////////
  ////  Methods | Instance
  //////////////////////////////////////////////////////////
  */

  // --------------------------- Button
  public function render_button( $button = [] ) {

    $html = '';
    $block_name = 'button';

    if ( $button ) {

      $link = $link_attachment = $link_category = $link_external = $link_page = $link_type = $title = false;
      $appearance = 'primary';
      $target = '_self';

      extract( $button );

      switch ( $link_type ) {
        case 'attachment':
          $link = ( isset($link_attachment['url']) ) ? $link_attachment['url'] : false;
          $target = '_blank';
          break;
        case 'category':
          $link = get_category_link( $link_category );
          break;
        case 'external':
          $link = $link_external;
          $target = '_blank';
          break;
        case 'page':
          $link = get_permalink( $link_page );
          break;
      }

      if ( $link && $title ) {
        $html .= '<a
          class="' . $block_name . '"
          href="' . $link . '"
          target="' . $target . '"
          data-appearance="' . $appearance . '"
        >' . $title . '</a>';
      }

    }

    return $html;

  }

  // --------------------------- Call to Action
  public function render_card( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'background' => [],
        'block_name' => 'card',
        'classes' => '',
        'html' => '',
        'id' => false,
        'index' => 'not-set',
        'style' => 'default',
      ],
      $params
    ));

    // ---------------------------------------- Data
    $block_classes = $classes ? $classes . ' ' . $block_name : $block_name;
    $excerpt = get_the_excerpt( $id ) ?: '';
    $featured_image = $this->get_featured_image_by_post_id( $id );
    $permalink = get_permalink( $id ) ?: '';
    $post_type = get_post_type( $id ) ?: 'not-set';
    $title = get_the_title( $id ) ?: '';

    // ---------------------------------------- Template
    if ( $id ) {
      $html .= '<article class="' . $block_classes . ' ' . $post_type . '" data-post-type="' . $post_type . '" data-style="' . $style . '" data-index="' . $index . '">';
        switch ( $style ) {

          /*
          //////////////////////////////////////////////////////////
          ////  Product
          //////////////////////////////////////////////////////////
          */

          case 'products-card':

            $measurements = get_field( 'measurements', $id ) ?: [];
            $categories = get_the_category( $id ) ?: [];
            $category = $categories[0]->name ?? 'Not Set';

            if ( $featured_image ) {
              $html .= '<div class="' . $block_name . '__image d-block d-lg-none">';
                $html .= $this->render_lazyload_image([ 'background' => true, 'image' => $featured_image ]);
              $html .= '</div>';
              $html .= '<div class="' . $block_name . '__image d-none d-lg-block">';
                $html .= $this->render_lazyload_image([ 'image' => $featured_image ]);
              $html .= '</div>';
            }

            if ( $category || $excerpt || $measurements || $title ) {
              $html .= '<div class="' . $block_name . '__content">';
                $html .= $title ? '<h2 class="' . $block_name . '__title">' . $title . '</h2>' : '';
                $html .= $category ? '<strong class="' . $block_name . '__category">' . $category . '</strong>' : '';
                if ( $measurements ) {
                  $html .= '<div class="' . $block_name . '__measurements">';
                    foreach ( $measurements as $index => $item ) {
                      $label = $item['label'] ?? '';
                      $value = $item['value'] ?? '';
                      if ( $label || $value ) {
                        $html .= '<div class="' . $block_name . '__measurements-item">';
                          $html .= $label ? '<span class="' . $block_name . '__measurements-label">' . $label . '</span>' : '';
                          $html .= $value ? '<span class="' . $block_name . '__measurements-value">' . $value . '</span>' : '';
                        $html .= '</div>';
                      }
                    }
                  $html .= '</div>';
                }
                if ( $excerpt ) {
                  $html .= '<div class="' . $block_name . '__excerpt">';
                    $html .= $background ? $this->render_lazyload_image([
                      'background' => true, 'image' => $background,
                      'classes' => $block_name . '__excerpt-background',
                    ]) : '';
                    $html .= '<span class="' . $block_name . '__excerpt-inner body-copy--primary">' . trim_string( $excerpt, 105 ) . '</span>';
                  $html .= '</div>';
                }

              $html .= '</div>';
            }

            break;

        }
      $html .= '</article>';
    }

    return $html;

  }

  // ---------------------------------------- Product Card
  public function render_card_product( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        "block_name" => "card-product",
        "html" => "",
        "id" => false,
        "product_obj" => [],
      ],
      $params
    ));

    $id = $product_obj->ID ?? 0;
    $title = $product_obj->post_title ?? "";
    $excerpt = $product_obj->post_excerpt?? "";
    $excerpt_trimmed = trim_string( $excerpt, 105 );
    $feature_image = $this->get_featured_image_by_post_id($id);
    $feature_image_lazy = $this->render_lazyload_image([ "image" => $feature_image ]);
    $permalink = get_permalink($id) ?: "";
    $measurements = get_field( "measurements", $id ) ?: [];
    $measurements_html = "";
    $categories = get_the_category($id) ?: [];
    $category = $categories[0]->name ?? "Not Set";

    if ( !empty($measurements) ) {
      foreach ( $measurements as $item ) {
        $label = $item["label"] ?: "";
        $value = $item["value"] ?: "";
        if ( $label || $value ) {
          $measurements_html .= "
            <div class='{$block_name}__measurements-item'>
              " . ( $label ? "<div class='{$block_name}__measurements-label'>{$label}</div>" : "" ) . "
              " . ( $value ? "<div class='{$block_name}__measurements-value'>{$value}</div>" : "" ) . "
            </div>
          ";
        }
      }
    }

    return $title && $feature_image_lazy ? "
      <article class='{$block_name}'>

        " . ( $feature_image_lazy ? "<div class='{$block_name}__feature-image'>{$feature_image_lazy}</div>" : "" ) . "
        <div class='{$block_name}__content'>
          <strong class='{$block_name}__title'>{$title}</strong>
          <div class='{$block_name}__details'>
            <div class='{$block_name}__category'>{$category}</div>
            " . ( $measurements_html ? "<div class='{$block_name}__measurements'>{$measurements_html}</div>" : "" ) . "
          </div>
          " . ( $excerpt_trimmed ? "<div class='{$block_name}__excerpt body-copy--primary'><p>{$excerpt_trimmed}</p></div>" : "" ) . "
        </div>

        <div class='{$block_name}__hover'>
          " . ( $excerpt ? "<div class='{$block_name}__hover-excerpt body-copy--primary'><p>{$excerpt}</p></div>" : "" ) . "
        </div>

      </article>
    " : "";

  }

  // --------------------------- Navigation Item
  public function render_navigation_item( $params = [] ) {

    extract(array_merge(
      [
        "active" => false,
        "anchor" => "",
        "block_name" => "",
        "current_id" => 0,
        "link_external" => "",
        "link_page" => [],
        "link_target" => "_self",
        "link_type" => "",
        "title" => "",
      ],
      $params
    ));

    switch ( $link_type ) {
      case "page": {
        $link_page_id = $link_page->ID ?? 0;
        $link = get_the_permalink($link_page_id);
        if ( $link_page_id === $current_id ) {
          $active = true;
        }
        if ( $anchor ) {
          if ( $link_page_id === $current_id ) {
            $link = "{$anchor}-anchor";
            $active = false;
          } else {
            $link .= "{$anchor}-anchor";
          }
        }
        break;
      }
      case "external": {
        $link = $link_external ?: "";
        $link .= $anchor ? "{$anchor}-anchor" : "";
        $link_target = "_blank";
        break;
      }
      default: {
        $link = "";
        break;
      }
    }
    $link_state = $active ? " active" : "";

    return  $link && $title ? "
      <div class='{$block_name}__navigation-item'>
        <a class='{$block_name}__navigation-link link{$link_state}' href='{$link}' target='{$link_target}' title='{$title}'>{$title}</a>
      </div>
    " : "";

  }

  // --------------------------- Organization Item
  public function render_organization_item($params = []) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        "block_name" => "organization",
        "about" => "",
        "html" => "",
        "link_title" => "",
        "link_url" => "",
        "logo" => [],
        "name" => ""
      ],
      $params
    ));

    $link_html = $this->render_link([
      "classes" => "{$block_name}__cta-link",
      "title" => $link_title,
      "url" => $link_url,
    ]);
    $logo_lazy = $this->render_lazyload_image([ 'image' => $logo ]);

    return $about ? "
      <div class='{$block_name}'>
        " . ( $logo_lazy ? "<div class='{$block_name}__logo'>{$logo_lazy}</div>" : "" ) . "
        <div class='{$block_name}__content body-copy--secondary'>
          <div class='{$block_name}__info'>{$about}</div>
          " . ( $link_html ? "<div class='{$block_name}__cta'>{$link_html}</div>" : "" ) . "
        </div>
      </div>
    " : "";

  }

  // ---------------------------------------- Page Background
  public function render_page_background( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'html' => '',
        'id' => false,
      ],
      $params
    ));

    // ---------------------------------------- Data
    switch ( $id ) {
      case 'no-post-id':
        $error_data = get_field( 'error_404', 'options' ) ?: [];
        $background = $error_data['background'] ?? [];
        break;
      default:
        $background = get_field( 'page_background', $id ) ?: [];
        break;
    }

    $background_lazy = $this->render_lazyload_image([ 'image' => $background ]);

    return $background_lazy ? "<div class='page-background'>{$background_lazy}</div>" : "";

  }

  // --------------------------- Pagination
  public function render_pagination( $params = [] ) {

    $block_name = 'pagination';
    $defaults = [
      'next' => false,
      'prev' => false,
      'pages' => false,
    ];
    $html = '';

    extract( array_merge( $defaults, $params ) );

    // ---------------------------------------- Template
    $html .= '<section class="' . $block_name . '__pagination pagination">';
      $html .= $this->render_bs_container( 'open', 'col-12', 'container-fluid' );
        $html .= '<div class="' . $block_name . '__main">';

          $html .= '<div class="' . $block_name . '__item prev ' . ( $prev ? 'active' : 'not-active' ) . '">';
            $html .= $prev ? $prev : 'Prev';
          $html .= '</div>';

          foreach( $pages as $i => $item ) {
            $html .= '<div class="' . $block_name . '__item page">' . $item . '</div>';
          }

          $html .= '<div class="' . $block_name . '__item next ' . ( $next ? 'active' : 'not-active' ) . '">';
            $html .= $next ? $next : 'Next';
          $html .= '</div>';

        $html .= '</div>';
      $html .= $this->render_bs_container( 'closed' );
    $html .= '</section>';

    return $html;

  }

  // --------------------------- Post Categories
  public function render_post_categories( $params = [] ) {

    $block_name = 'categories';
    $defaults = [ 'show_cat_icon' => false, 'post_id' => false, 'limit' => 1 ];
    $html = '';

    extract( array_merge( $defaults, $params ) );

    if ( $post_id ) {

      $categories = get_the_category( $post_id ) ? get_the_category( $post_id ) : [];

      foreach( $categories as $i => $cat ) {



        if ( $i < $limit ) {

          // ---------------------------------------- WP Data
          $cat_name = $cat->name;
          $cat_id = $cat->term_id;
          $cat_slug = $cat->slug;
          $cat_url = get_category_link( $cat_id );
          $cat_term = get_term( $cat_id ) ? get_term( $cat_id ) : false;

          // ---------------------------------------- ACF Data
          $cat_icon = get_field( 'icon_svg', $cat_term );

          if ( $i > 0 ) {
            $html .= '<div class="' . $block_name . '__item delimiter">|</div>';
          }

          $html .= '<div class="' . $block_name . '__item">';
            $html .= ( $show_cat_icon && $cat_icon ) ? '<div class="' . $block_name . '__icon">' . $cat_icon . '</div>' : '';
            $html .= '<div class="' . $block_name . '__name">' . $cat_name . '</div>';
          $html .= '</div>';

        }
      }

    }

    return $html;

  }

  // --------------------------- Post Meta
  public function render_post_meta( $params = [] ) {

    $block_name = 'article';
    $defaults = [ 'show_cat_icon' => false, 'cat_limit' => 1, 'date_format' => 'm.d.y', 'meta_types' => [], 'post_id' => false ];
    $html = $meta_html = '';

    extract( array_merge( $defaults, $params ) );

    if ( $post_id && $meta_types ) {

      if ( 'post' !== get_post_type( $post_id ) ) {
        $block_name = get_post_type( $post_id );
      }

      foreach ( $meta_types as $i => $meta ) {

        switch( $meta ) {
          case 'author':

            $author_id = get_post_field( 'post_author', $post_id );
            $author = get_the_author_meta( 'display_name', $author_id );
            $meta_html = ( $author ) ? '<div class="' . $block_name . '__author author">' . $author . '</div>' : '';
            break;

          case 'categories':

            $categories = $this->render_post_categories([ 'show_cat_icon' => $show_cat_icon, 'post_id' => $post_id, 'limit' => $cat_limit ]);
            $meta_html = ( $categories ) ? '<div class="' . $block_name . '__categories categories" data-show-cat-icont="' . $show_cat_icon . '">' . $categories . '</div>' : '';
            break;

          case 'date':

            $date = get_the_date( $date_format, $post_id );
            $meta_html = ( $date ) ? '<time class="' . $block_name . '__date date" datetime="' . $date . '">' . $date . '</time>' : '';
            break;

          case 'issue':

            $issue = get_field( 'issue', $post_id );
            $meta_html = ( $issue ) ? '<div class="' . $block_name . '__issue issue">Issue ' . ( $issue > 10 ? $issue : '0' . $issue ) . '</div>' : '';
            break;

        }

        if ( $meta_html ) {
          $html .= ( $i > 0 ) ? '<div class="' . $block_name . '__delimiter delimiter">|</div>' . $meta_html : $meta_html;
        }

      }
    }

    return $html;

  }

  // --------------------------- Post Preview
  public function render_post_preview( $post_id = false, $params = [] ) {

    $html = '';
    $block_name = 'article';

    if ( $post_id ) {

      // default $params
      $appearance = '';
      $date_format = 'F j, Y';

      if ( $params ) {
        extract( $params );
      }

      // get data
      $date = ( get_the_date( $date_format, $post_id ) ) ? get_the_date( $date_format, $post_id ) : false;
      $excerpt = ( get_the_excerpt( $post_id ) ) ? get_the_excerpt( $post_id ) : false;
      $featured_image = ( $this->get_featured_image_by_post_id( $post_id ) ) ? $this->get_featured_image_by_post_id( $post_id ) : false;
      $permalink = ( get_permalink( $post_id ) ) ? get_permalink( $post_id ) : false;
      $title = ( get_the_title( $post_id ) ) ? get_the_title( $$post_idid ) : false;

      // build template
      $html .= '<article class="' . $block_name . ( $appearance ? ' ' . $block_name . '--' . $appearance : '' ) . '" data-post-id="' . $post_id . '">';
        $html .= '<a href="' . $permalink . '" target="_self">';

          $html .= '<div class="' . $block_name . '__featured-image">';
            if ( $featured_image ) {
              $html .= $this->render_lazyload_image( $featured_image );
            }
          $html .= '</div>';

          $html .= '<div class="' . $block_name . '__content">';
           if ( $date ) {
              $html .= '<div class="' . $block_name . '__date">' . $date . '</div>';
           }
            if ( $title ) {
              $html .= '<h2 class="' . $block_name . '__title">' . $title . '</h2>';
            }
          $html .= '</div>';

        $html .= '</a>';
      $html .= '</article>';

    }

    return $html;

  }

  // --------------------------- Preload Fonts
  public function render_preload_fonts( $fonts = [] ) {
    $html = '';
    foreach ( $fonts as $font ) {
      $font_src = $this->get_theme_directory('assets') . '/fonts/' . $font . '.woff2';
      $html .= '<link rel="preload" href="' . $font_src . '" as="font" type="font/woff2" crossorigin>';
    }
    return $html;
  }

  // --------------------------- Preload Fonts
  public function render_seo( $enable = true ) {
    $html = '<meta name="robots" content="noindex, nofollow">';
    if ( $enable && !is_attachment( $this->get_theme_info('post_ID') ) ) {
			if ( defined( 'WPSEO_VERSION' ) ) {
				$html = '<!-- Yoast Plugin IS ACTIVE! -->';
			} else {
				$html = '<!-- Yoast Plugin IS NOT ACTIVE! -->';
				$html .= '<meta name="description" content="' . get_bloginfo( 'description' ) . '">';
			}
    }
    return $html;
  }

  // --------------------------- Subnavigation Item
  public function render_subnavigation_item ( $params = [], $current_id = 0 ) {

    // default data
    $html = '';
    $block_name = 'subnavigation';
    $link = $link_attachment = $link_category = $link_external = $link_id = $link_page = $link_type = $title = false;
    $target = '_self';

    extract( $params );

    switch( $link_type ) {
      case 'attachment':
        $link = ( isset($link_attachment['url']) ) ? $link_attachment['url'] : false;
        $target = '_blank';
        break;
      case 'category':
        $link_id = $link_category;
        $link = get_category_link( $link_id );
        break;
      case 'external':
        $link = $link_external;
        $target = '_blank';
        break;
      case 'page':
        $link_id = $link_page;
        $link = get_permalink( $link_id );
        break;
    }

    $is_active = ( $current_id === $link_id ) ? true : false;

    if ( $title && $link ) {
      $html .= '<div class="' . $block_name . '__item" data-is-active="' . ( $is_active ? 'true' : 'false' ) . '">';
        $html .= '<a class="' . $block_name . '__link' . ( $is_active ? ' active' : '' ) . '" href="' . $link . '" target="' . $target . '">' . $title . '</a>';
      $html .= '</div>';
    }

    return $html;

  }

  // --------------------------- Placeholder Content
  public function render_placeholder_content( $type = 'grid', $container = 'container' ) {

    $html = '<div class="placeholder">';
      $html .= $this->render_bs_container( 'open', 'col-12', $container );

        switch ( $type ) {
          case 'content':

            $html .= '<div class="placeholder__content rte">';

              $html .= '<h1>H1 - Placeholder Content</h1>';
              $html .= '<h2>H2 - Mauris turpis enim venenatis quis mi egestas mattis purus</h2>';
              $html .= '<p>Pellentesque at interdum enim. Suspendisse vulputate convallis mi quis auctor. Cras at urna mi. Quisque pretium tempus lacus in viverra. Sed auctor erat enim, sed accumsan orci tristique sit amet. Mauris turpis enim, venenatis quis mi in, egestas mattis purus. Duis eleifend varius tempus. Aliquam rutrum commodo ex, vitae imperdiet tortor sodales sagittis. Mauris tellus neque, imperdiet a lectus sed, placerat mollis turpis.</p>';
              $html .= '<p>Sed tincidunt nibh vel sapien consequat placerat. In molestie, lacus sit amet imperdiet convallis, enim ex vestibulum nibh, in accumsan est elit non ligula. Etiam tellus dolor, pharetra ac tempor vel, facilisis nec elit. Duis consectetur ligula eu metus cursus bibendum. Praesent tellus est, vehicula varius volutpat at, hendrerit sed velit. Morbi in tempus nibh. Ut ultrices viverra elit, id lacinia eros tincidunt a. In eget purus massa. Nulla facilisi. Interdum et malesuada fames ac ante ipsum primis in faucibus. Mauris laoreet sapien vel odio accumsan, sed posuere libero vestibulum. Praesent est felis, tincidunt eu tellus id, bibendum placerat enim. Praesent pulvinar tortor tortor, at tincidunt erat molestie vel. Pellentesque accumsan sem massa, ac tincidunt velit rutrum non. Etiam vel turpis id dolor bibendum gravida ac pharetra ex. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>';
              $html .= '<p>Vestibulum id nunc tempor, faucibus leo eu, tincidunt dui. In cursus, metus vel commodo tincidunt, odio felis facilisis arcu, in egestas erat enim id quam. In laoreet metus id luctus pellentesque. Nullam ac nunc non arcu porta maximus ac non odio. Suspendisse luctus mauris sit amet dignissim lacinia. Duis volutpat facilisis nisl quis vulputate. Sed risus purus, mollis in pulvinar in, rhoncus tristique nisi. Nunc sollicitudin sapien nibh, laoreet congue velit porttitor at. Vestibulum elementum maximus condimentum. Nam aliquam, velit ut consectetur scelerisque, sapien magna bibendum sem, ut vulputate libero massa ut justo.</p>';

              $html .= '<h3>H3 - Lorem ipsum dolor sit amet consectetur adipiscing elit</h3>';
              $html .= '<p>Donec mattis eget lorem id fermentum. Duis rhoncus nulla porta, commodo turpis eu, bibendum erat. Donec non scelerisque arcu, id imperdiet odio. Nulla sit amet mi non elit ornare laoreet et nec ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed volutpat urna sed suscipit rutrum. Phasellus feugiat turpis nibh, a accumsan elit suscipit vitae. Nulla turpis risus, fermentum id mattis eget, ullamcorper ac neque. Vestibulum congue tortor eu pellentesque venenatis. Ut sagittis ante in vestibulum pharetra. Nam cursus auctor nibh. Praesent eu libero urna.</p>';
              $html .= '<p>Fusce fringilla eget nisl vitae eleifend. Proin aliquam odio ut felis ornare feugiat. Integer ac enim et nisi laoreet commodo sed sit amet metus. Aliquam porta semper dolor ac cursus. Nunc bibendum ipsum non lobortis vehicula. Phasellus iaculis sagittis ipsum id porta. Nulla eu ante ut sapien fringilla egestas iaculis at ex. Sed in varius nibh. Etiam eros neque, tincidunt ut diam et, congue imperdiet metus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec tincidunt enim non urna laoreet eleifend. Suspendisse purus risus, suscipit vel urna viverra, venenatis lobortis ante.</p>';

              $html .= '<ol>';
                $html .= '<li>Pellentesque fringilla massa non metus cursus, vitae pretium dolor feugiat.</li>';
                $html .= '<li>Nunc bibendum sapien ac cursus sollicitudin.</li>';
                $html .= '<li>Pellentesque ut elit ac arcu luctus tincidunt.</li>';
                $html .= '<li>Morbi a arcu a lacus iaculis efficitur.</li>';
                $html .= '<li>Suspendisse efficitur nibh in lectus porttitor, vel faucibus lacus sagittis.</li>';
              $html .= '</ol>';

              $html .= '<p>Quisque eget suscipit dui. Etiam lacinia pulvinar felis sed fringilla. Ut vitae diam et lorem eleifend porttitor a quis felis. Nulla malesuada volutpat felis, at consectetur elit consequat non. Fusce sed erat sagittis, venenatis urna a, ultricies tellus. Nunc tempor semper ligula, eget consequat elit blandit non. Donec consectetur, est vitae imperdiet vestibulum, ligula urna ultrices est, in auctor neque lectus nec sapien. Pellentesque ac rutrum purus, eget iaculis quam. Vestibulum non lacinia erat.</p>';
              $html .= '<p>Quisque sodales tristique tincidunt. Phasellus suscipit velit vel massa feugiat placerat. Cras quis dolor iaculis nunc commodo rhoncus ac eu ex. Morbi pharetra egestas nunc, at fermentum lorem condimentum quis. Praesent sed erat id diam sollicitudin tincidunt eget vitae odio. Praesent quam odio, ultricies consectetur est eu, varius rhoncus felis. Nam vitae lectus cursus, porttitor orci in, fringilla ipsum. Vestibulum facilisis lacus turpis.</p>';

              $html .= '<h4>H4 - Lorem ipsum dolor sit amet consectetur adipiscing elit</h4>';

              $html .= '<p>Ted aliquam augue a auctor vulputate. Nunc ut congue tellus, non mattis purus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Morbi quam ex, euismod at elit nec, placerat aliquam mi. Mauris nec blandit lorem, lacinia tristique enim. Donec suscipit lobortis arcu, non hendrerit urna rutrum in. Sed vitae metus id ex accumsan tempor. Phasellus sed commodo ex. Maecenas quis dui tortor. Vivamus quis commodo orci. Vivamus dignissim, diam malesuada imperdiet vehicula, ligula ligula ultricies libero, ut tempor turpis lacus non ipsum. Quisque non magna quis mauris ullamcorper vestibulum. Aliquam ipsum urna, faucibus congue erat vitae, maximus tincidunt nunc. Maecenas iaculis dui at velit egestas, vel commodo magna scelerisque. Nulla quis risus eu velit ornare aliquam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>';
              $html .= '<p>Nam ornare laoreet vulputate. Aliquam cursus risus libero, ac tincidunt ante interdum ac. Proin scelerisque dolor non justo vestibulum, nec blandit ante fermentum. Aliquam at odio lobortis, vestibulum felis vitae, vehicula nulla. Sed dapibus sit amet nisl quis tempus. Nulla rutrum non velit id fermentum. Praesent orci nulla, finibus ac sapien nec, tristique imperdiet enim. Nulla elementum lacus vel iaculis condimentum. Vivamus eu semper ligula. Quisque ut ultricies nibh. Maecenas risus justo, gravida eu urna vestibulum, sollicitudin dictum sapien.</p>';

              $html .= '<ul>';
                $html .= '<li>Pellentesque in ligula quis nisl egestas pharetra.</li>';
                $html .= '<li>Cras elementum arcu vel leo tempus convallis.</li>';
                $html .= '<li>Pellentesque non orci id ipsum condimentum laoreet.</li>';
                $html .= '<li>Nulla maximus enim nec neque volutpat, ultrices viverra tellus facilisis.</li>';
                $html .= '<li>Morbi pharetra nunc sed tristique posuere.</li>';
                $html .= '<li>Quisque efficitur odio vitae ipsum fringilla, id aliquam ipsum pretium.</li>';
              $html .= '</ul>';

              $html .= '<p>Fusce fringilla eget nisl vitae eleifend. Proin aliquam odio ut felis ornare feugiat. Integer ac enim et nisi laoreet commodo sed sit amet metus. Aliquam porta semper dolor ac cursus. Nunc bibendum ipsum non lobortis vehicula. Phasellus iaculis sagittis ipsum id porta. Nulla eu ante ut sapien fringilla egestas iaculis at ex. Sed in varius nibh. Etiam eros neque, tincidunt ut diam et, congue imperdiet metus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec tincidunt enim non urna laoreet eleifend. Suspendisse purus risus, suscipit vel urna viverra, venenatis lobortis ante.</p>';
              $html .= '<p>Quisque eget suscipit dui. Etiam lacinia pulvinar felis sed fringilla. Ut vitae diam et lorem eleifend porttitor a quis felis. Nulla malesuada volutpat felis, at consectetur elit consequat non. Fusce sed erat sagittis, venenatis urna a, ultricies tellus. Nunc tempor semper ligula, eget consequat elit blandit non. Donec consectetur, est vitae imperdiet vestibulum, ligula urna ultrices est, in auctor neque lectus nec sapien. Pellentesque ac rutrum purus, eget iaculis quam. Vestibulum non lacinia erat.</p>';
              $html .= '<p>Mauris fermentum dui sed leo commodo efficitur. Sed quis fringilla mi. Etiam lectus odio, ultricies vestibulum leo sodales, placerat auctor arcu. Donec lorem orci, scelerisque at ante sed, vehicula condimentum purus. In ultrices facilisis nibh, sed iaculis justo vestibulum vestibulum. Duis ut felis id nunc gravida consectetur sit amet ut metus. Integer at neque imperdiet, maximus arcu nec, tincidunt turpis. Vestibulum sit amet felis quis nibh aliquam vehicula eget efficitur eros. Pellentesque semper vulputate nisl, iaculis rutrum magna molestie vitae. Nunc ac dolor ut leo suscipit gravida id non dui. Fusce vestibulum tellus elit, euismod semper libero rutrum eu. Sed tempus, lorem et dapibus interdum, arcu urna rutrum odio, sed dictum dui leo nec nunc. Vestibulum congue tortor tellus. Vivamus euismod risus a tellus laoreet, eu consequat enim ullamcorper. Etiam a iaculis odio, in faucibus purus. Pellentesque venenatis, mauris eu iaculis tempus, dui est vulputate felis, nec interdum magna velit at enim.</p>';
              $html .= '<p>Donec mattis eget lorem id fermentum. Duis rhoncus nulla porta, commodo turpis eu, bibendum erat. Donec non scelerisque arcu, id imperdiet odio. Nulla sit amet mi non elit ornare laoreet et nec ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed volutpat urna sed suscipit rutrum. Phasellus feugiat turpis nibh, a accumsan elit suscipit vitae. Nulla turpis risus, fermentum id mattis eget, ullamcorper ac neque. Vestibulum congue tortor eu pellentesque venenatis. Ut sagittis ante in vestibulum pharetra. Nam cursus auctor nibh. Praesent eu libero urna.</p>';
            $html .= '</div>';

            break;
          case 'grid':

            $html .= '<div class="placeholder__grid rte">';

              $html .= '<h1>H1 - Placeholder Grid</h1>';

              $html .= '<div class="row row--inner">';
                for ( $i = 1; $i <= 12; $i++ ) {
                  $html .= '<div class="col-1">';
                    $html .= '<span>' . $i . '</span>';
                  $html .= '</div>';
                }
              $html .= '</div>';

              $html .= '<div class="row row--inner">';
                for ( $i = 1; $i <= 6; $i++ ) {
                  $html .= '<div class="col-2">';
                    $html .= '<span>' . $i . '</span>';
                  $html .= '</div>';
                }
              $html .= '</div>';

              $html .= '<div class="row row--inner">';
                for ( $i = 1; $i <= 4; $i++ ) {
                  $html .= '<div class="col-3">';
                    $html .= '<span>' . $i . '</span>';
                  $html .= '</div>';
                }
              $html .= '</div>';

              $html .= '<div class="row row--inner">';
                for ( $i = 1; $i <= 3; $i++ ) {
                  $html .= '<div class="col-4">';
                    $html .= '<span>' . $i . '</span>';
                  $html .= '</div>';
                }
              $html .= '</div>';

              $html .= '<div class="row row--inner">';
                for ( $i = 1; $i <= 2; $i++ ) {
                  $html .= '<div class="col-6">';
                    $html .= '<span>' . $i . '</span>';
                  $html .= '</div>';
                }
              $html .= '</div>';

            $html .= '</div>';

            break;

        } // switch $type

      $html .= $this->render_bs_container( 'close' );
    $html .= '</div>';

    return $html;

  }

}
