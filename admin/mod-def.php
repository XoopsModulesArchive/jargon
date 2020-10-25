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

function modifok($id, $idcat, $lettre, $nom, $definition, $lien)
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    if ('' == $id) {
        xoops_cp_header();

        // OpenTable();

        echo ' ' . _THEREISPROBLEM . ' ';

        echo '<p><center>[ <a href="javascript:history.go(-1)">' . _COMEBACK . '</a> ]</center>';

        // CloseTable();

        xoops_cp_footer();
    } else {
        $nom = $myts->addSlashes($nom);

        $definition = $myts->addSlashes($definition);

        $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('jargon') . " SET idcat= '$idcat', lettre = '$lettre' , nom = '$nom' , definition = '$definition', affiche='O', lien = '$lien'  WHERE id='$id'");

        redirect_header('index.php', 1, _DEFUPDATED);

        exit();
    }
}

function modif($id)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $definition;

    xoops_cp_header();

    $myts = MyTextSanitizer::getInstance();

    echo '<B>' . _ADMIN . ' ' . $xoopsConfig['sitename'] . '</B><p>';

    echo '<CENTER>[ <A HREF="index.php">' . _ADMIN2 . '</A> | <A HREF="../index.php">' . _SEELIST . '</A> | <A HREF="ajout-def.php">' . _ADDDEF . '</A> ]</CENTER>';

    $TableRep = $xoopsDB->query('SELECT id, idcat, lettre, nom, definition, lien FROM ' . $xoopsDB->prefix('jargon') . " WHERE id = '$id'");

    $NombreEntrees = $xoopsDB->getRowsNum($TableRep);

    if (0 == $NombreEntrees) {
        echo '<P><br>' . _NOEXISTDEF . " $id";
    } else {
        [$texte_id, $texte_idcat, $texte_lettre, $texte_nom, $definition, $texte_aff, $texte_lien] = $xoopsDB->fetchRow($TableRep);

        echo "<FORM enctype='multipart/form-data' ACTION='mod-def.php?pa=modifok&id=$id' METHOD=POST>
<INPUT TYPE=\"hidden\" NAME=\"affiche\" VALUE=\"O\">
<INPUT TYPE=\"hidden\" NAME=\"id\" VALUE=\"$texte_id\">";

        // recherche par catégorie ou toutes les catégories

        echo "id : $id";

        echo '<br><br><B>' . _MOVALDEF . '</B><br>
<TABLE BORDER=0 CELLPADDING=5><tr><td>' . _CAT2 . ' :</td><td>';

        // recherche des catégories

        $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('jargon_cat') . ' ');

        // affichage des catégories

        echo '<select name="idcat">';

        while (list($idcat, $nomcat) = $xoopsDB->fetchRow($result_cat)) {
            $idcat = $myts->displayTarea($idcat);

            $nomcat = $myts->displayTarea($nomcat);

            if ($idcat == $texte_idcat) {
                $selected = 'selected';
            } else {
                $selected = '';
            }

            echo "<option value='$idcat' " . $selected . ">$idcat : $nomcat </option>";
        }

        echo '</select></td></tr>';

        echo '
    <TR>
      <TD ALIGN="LEFT">' . _LETTRE . ' </TD>
      <TD>';

        LettreGloAj($texte_lettre);

        echo '</TD>
    </TR>
    <TR>
      <TD ALIGN="LEFT">' . _TERME2 . "</TD>
      <TD><INPUT TYPE='text' NAME='nom' VALUE='$texte_nom' SIZE=50></TD>
    </TR>
    <TR>
      <TD ALIGN=\"LEFT\">" . _DEF3 . '</TD>
      <TD>';

        require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';

        //  	$definition = $myts->displayTarea($definition);

        //	echo "<HR><u><b>Ancienne définition</b></u> :<br><br>".$definition."<br><HR><br>";

        $allowhtml = 0;

        $allowbbcode = 1;

        $allowsmileys = 1;

        if (1 == $allowbbcode) {
            xoopsCodeTarea('definition', 60, 15);
        } else {
            echo "<textarea name=\"definition\" wrap=\"virtual\" cols=50 rows=10>$definition</textarea><br>";
        }

        if (1 == $allowsmileys) {
            xoopsSmilies('definition');
        }

        //echo "<br><TEXTAREA NAME=\"definition\" COLS=50 ROWS=10>$definition</TEXTAREA></TD>";

        echo '<br></TD>';

        //echo "</TD>";

        echo '    </TR>
    <TR>
      <TD ALIGN="LEFT">' . _LINKSASS2 . "</TD>
      <TD><INPUT TYPE='text' NAME='lien' VALUE='$texte_lien' SIZE=50></TD>
    </TR>
    <TR>
      <TD ALIGN=\"CENTER\" COLSPAN=2><CENTER><INPUT TYPE='submit' NAME='Validation'  VALUE='" . _MODVAL . "'></CENTER></FORM></TD>
    </TR>
    <TR>
      <TD ALIGN=\"CENTER\" COLSPAN=2><CENTER><FORM ACTION='suppr-def.php?&id=$texte_id' METHOD=POST><INPUT TYPE=\"submit\" VALUE=\"" . _SUPPR . '"></FORM></CENTER></TD>
    </TR>
</TABLE>';
    }

    xoops_cp_footer();
}

switch ($pa) {
    case 'modifok':
        modifok($id, $idcat, $lettre, $nom, $definition, $lien);
        break;
    case 'supprcomm':
        supprcomm($id);
        break;
    case 'modif':
    default:
        modif($id);
        break;
}
