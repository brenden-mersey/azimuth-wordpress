<?php

  /**
  *
  *	Filename: newsletter-form.php
  *
  */

  $THEME = new CustomTheme();

?>


<form
  class="form form--contact-us js--validate-me"
  action="https://formspree.io/f/xeqnzdbj"
  method="POST"
  enctype="multipart/form-data"
  data-redirect-url="<?= $THEME->get_theme_directory('home'); ?>/thank-you"
>

  <div class="form__main">
    <div class="form__fields">
      <div class="form__field rude">
        <?=
          $THEME->render_form_input([
            "honeypot" =>true,
            "name" => "rude",
            "type" => "text",
          ]);
        ?>
      </div>
      <div class="form__field email">
        <?=
          $THEME->render_form_input([
            "placeholder" => "Email",
            "name" => "_replyto",
            "required" => true,
            "type" => "email",
          ]);
        ?>
        <div class="form__field-error-message">Please enter a valid email address.</div>
      </div>
    </div>
    <div class="form__actions">
      <button class="form__button-submit button--primary" type="submit">Submit</button>
    </div>
  </div>

  <div class="form__loading">
    <div class="form__spinner"></div>
  </div>

</form>
