<?php


$dictionary=array(

	'ZONEMINDER_COLUMN_ID'=>'№',
    'ZONEMINDER_COLUMN_NAME'=>'Имя',
    'ZONEMINDER_COLUMN_FUNCTION'=>'Режим',
    'ZONEMINDER_COLUMN_ENABLED'=>'Включена',
    'ZONEMINDER_COLUMN_ZONE_COUNT'=>'Зоны',
    'ZONEMINDER_COLUMN_SOURCE'=>'Источник',
    'ZONEMINDER_COLUMN_HOUR'=>'Час',
    'ZONEMINDER_COLUMN_DAY'=>'День',
    'ZONEMINDER_COLUMN_WEEK'=>'Неделя',
    'ZONEMINDER_COLUMN_MONTH'=>'Месяц',
    'ZONEMINDER_DISKSPACE_B'=>'б',
    'ZONEMINDER_DISKSPACE_KB'=>'Кб',
    'ZONEMINDER_DISKSPACE_MB'=>'Мб',
    'ZONEMINDER_DISKSPACE_GB'=>'Гб',
    'ZONEMINDER_DISKSPACE_TB'=>'Тб',
    'ZONEMINDER_BANDWIDTH_KBS'=>'Кб/с',
    'ZONEMINDER_COLUMN_MONITOR'=>'Камера',
    'ZONEMINDER_COLUMN_START_TIME'=>'Начало',
    'ZONEMINDER_COLUMN_END_TIME'=>'Конец',
    'ZONEMINDER_COLUMN_DURATION'=>'Длительность',
    'ZONEMINDER_COLUMN_DISK_SPACE'=>'Размер',
    'ZONEMINDER_COLUMN_THUMBNAIL'=>'Предпросмотр',
    'ZONEMINDER_PAGE_PREV'=>'Пред',
    'ZONEMINDER_PAGE_NEXT'=>'След',
    'ZONEMINDER_NO_EVENTS_TEXT'=>'Записей не найдено',
    'ZONEMINDER_TOTAL'=>'Всего',

    'ZONEMINDER_SETTINGS_SERVER_ADDRESS'=>'Адрес сервера',
    'ZONEMINDER_SETTINGS_USERNAME'=>'Пользователь',
    'ZONEMINDER_SETTINGS_PASSWORD'=>'Пароль',
    'ZONEMINDER_SETTINGS_TEXT'=>'Настройки',

    'ZONEMINDER_ABOUT'=>'О модуле',
    'ZONEMINDER_HELP'=>'Справка',
    'ZONEMINDER_CLOSE'=>'Закрыть',
    'ZONEMINDER_ABOUT_TEXT'=>'Модуль поддержки системы видеонаблюдения <b>Zoneminder</b>.<br><br>
               Обсуждение модуля на <a href="https://mjdm.ru/forum/viewtopic.php" target="_blank">форуме</a>.<br>
               Проект в <a href="https://github.com/layet/majordomo-zoneminder" target="_blank">Github</a>.<br>
               Проект в <a href="https://connect.smartliving.ru/tasks/922.html" target="_blank">Connect</a>.<br>',
);

foreach ($dictionary as $k=>$v) {
	if (!defined('LANG_'.$k)) {
		define('LANG_'.$k, $v);
	}
}
