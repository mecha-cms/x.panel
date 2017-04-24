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
            <p class="f f-user">
              <label for="f-user"><?php echo $language->user; ?></label>
              <span><?php echo Form::text('user', null, User::ID . l($language->user), ['classes' => ['input', 'block'], 'id' => 'f-user', 'pattern' => '^' . x(User::ID) . '[a-z\\d-]+$', 'autofocus' => true, 'required' => true]); ?></span>
            </p>
            <p class="f f-status">
              <label for="f-status"><?php echo $language->status; ?></label>
              <span><?php $__status = a($language->panel->user); echo Form::select('status', [(g(ENGINE . DS . 'log' . DS . 'user', 'page') ? "" : '.') . '2' => $__status[2], '1' => $__status[1]], null, ['classes' => ['select', 'block'], 'id' => 'f-status']); ?></span>
            </p>
            <p class="f f-author">
              <label for="f-author"><?php echo $language->name; ?></label>
              <span><?php echo Form::text('author', null, null, ['classes' => ['input', 'block'], 'id' => 'f-author', 'required' => true]); ?></span>
            </p>
            <p class="f f-email">
              <label for="f-email"><?php echo $language->email; ?></label>
              <span><?php echo Form::email('email', null, null, ['classes' => ['input', 'block'], 'id' => 'f-email']); ?></span>
            </p>
            <p class="f f-link">
              <label for="f-link"><?php echo $language->link; ?></label>
              <span><?php echo Form::url('link', null, $url->protocol, ['classes' => ['input', 'block'], 'id' => 'f-link']); ?></span>
            </p>
            <div class="f f-content p">
              <label for="f-content"><?php echo $language->description; ?></label>
              <div><?php echo Form::textarea('content', null, $language->f_description($language->user), ['classes' => ['textarea', 'block', 'expand', 'code', 'editor'], 'id' => 'f-content', 'data' => ['type' => 'HTML']]); ?></div>
            </div>
            <p class="f f-type">
              <label for="f-type"><?php echo $language->type; ?></label>
              <span>
<?php $__types = a(Config::get('panel.f.page.types')); ?>
<?php asort($__types); ?>
<?php echo Form::select('type', $__types, null, [
    'classes' => ['select'],
    'id' => 'f-type'
]); ?>
              </span>
            </p>
          </fieldset>
          <p class="f f-set expand">
            <label for="f-set"><?php echo $language->set; ?></label>
            <span><?php echo Form::submit('set', 1, $language->create, ['classes' => ['button', 'set'], 'id' => 'f-set']); ?></span>
          </p>
          <?php echo Form::hidden('token', $__token); ?>
        </form>
      </main>
    </div>
  </body>
</html>