<?php

#####################################################
#	jargon version 2.0 pour xoops 2.X
#	Copyright 2004, Martial Le Peillet
#	webmaster@toplenet.com - http://www.toplenet.com
#
#  Licence : GPL
#  Merci de laisser ce copyright en place...
#####################################################
include 'admin_header.php';
require XOOPS_ROOT_PATH . '/modules/jargon/jargon-config.php';
require XOOPS_ROOT_PATH . '/modules/jargon/function.php';

// Récupération des variables
if ('1' != ini_get('register_globals')) {
    if (!empty($_GET)) {
        extract($_GET);
    } elseif (!empty($_GET)) {
        extract($_GET);
    }

    if (!empty($_POST)) {
        extract($_POST);
    } elseif (!empty($_POST)) {
        extract($_POST);
    }
}

function SupprOk($idcat)
{
    global $xoopsDB;

    // On efface d'abord la catégorie

    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('jargon_cat') . " WHERE idcat = '$idcat'");

    // On efface ensuite les commentaires associés

    $result = $xoopsDB->query('select id, idcat from ' . $xoopsDB->prefix('jargon') . " WHERE idcat = '$idcat'");

    while (list($idresult, $idcatresult) = $xoopsDB->fetchRow($result)) {
        $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('jargon_comm') . " WHERE def = '$idresult'");
    }

    // et on termine avec les définitions.

    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('jargon') . " WHERE idcat = '$idcat'");

    // Terminé !

    redirect_header('index.php', 1, _DELCAT);

    exit();
}

function Suppr($idcat)
{
    global $xoopsDB, $xoopsConfig;

    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    echo '<B>' . _ADMIN . ' ' . $xoopsConfig['sitename'] . '</B><p>';

    echo '<CENTER>[ <A HREF="index.php">' . _ADMIN2 . '</A> | <A HREF="../index.php">' . _SEELIST . '</A> | <A HREF="ajout-def.php">' . _ADDDEF . '</A> ]</CENTER>';

    $result_cat = $xoopsDB->query('SELECT idcat, nomcat FROM ' . $xoopsDB->prefix('jargon_cat') . " WHERE idcat = $idcat");

    [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

    $nomcat = $myts->displayTarea($nomcat);

    echo '<P><br><B>' . _SUPPRCAT . "<FONT COLOR=\"#FF0000\">$idcat</FONT> " . _ANDDEFCOMLINK . ' ?</B></CENTER><P>';

    echo '<TABLE BORDER=0 CELLPADDING=5>
    <TR>
      <TD align=left><B>' . _CAT . " </B></TD>
      <TD align=left>$nomcat</TD><P>
    </TR>
    <TR>
      <TD COLSPAN=2 align=center><CENTER>
<form method=post action=suppr-cat.php?&idcat=$idcat&pa=SupprOk><input type=submit value=\"" . _SUPPR2 . '"></form>
 </CENTER></TD>
    </TR>
</TABLE>';

    xoops_cp_footer();
}

switch ($pa) {
    case 'SupprOk':
        SupprOk($idcat);
        break;
    default:
        Suppr($idcat);
        break;
}
