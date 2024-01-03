<?php

class CustomThemeBase {

  /*
  //////////////////////////////////////////////////////////
  ////  Properties
  //////////////////////////////////////////////////////////
  */

  private $name = 'Custom Theme Base';
  private $version = '2.0';

  public $aos_delay = 250;
  public $custom_image_title = 'custom-image-size';
  public $custom_image_sizes = [ 1, 10, 90, 180, 360, 540, 720, 900, 1080, 1296, 1512, 1728, 2048 ];

  /*
  //////////////////////////////////////////////////////////
  ////  Methods
  //////////////////////////////////////////////////////////
  */

  // --------------------------- Get Acronym from Words
  public function get_acronym_from_words( $words = '' ) {

    $acronym = '';

    $words = preg_split("/[\s,_-]+/", $words );

    if ( $words ) {
      foreach ( $words as $i => $word ) {
        $acronym .= $word[0];
      }
    }

    return $acronym;

  }

  // ---------------------------------------- Get Featured Image by ID
  public function get_featured_image_by_post_id( $post_id = 0 ) {

    $image = [];
    $image_alt = $image_attributes = $post_thumbnail_id = $sizes = false;

    if ( $post_id ) {

      // get image sizes
      if ( get_intermediate_image_sizes() ) {
        $sizes = get_intermediate_image_sizes();
      }

      // get post thumbnail id
      if ( get_post_thumbnail_id( $post_id ) ) {
        $post_thumbnail_id = get_post_thumbnail_id( $post_id );
      }

      // if image sizes and image id
      if ( $sizes && $post_thumbnail_id ) {

        $image_alt = get_post_meta( $post_thumbnail_id , '_wp_attachment_image_alt', true );
        $image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, "full" );

        $image = [
          "url" => get_the_post_thumbnail_url( $post_id ),
          "sizes" => [],
          "alt" => $image_alt,
          "width" => $image_attributes[1],
          "height" => $image_attributes[2],
        ];

        foreach ( $sizes as $index => $size ) {
          $image["sizes"][$size] = wp_get_attachment_image_src( $post_thumbnail_id, $size )[0];
        }

      }

    }

    return $image;

  }

  // --------------------------- Google Maps Directions Link
  public function get_google_maps_directions_link( $params = [] ) {

    $html = '';
    $base = 'https://www.google.com/maps/search/?api=1&query=';

    if ( $params ) {

      // default data
      $name = $city = $country = $region = $postal = $address = $address_2 = false;

      // extract $params
      extract( $params );

      $html .= ( $address ) ? $address : '';
      $html .= ( $city ) ? ' ' . $city : '';
      $html .= ( $region ) ? ' ' . $region : '';
      $html .= ( $postal ) ? ' ' . $postal : '';
      $html .= ( $country ) ? ' ' . $country : '';
      $html .= ( $name ) ? ' ' . $name : '';

      if ( $address_2 ) {
        $html = $address_2 . '–' . $html;
      }

      if ( $html ) {
        $html = $base . trim($html);
      }

    }

    return $html;

  }

  // ---------------------------------------- Get Link
  public function get_link($params = []) {

    extract(array_merge(
      [
        "link_type" => "",
        "link_pages" => "",
        "link_external" => "",
      ],
      $params
    ));

    switch ( $link_type ) {
      case "external": {
        $link = $link_external;
        break;
      }
      case "pages-posts": {
        $link = $link_pages;
        break;
      }
      default: {
        $link = "";
        break;
      }
    }

    return $link;

  }

  // --------------------------- Theme Classes
  public function get_theme_classes() {

    global $post;
    global $template;

    $classes = '';

  	if ( isset( $post ) ) {

  		$post_ID = $post->ID;
  		$post_type = $post->post_type;
  		$post_slug = $post->post_name;
  		$template = basename( $template, '.php' );

  		$classes .= 'post-type--' . $post_type;
  		$classes .= ' ' . $post_type . '--' . $post_slug;
  		$classes .= ' page-id--' . $post_ID;
  		$classes .= ' template--' . $template;
  		$classes .= ' ' . $template;

      if ( is_front_page() ) {
        $classes .= ' is-front-page';
      }

      if ( is_page() ) {
        $classes .= ' is-page';
      }

      if ( is_page_template( 'page-templates/page--about-us.php') ) {
        $classes .= ' is-about-us-page';
      }

      if ( is_single() ) {
        $classes .= ' is-single';
      }

      if ( is_archive() ) {
        $classes .= ' is-archive';
      }

      if ( is_category() ) {
        $classes .= ' is-category';
      }

  	}

    if ( is_404() ) {
      $classes .= ' is-404';
    }

  	return trim($classes);

  }

  // --------------------------- Theme Directory
  public function get_theme_directory( $level = 'base' ) {

    switch ( $level ) {
      case 'assets':
        return get_template_directory_uri() . '/assets';
        break;
      case 'base':
        return get_template_directory_uri();
        break;
      case 'home':
        return get_home_url();
        break;
    }

  }

  // --------------------------- Theme Info
  public function get_theme_info( $param = 'version' ) {

    global $post;

    switch ( $param ) {
      case 'object_ID':
				$html = get_queried_object_id();
				break;
      case 'php_version':
				$html = phpversion();
				break;
      case 'post_ID':
				$html = ( $post ) ? $post->ID : 'no-post-id';
				break;
			case 'post_type':
				$html = get_post_type( $post->ID );
				break;
      case 'template':
				$html = basename( get_page_template(), ".php" );
				break;
      case 'version':
				$html = $this->version;
				break;
      case 'vitals':
        $html = "<!-- PHP Version: " . $this->get_theme_info( 'php_version' ) . " -->";
  	    $html .= "<!-- WP Version: " . $this->get_theme_info( 'wp_version' ) . " -->";
  	    $html .= "<!-- Current Template: " . $this->get_theme_info( 'template' ) . " -->";
  	    $html .= "<!-- Post ID: " . $this->get_theme_info( 'post_ID' ) . " -->";
        $html .= "<!-- Object ID: " . $this->get_theme_info( 'object_ID' ) . " -->";
				break;
      case 'wp_version':
				$html = get_bloginfo( "version" );
				break;
      default:
        $html = '';
        break;
    }

    return $html;

  }

  // --------------------------- Get Unique ID
  public function get_unique_id( $prefix = "id--" ) {
    return trim( $prefix . md5(uniqid(rand(), true)) );
  }

  // --------------------------- AOS Attributes
  public function render_aos_attrs( $params = [] ) {

    /*
    *  Note:
    *  AOS library (https://www.npmjs.com/package/aos) needs to be installed
    *  and initialized. JS and CSS files are required for anything to happen.
    *
    *  Timing:
    *  Both delay and duration must be increments of 50
    *
    *  Bugs:
    *  Offset and Mirror are buggy. Disable them for now.
    */

    // ---------------------------------------- Defaults
    $settings = array_merge([
        'anchor' => '',                           // element id
        'anchor_placement' => 'top-bottom',
        'delay' => 0,
        'duration' => 650,
        'easing' => 'ease-in-out',
        // 'mirror' => 'false',                   // DEF buggy, hide for now
        'offset' => 175,                          // buggy, so hide for now (...or?)
        'once' => 'true',
        'transition' => 'fade-in',
      ], $params
    );

    $html = '';

    foreach ( $settings as $key => $value ) {
      switch ( $key ) {
        case 'anchor': {
          $html .= ' data-aos-anchor="#' . $value . '"';
          break;
        }
        case 'anchor_placement': {
          $html .= ' data-aos-anchor-placement="' . $value . '"';
          break;
        }
        case 'transition': {
          $html .= ' data-aos="' . $value . '"';
          break;
        }
        default:
          $html .= ' data-aos-' . $key . '="' . $value . '"';
      }
    }

    return $html;

  }

  // --------------------------- Bootstrap Accordion
  public function render_bs_accordion( $params = [] ) {

    extract(array_merge(
      [
        'block_name' => 'accordion',
        'content' => '',
        'id' => '',
        'open' => false,
        'title' => '',
      ],
      $params
    ));

    $aria_state = $open ? "true" : "false";
    $button_classes = "{$block_name}__trigger button button--accordion" . ( $open ? "" : " collapsed" );
    $main_classes = "{$block_name}__main collapse" . ( $open ? " show" : "" );

    if ( $content && $id && $title ) {
      return "
        <button class='{$button_classes}' type='button' data-bs-toggle='collapse' data-bs-target='#{$id}' aria-expanded='{$aria_state}' aria-controls='{$id}'>
          <span class='{$block_name}__trigger-title'>{$title}</span>
          <span class='{$block_name}__trigger-icon'></span>
        </button>
        <div class='{$main_classes}' id='{$id}'>
          <div class='{$block_name}__main-content'>{$content}</div>

        </div>
      ";
    }

    return;

  }

  // --------------------------- Container
  public function render_bs_container( $state = 'open', $col = 'col-12', $container = 'container' ) {
    if ( "full-width" !== $container ) {
      return "open" !== $state ? "</div></div></div>" : "<div class='{$container}'><div class='row'><div class='{$col}'>";
    }
    return;
  }

  // --------------------------- Call to Action
  public function render_cta( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'block_name' => 'cta',
        'classes' => '',
        'cta' => [],
        'html' => '',
        'justification' => 'not-set',
        'link' => '',
        'style' => 'primary',
        'title' => '',
        'type' => 'button',
      ],
      $params
    ));

    // ---------------------------------------- Data
    $block_classes = $classes ? $classes . ' ' . $block_name : $block_name;
    $title = $cta['title'] ?? $title;
    $link_type = $cta['link_type'] ?? 'not-set';
    $target = '_self';
    $rel = '';

    switch( $link_type ) {
      case 'category':
        $link_id = $cta['link_category'] ?? '';
        $link = get_category_link( $link_id ) ?: '';
        break;
      case 'external':
        $link = $cta['link_external'] ?? '';
        $rel = 'noopener';
        $target = '_blank';
        break;
      case 'pages-posts':
        $link = $cta['link_pages'] ?? '';
        break;
    }

    if ( $link && $title ) {
      $html .= '<div class="' . $block_classes . '" data-justify="' . $justification . '">';
        $html .= $this->render_link([
          'block_name' => $type,
          'link' => $link,
          'rel' => $rel,
          'style' => $style,
          'target' => $target,
          'title' => $title,
        ]);
      $html .= '</div>';
    }

    return $html;

  }

  // ---------------------------------------- Element Styles
  public function render_element_styles( $params = [] ) {

    extract(array_merge(
      [
        "background_colour" => "none",
        "id" => "",
        "padding_bottom" => 0,
        "padding_top" => 0,
        "text_colour" => "powder",
      ],
      $params
    ));

    $background_colour = ( "none" === $background_colour ) ? "" : "background: rgba(var(--theme-colour-{$background_colour}), 1);";

    if ( $id ) {
      return "
        #{$id} {
          {$background_colour}
          color: rgba(var(--theme-colour-{$text_colour}), 1);
          padding-top: calc({$padding_top}px * 0.75);
          padding-bottom: calc({$padding_bottom}px  * 0.75);
        }
        @media screen and (min-width: 992px) {
          #{$id} {
            padding-top: {$padding_top}px;
            padding-bottom: {$padding_bottom}px;
          }
        }
      ";
    }

  }

  // ---------------------------------------- Form Field
  public function render_form_field( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'accept' => '',
        'block_name' => 'form',
        'checkbox_options' => [],
        'checbox_type' => 'boolean',
        'disabled' => false,
        'error_message' => [
          'checkbox' => 'This field is required',
          'email' => 'Please enter a valid email address',
          'file' => 'File type or file size incorrect',
          'tel' => 'Please enter a valid phone number',
          'select' => 'Please select a valid option',
          'text' => 'This field cannot be blank',
          'textarea' => 'This field cannot be blank',
        ],
        'html' => '',
        'label' => '',
        'multiple' => false,
        'name' => '',
        'placeholder' => '',
        'required' => false,
        'select_options' => [],
        'type' => 'text',
        'value' => '',
      ],
      $params
    ));

    $input_classes = $block_name . '__input input input--' . $type;
    $input_classes .= $required ? ' required' : '';

    $field_classes = $block_name . '__field field field--' . $type;
    $field_classes .= $multiple ? ' field--' . $type . '-multiple' : '';
    $field_classes .= 'rude' === $name ? ' field--' . $name : '';

    $label_classes = $block_name . '__label label label--' . $type;
    $label_classes .= $multiple ? ' label--' . $type . '-multiple' : '';

    $select_classes = $block_name . '__select select';
    $select_classes .= $required ? ' required' : '';
    $select_classes .= $multiple ? ' ' . $type . '--multiple' : '';

    $textarea_classes = $block_name . '__textarea textarea';
    $textarea_classes .= $required ? ' required' : '';

    $label = ( $required && $label ) ? $label . '<span class="required-marker">*</span>' : $label;

    if ( $name && $type ) {
      $html .= '<div class="' . $field_classes . '">';

      switch ( $type ) {

        /*
        //////////////////////////////////////////////////////////
        ////  Checkbox
        //////////////////////////////////////////////////////////
        */

        case 'checkbox':

          if ( $label ) {
            $html .= '<div class="checkbox">';
              $html .= '<label><div class="checkbox__label">' . $label . '</div>';
                $html .= '<input
                  class="' . $input_classes . '"
                  type="' . $type . '"
                  name="' . $name . '"
                  value="' . $value . '"
                />';
                $html .= '<div class="checkbox__content">';
                  if ( 'boolean' === $checbox_type ) {
                    $html .= '<div class="checkbox__value">' . $value . '</div>';
                  }
                $html .= '</div>';
              $html .= '</label>';
            $html .= '</div>';
          }

          break;

        /*
        //////////////////////////////////////////////////////////
        ////  Email, File, Tel & Text
        //////////////////////////////////////////////////////////
        */

        case 'email':
        case 'file':
        case 'tel':
        case 'text':

          $html .= $label ? '<label class="' . $label_classes . '">' . $label . '</label>' : '';
          $html .= '<input
            class="' . $input_classes . '"
            type="' . $type . '"
            name="' . $name . '"
            ' . ( 'rude' === $name ? ' tabindex="-1"' : '' ) .'
            ' . ( $value ? ' value="' . $value . '"' : '' ) . '
            ' . ( $placeholder ? ' placeholder="' . $placeholder . '"' : '' ) . '
            ' . ( $disabled ? ' disabled' : '' ) . '
            ' . ( $multiple ? ' multiple' : '' ) . '
            ' . ( $accept ? ' accept="' . $accept . '"' : '' ) . '
          >';

          break;

        /*
        //////////////////////////////////////////////////////////
        ////  Select
        //////////////////////////////////////////////////////////
        */

        case 'select':

          $html .= $label ? '<label class="' . $label_classes . '">' . $label . '</label>' : '';
          $html .= '<select
            class="' . $select_classes . '"
            name="' . $name . '"
            ' . ( $multiple ? ' multiple' : '' ) . '
          >';
            $html .= !$multiple ? '<option value="">Select one</option>' : '';
            if ( $select_options ) {
              foreach ( $select_options as $item ) {
                $value = $item['value'] ?? '';
                $title = $item['title'] ?? $value;
                if ( $value ) {
                  $html .= '<option value="' . $value . '">' . $title . '</option>';
                }
              }
            }
          $html .= '</select>';

          break;

        /*
        //////////////////////////////////////////////////////////
        ////  Textarea
        //////////////////////////////////////////////////////////
        */

        case 'textarea':

          $html .= $label ? '<label class="' . $label_classes . '">' . $label . '</label>' : '';
          $html .= '<textarea
            class="' . $textarea_classes . '"
            name="' . $name . '"
            ' . ( $placeholder ? ' placeholder="' . $placeholder . '"' : '' ) . '
          ></textarea>';

          break;

      }

      $html .= '<div class="field__error-message error-message">' . ( $error_message[$type] ?? '' ) . '</div>';

      $html .= '</div>';
    }

    return $html;

  }

  // ---------------------------------------- Form Input
  public function render_form_input( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'accept' => '',
        'classes' => 'form__input input',
        'disabled' => false,
        'honeypot' => false,
        'hidden' => false,
        'html' => '',
        'id' => '',
        'multiple' => false,
        'name' => '',
        'placeholder' => '',
        'readonly' => false,
        'required' => false,
        'type' => 'text',
        'value' => '',
      ],
      $params
    ));

    if ( $name ) {

      $classes .= ' input--' . $type;
      $classes .= $hidden ? ' d-none' : '';
      $classes .= $required ? ' required' : '';

      $html .= '<input
        class="' . $classes . '"
        ' . ( $accept ? 'accept="' . $accept . '"' : '' ) . '
        ' . ( $id ? 'id="' . $id . '"' : '' ) . '
        ' . ( $honeypot ? 'tabindex="-1"' : '' ) . '
        ' . ( $value ? 'value="' . $value . '"' : '' ) . '
        ' . ( $placeholder ? 'placeholder="' . $placeholder . '"' : '' ) . '
        ' . ( $multiple ? 'multiple' : '' ) . '
        ' . ( $disabled ? 'disabled' : '' ) . '
        ' . ( $readonly ? 'readonly' : '' ) . '
        ' . ( $type === 'email' ? 'spellcheck="false" autocapitalize="off"' : '' ) . '
        ' . ( $type === 'tel' ? 'pattern="[0-9\-]*"' : '' ) . '
        type="' . $type . '"
        name="' . $name . '"
        autocomplete="' . $type . '"
      >';

    }

    return $html;

  }

  // ---------------------------------------- Form Label
  public function render_form_label( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'classes' => 'form__label label',
        'for' => '',
        'hidden' => false,
        'html' => '',
        'modifier' => '',
        'required' => false,
        'value' => '',
      ],
      $params
    ));

    $classes .= $modifier ? $classes . ' label--' . $modifier : '';
    $classes .= $hidden ? ' d-none' : '';
    $value .= $required ? '<span class="required-marker">*</span>' : '';

    if ( $value ) {
      $html .= '<label
        class="' . $classes . '"
        ' . ( $for ? 'for="' . $for . '"' : '' ) . '
      >' . $value . '</label>';
    }

    return $html;

  }

  // ---------------------------------------- Form Select
  public function render_form_select( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'classes' => 'form__select select',
        'default' => '',
        'disabled' => false,
        'hidden' => false,
        'html' => '',
        'id' => '',
        'modifier' => '',
        'multiple' => false,
        'name' => '',
        'readonly' => false,
        'required' => false,
        'selected' => '',
        'options' => [],
      ],
      $params
    ));

    if ( $name && $options ) {

      $classes .= $modifier ? $classes . ' select--' . $modifier : '';
      $classes .= $hidden ? ' d-none' : '';
      $classes .= $required ? ' required' : '';

      $html .= '<select
        class="' . $classes . '"
        ' . ( $id ? 'id="' . $id . '"' : '' ) . '
        ' . ( $disabled ? 'disabled' : '' ) . '
        ' . ( $readonly ? 'readonly' : '' ) . '
        ' . ( $multiple ? 'multiple' : '' ) . '
        name="' . $name . '"
      >';
      $html .= $default ? '<option value="">' . $default . '</option>' : '';
      foreach ( $options as $index => $option ) {
        $title = $option['title'] ?? '';
        $value = $option['value'] ?? '';
        if ( $title && $value ) {
          $html .= '<option value="' . $value . '" ' . ( $selected === $value ? 'selected' : '' ) . '>' . $title . '</option>';
        }
      }
      $html .= '</select>';

    }

    return $html;

  }

  // ---------------------------------------- Form Textarea
  public function render_form_textarea( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'classes' => 'form__textarea textarea',
        'disabled' => false,
        'hidden' => false,
        'html' => '',
        'id' => '',
        'modifier' => '',
        'name' => '',
        'placeholder' => '',
        'readonly' => false,
        'required' => false,
        'value' => '',
      ],
      $params
    ));

    if ( $name ) {

      $classes .= $modifier ? $classes . ' textarea--' . $modifier : '';
      $classes .= $hidden ? ' d-none' : '';
      $classes .= $required ? ' required' : '';

      $html .= '<textarea
        ' . ( $classes ? 'class="' . $classes . '"' : '' ) . '
        ' . ( $id ? 'id="' . $id . '"' : '' ) . '
        ' . ( $disabled ? 'disabled' : '' ) . '
        ' . ( $readonly ? 'readonly' : '' ) . '
        ' . ( $placeholder ? 'placeholder="' . $placeholder . '"' : '' ) . '
        ' . ( $value ? 'value="' . $value . '"' : '' ) . '
        name="' . $name . '"
      ></textarea>';

    }

    return $html;

  }

  // ---------------------------------------- Lazyload iFrame
  public function render_lazyload_iframe( $params = [] ) {

    $defaults = [
      'aspect_ratio' => '16-9',
      'background' => false,
      'classes' => '',
      'delay' => 0,
      'duration' => 750,
      'html' => '',
      'preload' => false,
      'video_id' => '', // 160730254, 163590531, 221632885
      'video_source' => 'vimeo',
    ];

    extract( array_merge( $defaults, $params ) );

    $iframe_classes = 'lazyload lazyload-item lazyload-item--iframe';
    $iframe_classes .= $background ? ' lazyload-item--background' : ' lazyload-item--inline';
    $iframe_classes .= $preload ? ' lazypreload' : '';
    $iframe_classes = $classes ? $classes . ' ' . $iframe_classes : $iframe_classes;

    $video_source_url = ( 'vimeo' == $video_source ) ? 'https://player.vimeo.com/video/' : 'https://www.youtube.com/embed/';
    $video_source_url .= $video_id;
    $video_source_url .= ( $background ) ? '?autoplay=1&loop=1&autopause=0&muted=1&background=1' : '?autoplay=0&loop=1&autopause=0&muted=0&background=0';

    if ( $video_source_url && $video_id ) {

      $html = '
        <iframe
          class="' . trim($iframe_classes) . '"
          data-aspect-ratio="' . $aspect_ratio . '"
          data-src="' . $video_source_url . '"
          data-transition-delay="' . $delay . '"
          data-transition-duration="' . $duration . '"
          frameborder="0"
          allow="autoplay; encrypted-media"
          webkitallowfullscreen mozallowfullscreen allowfullscreen
        ></iframe>
      ';

    }

    return $html;

  }

  // ---------------------------------------- Lazyload Image
  public function render_lazyload_image( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'alt_text' => '',
        'classes' => '',
        'custom_sizes_title' => $this->custom_image_title,
        'custom_sizes' => $this->custom_image_sizes,
        'delay' => 0,
        'duration' => 300,
        'html' => '',
        'image' => [],
      ],
      $params
    ));

    $base_classes = 'lazyload lazyload-item lazyload-item--image lazypreload';
    $classes = ( $classes ?? '' ) . ' ' . $base_classes;
    $img_attrs = '';
    $img_srcset = '';

    if ( !empty($image) ) {

      $img_type = $image['subtype'] ?? 'no-set';
      $img_src = $image['url'] ?? '';

      $img_attrs .= 'class="' . trim($classes) . '"';
      $img_attrs .= $image['width'] ? ' width="' . $image['width'] . '"' : '';
      $img_attrs .= $image['height'] ? ' height="' . $image['height'] . '"' : '';
      $img_attrs .= ' alt="' . ( $alt_text ?: ( $image['alt'] ?? get_bloginfo('name') . ' Photography' ) ) . '"';
      $img_attrs .= $img_src ? ' data-src="' . $img_src . '"' : '';
      $img_attrs .= ' data-transition-delay="' . $delay . '"';
      $img_attrs .= ' data-transition-duration="' . $duration . '"';

      switch ( $img_type ) {
        case 'svg+xml': {
          $img_attrs .= $img_src ? ' src="' . $img_src . '"' : '';
          break;
        }
        default: {
          if ( isset($image['sizes']) && !empty($image['sizes']) ) {
            foreach ( $custom_sizes as $i => $size ) {
              $img_srcset .= ( $i > 0 ) ? ', ' : '';
              $img_srcset .= $image['sizes'][$custom_sizes_title . '-' . $size] . ' ' . $size . 'w';
            }
            $img_attrs .= $img_srcset ? ' data-sizes="auto" data-srcset="' . $img_srcset . '"' : '';
          }
          break;
        }

      }

      $html = '<img ' . $img_attrs . ' />';

    }

    return $html;

  }

  // ---------------------------------------- Lazyload Video
  public function render_lazyload_video( $params = [] ) {

    $defaults = [
      'background' => false,
      'classes' => '',
      'delay' => 0,
      'duration' => 750,
      'html' => '',
      'mime_type' => '',
      'preload' => false,
      'video' => [],
      'video_url' => '',
    ];

    extract( array_merge( $defaults, $params ) );

    $video_classes = 'lazyload lazyload-item lazyload-item--video';
    $video_classes .= $background ? ' lazyload-item--background' : ' lazyload-item--inline';
    $video_classes .= $preload ? ' lazypreload' : '';
    $video_classes = $classes ? $classes . ' ' . $video_classes : $video_classes;

    $video_url = isset($video['url']) ? $video['url'] : false;
    $mime_type = isset($video['mime_type']) ? $video['mime_type'] : false;

    if ( $video_url && $mime_type ) {
      $html = '<video
        class="' . $video_classes . '"
        src="' . $video_url . '"
        data-transition-delay="' . $delay . '"
        data-transition-duration="' . $duration . '"
        preload="none"
        muted=""
        data-autoplay=""
        data-poster=""
        loop
        playsinline
        muted
      >';
        $html .= '<source src="' . $video_url . '" type="' . $mime_type . '">';
      $html .= '</video>';
    }

    return $html;

  }

    // ---------------------------------------- Link
  public function render_link( $params = [] ) {

    // ---------------------------------------- Deconstruct
    extract(array_merge(
      [
        "active" => false,
        "block_name" => "link",
        "classes" => "",
        "style" => "primary",
        "title" => "",
        "type" => "",
        "url" => ""
      ],
      $params
    ));

    $classes .= "{$block_name} {$block_name}--{$style}";
    $classes .= $active ? " active" : "";
    $target = str_contains( $url, $_SERVER['SERVER_NAME'] ) || "anchor" == $type ? "_self" : "_blank";

    if ( $title && $url ) {
      return "
        <a class='{$classes}' href='{$url}' target='{$target}' title='{$title}'>
          <span class='{$block_name}__title'>{$title}</span>
        </a>
      ";
    }

  return;

  }

  // --------------------------- Navigation for WP
  public function render_navigation_wp( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'block_name' => 'navigation',
        'current_id' => false,
        'html' => '',
        'menu_title' => '',
      ],
      $params
    ));

    // ---------------------------------------- Data
    $menu_items = wp_get_nav_menu_items( $menu_title ) ?: [];

    // ---------------------------------------- Template
    foreach ( $menu_items as $item ) {

      $id = $item->object_id;
      $link = $item->url;
      $title = $item->title;
      $object_type = $item->object;
      $link_type = $item->type;
      $active = ( $id == $current_id ) ? true : false;
      $target = ( 'custom' == $link_type ) ? '_blank' : '_self';
      $rel = ( 'custom' == $link_type ) ? 'noopener' : '';

      $html .= '<div class="' . $block_name . '__item">';
        $html .= $this->render_link([
          'active' => $active,
          'classes' => $block_name . '__link',
          'target' => $target,
          'rel' => $rel,
          'title' => $title,
          'link' => $link,
        ]);
      $html .= '</div>';

    }

    return $html;

  }

  // --------------------------- Pre-connect Scripts
  public function render_preconnect_resources( $resources = [] ) {
    $html = '';
    $relationships = [ 'preconnect', 'dns-prefetch' ];
    if ( !empty( $resources ) && !empty( $relationships ) ) {
      foreach ( $relationships as $rel ) {
        foreach ( $resources as $resource ) {
          $html .= "<link rel='{$rel}' href='{$resource}'>";
        }
      }
    }
    return $html;
  }

  // --------------------------- Preload Fonts
  public function render_preload_fonts( $fonts = [] ) {
    $html = '';
    if ( $fonts ) {
      foreach ( $fonts as $font ) {
        $font_src = $this->get_theme_directory('assets') . "/fonts/{$font}.woff2";
        $html .= "<link rel='preload' href='{$font_src}' as='font' type='font/woff2' crossorigin>";
      }
    }
    return $html;
  }

  // --------------------------- SEO
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

  // --------------------------- SVG
  public function render_svg( $params = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'html' => '',
        'type' => 'burger'
      ],
      $params
    ));

    // ---------------------------------------- Template
    switch( $type ) {

      case 'brand.logo':
        $html = '<svg x="0px" y="0px" viewBox="0 0 96 62" style="enable-background:new 0 0 96 62;" xml:space="preserve">
          <style type="text/css">
	          .theme-colour-sky{fill:rgba(var(--theme-colour-sky),1);}
	          .theme-colour-powder{fill:rgba(var(--theme-colour-powder),1);}
          </style>
          <g>
	          <g>
		          <polygon class="theme-colour-sky" points="59.3,7.8 51.7,15.5 51.8,15.8 59.5,8"/>
		          <polygon class="theme-colour-sky" points="52,3.6 50.2,10.6 50.3,11.1 52.3,3.7"/>
		          <polygon class="theme-colour-sky" points="54.8,7.2 51.1,13.6 51.3,14.1 55.1,7.4"/>
		          <polygon class="theme-colour-sky" points="43.7,3.7 45.6,11.1 45.8,10.6 44,3.6"/>
		          <polygon class="theme-colour-sky" points="59.9,12.3 52.1,16.9 52.2,17.2 60.1,12.6"/>
		          <polygon class="theme-colour-sky" points="61.9,19.3 52.9,19.3 53,19.6 61.9,19.6"/>
		          <polygon class="theme-colour-sky" points="63.5,15 52.5,18 52.5,18 52.5,18 52.6,18.3 63.6,15.4"/>
		          <polygon class="theme-colour-sky" points="34.1,19.6 43,19.6 43.1,19.3 34.1,19.3"/>
		          <polygon class="theme-colour-sky" points="40.9,7.4 44.7,14.1 44.9,13.6 41.2,7.2"/>
		          <polygon class="theme-colour-sky" points="54.1,23.2 59.9,26.6 59.9,26.6 59.9,26.6 60.1,26.3 53.9,22.7"/>
		          <polygon class="theme-colour-sky" points="32.5,23.8 42.6,21 42.7,20.7 32.4,23.5"/>
		          <polygon class="theme-colour-sky" points="63.6,23.5 53.3,20.7 53.3,20.7 53.3,20.7 53.4,21 63.5,23.8 63.6,23.5 63.6,23.5"/>
		          <polygon class="theme-colour-sky" points="36.1,26.6 41.9,23.2 42.1,22.7 35.9,26.3"/>
		          <polygon class="theme-colour-sky" points="32.4,15.4 43.4,18.3 43.5,18 32.5,15"/>
		          <polygon class="theme-colour-sky" points="36.5,8 44.2,15.8 44.3,15.5 36.7,7.8"/>
		          <polygon class="theme-colour-sky" points="35.9,12.6 43.8,17.2 43.9,16.9 36.1,12.3"/>
	          </g>
	          <path class="theme-colour-sky" d="M43.8,17.4l-7.8-4.5l0.2-0.3l7.7,4.5L43.8,17.4z"/>
	        <g>
		      <path class="theme-colour-powder" d="M34.5,33.2h0.5L48,19.9l13.1,13.3h0.5l0,0c2.7-2.7,4.5-6.2,5.3-9.9c0.7-3.8,0.4-7.7-1.1-11.2
			      s-3.9-6.6-7.1-8.7C55.5,1.1,51.8,0,48,0c-3.8,0-7.5,1.1-10.7,3.3c-3.2,2.1-5.6,5.2-7.1,8.7c-1.5,3.6-1.8,7.5-1.1,11.2
			      C29.9,27,31.8,30.5,34.5,33.2z M36.5,30.6c-2.2-2.3-3.6-5.2-4.2-8.3c-0.6-3.1-0.2-6.3,1-9.2c1.2-2.9,3.3-5.4,5.9-7.1
			      c2.5-1.7,5.4-2.6,8.3-2.7l-7.2,23.4L36.5,30.6z M51.3,22.3l-3-3.1h0l0,0h0L48,19l-0.2,0.2h0l0,0h0l-1.3,1.3l-3.4,3.5l-1.7,1.7
			      L48,4.1l6.6,21.6L51.3,22.3z M55.7,26.7L48.5,3.3c3,0.1,5.9,1,8.3,2.7c2.6,1.7,4.7,4.2,5.9,7.1c1.2,2.9,1.6,6.1,1,9.2
			      c-0.6,3.1-2,6-4.2,8.3L55.7,26.7z M30.9,12.1c1.4-3.4,3.8-6.3,6.8-8.3c3-2,6.6-3.1,10.2-3.1h0c3.6,0,7.2,1.1,10.2,3.1
			      c3,2,5.4,4.9,6.8,8.3c1.4,3.4,1.8,7.1,1.2,10.7c-0.7,3.6-2.4,7-4.9,9.6L60,31.1c2.2-2.3,3.7-5.2,4.3-8.4c0.6-3.3,0.3-6.6-0.9-9.7
			      c-1.3-3.1-3.4-5.7-6.1-7.5c-2.7-1.8-5.9-2.8-9.2-2.8c-3.3,0-6.5,1-9.2,2.8C36,7.3,33.9,9.9,32.6,13c-1.3,3.1-1.6,6.5-0.9,9.7
			      c0.6,3.2,2.1,6.1,4.3,8.4l-1.4,1.4c-2.5-2.6-4.2-6-4.9-9.6C29.1,19.2,29.5,15.5,30.9,12.1z"/>
		      <path class="theme-colour-powder" d="M29.2,61.5c-0.8,0-1.3-0.7-1.3-1.7c0-1,0.5-1.7,1.3-1.7c0.3,0,0.5,0.1,0.7,0.2c0.2,0.2,0.3,0.4,0.4,0.7
			      l0.6-0.1c-0.2-0.9-0.8-1.4-1.7-1.4c-1.2,0-2,0.9-2,2.2c0,0.5,0.1,1.1,0.5,1.5c0.2,0.2,0.4,0.4,0.7,0.6c0.3,0.1,0.5,0.2,0.8,0.2
			      c0.3,0,0.6,0,0.8-0.2c0.3-0.1,0.5-0.3,0.6-0.6c0.2-0.3,0.3-0.6,0.4-1h-0.6C30.3,61.1,29.8,61.5,29.2,61.5z"/>
		      <path class="theme-colour-powder" d="M34.2,57.6c-1.2,0-2,0.9-2,2.2c0,1.3,0.9,2.2,2,2.2c1.2,0,2-0.9,2-2.2C36.3,58.5,35.4,57.6,34.2,57.6z
			       M34.2,61.5c-0.8,0-1.4-0.7-1.4-1.7c0-1,0.6-1.7,1.4-1.7c0.9,0,1.4,0.7,1.4,1.7C35.6,60.8,35.1,61.5,34.2,61.5z"/>
		      <polygon class="theme-colour-powder" points="38.3,57.7 37.7,57.7 37.7,62 40.5,62 40.5,61.4 38.3,61.4 		"/>
		      <polygon class="theme-colour-powder" points="42.4,57.7 41.8,57.7 41.8,62 44.6,62 44.6,61.4 42.4,61.4 		"/>
		      <polygon class="theme-colour-powder" points="46.4,60 48.6,60 48.6,59.5 46.4,59.5 46.4,58.2 48.8,58.2 48.8,57.7 45.8,57.7 45.8,62 48.9,62
			      48.9,61.4 46.4,61.4 		"/>
		      <path class="theme-colour-powder" d="M51.9,61.5c-0.8,0-1.3-0.7-1.3-1.7c0-1,0.5-1.7,1.3-1.7c0.3,0,0.5,0.1,0.7,0.2c0.2,0.2,0.3,0.4,0.4,0.7
			      l0.6-0.1c-0.2-0.9-0.8-1.4-1.7-1.4c-1.2,0-2,0.9-2,2.2c0,0.5,0.2,1.1,0.5,1.5c0.2,0.2,0.4,0.4,0.6,0.6c0.3,0.1,0.5,0.2,0.8,0.2
			      c0.3,0,0.6,0,0.8-0.2c0.3-0.1,0.5-0.3,0.6-0.6c0.2-0.3,0.3-0.6,0.4-1h-0.6C53,61.1,52.6,61.5,51.9,61.5z"/>
		      <polygon class="theme-colour-powder" points="54.6,58.2 55.9,58.2 55.9,62 56.5,62 56.5,58.2 57.9,58.2 57.9,57.7 54.6,57.7 		"/>
		      <rect x="59.1" y="57.7" class="theme-colour-powder" width="0.6" height="4.3"/>
		      <path class="theme-colour-powder" d="M63,60.5c-0.1,0.3-0.2,0.5-0.3,0.9c-0.1-0.5-0.2-0.6-0.3-0.9l-0.9-2.8h-0.6l1.5,4.3H63l1.5-4.3h-0.6L63,60.5z
			      "/>
		      <polygon class="theme-colour-powder" points="66.3,60 68.5,60 68.5,59.5 66.3,59.5 66.3,58.2 68.7,58.2 68.7,57.7 65.7,57.7 65.7,62 68.8,62
			      68.8,61.4 66.3,61.4 		"/>
		      <path class="theme-colour-powder" d="M29.9,51.2c-0.4,0.6-0.9,1-1.5,1.3H34c-0.6-0.3-1.2-0.7-1.5-1.3c-0.4-0.6-0.6-1.2-0.6-1.9l0-9.5
			      c0-0.7,0.2-1.4,0.6-1.9c0.4-0.6,0.9-1,1.5-1.3h-5.6c0.6,0.3,1.2,0.7,1.5,1.3c0.4,0.6,0.6,1.3,0.6,1.9l0,9.5
			      C30.5,50,30.3,50.7,29.9,51.2z"/>
		      <path class="theme-colour-powder" d="M52.8,37.8c0.4-0.5,0.9-1,1.5-1.2h-3.6l-6.3,13.2l-5.9-13.1l0-0.1h-3.8c0.6,0.2,1.1,0.6,1.5,1.2
			      c0.4,0.5,0.6,1.2,0.6,1.8v9.9c0,0.6-0.2,1.2-0.5,1.7c-0.3,0.5-0.8,0.9-1.3,1.2l-0.3,0.1h5.2l-0.3-0.1c-0.5-0.3-1-0.7-1.3-1.2
			      c-0.3-0.5-0.5-1.1-0.5-1.7v-9.7l6.1,12.8l6.5-13.4v10.4c0,0.6-0.2,1.2-0.5,1.7c-0.3,0.5-0.8,0.9-1.3,1.2l-0.3,0.1h6.1L54,52.4
			      c-0.5-0.3-1-0.7-1.3-1.2c-0.3-0.5-0.5-1.1-0.5-1.7v-9.9C52.2,39,52.4,38.3,52.8,37.8z"/>
		      <path class="theme-colour-powder" d="M9.5,36.3H6c0,0,2.4,0.9,1.8,2.3l0,0.1l-4.1,8.7C2.7,49.3,1.5,51,0,52.6h5.2l0,0c-0.5-0.5-0.9-1.1-1.1-1.8
			      C4,50,4.1,49.3,4.4,48.6l1-2.1v0.1h6.9l1.9,4l1.4-2L9.5,36.3z M5.6,46.1l3.2-6.8l3.3,6.8H5.6z"/>
		      <path class="theme-colour-powder" d="M27.5,48.7L27.5,48.7C27.5,48.7,27.5,48.7,27.5,48.7L27.5,48.7z"/>
		      <path class="theme-colour-powder" d="M27.5,52.6v-3.8c-0.4,0.8-1.1,1.5-1.9,2c-0.8,0.5-1.7,0.8-2.7,0.8h-5.1l9.7-14.9H15.9v2.9
			      c0.4-0.6,1-1.1,1.6-1.4c0.6-0.3,1.3-0.5,2-0.5h5.5l-9.7,14.9h3.1l0,0h0.3c0,0,0,0,0,0H27.5z"/>
		      <path class="theme-colour-powder" d="M94.5,51.6c-0.4-0.5-0.6-1.1-0.6-1.7V39.3c0-0.6,0.2-1.2,0.6-1.7c0.4-0.5,0.9-0.8,1.5-1h-6.1
			      c0.6,0.1,1.1,0.5,1.5,0.9c0.4,0.5,0.6,1.1,0.6,1.7v4.6h-8.2v-4.6c0-0.6,0.2-1.2,0.6-1.7c0.4-0.5,0.9-0.8,1.5-1l-10,0v0h-1.9v0
			      l-8.9,0c1.2,0.3,2,1.4,2.1,3c0,0,0,0,0,0v7.2c0,2.7-1.7,4.5-4.3,4.5c-2.7,0-4.5-1.8-4.5-4.5v-7.4c0-0.6,0.2-1.2,0.6-1.7
			      c0.4-0.5,0.9-0.9,1.5-1h-6c0.6,0.2,1.1,0.5,1.5,1c0.4,0.5,0.6,1.1,0.6,1.7v7.8c0,3.7,2.3,5.9,6.1,5.9c3.6,0,5.8-2.3,5.8-6.1v-7.4
			      l0-0.2l0,0c0-2,2.2-2,3.7-2l1.9,0v12.4c0,0.6-0.2,1.2-0.6,1.7c-0.4,0.5-0.9,0.9-1.5,1h6.1c-0.2,0-0.3-0.1-0.4-0.2
			      c0.1,0.1,0.3,0.1,0.4,0.2h0c-0.6-0.2-1.1-0.5-1.5-1c-0.1-0.1-0.1-0.2-0.2-0.3c-0.3-0.4-0.4-0.9-0.4-1.5V37.3H78
			      c1.5,0,3.9,0,3.9,1.9v10.6c0,0.6-0.2,1.2-0.6,1.7c-0.4,0.5-0.9,0.8-1.5,1l0,0h6.1c-0.6-0.2-1.1-0.5-1.5-1
			      c-0.4-0.5-0.6-1.1-0.6-1.7v-5H92v5c0,0.6-0.2,1.2-0.6,1.7c-0.4,0.5-0.9,0.8-1.5,1h6C95.4,52.4,94.9,52.1,94.5,51.6z"/>
	          </g>
          </g>
        </svg>';
      break;

      case 'brand.monogram':
        $html = '<svg x="0px" y="0px" viewBox="0 0 71.7 62" style="enable-background:new 0 0 71.7 62;" xml:space="preserve">
          <style type="text/css">
	          .theme-colour-sky{fill:rgba(var(--theme-colour-sky),1);}
	          .theme-colour-powder{fill:rgba(var(--theme-colour-powder),1);}
          </style>
          <g>
	          <g>
		          <polygon class="theme-colour-sky" points="56.9,14.5 42.7,28.9 42.9,29.5 57.3,15 		"/>
		          <polygon class="theme-colour-sky" points="43.3,6.8 39.9,19.7 40.2,20.8 43.9,6.9 		"/>
		          <polygon class="theme-colour-sky" points="48.5,13.5 41.7,25.5 41.9,26.3 49,13.8 		"/>
		          <polygon class="theme-colour-sky" points="27.8,6.9 31.4,20.8 31.8,19.7 28.3,6.8 		"/>
		          <polygon class="theme-colour-sky" points="58.1,23 43.5,31.5 43.7,32.1 58.4,23.5 		"/>
		          <polygon class="theme-colour-sky" points="61.7,36 44.9,36 45.1,36.6 61.7,36.6 		"/>
		          <polygon class="theme-colour-sky" points="64.8,28.1 44.2,33.7 44.2,33.7 44.2,33.7 44.4,34.3 65,28.7 		"/>
		          <polygon class="theme-colour-sky" points="9.9,36.6 26.6,36.6 26.8,36 9.9,36 		"/>
		          <polygon class="theme-colour-sky" points="22.6,13.8 29.8,26.3 30,25.5 23.1,13.5 		"/>
		          <polygon class="theme-colour-sky" points="47.1,43.3 58.1,49.7 58.1,49.7 58.1,49.7 58.4,49.2 46.9,42.4 		"/>
		          <polygon class="theme-colour-sky" points="6.9,44.4 25.7,39.3 26,38.6 6.7,43.9 		"/>
		          <polygon class="theme-colour-sky" points="65,43.9 45.7,38.6 45.7,38.7 45.7,38.6 45.9,39.3 64.8,44.4 65,43.9 65,43.9 		"/>
		          <polygon class="theme-colour-sky" points="13.6,49.7 24.5,43.3 24.8,42.4 13.3,49.2 		"/>
		          <polygon class="theme-colour-sky" points="6.7,28.7 27.3,34.3 27.5,33.7 6.9,28.1 		"/>
		          <polygon class="theme-colour-sky" points="14.4,15 28.8,29.5 29,28.9 14.8,14.5 		"/>
		          <polygon class="theme-colour-sky" points="13.3,23.5 28,32.1 28.2,31.5 13.6,23 		"/>
	          </g>
	          <path class="st0" d="M28.1,32.4l-14.5-8.5l0.3-0.5l14.4,8.4L28.1,32.4z"/>
	          <g>
		          <path class="theme-colour-powder" d="M10.5,62h0.9l24.4-24.8L60.3,62h0.9l0,0c5-5.1,8.4-11.5,9.8-18.6c1.4-7,0.7-14.3-2-21
			          c-2.7-6.6-7.3-12.3-13.2-16.3C49.9,2.1,42.9,0,35.8,0s-14,2.1-19.9,6.1c-5.9,4-10.5,9.7-13.2,16.3c-2.7,6.6-3.4,13.9-2,21
			          C2.1,50.4,5.5,56.9,10.5,62z M14.3,57.1c-4-4.3-6.8-9.6-7.8-15.4c-1.1-5.8-0.4-11.8,1.9-17.2c2.3-5.4,6.1-10.1,11-13.3
			          C24,8,29.4,6.3,35,6.2L21.6,49.8L14.3,57.1z M41.9,41.6l-5.7-5.7h0l0,0h0l-0.4-0.4l-0.4,0.4h0l0,0h0L33,38.2l-6.4,6.5l-3.2,3.2
			          L35.8,7.6l12.4,40.3L41.9,41.6z M50.1,49.9L36.7,6.2c5.5,0.2,10.9,1.9,15.6,5c4.9,3.3,8.7,7.9,11,13.3c2.3,5.4,3,11.4,1.9,17.2
			          c-1.1,5.8-3.8,11.2-7.8,15.4L50.1,49.9z M4,22.6c2.7-6.3,7.1-11.7,12.8-15.5C22.4,3.3,29,1.3,35.8,1.3h0c6.8,0,13.4,2,19.1,5.8
			          c5.7,3.8,10.1,9.2,12.8,15.5c2.7,6.3,3.4,13.3,2.2,20.1c-1.2,6.8-4.4,13-9.1,17.9L58.2,58c4.1-4.3,6.9-9.7,8.1-15.6
			          c1.2-6.1,0.6-12.4-1.8-18.1c-2.3-5.7-6.3-10.6-11.4-14.1C48,6.7,42,4.9,35.8,4.9c-6.1,0-12.1,1.8-17.2,5.3
			          c-5.1,3.5-9.1,8.4-11.4,14.1c-2.3,5.7-3,12.1-1.8,18.1c1.2,5.9,4,11.3,8.1,15.7L11,60.6C6.2,55.7,3,49.4,1.8,42.7
			          C0.6,35.9,1.3,28.9,4,22.6z"/>
	          </g>
          </g>
        </svg>';
      break;

      case 'icon.arrow':
        $html = '<svg x="0px" y="0px" viewBox="0 0 50 93" style="enable-background:new 0 0 50 93;" xml:space="preserve">
          <polygon points="3.4,93 0,89.6 43.2,46.5 0,3.4 3.4,0 50,46.5 "/>
        </svg>';
        break;

      case 'icon.circle':
        $html = '<svg x="0px" y="0px" viewBox="0 0 28 28" style="enable-background:new 0 0 28 28;" xml:space="preserve">
          <g>
	          <circle cx="14" cy="14" r="7"/>
	          <path d="M14,0C6.3,0,0,6.3,0,14s6.3,14,14,14s14-6.3,14-14S21.7,0,14,0z M14,27C6.8,27,1,21.2,1,14C1,6.8,6.8,1,14,1
		          c7.2,0,13,5.8,13,13C27,21.2,21.2,27,14,27z"/>
          </g>
        </svg>';
        break;

      case 'icon.email':
        $html = '<svg x="0px" y="0px" viewBox="0 0 25 20" style="enable-background:new 0 0 25 20;" xml:space="preserve">
          <path d="M21.8,0H3.2C1.5,0,0,1.4,0,3.2v13.6C0,18.6,1.5,20,3.2,20h18.5c1.8,0,3.2-1.4,3.2-3.2V3.2C25,1.4,23.5,0,21.8,0z M3.2,1.8
	        h18.5c0.6,0,1.1,0.4,1.3,1L12.5,10L1.9,2.8C2.1,2.2,2.6,1.8,3.2,1.8z M21.8,18.2H3.2c-0.8,0-1.4-0.6-1.4-1.4V4.9l10.1,7
	        c0.2,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2l10.1-7v11.9C23.2,17.6,22.5,18.2,21.8,18.2z"/>
        </svg>';
        break;

      case 'icon.facebook':
        $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 8.6 16" style="enable-background:new 0 0 8.6 16;" xml:space="preserve">
          <path d="M8.6,0H6.3c-1,0-2,0.4-2.8,1.2C2.8,1.9,2.3,2.9,2.3,4v2.4H0v3.2h2.3V16h3.1V9.6h2.3l0.8-3.2H5.5V4c0-0.2,0.1-0.4,0.2-0.6C5.8,3.3,6,3.2,6.3,3.2h2.3V0z"/>
        </svg>';
        break;

      case 'icon.houzz':
        $html = '<svg width="95" height="94" viewBox="0 0 95 94" xmlns="http://www.w3.org/2000/svg">
          <path d="M85.2111 0H10.0137C7.52074 0 5.12989 0.990347 3.36711 2.75319C1.60433 4.51603 0.614014 6.90697 0.614014 9.4V84.6C0.614014 87.093 1.60433 89.484 3.36711 91.2468C5.12989 93.0096 7.52074 94 10.0137 94H85.2111C87.7041 94 90.0949 93.0096 91.8577 91.2468C93.6205 89.484 94.6108 87.093 94.6108 84.6V9.4C94.6108 6.90697 93.6205 4.51603 91.8577 2.75319C90.0949 0.990347 87.7041 0 85.2111 0ZM74.9185 77.3385H54.7091V57.105H40.6096V77.3385H20.3768V16.6615H34.4764V30.7615L74.9185 42.4175V77.3385Z"/>
        </svg>';
        break;

      case 'icon.instagram':
        $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
          <g>
            <path d="M8,3.6c-0.9,0-1.7,0.3-2.4,0.7C4.8,4.8,4.3,5.5,3.9,6.3C3.6,7.1,3.5,7.9,3.7,8.8c0.2,0.8,0.6,1.6,1.2,2.2
	            c0.6,0.6,1.4,1,2.3,1.2c0.9,0.2,1.7,0.1,2.5-0.2c0.8-0.3,1.5-0.9,2-1.6c0.5-0.7,0.7-1.5,0.7-2.4c0-1.1-0.5-2.2-1.3-3
	            C10.3,4.1,9.2,3.6,8,3.6z M10,9.9c-0.5,0.5-1.3,0.8-2,0.8c-0.6,0-1.1-0.2-1.6-0.5C5.9,10,5.5,9.5,5.3,9S5.1,7.9,5.2,7.4
	            c0.1-0.5,0.4-1,0.8-1.4c0.4-0.4,0.9-0.7,1.5-0.8s1.1-0.1,1.7,0.2c0.5,0.2,1,0.6,1.3,1c0.3,0.5,0.5,1,0.5,1.6
	            C10.9,8.7,10.6,9.4,10,9.9z"/>
            <path d="M12.5,2.6c-0.6,0-1,0.5-1,1c0,0.6,0.5,1,1,1c0.6,0,1-0.5,1-1C13.5,3,13.1,2.6,12.5,2.6z"/>
            <path d="M11.3,0H4.7C2.1,0,0,2,0,4.5v6.9C0,13.9,2.1,16,4.7,16h6.7c2.6,0,4.6-2,4.6-4.5V4.5C16,2,13.9,0,11.3,0z
	             M14.7,11.5c0,0.9-0.4,1.7-1,2.3c-0.6,0.6-1.5,1-2.4,1H4.6c-0.9,0-1.8-0.4-2.4-1c-0.6-0.6-1-1.5-1-2.3V4.5c0-0.9,0.4-1.7,1-2.3
	            s1.5-1,2.4-1h6.7c0.9,0,1.8,0.4,2.4,1c0.6,0.6,1,1.5,1,2.3V11.5z"/>
          </g>
        </svg>';
        break;

      case 'icon.linkedin':
        $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
          <path d="M18,0H2C1.8,0,1.5,0.1,1.2,0.2C1,0.3,0.8,0.4,0.6,0.6C0.4,0.8,0.3,1,0.2,1.2C0.1,1.5,0,1.7,0,2v16c0,0.3,0.1,0.5,0.2,0.8
	        c0.1,0.2,0.2,0.5,0.4,0.6c0.2,0.2,0.4,0.3,0.7,0.4C1.5,19.9,1.8,20,2,20h16c0.3,0,0.5-0.1,0.8-0.2c0.2-0.1,0.5-0.2,0.7-0.4
	        c0.2-0.2,0.3-0.4,0.4-0.6c0.1-0.2,0.2-0.5,0.2-0.8V2c0-0.5-0.2-1-0.6-1.4C19,0.2,18.5,0,18,0z M6.4,15.8H3.7V7.3h2.7V15.8z M5.1,6.2
	        c-0.9,0-1.4-0.6-1.4-1.3c0-0.8,0.6-1.3,1.4-1.3c0.9,0,1.4,0.6,1.4,1.3C6.5,5.6,6,6.2,5.1,6.2z M16.2,15.8h-2.7v-4.7
	        c0-1.1-0.4-1.9-1.4-1.9c-0.7,0-1.2,0.5-1.4,1c-0.1,0.2-0.1,0.4-0.1,0.7v4.9H8V10C8,8.9,8,8,8,7.3h2.3l0.1,1.2h0.1
	        c0.3-0.6,1.2-1.4,2.7-1.4c1.8,0,3.1,1.2,3.1,3.7L16.2,15.8z"/>
        </svg>';
        break;

      case 'icon.minus':
        $html = '<svg x="0px" y="0px" viewBox="0 0 15 15" style="enable-background:new 0 0 15 15;" xml:space="preserve">
	        <rect y="7" width="15" height="1"/>
        </svg>';
        break;

      case 'icon.pinterest':
        $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
          <g>
	          <path d="M8,0C6.2,0,4.3,0.7,2.9,1.8C1.5,3,0.5,4.7,0.2,6.5c-0.3,1.8,0,3.7,0.9,5.4c0.9,1.6,2.3,2.9,4.1,3.6c-0.1-0.6-0.1-1.6,0-2.3
		          c0.1-0.6,0.9-4,0.9-4C5.9,8.8,5.8,8.4,5.8,8C5.8,6.9,6.5,6,7.3,6c0.7,0,1,0.5,1,1.1c0,0.7-0.4,1.7-0.7,2.7
		          c-0.2,0.8,0.4,1.4,1.2,1.4c1.4,0,2.5-1.5,2.5-3.7c0-1.9-1.4-3.2-3.3-3.2c-2.3,0-3.6,1.7-3.6,3.5c0,0.7,0.3,1.4,0.6,1.8
		          c0,0,0,0.1,0.1,0.1c0,0,0,0.1,0,0.1c-0.1,0.3-0.2,0.8-0.2,0.9c0,0.1-0.1,0.2-0.3,0.1c-1-0.5-1.6-1.9-1.6-3.1C2.9,5.3,4.7,3,8.2,3
		          c2.8,0,4.9,2,4.9,4.6c0,2.8-1.7,5-4.1,5c-0.8,0-1.6-0.4-1.8-0.9c0,0-0.4,1.5-0.5,1.9c-0.2,0.7-0.7,1.6-1,2.1
		          C6.7,16,7.9,16.1,9,15.9c1.1-0.1,2.2-0.5,3.2-1.2s1.8-1.4,2.4-2.4c0.6-1,1-2,1.2-3.2C16.1,8,16,6.9,15.7,5.8
		          c-0.3-1.1-0.9-2.1-1.6-3c-0.7-0.9-1.7-1.6-2.7-2.1C10.3,0.3,9.2,0,8,0z"/>
          </g>
        </svg>';
        break;

      case 'icon.plus':
        $html = '<svg x="0px" y="0px" viewBox="0 0 15 15" style="enable-background:new 0 0 15 15;" xml:space="preserve">
	        <polygon points="15,7 8,7 8,0 7,0 7,7 0,7 0,8 7,8 7,15 8,15 8,8 15,8 "/>
        </svg>';
        break;

      case 'icon.twitter':
        $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 19.7 16" style="enable-background:new 0 0 19.7 16;" xml:space="preserve">
          <path d="M19.7,0c-0.9,0.6-1.8,1.1-2.8,1.4c-0.5-0.6-1.3-1.1-2-1.3C14-0.1,13.2,0,12.4,0.3c-0.8,0.3-1.4,0.8-1.9,1.5C10.1,2.4,9.8,3.2,9.8,4v0.9c-1.6,0-3.1-0.3-4.5-1c-1.4-0.7-2.6-1.7-3.5-3c0,0-3.6,8,4.5,11.6c-1.8,1.2-4,1.9-6.3,1.8c8,4.5,17.9,0,17.9-10.2c0-0.2,0-0.5-0.1-0.7C18.7,2.4,19.4,1.2,19.7,0z"/>
          </svg>';
        break;

    }

    return $html;

  }

  // ---------------------------------------- SVG Icon
  public function render_svg_icon( $type = "" ) {
    switch ( $type ) {
       case 'arrow': {
        $html = '<svg x="0px" y="0px" viewBox="0 0 50 93" style="enable-background:new 0 0 50 93;" xml:space="preserve">
          <polygon points="3.4,93 0,89.6 43.2,46.5 0,3.4 3.4,0 50,46.5"/>
        </svg>';
        break;
      }
      case 'circle': {
        $html = '<svg x="0px" y="0px" viewBox="0 0 28 28" style="enable-background:new 0 0 28 28;" xml:space="preserve">
          <g>
	          <circle cx="14" cy="14" r="7"/>
	          <path d="M14,0C6.3,0,0,6.3,0,14s6.3,14,14,14s14-6.3,14-14S21.7,0,14,0z M14,27C6.8,27,1,21.2,1,14C1,6.8,6.8,1,14,1
		          c7.2,0,13,5.8,13,13C27,21.2,21.2,27,14,27z"/>
          </g>
        </svg>';
        break;
      }
      case 'email': {
        $html = '<svg x="0px" y="0px" viewBox="0 0 25 20" style="enable-background:new 0 0 25 20;" xml:space="preserve">
          <path d="M21.8,0H3.2C1.5,0,0,1.4,0,3.2v13.6C0,18.6,1.5,20,3.2,20h18.5c1.8,0,3.2-1.4,3.2-3.2V3.2C25,1.4,23.5,0,21.8,0z M3.2,1.8
	        h18.5c0.6,0,1.1,0.4,1.3,1L12.5,10L1.9,2.8C2.1,2.2,2.6,1.8,3.2,1.8z M21.8,18.2H3.2c-0.8,0-1.4-0.6-1.4-1.4V4.9l10.1,7
	        c0.2,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2l10.1-7v11.9C23.2,17.6,22.5,18.2,21.8,18.2z"/>
        </svg>';
        break;
      }
      case 'facebook': {
        $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 8.6 16" style="enable-background:new 0 0 8.6 16;" xml:space="preserve">
          <path d="M8.6,0H6.3c-1,0-2,0.4-2.8,1.2C2.8,1.9,2.3,2.9,2.3,4v2.4H0v3.2h2.3V16h3.1V9.6h2.3l0.8-3.2H5.5V4c0-0.2,0.1-0.4,0.2-0.6C5.8,3.3,6,3.2,6.3,3.2h2.3V0z"/>
        </svg>';
        break;
      }
      case 'houzz': {
        $html = '<svg width="95" height="94" viewBox="0 0 95 94" xmlns="http://www.w3.org/2000/svg">
          <path d="M85.2111 0H10.0137C7.52074 0 5.12989 0.990347 3.36711 2.75319C1.60433 4.51603 0.614014 6.90697 0.614014 9.4V84.6C0.614014 87.093 1.60433 89.484 3.36711 91.2468C5.12989 93.0096 7.52074 94 10.0137 94H85.2111C87.7041 94 90.0949 93.0096 91.8577 91.2468C93.6205 89.484 94.6108 87.093 94.6108 84.6V9.4C94.6108 6.90697 93.6205 4.51603 91.8577 2.75319C90.0949 0.990347 87.7041 0 85.2111 0ZM74.9185 77.3385H54.7091V57.105H40.6096V77.3385H20.3768V16.6615H34.4764V30.7615L74.9185 42.4175V77.3385Z"/>
        </svg>';
        break;
      }
      case 'instagram': {
        $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
          <g>
            <path d="M8,3.6c-0.9,0-1.7,0.3-2.4,0.7C4.8,4.8,4.3,5.5,3.9,6.3C3.6,7.1,3.5,7.9,3.7,8.8c0.2,0.8,0.6,1.6,1.2,2.2
	            c0.6,0.6,1.4,1,2.3,1.2c0.9,0.2,1.7,0.1,2.5-0.2c0.8-0.3,1.5-0.9,2-1.6c0.5-0.7,0.7-1.5,0.7-2.4c0-1.1-0.5-2.2-1.3-3
	            C10.3,4.1,9.2,3.6,8,3.6z M10,9.9c-0.5,0.5-1.3,0.8-2,0.8c-0.6,0-1.1-0.2-1.6-0.5C5.9,10,5.5,9.5,5.3,9S5.1,7.9,5.2,7.4
	            c0.1-0.5,0.4-1,0.8-1.4c0.4-0.4,0.9-0.7,1.5-0.8s1.1-0.1,1.7,0.2c0.5,0.2,1,0.6,1.3,1c0.3,0.5,0.5,1,0.5,1.6
	            C10.9,8.7,10.6,9.4,10,9.9z"/>
            <path d="M12.5,2.6c-0.6,0-1,0.5-1,1c0,0.6,0.5,1,1,1c0.6,0,1-0.5,1-1C13.5,3,13.1,2.6,12.5,2.6z"/>
            <path d="M11.3,0H4.7C2.1,0,0,2,0,4.5v6.9C0,13.9,2.1,16,4.7,16h6.7c2.6,0,4.6-2,4.6-4.5V4.5C16,2,13.9,0,11.3,0z
	             M14.7,11.5c0,0.9-0.4,1.7-1,2.3c-0.6,0.6-1.5,1-2.4,1H4.6c-0.9,0-1.8-0.4-2.4-1c-0.6-0.6-1-1.5-1-2.3V4.5c0-0.9,0.4-1.7,1-2.3
	            s1.5-1,2.4-1h6.7c0.9,0,1.8,0.4,2.4,1c0.6,0.6,1,1.5,1,2.3V11.5z"/>
          </g>
        </svg>';
        break;
      }
      case 'linkedin': {
        $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
          <path d="M18,0H2C1.8,0,1.5,0.1,1.2,0.2C1,0.3,0.8,0.4,0.6,0.6C0.4,0.8,0.3,1,0.2,1.2C0.1,1.5,0,1.7,0,2v16c0,0.3,0.1,0.5,0.2,0.8
	        c0.1,0.2,0.2,0.5,0.4,0.6c0.2,0.2,0.4,0.3,0.7,0.4C1.5,19.9,1.8,20,2,20h16c0.3,0,0.5-0.1,0.8-0.2c0.2-0.1,0.5-0.2,0.7-0.4
	        c0.2-0.2,0.3-0.4,0.4-0.6c0.1-0.2,0.2-0.5,0.2-0.8V2c0-0.5-0.2-1-0.6-1.4C19,0.2,18.5,0,18,0z M6.4,15.8H3.7V7.3h2.7V15.8z M5.1,6.2
	        c-0.9,0-1.4-0.6-1.4-1.3c0-0.8,0.6-1.3,1.4-1.3c0.9,0,1.4,0.6,1.4,1.3C6.5,5.6,6,6.2,5.1,6.2z M16.2,15.8h-2.7v-4.7
	        c0-1.1-0.4-1.9-1.4-1.9c-0.7,0-1.2,0.5-1.4,1c-0.1,0.2-0.1,0.4-0.1,0.7v4.9H8V10C8,8.9,8,8,8,7.3h2.3l0.1,1.2h0.1
	        c0.3-0.6,1.2-1.4,2.7-1.4c1.8,0,3.1,1.2,3.1,3.7L16.2,15.8z"/>
        </svg>';
        break;
      }
      case 'minus': {
        $html = '<svg x="0px" y="0px" viewBox="0 0 15 15" style="enable-background:new 0 0 15 15;" xml:space="preserve">
	        <rect y="7" width="15" height="1"/>
        </svg>';
        break;
      }
      case 'pinterest': {
        $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
          <g>
	          <path d="M8,0C6.2,0,4.3,0.7,2.9,1.8C1.5,3,0.5,4.7,0.2,6.5c-0.3,1.8,0,3.7,0.9,5.4c0.9,1.6,2.3,2.9,4.1,3.6c-0.1-0.6-0.1-1.6,0-2.3
		          c0.1-0.6,0.9-4,0.9-4C5.9,8.8,5.8,8.4,5.8,8C5.8,6.9,6.5,6,7.3,6c0.7,0,1,0.5,1,1.1c0,0.7-0.4,1.7-0.7,2.7
		          c-0.2,0.8,0.4,1.4,1.2,1.4c1.4,0,2.5-1.5,2.5-3.7c0-1.9-1.4-3.2-3.3-3.2c-2.3,0-3.6,1.7-3.6,3.5c0,0.7,0.3,1.4,0.6,1.8
		          c0,0,0,0.1,0.1,0.1c0,0,0,0.1,0,0.1c-0.1,0.3-0.2,0.8-0.2,0.9c0,0.1-0.1,0.2-0.3,0.1c-1-0.5-1.6-1.9-1.6-3.1C2.9,5.3,4.7,3,8.2,3
		          c2.8,0,4.9,2,4.9,4.6c0,2.8-1.7,5-4.1,5c-0.8,0-1.6-0.4-1.8-0.9c0,0-0.4,1.5-0.5,1.9c-0.2,0.7-0.7,1.6-1,2.1
		          C6.7,16,7.9,16.1,9,15.9c1.1-0.1,2.2-0.5,3.2-1.2s1.8-1.4,2.4-2.4c0.6-1,1-2,1.2-3.2C16.1,8,16,6.9,15.7,5.8
		          c-0.3-1.1-0.9-2.1-1.6-3c-0.7-0.9-1.7-1.6-2.7-2.1C10.3,0.3,9.2,0,8,0z"/>
          </g>
        </svg>';
        break;
      }
      case 'plus': {
        $html = '<svg x="0px" y="0px" viewBox="0 0 15 15" style="enable-background:new 0 0 15 15;" xml:space="preserve">
	        <polygon points="15,7 8,7 8,0 7,0 7,7 0,7 0,8 7,8 7,15 8,15 8,8 15,8 "/>
        </svg>';
        break;
      }
      case 'twitter': {
        $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 19.7 16" style="enable-background:new 0 0 19.7 16;" xml:space="preserve">
          <path d="M19.7,0c-0.9,0.6-1.8,1.1-2.8,1.4c-0.5-0.6-1.3-1.1-2-1.3C14-0.1,13.2,0,12.4,0.3c-0.8,0.3-1.4,0.8-1.9,1.5C10.1,2.4,9.8,3.2,9.8,4v0.9c-1.6,0-3.1-0.3-4.5-1c-1.4-0.7-2.6-1.7-3.5-3c0,0-3.6,8,4.5,11.6c-1.8,1.2-4,1.9-6.3,1.8c8,4.5,17.9,0,17.9-10.2c0-0.2,0-0.5-0.1-0.7C18.7,2.4,19.4,1.2,19.7,0z"/>
          </svg>';
        break;
      }
      default: {
        $html = "";
      }
    }
    return $html;
  }

  /*
  //////////////////////////////////////////////////////////
  ////  Constructor
  //////////////////////////////////////////////////////////
  */

  public function __construct() {}

}
