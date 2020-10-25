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

function supprcomm($id)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule;

    if ('' == $id) {
        xoops_cp_header();

        // OpenTable();

        echo ' ' . _THEREISPROBLEM . ' ';

        echo '<p><center>[ <a href="javascript:history.go(-1)">' . _COMEBACK . '</a> ]</center>';

        // CloseTable();

        xoops_cp_footer();
    } else {
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jargon_comm') . " WHERE id = '$id'");

        redirect_header('index.php', 1, _SUPPCOMM);

        exit();
    }
}

function modifok($id, $def, $auteur, $date, $commentaire, $url)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule;

    $myts = MyTextSanitizer::getInstance();

    if ('' == $id) {
        xoops_cp_header();

        // OpenTable();

        echo ' ' . _THEREISPROBLEM . ' ';

        echo '<p><center>[ <a href="javascript:history.go(-1)">' . _COMEBACK . '</a> ]</center>';

        // CloseTable();

        xoops_cp_footer();
    } else {
        $def = $myts->addSlashes($def);

        $commentaire = $myts->addSlashes($commentaire);

        $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('jargon_comm') . " SET id = '$id' , def = '$def' , auteur = '$auteur', date = '$date', commentaire = '$commentaire', url = '$url' , affiche = 'O'  WHERE id='$id'");

        redirect_header('index.php', 1, _COMMUPDATED);

        exit();
    }
}

function modif($id)
{
    global $xoopsDB, $xoopsConfig, $commentaire;

    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    // OpenTable();

    echo '<B>' . _ADMIN . ' ' . $xoopsConfig['sitename'] . '</B><p>';

    echo '<CENTER>[ <A HREF="index.php">' . _ADMIN2 . '</A> | <A HREF="../index.php">' . _SEELIST . '</A> | <A HREF="ajout-def.php">' . _ADDDEF . '</A> ]</CENTER>';

    $TableRep = $xoopsDB->query('SELECT id, def, auteur, date, commentaire, url FROM ' . $xoopsDB->prefix('jargon_comm') . " WHERE id = '$id'");

    $NombreEntrees = $xoopsDB->getRowsNum($TableRep);

    if (0 == $NombreEntrees) {
        echo '<P><br>' . _NOEXISTCOM . "  $id";
    } else {
        [$texte_id, $texte_def, $texte_auteur, $texte_date, $commentaire, $texte_url] = $xoopsDB->fetchRow($TableRep);

        $texte_def = htmlspecialchars($texte_def, ENT_QUOTES | ENT_HTML5);

        $texte_comm = htmlspecialchars($texte_comm, ENT_QUOTES | ENT_HTML5);

        echo "<FORM ACTION=\"mod-comm.php?pa=modifok&id=$texte_id\" METHOD=POST>
<INPUT TYPE=\"hidden\" NAME=\"def\" VALUE=\"$texte_def\">
<INPUT TYPE=\"hidden\" NAME=\"date\" VALUE=\"$texte_date\">
<INPUT TYPE=\"hidden\" NAME=\"auteur\" VALUE=\"$texte_auteur\">";

        echo '<B>' . _MOVALCOMM . '</B>
<TABLE BORDER=0 CELLPADDING=5>
    <TR>
      <TD ALIGN="LEFT">' . _AUTOR2 . " </TD>
      <TD>$texte_auteur</TD>
    </TR>
    <TR>
      <TD ALIGN=\"LEFT\">" . _COM3 . ' </TD>
      <TD>';

        $allowbbcode = 1;

        $allowsmileys = 1;

        require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';

        if (1 == $allowbbcode) {
            xoopsCodeTarea('commentaire');
        } else {
            echo "<textarea id='commentaire' name='commentaire' wrap='virtual' cols='50' rows='10'>$commentaire</textarea><br>";
        }

        if (1 == $allowsmileys) {
            xoopsSmilies('commentaire');
        }

        //	  echo "<TEXTAREA NAME=\"commentaire\" COLS=40 ROWS=8>$texte_comm</TEXTAREA></TD>

        echo '</TD>
    </TR>
    <TR>
      <TD ALIGN="LEFT">' . _LINKSASS2 . " </TD>
      <TD><INPUT TYPE=\"text\" NAME=\"url\" VALUE=\"$texte_url\" SIZE=50></TD>
    </TR>
    <TR>
      <TD ALIGN=\"CENTER\" COLSPAN=2><CENTER><INPUT TYPE=\"submit\" NAME=\"Validation\"  VALUE=\"" . _MODVAL . "\"></FORM></CENTER></TD>
    </TR>
    <TR>
      <TD ALIGN=\"CENTER\" COLSPAN=2><CENTER><FORM ACTION='mod-comm.php?pa=supprcomm&id=$texte_id' METHOD=POST><INPUT TYPE=\"submit\" VALUE=\"" . _SUPPR . '"></FORM></CENTER></TD>
    </TR>
</TABLE>';

        // CloseTable();
    }

    xoops_cp_footer();
}

switch ($pa) {
    case 'modifok':
        modifok($id, $def, $auteur, $date, $commentaire, $url);
        break;
    case 'supprcomm':
        supprcomm($id);
        break;
    default:
        modif($id);
        break;
}
