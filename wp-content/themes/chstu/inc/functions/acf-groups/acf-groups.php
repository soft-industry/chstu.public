<?php

/*
Attention!!!
ACF Plugin has bug in export groups.
Wrong value in location -> value, but need taxonomy slug, not ID.
*/

function export_acf_groups(){
//	require_once 'intro-slide.php'; // Intro-слайд
//	require_once 'seo-and-media.php'; // SEO & Media
//	require_once 'front-page.php'; // Главная страница
//	require_once 'service-icon.php'; // Иконка услуги
//	require_once 'post-short-description.php'; // Краткое описание публикации
//	require_once 'theme-options.php'; // Опции
//	require_once 'category.php'; // Категория
//	require_once 'vacancies.php'; // Вакансии
//	require_once 'office.php'; // Офис
//	require_once 'our-team.php'; // Наша команда
}

add_action('acf/init', 'export_acf_groups');