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

function SupprOk($id)
{
    global $xoopsDB;

    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('jargon') . " WHERE id = '$id'");

    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('jargon_comm') . " WHERE def = '$id'");

    redirect_header('index.php', 1, _DEFNUM);

    exit();
}

function Suppr($id)
{
    global $xoopsDB, $xoopsConfig;

    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    // OpenTable();

    echo '<B>' . _ADMIN . ' ' . $xoopsConfig['sitename'] . '</B><p>';

    echo '<CENTER>[ <A HREF="index.php">' . _ADMIN2 . '</A> | <A HREF="../index.php">' . _SEELIST . '</A> | <A HREF="ajout-def.php">' . _ADDDEF . '</A> ]</CENTER>';

    $result = $xoopsDB->query('SELECT idcat, lettre, nom, definition, lien FROM ' . $xoopsDB->prefix('jargon') . " WHERE id = '$id'");

    [$idcat, $texte_lettre, $texte_nom, $texte_def, $texte_lien] = $xoopsDB->fetchRow($result);

    $result_cat = $xoopsDB->query('SELECT idcat, nomcat FROM ' . $xoopsDB->prefix('jargon_cat') . " WHERE idcat = '$idcat'");

    [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

    $texte_nom = $myts->displayTarea($texte_nom);

    $texte_def = $myts->displayTarea($texte_def);

    echo '<P><br><B>' . _SUPPRSURE . " <FONT COLOR=\"#FF0000\">$id</FONT> " . _ANDCOMLINK . ' ?</B></CENTER><P>';

    echo '<TABLE BORDER=0 CELLPADDING=5>
    <TR>
      <TD><B>' . _CAT . " </B></TD>
      <TD>$nomcat</TD><P>
    </TR>
    <TR>
      <TD><B>" . _LETTRE . " </B></TD>
      <TD>$texte_lettre</TD><P>
    </TR>
    <TR>
      <TD><B>" . _TERME2 . " </B></TD>
      <TD>$texte_nom</TD>
    </TR>
    <TR>
      <TD VALIGN=\"TOP\"><B>" . _DEF3 . " </B></TD>
      <TD>$texte_def</TD>
    </TR>
    <TR>
      <TD VALIGN=\"TOP\"><B>" . _LINKSASS2 . " </B></TD>
      <TD><A HREF=\"$texte_lien\" TARGET=\"_blank\">$texte_lien</A></TD>
    </TR>
    <TR>
      <TD COLSPAN=2>&nbsp;</TD>
    </TR>
    <TR>
      <TD COLSPAN=2><CENTER>
<form method=post action=suppr-def.php?&id=$id&pa=SupprOk><input type=submit value=\"" . _SUPPR2 . '"></form>
 </CENTER></TD>
    </TR>
</TABLE>';

    // CloseTable();

    xoops_cp_footer();
}

switch ($pa) {
    case 'SupprOk':
        SupprOk($id);
        break;
    default:
        Suppr($id);
        break;
}
