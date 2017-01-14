<?php include __DIR__ . DS . 'top.php'; ?>
<div class="container">
  <aside class="secondary">
    <h3>Children</h3>
    <ul>
      <li><a href="">foo/lorem-ipsum</a></li>
      <li><a href="">foo/dolor-sit</a></li>
      <li><a href="">foo/amet</a></li>
      <li><a href="">&#x271A;</a></li>
    </ul>
  </aside>
  <article class="main">
    <form>
      <fieldset>
        <legend>New Page</legend>
        <p class="control expand">
          <label for="control-page-data-title"><?php echo $language->title; ?></label> <span>
            <input class="input block" id="control-page-data-title" type="text" value="<?php echo $__page->title; ?>" placeholder="Title Here">
          </span>
        </p>
        <p class="control expand">
          <label for="control-page-data-slug"><?php echo $language->slug; ?></label> <span>
            <input class="input block" id="control-page-data-slug" type="text" value="<?php echo $__page->slug; ?>" placeholder="title-here">
          </span>
        </p>
        <p class="control expand">
          <label for="control-page-data-content"><?php echo $language->content; ?></label> <span>
            <textarea class="textarea block expand code" id="control-page-data-content"><?php echo htmlentities($__page->content); ?></textarea>
          </span>
        </p>
        <p class="control">
          <label for="control-page-data-type"><?php echo $language->type; ?></label> <span>
            <select class="select" id="control-page-data-type">
              <option value="HTML">HTML</option>
              <option value="Markdown">Markdown</option>
            </select>
          </span>
        </p>
        <p class="control">
          <label for="control-page-data-description">Description</label> <span>
            <textarea class="textarea block" id="control-page-data-description"><?php echo $__page->description; ?></textarea>
          </span>
        </p>
        <p class="control">
          <label for="control-page-data-kind"><?php echo $language->kind; ?></label> <span>
            <input class="input query" name="tags" type="text" placeholder="foo, bar">
          </span>
        </p>
      </fieldset>
      <fieldset>
        <legend>Embedded</legend>
        <p class="control"><label>TOC</label> <span><textarea class="textarea"></textarea></span></p>
        <p class="control"><label>Ads</label> <span><input class="input" type="checkbox"> Enable</span></p>
      </fieldset>
      <p><button class="button">Publish</button> <button class="button">Save</button> <button class="button">Delete</button></p>
    </form>
    <footer class="main-footer">
      <p>&copy; 2017 &middot; Mecha CMS</p>
    </footer>
  </article>
  <aside class="secondary">
    <h3>Search</h3>
    <form class="search">
      <p><input class="input" name="q" type="text"> <button class="button">Search</button>
    </form>
    <h3>Data</h3>
    <ul>
      <li><a href="">css</a></li>
      <li><a href="">js</a></li>
      <li><a href="">toc</a></li>
      <li><a href="">&#x271A;</a></li>
    </ul>
  </aside>
</div>
<?php include __DIR__ . DS . 'bottom.php'; ?>