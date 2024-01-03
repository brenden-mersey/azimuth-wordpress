<?php

  /**
  *
  *   Team
  *
  */

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Block
  $block_name = "team";
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
  $cols = get_field("cols") ?: "col-12";
  $container = get_field("container") ?: "container";
  $enable = get_field("enable") ?: false;
  $padding_bottom = get_field("padding_bottom") ?: 0;
  $padding_top = get_field("padding_top") ?: 0;
  $team_listing = get_field("team_listing") ?: [];
  $text_colour = get_field("text_colour") ?: "";

?>

<?php if ( $enable ) : ?>

  <style data-block-id="<?= $block_name; ?>">
    <?=
      $THEME->render_element_styles([
        "background_colour" => $background_colour,
        "id" => $block_id,
        "padding_bottom" => $padding_bottom,
        "padding_top" => $padding_top,
        "text_colour" => $text_colour,
      ]);
    ?>
  </style>

  <section class="<?= esc_attr( $block_classes ); ?>" id="<?= esc_attr( $block_id ); ?>" data-scroll-to-target="#<?= $block_name; ?>">
    <div class="block__anchor" id="<?= $block_name; ?>-anchor"></div>

    <?php if ( $background_image_lazy ) : ?>
      <div class="<?= $block_name; ?>__background-image"><?= $background_image_lazy; ?></div>
    <?php endif; ?>

    <?php if ( !empty($team_listing) ) : ?>
      <div class="<?= $block_name; ?>__main">
        <?= $THEME->render_bs_container( "open", $cols, $container ); ?>
          <div class="<?= $block_name; ?>__listing">
            <?php foreach ( $team_listing as $group ) : ?>

              <?php
                $group_heading = $group["heading"] ?? "";
                $group_members = $group["members"] ?? [];
              ?>

              <?php if ( $group_heading && !empty($group_members) ) : ?>
               <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-up" ]); $aos_delay += $aos_increment; ?>
                <div class="<?= $block_name; ?>__group" <?= $aos_attrs; ?>>
                  <?php if ( $group_heading ) : ?>
                    <h2 class="<?= $block_name; ?>__group-heading heading--2"><?= $group_heading; ?></h2>
                  <?php endif; ?>
                  <?php if ( !empty($group_members) ) : ?>
                    <div class="<?= $block_name; ?>__group-listing">
                      <?php
                        foreach ( $group_members as $member ) {

                          $member_collapse_icon_open = $THEME->render_svg_icon("plus");
                          $member_collapse_icon_closed = $THEME->render_svg_icon("minus");
                          $member_collapse_id = $THEME->get_unique_id("team-member--");
                          $member_bio = $member["bio"] ?? "";
                          $member_job_title = $member["job_title"] ?? "";
                          $member_name = $member["name"] ?? "";

                          if ( $member_bio && $member_name ) {
                            echo "
                              <div class='{$block_name}__group-item collapsible'>
                                <button
                                  class='{$block_name}__button button collapsible__trigger collapsed'
                                  type='button'
                                  data-bs-toggle='collapse'
                                  data-bs-target='#{$member_collapse_id}'
                                  aria-expanded='false'
                                  aria-controls='{$member_collapse_id}'
                                >
                                  <span class='collapsible__trigger-title'>{$member_name}" . ( $member_job_title ? ", {$member_job_title}" : "" ) . "</span>
                                  <span class='collapsible__trigger-icon'>
                                    <span class='collapsible__trigger-icon-plus'>{$member_collapse_icon_open}</span>
                                    <span class='collapsible__trigger-icon-minus'>{$member_collapse_icon_closed}</span>
                                  </span>
                                </button>
                                <div class='collapsible__content collapse' id='{$member_collapse_id}'>
                                  <div class='collapsible__content-padding body-copy--primary'>{$member_bio}</div>
                                </div>
                              </div>
                            ";
                          }

                        }
                      ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

            <?php endforeach; ?>
          </div>
        <?= $THEME->render_bs_container( "closed", $cols, $container ); ?>
      </div>
    <?php endif; ?>

  </section>
<?php endif; ?>
