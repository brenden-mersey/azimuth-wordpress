<?php

  /**
  *
  *	Filename: 404.php
  *
  */

  // ---------------------------------------- Mount WP Header
  get_header();

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Block
  $block_name = 'error-404';
  $block_classes = "{$block_name} block block--{$block_name}";
  $block_id = $THEME->get_unique_id("{$block_name}--");

  // ---------------------------------------- AOS
  $aos_id = $block_id;
  $aos_delay = 250;
  $aos_increment = 250;

  // ---------------------------------------- Content (ACF)
  $error_404 = get_field( 'error_404', 'options' ) ?: [];
  $cols = $error_404["cols"] ?? "col-12 col-lg-4 offset-lg-4";
  $container = $error_404["container"] ?? "container";
  $cta = $error_404['cta'] ?? [];
  $cta_link = $THEME->get_link($cta);
  $cta_title = $cta["title"] ?? "";
  $heading = $error_404['heading'] ?? 'Error 404';
  $message = $error_404['message'] ?? '<p>Page or Content Not Found.</p>';

  // ---------------------------------------- Conditionals
  $has_cta = $cta_link && $cta_title ? true : false;

?>

<section class="<?= esc_attr( $block_classes ); ?>" id="<?= esc_attr( $block_id ); ?>">
  <?= $THEME->render_bs_container( "open", $cols, $container ); ?>
    <div class="<?= $block_name; ?>__main">
      <?php if ( $heading ) : ?>
        <?php $aos_attrs = $THEME->render_aos_attrs([ 'anchor' => $aos_id, 'delay' => $aos_delay, 'transition' => 'fade-left' ]); $aos_delay += $aos_increment; ?>
        <h1 class="<?= $block_name; ?>__heading heading--1" <?= $aos_attrs; ?>><?= $heading; ?></h1>
      <?php endif; ?>
      <?php if ( $message ) : ?>
        <?php $aos_attrs = $THEME->render_aos_attrs([ 'anchor' => $aos_id, 'delay' => $aos_delay, 'transition' => 'fade-left' ]); $aos_delay += $aos_increment; ?>
        <div class="<?= $block_name; ?>__message body-copy--primary" <?= $aos_attrs; ?>><?= $message; ?></div>
      <?php endif; ?>
      <?php if ( $has_cta ) : ?>
        <?php $aos_attrs = $THEME->render_aos_attrs([ 'anchor' => $aos_id, 'delay' => $aos_delay, 'transition' => 'fade-up' ]); ?>
        <div class="<?= $block_name; ?>__cta" <?= $aos_attrs; ?>>
          <?=
            $THEME->render_link([
              "block_name" => "button",
              "title" => $cta_title,
              "url" => $cta_link,
            ]);
          ?>
        </div>
      <?php endif; ?>
    </div>
  <?= $THEME->render_bs_container( "closed", $cols, $container ); ?>
</section>

<?php get_footer(); ?>
