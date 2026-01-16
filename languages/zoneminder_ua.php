<?php


$dictionary=array(

	'ZONEMINDER_COLUMN_ID'=>'№',
    'ZONEMINDER_COLUMN_NAME'=>'Ім\'я',
    'ZONEMINDER_COLUMN_FUNCTION'=>'Режим',
    'ZONEMINDER_COLUMN_ENABLED'=>'Діє',
    'ZONEMINDER_COLUMN_ZONE_COUNT'=>'Зони',
    'ZONEMINDER_COLUMN_SOURCE'=>'Джерело',
    'ZONEMINDER_COLUMN_HOUR'=>'Час',
    'ZONEMINDER_COLUMN_DAY'=>'День',
    'ZONEMINDER_COLUMN_WEEK'=>'Тиждень',
    'ZONEMINDER_COLUMN_MONTH'=>'Місяць',
    'ZONEMINDER_DISKSPACE_B'=>'б',
    'ZONEMINDER_DISKSPACE_KB'=>'Кб',
    'ZONEMINDER_DISKSPACE_MB'=>'Мб',
    'ZONEMINDER_DISKSPACE_GB'=>'Гб',
    'ZONEMINDER_DISKSPACE_TB'=>'Тб',
    'ZONEMINDER_BANDWIDTH_KBS'=>'Кб/с',
    'ZONEMINDER_COLUMN_MONITOR'=>'Камера',
    'ZONEMINDER_COLUMN_START_TIME'=>'Початок',
    'ZONEMINDER_COLUMN_END_TIME'=>'Кінець',
    'ZONEMINDER_COLUMN_DURATION'=>'Тривалість',
    'ZONEMINDER_COLUMN_DISK_SPACE'=>'Розмір',
    'ZONEMINDER_COLUMN_THUMBNAIL'=>'Передпрогляд',
    'ZONEMINDER_PAGE_PREV'=>'Попер',
    'ZONEMINDER_PAGE_NEXT'=>'Наст',
    'ZONEMINDER_NO_EVENTS_TEXT'=>'Записів не знайдено',
    'ZONEMINDER_TOTAL'=>'Усього',

    'ZONEMINDER_SETTINGS_SERVER_ADDRESS'=>'Адреса сервера',
    'ZONEMINDER_SETTINGS_USERNAME'=>'Користувач',
    'ZONEMINDER_SETTINGS_PASSWORD'=>'Пароль',
    'ZONEMINDER_SETTINGS_TEXT'=>'Опції',

    'ZONEMINDER_ABOUT'=>'Про модуль',
    'ZONEMINDER_HELP'=>'Допомога',
    'ZONEMINDER_CLOSE'=>'Закрити',
    'ZONEMINDER_ABOUT_TEXT'=>'Модуль підтримки системи відеоспостереження <b>Zoneminder</b>.<br><br>
               Обговорення модуля на <a href="https://mjdm.ru/forum/viewtopic.php?t=8255" target="_blank">форумі</a>.<br>
               Проект в <a href="https://github.com/layet/majordomo-zoneminder" target="_blank">Github</a>.<br>
               Проект в <a href="https://connect.smartliving.ru/tasks/923.html" target="_blank">Connect</a>.<br>
               Канал в <a href="https://t.me/mjdm_zoneminder" target="_blank">Telegram</a>.<br>',
);

foreach ($dictionary as $k=>$v) {
	if (!defined('LANG_'.$k)) {
		define('LANG_'.$k, $v);
	}
}
