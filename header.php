<?php

#####################################################
#	jargon version 2.0 pour xoops 2.X
#	Copyright 2004, Martial Le Peillet
#	webmaster@toplenet.com - http://www.toplenet.com
#
#	Module Original :
#	jargon version 1.6 pour xoops 1.0 RC3
#	Copyright © 2002, Pascal Le Boustouller
#
#  Licence : GPL
#  Merci de laisser ce copyright en place...
#####################################################

include '../../mainfile.php';

if (file_exists(XOOPS_ROOT_PATH . '/modules/jargon/language/' . $xoopsConfig['language'] . '/main.php')) {
    require XOOPS_ROOT_PATH . '/modules/jargon/language/' . $xoopsConfig['language'] . '/main.php';
} else {
    require XOOPS_ROOT_PATH . '/modules/jargon/language/english/main.php';
}
