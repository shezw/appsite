<?php

$website->requireLogin('manager/login');
$website->requireGroupLevel(90000,'manager/insufficient');
$website->requireGroupCharacter('manager','manager/insufficient');

$website->setTitle('API Test');

$website->appendTemplateByFile(THEME_DIR.'common/header.html');

$website->setMenuActive(['setting','settingApiTest']);
$website->appendTemplateByFile(THEME_DIR.'common/sidebar.html');

$website->appendTemplateByFile(THEME_DIR.'class/apiTest/interface.html');

$website->appendTemplateByFile(THEME_DIR.'common/footer.html');

$website->rend();
