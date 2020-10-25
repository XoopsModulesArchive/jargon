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
function jargon_showrand()
{
    global $xoopsDB, $xoopsConfig;

    $myts = MyTextSanitizer::getInstance();

    $block = [];

    $block['title'] = _MB_JARGONRAND_TITLE;

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) from ' . $xoopsDB->prefix('jargon') . " WHERE affiche='O'"));

    if ($numrows > 1) {
        $numrows -= 1;

        // mt_srand((double)microtime() * 1000000);

        $bannum = random_int(0, $numrows);
    } else {
        $bannum = 0;
    }

    $result = $xoopsDB->query('SELECT idcat, nom, definition FROM ' . $xoopsDB->prefix('jargon') . " WHERE affiche='O' LIMIT $bannum,1");

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $nom = $myts->displayTarea($myrow['nom']);

        $definition = $myts->displayTarea($myrow['definition']);

        $idcat = $myrow['idcat'];

        $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('jargon_cat') . " where idcat=$idcat");

        [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

        $nomcat = $myts->displayTarea($nomcat);

        $block['content'] .= '<center><small>' . _CAT . " $nomcat</small></center>";

        $block['content'] .= "<center>[&nbsp;<b>$nom</b>&nbsp;]</center><span class='bggreen'>";

        $block['content'] .= '<Marquee Behavior="Scroll" Direction="up" Height="140" ScrollAmount="2" ScrollDelay="100" onMouseOver="this.stop()" onMouseOut="this.start()"><br>';

        $block['content'] .= "<small><br>$definition</small>";

        $block['content'] .= '</marquee></span>';
    }

    $block['content'] .= '<center><small><A HREF="' . XOOPS_URL . '/modules/jargon/">' . _MB_OTHERS_DEF . '...</A></small></center><br>';

    return $block;
}
