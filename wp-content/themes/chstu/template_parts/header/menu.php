<nav class="menu">
	<?php wp_nav_menu([
		//'theme_location'  => '',
		//'menu'            => 'Main menu',
		'container'       => false,
		//'container_class' => '',
		//'container_id'    => '',
		'menu_class'      => 'menu__list',
		//'menu_id'         => false,
		//'echo'            => true,
		//'fallback_cb'     => 'wp_page_menu',
		//'fallback_cb'     => '',
		//'before'          => '',
		//'after'           => '',
		//'link_before'     => '',
		//'link_after'      => '',
		'items_wrap'      => '<ul class="%2$s"><li class="menu__home">Home</li>%3$s</ul>',
//		'depth'           => 0,
		'walker' =>new SH_Nav_Menu_Walker
		//'walker'          => '',
	]); ?>
</nav>