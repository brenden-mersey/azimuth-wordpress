<?php

  /**
  *
  *	Filename: team-modal.php
  *
  */

  $THEME = new CustomTheme();

  $template = 'team-modal';
  $template_id = $template;

?>


<div class="modal modal--<?= $template; ?> micromodal-slide" id="<?= $template_id; ?>" aria-hidden="true">
  <div class="modal__overlay" tabindex="-1" data-micromodal-close>

    <button class="modal__button button" aria-label="Close modal" type="button" data-micromodal-close>
      <?= $THEME->render_svg([ 'type' => 'icon.close' ]); ?>
    </button>

    <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
      <div class="modal__content team">
        <!-- Updated with JS -->
      </div>
    </div>

  </div>
</div>
