<?php

  /**
  *
  *   Header
  *
  */

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Template Data
  $block_name = "footer";
  $block_classes = $block_name;
  $block_id = $block_name;

  // ---------------------------------------- Content (ACF)
  $cols = get_field("cols") ?: "col-12";
  $company = get_field( 'company', 'options' ) ?: [];
  $company_social = $company['social'] ?? [];
  $company_email = $company['general']['contact_email'] ?? 'johnsmith@example.com';
  $company_full_address = $company['general']['full_address'] ?? '1234 Main Street Vancouver, BC V6T 1Y9';
  $container = get_field("container") ?: "container";
  $footer = get_field("footer", "options") ?: [];
  $footer_navigation = $footer["navigation"] ?: [];

?>

<footer class="<?= esc_attr( $block_classes ); ?>" id="<?= esc_attr( $block_id ); ?>">
  <?= $THEME->render_bs_container( "open", $cols, $container ); ?>
    <div class="<?= $block_name; ?>__layout">

      <div class="<?= $block_name; ?>__vr"></div>

      <div class="<?= $block_name; ?>__newsletter">
        <h3 class="<?= $block_name; ?>__heading heading--2">Newsletter</h3>
        <?php include( locate_template( './snippets/forms/newsletter-form.php' ) ); ?>
      </div>

      <div class="<?= $block_name; ?>__contact d-flex">
        <div class="<?= $block_name; ?>__contact-info">
          <h3 class="<?= $block_name; ?>__heading heading--2">Contact</h3>
          <div class="<?= $block_name; ?>__contact-email">
            <a class="<?= $block_name; ?>__contact-email-link link" href="mailto:<?= $company_email; ?>" title="Email Us" target="_blank"><?= $company_email; ?></a>
          </div>
          <?php if ( $company_social ) : ?>
            <nav class="<?= $block_name; ?>__social d-none d-lg-inline-flex">
              <?php
                foreach( $company_social as $i => $item ) {
                  $account = $item['account'] ?? '';
                  $account_upper = ucfirst($account);
                  $svg_icon = $THEME->render_svg([ 'type' => 'icon.' . $account ]);
                  $url = $item['url'] ?? '';
                  if ( $account && $url ) {
                    echo "
                      <div class='{$block_name}__social-item'>
                        <a class='{$block_name}__social-link link' href='{$url}' target='_blank' title='{$account_upper}'>{$svg_icon}</a>
                      </div>
                    ";
                  }
                }
              ?>
            </nav>
          <?php endif; ?>
        </div>
        <div class="<?= $block_name; ?>__brand d-block d-lg-none">
          <a href="<?= $THEME->get_theme_directory('home'); ?>" title="Home">
            <?= $THEME->render_svg([ 'type' => 'brand.logo' ]); ?>
          </a>
        </div>
      </div>

      <div class="<?= $block_name; ?>__brand d-none d-lg-flex">
        <a href="<?= $THEME->get_theme_directory('home'); ?>" title="Home">
          <?= $THEME->render_svg([ 'type' => 'brand.logo' ]); ?>
        </a>
      </div>

    </div>

    <div class="<?= $block_name; ?>__links d-lg-none">
      <div class="<?= $block_name; ?>__hr"></div>
      <?php if ( $footer_navigation ) : ?>
        <nav class="<?= $block_name; ?>__navigation">
          <?php
            foreach ( $footer_navigation as $item ) {
              $item["block_name"] = $block_name;
              $item["current_id"] = $id;
              echo $THEME->render_navigation_item($item);
            }
          ?>
        </nav>
      <?php endif; ?>
      <?php if ( $company_social ) : ?>
        <nav class="<?= $block_name; ?>__social">
          <?php
            foreach( $company_social as $i => $item ) {
              $account = $item['account'] ?? '';
              $account_upper = ucfirst($account);
              $svg_icon = $THEME->render_svg([ 'type' => 'icon.' . $account ]);
              $url = $item['url'] ?? '';
              if ( $account && $url ) {
                echo "
                  <div class='{$block_name}__social-item'>
                    <a class='{$block_name}__social-link link' href='{$url}' target='_blank' title='{$account_upper}'>{$svg_icon}</a>
                  </div>
                ";
              }
            }
          ?>
        </nav>
      <?php endif; ?>
      <strong class="<?= $block_name; ?>__copyright">C/O <?= date('Y') . ' ' . get_bloginfo('name'); ?></strong>

      <div class="<?= $block_name; ?>__address"><?= $company_full_address; ?></div>
    </div>

    <div class="<?= $block_name; ?>__links d-none d-lg-grid">

      <div class="<?= $block_name; ?>__legal">
        <strong class="<?= $block_name; ?>__copyright">C/O <?= date('Y') . ' ' . get_bloginfo('name'); ?></strong>
        <div class="<?= $block_name; ?>__address"><?= $company_full_address; ?></div>
      </div>

      <?php if ( $footer_navigation ) : ?>
        <nav class="<?= $block_name; ?>__navigation">
          <?php
            foreach ( $footer_navigation as $item ) {
              $item["block_name"] = $block_name;
              $item["current_id"] = $id;
              echo $THEME->render_navigation_item($item);
            }
          ?>
        </nav>
      <?php endif; ?>

    </div>

  <?= $THEME->render_bs_container( "closed", $cols, $container ); ?>
</footer>
