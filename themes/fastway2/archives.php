<?php get_header(); ?>

<div id="container">

	<div id="content">
<div class="post">
<div id="div-h2">Архивы</div>
<div id="div-h3">Месяца:</div>
  <ul>
    <?php wp_get_archives('type=monthly'); ?>
  </ul>

<div id="div-h3">Категории:</div>
  <ul>
     <?php wp_list_cats(); ?>
  </ul>

</div>	
</div>

	
</div>

<?php get_sidebar(); ?>

<?php get_footer() ?>

</div>
</body>
</html>
