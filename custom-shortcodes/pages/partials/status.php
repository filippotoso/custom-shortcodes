<?php if ($status == 'deleted'): ?>
    <div id="message" class="notice notice-warning is-dismissible"><p>Shortcode successfully delete.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notification.</span></button></div>
<?php elseif ($status == 'activated'): ?>
    <div id="message" class="notice notice-info is-dismissible"><p>Shortcode successfully activated.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notification.</span></button></div>
<?php elseif ($status == 'deactivated'): ?>
    <div id="message" class="notice notice-info is-dismissible"><p>Shortcode successfully deactivated.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notification.</span></button></div>
<?php elseif ($status == 'stored'): ?>
    <div id="message" class="notice notice-info is-dismissible"><p>Shortcode successfully created.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notification.</span></button></div>
<?php elseif ($status == 'updated'): ?>
    <div id="message" class="notice notice-info is-dismissible"><p>Shortcode successfully updated.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notification.</span></button></div>
<?php elseif ($status == 'not-found'): ?>
    <div id="message" class="notice notice-error is-dismissible"><p>Shortcode not found!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notification.</span></button></div>
<?php elseif ($status == 'already-exists'): ?>
    <div id="message" class="notice notice-error is-dismissible"><p>Shortcode already exists!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notification.</span></button></div>
<?php endif; ?>
