<?php

  /**
  *
  *   Community
  *
  */

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Block
  $block_name = "community";
  $block_classes = "{$block_name} block block--{$block_name}";
  $block_data = $block["data"] ?? [];
  $block_id = isset($block["id"]) && !empty($block["id"]) ? "{$block_name}--{$block["id"]}" : $block_name;

  // ---------------------------------------- AOS
  $aos_id = $block_id;
  $aos_delay = 250;
  $aos_increment = 250;

  // ---------------------------------------- Content (ACF)
  $background_colour = get_field("background_colour") ?: "";
  $background_image = get_field("background_image") ?: [];
  $background_image_lazy = $THEME->render_lazyload_image([ "image" => $background_image ]);
  $banner_image = get_field("banner_image") ?: [];
  $banner_image_aspect_ratio = get_field("banner_image_ratio") ?: "";
  $banner_image_lazy = $THEME->render_lazyload_image([ "image" => $banner_image ]);
  $cols = get_field("cols") ?: "col-12";
  $container = get_field("container") ?: "container";
  $enable = get_field("enable") ?: false;
  $heading = get_field("heading") ?: "";
  $organizations = get_field("organizations") ?: [];
  $organizations_glider = count($organizations) > 1 ? true : false;
  $organizations_glider_id = $THEME->get_unique_id("{$block_name}-glider--");
  $message = get_field("message") ?: "";
  $padding_bottom = get_field("padding_bottom") ?: 0;
  $padding_top = get_field("padding_top") ?: 0;
  $subheading = get_field("subheading") ?: "";
  $text_colour = get_field("text_colour") ?: "";

?>

<?php if ( $enable ) : ?>

    <style data-block-id="<?= $block_name; ?>">
      <?=
        $THEME->render_element_styles([
          "id" => $block_id,
          "background_colour" => $background_colour,
          "text_colour" => $text_colour,
        ]);
      ?>
      #<?= $block_id; ?> .<?= $block_name; ?>__main {
        padding-top: calc(<?= $padding_top; ?>px * 0.75);
        padding-bottom: calc(<?= $padding_bottom; ?>px  * 0.75);
      }
      @media screen and (min-width: 992px) {
        #<?= $block_id; ?> .<?= $block_name; ?>__main {
          padding-top: <?= $padding_top; ?>px;
          padding-bottom: <?= $padding_bottom; ?>px;
        }
      }
    </style>

  <section class="<?= esc_attr( $block_classes ); ?>" id="<?= esc_attr( $block_id ); ?>" data-scroll-to-target="#<?= $block_name; ?>">
    <div class="block__anchor" id="<?= $block_name; ?>-anchor"></div>

    <?php if ( $banner_image_lazy ) : ?>
      <div class="<?= $block_name; ?>__banner<?= $banner_image_aspect_ratio ? " with-ratio" : ""; ?>">
        <div class="<?= $block_name; ?>__banner-image"><?= $banner_image_lazy; ?></div>
        <div class="<?= $block_name; ?>__banner-strokes strokes">
          <span class="strokes__hr"></span>
          <div class="strokes__grid">
            <div class="strokes__grid-item"></div>
            <div class="strokes__grid-item"></div>
            <div class="strokes__grid-item"></div>
            <div class="strokes__grid-item">
              <span class="strokes__vr"></span>
              <span class="strokes__vr-angled-left"></span>
              <span class="strokes__vr-angled-right"></span>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if ( $heading || $message || $subheading ) : ?>
      <div class="<?= $block_name; ?>__main">

        <?php if ( $background_image_lazy ) : ?>
          <div class="<?= $block_name; ?>__background-image"><?= $background_image_lazy; ?></div>
        <?php endif; ?>

        <div class="<?= $block_name; ?>__strokes strokes d-none d-lg-block">
          <div class="strokes__grid">
            <div class="strokes__grid-item">
              <span class="strokes__vr"></span>
            </div>
            <div class="strokes__grid-item">
              <span class="strokes__vr"></span>
            </div>
            <div class="strokes__grid-item">
              <span class="strokes__vr"></span>
            </div>
            <div class="strokes__grid-item">
              <span class="strokes__vr"></span>
            </div>
          </div>
        </div>

        <div class="<?= $block_name; ?>__content">

          <?php if ( $heading ) : ?>
            <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-left" ]); $aos_delay += $aos_increment; ?>
            <h2 class="<?= $block_name; ?>__heading heading--2" <?= $aos_attrs; ?>><?= $heading; ?></h2>
          <?php endif; ?>
          <?php if ( $message ) : ?>
            <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-left" ]); $aos_delay += $aos_increment; ?>
            <div class="<?= $block_name; ?>__message body-copy--primary" <?= $aos_attrs; ?>><?= $message; ?></div>
          <?php endif; ?>

          <?php if ( !empty($organizations) ) : ?>
            <div class="<?= $block_name; ?>__organizations">

              <?php if ( $subheading ) : ?>
                <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-left" ]); $aos_delay += $aos_increment; ?>
                <h2 class="<?= $block_name; ?>__subheading heading--2" <?= $aos_attrs; ?>><?= $subheading; ?></h2>
              <?php endif; ?>

              <?php if ( $organizations_glider ) : ?>
                <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-up" ]); ?>
                <div class="<?= $block_name; ?>__organizations-glider d-block d-lg-none" <?= $aos_attrs; ?>>
                  <div class="glide js--glide" id="<?= $organizations_glider_id; ?>" data-glide-style="<?= $block_name; ?>">
                    <div class="glide__track" data-glide-el="track">
                      <ul class="glide__slides">
                        <?php
                          foreach ( $organizations as $i => $item ) {
                            echo "<li class='glide__slide'>";
                              echo $THEME->render_organization_item($item);
                            echo "</li>";
                          }
                        ?>
                      </ul>
                    </div>

                    <div class="glide__bullets" data-glide-el="controls[nav]">
                      <?php
                        foreach ( $organizations as $j => $item ) {
                          echo "<button class='glide__bullet' type='button' data-glide-dir='={$j}'></button>";
                        }
                      ?>
                    </div>

                  </div>
                </div>
              <?php endif; ?>

              <div class="<?= $block_name; ?>__organizations-grid d-none d-lg-grid" <?= $aos_attrs; ?>>
                <?php
                  foreach ( $organizations as $i => $item ) {
                    echo $THEME->render_organization_item($item);
                  }
                ?>
              </div>

            </div>
          <?php endif; ?>
        </div>

      </div>
    <?php endif; ?>

  </section>
<?php endif; ?>
