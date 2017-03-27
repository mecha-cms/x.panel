<!DOCTYPE html>
<html dir="<?php echo $site->direction; ?>" class="<?php echo 'p-' . str_replace('/', ' p-', $__path); ?>">
  <head>
    <meta charset="<?php echo $site->charset; ?>">
    <meta name="viewport" content="width=device-width">
    <!-- Prevent search engines from indexing the login page -->
    <meta name="robots" content="noindex">
    <title><?php echo $language->new__($language->user, true) . ' &#x00B7; ' . $language->__title; ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body spellcheck="false">
    <div class="c">
      <main class="m">
        <form id="form.m.set" action="" method="post">
          <?php echo $__message; ?>
          <fieldset>
            <legend><?php echo $language->new__($language->user, true); ?></legend>
            <p class="f f-_key">
              <label for="f-_key"><?php echo $language->user; ?></label>
              <span><?php echo Form::text('_key', null, User::ID . l($language->user), ['classes' => ['input', 'block'], 'id' => 'f-_key', 'pattern' => '^' . x(User::ID) . '[a-z\\d-]+$', 'autofocus' => true, 'required' => true]); ?></span>
            </p>
            <p class="f f-_status">
              <label for="f-_status"><?php echo $language->status; ?></label>
              <span><?php $__status = a($language->panel->user); echo Form::select('_status', [(glob(ENGINE . DS . 'log' . DS . 'user' . DS . '*.page') ? "" : '.') . '2' => $__status[2], '1' => $__status[1]], null, ['classes' => ['select', 'block']]); ?></span>
            </p>
            <p class="f f-_author">
              <label for="f-_author"><?php echo $language->name; ?></label>
              <span><?php echo Form::text('_author', null, null, ['classes' => ['input', 'block'], 'id' => 'f-_author', 'required' => true]); ?></span>
            </p>
            <p class="f f-_email">
              <label for="f-_email"><?php echo $language->email; ?></label>
              <span><?php echo Form::email('_email', null, null, ['classes' => ['input', 'block'], 'id' => 'f-_email']); ?></span>
            </p>
            <p class="f f-_link">
              <label for="f-_link"><?php echo $language->link; ?></label>
              <span><?php echo Form::url('_link', null, $url->protocol, ['classes' => ['input', 'block'], 'id' => 'f-_link']); ?></span>
            </p>
            <div class="f f-_description p">
              <label for="f-_description"><?php echo $language->description; ?></label>
              <div><?php echo Form::textarea('_description', null, $language->f_description($language->user), ['classes' => ['textarea', 'block', 'expand', 'code', 'editor'], 'id' => 'f-_description']); ?></div>
            </div>
          </fieldset>
          <p class="f f-_set expand">
            <label for="f-_set"><?php echo $language->set; ?></label>
            <span><?php echo Form::submit('_set', 1, $language->create, ['classes' => ['button', 'set'], 'id' => 'f-_set']); ?></span>
          </p>
          <?php echo Form::token(); ?>
        </form>
      </main>
    </div>
  </body>
</html>