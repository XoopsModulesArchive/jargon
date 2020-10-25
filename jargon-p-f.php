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

if (!isset($sid)) {
    exit();
}

function EnvDef($sid)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig;

    $myts = MyTextSanitizer::getInstance();

    $result = $xoopsDB->query('select idcat, nom, definition, lien from ' . $xoopsDB->prefix('jargon') . " where id=$sid");

    [$idcat, $nom, $definition, $lien] = $xoopsDB->fetchRow($result);

    $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('jargon_cat') . " where idcat=$idcat");

    [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

    $nom = $myts->displayTarea($nom);

    $definition = $myts->displayTarea($definition);

    echo '<center>
    <b>' . _SENDTO . "<b>$nom</b> " . _INTHISCAT . " <b>$nomcat</b> " . _FRIEND . "<br><br>
    <form action=\"jargon-p-f.php\" method=post>
    <input type=hidden name=sid value=$sid>";

    if ($xoopsUser) {
        $name = $xoopsUser->getVar('uname', 'E');

        $email = $xoopsUser->getVar('email', 'E');
    }

    echo '
	<TABLE BORDER=0 align=center>
    <TR>
      <TD>' . _NAME . " </TD>
      <TD><input class=textbox type=text name=\"yname\" value=\"$name\"></TD>
    </TR>
    <TR>
      <TD>" . _MAIL . " </TD>
      <TD><input class=textbox type=text name=\"ymail\" value=\"$email\"></TD>
    </TR>
    <TR>
      <TD COLSPAN=2>&nbsp;</TD>
    </TR>
    <TR>
      <TD>" . _NAMEFR . ' </TD>
      <TD><input class=textbox type=text name="fname"></TD>
    </TR>
    <TR>
      <TD>' . _MAILFR . ' </TD>
      <TD><input class=textbox type=text name="fmail"></TD>
    </TR>
</TABLE>
    <input type=hidden name=op value=MailDef><br>
    <input type=submit value=' . _SENDFR . '><br><br>
    <INPUT TYPE="BUTTON" VALUE="' . _CLOSEWINDOW . '" ONCLICK="window.close()"> 
</FORM></center> 
';

    Copyright();

    //include $xoopsConfig['root_path']."footer.php";

    exit;
}

function MailDef($sid, $yname, $ymail, $fname, $fmail, $nom, $definition, $lien)
{
    global $xoopsConfig, $xoopsUser, $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    if ('jargon' == $xoopsConfig['startpage']) {
        $xoopsOption['show_rblock'] = 1;

        require XOOPS_ROOT_PATH . '/header.php';

        make_cblock();

        echo '<br>';
    } else {
        $xoopsOption['show_rblock'] = 0;

        require XOOPS_ROOT_PATH . '/header.php';
    }

    $result2 = $xoopsDB->query('select idcat, nom, definition, lien from ' . $xoopsDB->prefix('jargon') . " where id=$sid");

    [$idcat, $nom, $definition, $lien] = $xoopsDB->fetchRow($result2);

    $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('jargon_cat') . " where idcat=$idcat");

    [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

    $nom = $myts->displayTarea($nom);

    $definition = htmlspecialchars($definition, ENT_QUOTES | ENT_HTML5);

    if ($lien) {
        $texte_lien = '' . _LINKSASS2 . " $lien\n";
    }

    $subject = '' . _SUBJET . ' ' . $xoopsConfig['sitename'] . '';

    $message = '' . _HELLOS . " $fname,\n\n$yname " . _MESSAGE . "\n\n\n" . _TERME2 . " <b>$nom</b> " . _INTHISCAT . " <b>$nomcat</b>\n\n" . _DEF3 . " $definition\n\n$texte_lien\n\n" . _INTERESS . ' ' . $xoopsConfig['sitename'] . "\n" . XOOPS_URL . '/jargon.php';

    mail($fmail, $subject, $message, "From: \"$yname\" <$ymail>\nX-Mailer: PHP/" . phpversion());

    redirect_header('index.php', 1, '' . _TERMEDEF . " <b>$nom</b> " . _INTHISCAT . " $nomcat " . _TERMESEND . " $fname...");

    exit();
}

function ImprDef($sid)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig;

    $myts = MyTextSanitizer::getInstance();

    $result = $xoopsDB->query('SELECT idcat, nom, definition, lien FROM ' . $xoopsDB->prefix('jargon') . " where id=$sid");

    [$idcat, $nom, $definition, $lien] = $xoopsDB->fetchRow($result);

    $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('jargon_cat') . " where idcat=$idcat");

    [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

    $nom = $myts->displayTarea($nom);

    $definition = $myts->displayTarea($definition);

    $nomcat = $myts->displayTarea($nomcat);

    echo '
    <html>
    <head><title>' . $xoopsConfig['sitename'] . '</title></head>
    <body bgcolor="#FFFFFF" text="#000000">
    <table border=0 align="center"><tr><td>
    
    <table border=0 width=640 cellpadding=0 cellspacing=1 bgcolor="#000000"><tr><td>
    <table border=0 width=640 cellpadding=20 cellspacing=1 bgcolor="#FFFFFF"><tr><td>
    <center><img src="' . XOOPS_URL . "/images/logo.gif\" border=0 alt=\"\"></center><P><br>
    <font face=\"$site_font\">
    <font size=2><b>" . _TERME2 . " : </b> \"$nom\" " . _INTHISCAT . " <b>$nomcat</b><P><DIV STYLE=\"text-align:justify;\"><b>" . _DEF3 . " : </b> $definition</DIV>";

    if ($lien) {
        echo '<P><B>' . _LINKSASS2 . "</B> <A HREF=\"$lien\" TARGET=\"_blank\">$lien</A>";
    }

    $hresult = $xoopsDB->query('SELECT commentaire, url FROM ' . $xoopsDB->prefix('jargon_comm') . " WHERE def='$sid'");

    while (list($commentaires, $urls) = $xoopsDB->fetchRow($hresult)) {
        $commentaires = $myts->displayTarea($commentaires);

        if ($hresult) {
            echo '<br><P><B>' . _COM2 . "</B>$commentaires<br>";

            if ($urls) {
                echo '<br>' . _LINKSASS2 . " <A HREF=\"$urls\" TARGET=\"_blank\">$urls</A>";
            }

            echo '<P>';
        }
    }

    echo "
	<br><br>
    </td></tr></table></td></tr></table>
    <br><br><center>
    <font face=\"$site_font\" size=2>" . _EXTRGLO . ' ' . $xoopsConfig['sitename'] . ' :<br>
    <a href="' . XOOPS_URL . '/modules/jargon/">' . XOOPS_URL . '/modules/jargon/</a>
    </td></tr></table><center>
	<FORM> 
<INPUT TYPE="BUTTON" VALUE="Fermer cette fenêtre !!" ONCLICK="window.close()"> 
</FORM></center>

    </body>
    </html>
    ';
}

switch ($op) {
    case 'EnvDef':
        EnvDef($sid);
        break;
    case 'MailDef':
        MailDef($sid, $yname, $ymail, $fname, $fmail, $nom, $definition, $lien);
        break;
    case 'ImprDef':
        ImprDef($sid);
        break;
    default:
        redirect_header('index.php', 1, '' . _RETURNGLO . '');
        break;
}
require XOOPS_ROOT_PATH . '/footer.php';
