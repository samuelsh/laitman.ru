<!-- Start leftbar -->

<div class="leftbar"><ul>
	
	<li><div class="leftbar_header">СОДЕРЖАНИЕ - <?php _e('Themes'); ?></div>
            <?php list_super_duper(); ?>
	</li>

	<?php if (function_exists('akpc_most_popular')) { ?>
	<li><div class="leftbar_header">САМЫЕ ПОПУЛЯРНЫЕ ПОСТЫ</div>
   	<ul>
   		<?php akpc_most_popular(); ?>
   	</ul>
	</li> 
	<?php } ?>

	<li>
		<div class="separator"></div>
		<div id="ml-links">
			<a rel="nofollow" href="http://www.kabbalah.info/rus/content/view/full/68576" target="_blank">Подписка на рассылку</a>
		</div>

<!-- 		<div id="rd-links">
			<a href="http://www.kabbalah.fm/" target="_blank">Каббала FM</a>
		</div> -->
	</li>

	<li id="archives"><div class="leftbar_header">Архив блога</div>
		<ul><li>
			<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
				<option value=""><?php echo esc_attr(__('Select Month')); ?></option>
				<?php wp_get_archives('type=monthly&format=option'); ?>
			</select>
			<?php get_calendar(false); ?>
		</li></ul>
	</li>

	<li>
    <div class="leftbar_header">Регистрация</div>
		<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<?php wp_meta(); ?>
		</ul>
	</li>

</ul></div>

<!-- End leftbar -->
