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

include 'header.php';
require XOOPS_ROOT_PATH . '/modules/jargon/jargon-config.php';
require XOOPS_ROOT_PATH . '/modules/jargon/function.php';

if ('jargon' == $xoopsConfig['startpage']) {
    $xoopsOption['show_rblock'] = 1;

    require XOOPS_ROOT_PATH . '/header.php';

    make_cblock();

    echo '<br>';
} else {
    $xoopsOption['show_rblock'] = 0;

    require XOOPS_ROOT_PATH . '/header.php';
}
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

function Start()
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $mode, $comm, $pa;

    $myts = MyTextSanitizer::getInstance();

    echo "<center><font><h2 class='bnewst'>" . _JARGON . "</h2></font></center>\n";

    if ($xoopsUser) {
        if ($xoopsUser->isAdmin()) {
            echo '<center><B>' . _ADMINCADRE . "</B><br></center>\n";

            echo '<CENTER>[ <A HREF="admin/ajout-def.php">' . _ADDDEF . '</A> ]</CENTER>';

            [$propo] = $xoopsDB->fetchRow($xoopsDB->query('SELECT  COUNT(*)  FROM ' . $xoopsDB->prefix('jargon') . " WHERE affiche='N' OR affiche='D' order by nom"));

            if (0 == $propo) {
                echo '<CENTER>' . _NODEF . '</FONT></CENTER>';
            } else {
                echo '<CENTER><FONT COLOR="#FF0000">' . _THEREIS . " $propo  " . _PROWAIT . '</FONT><br><A HREF="admin/index.php">' . _SEEIT . '</A></CENTER>';
            }    //	if($propo == 0)

            if (1 == $mode) {
                [$comm] = $xoopsDB->fetchRow($xoopsDB->query('SELECT  COUNT(*)  FROM ' . $xoopsDB->prefix('jargon_comm') . " WHERE affiche='M' order by date"));

                if (0 == $comm) {
                    echo '<CENTER>' . _NOCOMM . '</FONT></CENTER>';
                } else {
                    echo '<CENTER><FONT COLOR="#FF0000">' . _THEREIS . " $comm " . _COMWAIT . '</FONT><br><A HREF="admin/index.php">' . _SEECOM . '</A></CENTER>';
                }    // if($comm == 0)
            }    //	if ($mode == 1)
            echo '<P>';
        }    //	if ( $xoopsUser->isAdmin()
    }    //	if ( $xoopsUser )

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jargon') . " WHERE affiche='O'"));

    [$numcats] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jargon_cat') . ''));

    if (0 == $numrows) {
        echo '<P><CENTER><FONT COLOR="#FF0000">' . _ISEMPTY . '</FONT></CENTER>';
    } else {
        echo '<center>' . _ACTUELL . " <font color=#FF0000><b>$numrows</b></font> " . _DEF . " <font color=#FF0000><b>$numcats</b></font> " . _CATS . ",\n";
    }    //	if($numrows == 0)

    $sresult = $xoopsDB->query('SELECT count(lien) as nbbbs FROM ' . $xoopsDB->prefix('jargon') . " WHERE affiche='O' AND lien<>''");

    [$nbbbs] = $xoopsDB->fetchRow($sresult);

    if (0 != $nbbbs) {
        echo "<font color=#FF0000><b>$nbbbs</b></font> " . _LINKSASS . "<br>\n";
    }    //	if($nbbbs != 0)

    $hresult = $xoopsDB->query('SELECT count(id) as nbbs FROM ' . $xoopsDB->prefix('jargon_comm') . " WHERE affiche='O'");

    [$nbbs] = $xoopsDB->fetchRow($hresult);

    if (0 != $nbbs) {
        echo '' . _AND . " <font color=#FF0000><b>$nbbs</b></font> " . _COMMGLO . ' ' . $xoopsConfig['sitename'] . "</center><P>\n";
    }    //	if($nbbs != 0)

    echo "<table class='outer' width='50%' cellpadding='0' cellspacing'0' align='center'>
			<tr><td>
			<FORM ACTION=\"jargon-rech.php\" METHOD=POST>";

    echo "
	<br><center><B class='bgyellow'>" . _LOOKFOR . '</b><br>
	<SELECT NAME="type">
	<OPTION  VALUE="1"> ' . _TERME . '
	<OPTION  VALUE="2"> ' . _DEF2 . '
	<OPTION  VALUE="3"> ' . _TERMEANDDEF . '
	</SELECT>
	</center>';

    // recherche par catégorie ou toutes les catégories

    echo "<br><center><B class='bgyellow'>" . _LOOKFOR . ' ' . _CATS . '</b><br>';

    // recherche des catégories

    $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('jargon_cat') . ' ');

    // affichage des catégories

    echo '<select name="idcat">';

    echo "<option value='0'>0 : " . _ALLOPTIONS . ' </option>';

    while (list($idcat, $nomcat) = $xoopsDB->fetchRow($result_cat)) {
        $idcat = $myts->displayTarea($idcat);

        $nomcat = $myts->displayTarea($nomcat);

        echo "<option value='$idcat'>$idcat : $nomcat </option>";
    }    //	while (list($idcat, $nomcat)

    echo '</select>';

    echo "<br><br><B class='bgyellow'>" . _LOOKINGFOR . '</B>
	<br>
	<INPUT TYPE="text" CLASS=textbox NAME="terme" SIZE=20>
	<INPUT TYPE="submit" VALUE="Go">
	</center>';

    echo '</FORM><br>';

    echo "<br><center><B class='bgyellow'>" . _LOOKFORL . '</B></center>';

    LettreGlo();

    echo "</td></tr></table><br><P>\n";

    MenuGlo();    // Affichage du menu du jargon
    NouvDef();    // Affichage des nouvelles définitions
    Copyright();    // Affichage du copyright - Laisser en place pour respecter la licence GPL
}

function Propose($nouvdef)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $pa;

    $myts = MyTextSanitizer::getInstance();

    if (1 == $nouvdef || $xoopsUser) {
        echo '<B>' . _PRODEF2 . '</B><P>' . _YOUKNOW . "<P>\n";

        echo "<FORM ACTION=\"jargon-ajout.php?pa=Propdef\" METHOD=POST>
	<INPUT TYPE=\"hidden\" NAME=\"lettre\" VALUE=\"$lettre\">
	<INPUT TYPE=\"hidden\" NAME=\"affiche\" VALUE=\"N\">
	<INPUT TYPE=\"hidden\" NAME=\"logname\" VALUE=\"";

        if ($xoopsUser) {
            echo $xoopsUser->getVar('uname', 'E');
        } else {
            echo $xoopsConfig['anonymous'];
        }

        echo '">';

        echo '<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=3>
    <TR>
      <TD ALIGN="LEFT">' . _LETTRE2 . ' </TD>
      <TD>';

        LettreGloAj();

        echo '</TD>
    </TR>

	<tr><td>' . _CAT2 . '</td><td>';

        // recherche des catégories

        $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('jargon_cat') . ' ');

        // affichage des catégories

        echo '<select name="idcat">';

        while (list($idcat, $nomcat) = $xoopsDB->fetchRow($result_cat)) {
            $idcat = $myts->displayTarea($idcat);

            $nomcat = $myts->displayTarea($nomcat);

            echo "<option value='$idcat'>$idcat : $nomcat </option>";
        }

        echo '</select></td></tr>
    		<TR>
 		     <TD>' . _TERME2 . ' </TD>
 		     <TD><INPUT TYPE="text" NAME="terme" SIZE=25></TD>
		    </TR>
		    <TR>
 		     <TD>' . _DEF3 . ' </TD>
 		     <TD><TEXTAREA NAME="def" COLS=40 ROWS=8></TEXTAREA></TD>
 		   </TR>
 		   <TR>
 		     <TD>' . _LINKSASS2 . ' </TD>
  		     <TD><INPUT TYPE="text" NAME="lien" SIZE=50><br><FONT SIZE=1>Ex. : http://www.' . _NAMESIT . '.com</FONT></TD>
		    </TR>
 		   <TR>
 		     <TD COLSPAN=2>&nbsp;</TD>
 		   </TR>
 		   <TR>
 		     <TD COLSPAN=2><INPUT TYPE="submit" VALUE="' . _PRODEF3 . '"></TD>
		    </TR>
		</TABLE></FORM>';

        echo '<CENTER> [ <A HREF="index.php">' . _WELCOMEGLO . '</A> | ' . _PRODEF . ' | <A HREF="index.php?pa=Demande">' . _ASKDEF . "</A> ]</CENTER>\n";
    } else {
        echo '' . _MEMBERONLY . ' <a href=../../register.php>' . _REGISTER . '</a>';
    }

    Copyright();
}

function Demande()
{
    global $xoopsUser, $xoopsConfig, $pa;

    echo '<B>' . _ASKDEF . '</B><P>' . _HEY . "<P>\n";

    echo '<FORM ACTION="jargon-ajout.php?pa=demdef" METHOD=POST><INPUT TYPE="hidden" NAME="affiche" VALUE="D"><INPUT TYPE="hidden" NAME="logname" VALUE="';

    if ($xoopsUser) {
        echo $xoopsUser->getVar('uname', 'E');
    } else {
        echo $xoopsConfig['anonymous'];
    }

    echo '">
		<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=3>
		    <TR>
		      <TD>' . _LOOKINGFOR . ' </TD>
		      <TD><INPUT TYPE="text" CLASS=textbox NAME="terme" SIZE=25></TD>
		    </TR>
		    <TR>
 		     <TD COLSPAN=2>&nbsp;</TD>
		    </TR>
		    <TR>
		      <TD COLSPAN=2><INPUT TYPE="submit" VALUE="' . _SEND . '"></TD>
		    </TR>
		</TABLE></FORM>';

    echo '<CENTER> [ <A HREF="index.php">' . _WELCOMEGLO . '</A> | <A HREF="index.php?pa=Propose">' . _PRODEF . '</A> | ' . _ASKDEF . " ]</CENTER>\n";
}

switch ($pa) {
    case 'Demande':
        Demande();
        break;
    case 'Propose':
        Propose($nouvdef);
        break;
    default:
        Start();
        break;
}

require XOOPS_ROOT_PATH . '/footer.php';
