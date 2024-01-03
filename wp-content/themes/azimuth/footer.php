<?php

/*
*
*	Filename: footer.php
*
*/

// ---------------------------------------- Data
$THEME = new CustomTheme();
$post_id = $THEME->get_theme_info('post_ID');

echo '</main>';

// ---------------------------------------- Header
include( locate_template( './snippets/footer.php' ) );

wp_footer();

echo '</body>';
echo '</html>';

?>
