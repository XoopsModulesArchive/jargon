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

function modifcatok($idcat, $nomcat)
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    if ('' == $idcat) {
        xoops_cp_header();

        echo ' ' . _NOIDCAT . ' ';

        echo '<p><center>[ <a href="javascript:history.go(-1)">' . _COMEBACKCAT . '</a> ]</center>';

        xoops_cp_footer();
    } else {
        $nomcat = $myts->addSlashes($nomcat);

        $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('jargon_cat') . " SET nomcat = '$nomcat' WHERE idcat='$idcat'");

        redirect_header('index.php', 1, _CATUPDATED);

        exit();
    }
}

function modifcat($idcat)
{
    global $xoopsDB, $xoopsConfig;

    xoops_cp_header();

    $myts = MyTextSanitizer::getInstance();

    echo '<B>' . _ADMINCAT . ' ' . $xoopsConfig['sitename'] . '</B><p>';

    echo '<CENTER>[ <A HREF="index.php">' . _ADMINCAT . '</A> | <A HREF="../index.php">' . _SEELIST . '</A> | <A HREF="ajout-cat.php">' . _ADDCAT . '</A> ]</CENTER>';

    $TableRep = $xoopsDB->query('SELECT idcat, nomcat FROM ' . $xoopsDB->prefix('jargon_cat') . " WHERE idcat = '$idcat'");

    $NombreEntrees = $xoopsDB->getRowsNum($TableRep);

    if (0 == $NombreEntrees) {
        echo '<P><br>' . _NOEXISTCAT . " $idcat";
    } else {
        [$idcat, $nomcat] = $xoopsDB->fetchRow($TableRep);

        $nomcat = htmlspecialchars($nomcat, ENT_QUOTES | ENT_HTML5);

        echo "<FORM enctype='multipart/form-data' ACTION='mod-cat.php?pa=modifcatok&idcat=$idcat' METHOD=POST>
<INPUT TYPE=\"hidden\" NAME=\"affiche\" VALUE=\"O\">
<INPUT TYPE=\"hidden\" NAME=\"idcat\" VALUE=\"$idcat\">";

        echo '<B>' . _MOVALCAT . '</B>
<TABLE BORDER=0 CELLPADDING=5>
    <TR>
      <TD ALIGN="LEFT">' . _NOMCAT . "</TD>
      <TD><INPUT TYPE='text' NAME='nomcat' VALUE='$nomcat' SIZE=50></TD>
    </TR>
    <TR>
      <TD ALIGN=\"CENTER\" COLSPAN=2><CENTER><INPUT TYPE='submit' NAME='Validation'  VALUE='" . _MODVAL . "'></CENTER></FORM></TD>
    </TR>
    <TR>
      <TD ALIGN=\"CENTER\" COLSPAN=2><CENTER><FORM ACTION='suppr-cat.php?&idcat=$idcat' METHOD=POST><INPUT TYPE=\"submit\" VALUE=\"" . _SUPPR . '"></FORM></CENTER></TD>
    </TR>
</TABLE>';
    }

    xoops_cp_footer();
}

function showcat()
{
    global $xoopsDB, $xoopsConfig;

    xoops_cp_header();

    $myts = MyTextSanitizer::getInstance();

    echo '<B>' . _ADMINCAT . ' ' . $xoopsConfig['sitename'] . '</B><p>';

    echo '<CENTER>[ <A HREF="index.php">' . _ADMIN2 . '</A> | <A HREF="ajout-cat.php">' . _ADDCAT . '</A> ]</CENTER>';

    echo '<br><center><B>' . _MODCAT . '</B></center>';

    echo '<br>';

    $TableRep = $xoopsDB->query('SELECT idcat, nomcat FROM ' . $xoopsDB->prefix('jargon_cat') . '');

    $NombreEntrees = $xoopsDB->getRowsNum($TableRep);

    if (0 == $NombreEntrees) {
        echo '<center>' . _NOEXISTCAT . '</center> ';
    } else {
        echo "<table border='1' cellpadding='2' cellspacing='2' align='center'><tr><td>";

        $result = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('jargon_cat') . ' ');

        while (list($idcat, $nomcat) = $xoopsDB->fetchRow($result)) {
            $idcat = $myts->displayTarea($idcat);

            $nomcat = $myts->displayTarea($nomcat);

            echo '' . $idcat . ' : <a href="mod-cat.php?idcat=' . $idcat . '&pa=modifcat">' . $nomcat . '</a>';

            echo '<br>';
        }

        echo '</td></tr></table>';
    }

    xoops_cp_footer();
}

switch ($pa) {
    case 'modifcatok':
        modifcatok($idcat, $nomcat);
        break;
    case 'modifcat':
        modifcat($idcat);
        break;
    default:
        showcat();
        break;
}
