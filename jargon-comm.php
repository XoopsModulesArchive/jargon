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
require XOOPS_ROOT_PATH . '/header.php';

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

function LirComm($sid)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $mode, $anocomm;

    $myts = MyTextSanitizer::getInstance();

    echo '<center>';

    echo '<B>' . _COMM4 . "</B><br>\n";

    MenuGlo();

    echo '<B>' . _DEF3 . '</B><br>';

    $TableRep = $xoopsDB->query('SELECT nom, idcat, definition, lien FROM ' . $xoopsDB->prefix('jargon') . " WHERE affiche='O' AND id=$sid");

    $NombreEntrees = $xoopsDB->getRowsNum($TableRep);

    [$nom, $idcat, $definition, $lien] = $xoopsDB->fetchRow($TableRep);

    $result_cat = $xoopsDB->query('SELECT idcat, nomcat FROM ' . $xoopsDB->prefix('jargon_cat') . " WHERE idcat=$idcat");

    [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

    $nom = $myts->displayTarea($nom);

    $definition = $myts->displayTarea($definition);

    if (0 == $NombreEntrees) {
        echo '<P>' . _NONEDEF . '';
    } else {
        echo _CAT . " <b>$nomcat</b><br>" . _TERME2 . " : <B>$nom</B><br><table width='75%' class='outer' cellpadding='3' cellspacing='2'><tr><td align='left'>$definition</td></tr></table>";

        if ($lien) {
            echo '<br>+ ' . _LINKSASS2 . " <A HREF=\"$lien\" TARGET=\"_blank\">$lien</A>";
        }

        $result = $xoopsDB->query('SELECT id, auteur, date, commentaire, url FROM ' . $xoopsDB->prefix('jargon_comm') . " where affiche='O' AND def=$sid ORDER BY date");

        $Entrees = $xoopsDB->getRowsNum($result);

        if (0 != $Entrees) {
            echo '<P><B>' . _COM2 . '</B><br>';

            while (list($bid, $aut, $date, $commentaire, $url) = $xoopsDB->fetchRow($result)) {
                $commentaire = $myts->displayTarea($commentaire);

                $dats = formatTimestamp($date, $format = 's');

                $auteur = $xoopsDB->query('SELECT uid FROM ' . $xoopsDB->prefix('users') . " where uname='$aut'");

                [$uid] = $xoopsDB->fetchRow($auteur);

                if ($uid) {
                    echo '' . _FROM . " <A HREF=\"../../userinfo.php?uid=$uid\">$aut</A> " . _ON . " $dats<br><table width='75%' class='outer' cellpadding='3' cellspacing='2'><tr><td align='left'>$commentaire</td></tr></table>";
                } else {
                    echo '' . _FROM . " $aut " . _ON . " $dats<br><table width='75%' class='outer' cellpadding='3' cellspacing='2'><tr><td align='left'>$commentaire</td></tr></table>";
                }

                if ($url) {
                    echo "<table><tr><td align='left'>" . _LINKSASS2 . " <A HREF=\"$url\" TARGET=\"_blank\">$url</A></td></tr></table>";
                }

                if ($xoopsUser) {
                    if ($xoopsUser->isAdmin()) {
                        echo '<br>[ <A HREF="admin/mod-comm.php?pa=modif&id=' . $bid . '">' . _MODIFY . '</A> | <A HREF="admin/mod-comm.php?pa=supprcomm&id=' . $bid . '">' . _SUPPR . '</A> ]<P>';
                    }
                } else {
                    echo '<P>';
                }
            }
        }
    }

    echo '<P><CENTER>[ <a href="javascript:history.go(-1)">' . _GOBACK . '</a>';

    if (1 == $anocomm || $xoopsUser) {
        echo " | <a href=\"jargon-comm.php?sid=$sid\">" . _COMMADD . '</a>';
    }

    echo ' ]</CENTER>';

    //Copyright();

    //require XOOPS_ROOT_PATH."/footer.php";

    echo '</center>';
}

function CoMm($sid, $mode)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig;

    include 'header.php';

    $myts = MyTextSanitizer::getInstance();

    echo '<B>' . _JARGON . "</B><br>\n";

    MenuGlo();

    echo '<B>' . _COMMADD . '</B><P>';

    $TableRep = $xoopsDB->query('SELECT nom, idcat, definition, lien FROM ' . $xoopsDB->prefix('jargon') . " WHERE affiche='O' AND id=$sid");

    $NombreEntrees = $xoopsDB->getRowsNum($TableRep);

    [$nom, $idcat, $definition, $lien] = $xoopsDB->fetchRow($TableRep);

    $nom = $myts->displayTarea($nom);

    $definition = $myts->displayTarea($definition);

    $result_cat = $xoopsDB->query('SELECT idcat, nomcat FROM ' . $xoopsDB->prefix('jargon_cat') . " WHERE idcat=$idcat");

    [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

    if (0 == $NombreEntrees) {
        echo '<P><br>' . _NONEDEF . '<br>';
    } else {
        echo _CAT . " <b>$nomcat</b><br><br>" . _TERME2 . " : <B>$nom</B><br><br><table width='75%' cellpadding='3' cellspacing='2'><tr><td align='left'>$definition</td></tr></table>";

        if ($lien) {
            echo '<br>+ ' . _LINKSASS2 . " <A HREF=\"$lien\" TARGET=\"_blank\">$lien</A>";
        }

        $result = $xoopsDB->query('SELECT auteur, date, commentaire, url FROM ' . $xoopsDB->prefix('jargon_comm') . " where affiche='O' AND def=$sid ORDER BY date");

        $Entrees = $xoopsDB->getRowsNum($result);

        if (0 != $Entrees) {
            echo '<P><B>' . _ALLREADYPOST . '</B><br><br><HR>';

            while (list($aut, $date, $commentaire, $url) = $xoopsDB->fetchRow($result)) {
                $commentaire = $myts->displayTarea($commentaire);

                $dats = formatTimestamp($date, $format = 's');

                $auteur = $xoopsDB->query('SELECT uid FROM ' . $xoopsDB->prefix('users') . " where uname='$aut'");

                [$uid] = $xoopsDB->fetchRow($auteur);

                if ($uid) {
                    echo '<br>' . _FROM . " <A HREF=\"../../userinfo.php?uid=$uid\">$aut</A> " . _ON . " $dats<br><br>$commentaire";
                } else {
                    echo '<br>' . _FROM . " $aut " . _ON . " $dats<br><br>$commentaire";
                }

                if ($url) {
                    echo '<br><br>' . _LINKSASS2 . " <A HREF=\"$url\" TARGET=\"_blank\">$url</A><P><HR>";
                } else {
                    echo '<P><HR>';
                }
            }
        }
    }

    echo '<P><B>' . _ADDYOURCOM . '</B><P>';

    echo '<FORM ACTION="jargon-comm.php" METHOD=POST>';

    if (1 == $mode) {
        echo '<INPUT TYPE="hidden" NAME="affiches" VALUE="M">';
    } else {
        echo '<INPUT TYPE="hidden" NAME="affiches" VALUE="O">';
    }

    echo "<INPUT TYPE=\"hidden\" NAME=\"defs\" VALUE=\"$sid\">
		<INPUT TYPE=\"hidden\" NAME=\"op\" VALUE=\"AjComm\">
		<INPUT TYPE=\"hidden\" NAME=\"logname\" VALUE=\"";

    if ($xoopsUser) {
        echo $xoopsUser->getVar('uname', 'E');
    } else {
        echo $xoopsConfig['anonymous'];
    }

    echo "\">
	<table class='outer' width='50%' cellpadding='0' cellsapcing='0'><tr><td>
	<TABLE BORDER=0>
    <TR>
      <TD align='left'><b>" . _NICKNAME . '</b></TD>
      <TD><b>';

    if ($xoopsUser) {
        echo $xoopsUser->getVar('uname', 'E');
    } else {
        echo $xoopsConfig['anonymous'];
    }

    echo "</b></TD></TR>
	<TR>
		<TD align='left'>" . _COM3 . ' </TD><TD>';

    $allowbbcode = 0;

    $allowsmileys = 1;

    require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';

    if (1 == $allowbbcode) {
        xoopsCodeTarea('commentaire');
    } else {
        echo "<textarea id='commentaire' name='commentaire' wrap='virtual' cols='50' rows='10'></textarea><br>";
    }

    if (1 == $allowsmileys) {
        xoopsSmilies('commentaire');
    }

    //    	echo "<TEXTAREA class=textbox NAME=\"commentaire\" COLS=40 ROWS=5></TEXTAREA></TD>

    echo "</TD>
    </TR>
    <TR>
		<TD align='left'>" . _LINKSASS2 . " </TD>
		<TD align='left'><input class=textbox type=text name=\"urls\"><br><FONT SIZE=1 color='#FF0000'><b>Ex. : http://www." . _NAMESIT . '.com</b></FONT></TD>
    </TR></TABLE></td></tr></table><br>
		<INPUT TYPE="submit" VALUE="' . _ADD . '">
		<INPUT TYPE="BUTTON" VALUE="' . _CANCEL . '" ONCLICK="history.go(-1)"> 
</FORM>';
}

function AjComm($defs, $logname, $temp, $commentaire, $urls, $affiches)
{
    global $xoopsConfig, $xoopsUser, $xoopsDB;

    include 'header.php';

    $myts = MyTextSanitizer::getInstance();

    $temp = time();

    $defs = $myts->addSlashes($defs);

    $commentaire = $myts->addSlashes($commentaire);

    $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('jargon_comm') . " VALUES ('', '$defs', '$logname', '$temp', '$commentaire', '$urls', '$affiches')");

    if (1 == $mess) {
        if ($xoopsUser) {
            $result = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " where uname='$logname'");

            [$adrs] = $xoopsDB->fetchRow($result);
        } else {
            $adrs = $xoopsConfig['adminmail'];
        }

        $message = '' . _COMBY . " $logname\n\n" . XOOPS_URL . '/admin.php?fct=jargon';

        $subject = '' . _ADDCOMIN . '';

        mail((string)$adminmail, (string)$subject, $message, "From: $adrs");
    }

    redirect_header('index.php', 3, _WRITE);

    exit();
}

switch ($op) {
    case 'LirComm':
        LirComm($sid);
        break;
    case 'AjComm':
        AjComm($defs, $logname, $temp, $commentaire, $urls, $affiches);
        break;
    default:
        CoMm($sid, $mode);
        break;
}

Copyright();
require XOOPS_ROOT_PATH . '/footer.php';
