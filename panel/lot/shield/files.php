<?php Shield::get(__DIR__ . DS . 'header.php'); ?>
<header></header>
<main><?php echo panel\files(LOT . DS . $_path, [20, $_step === null ? 0 : $_step - 1]); ?></main>
<footer></footer>
<?php Shield::get(__DIR__ . DS . 'footer.php'); ?>