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

function MenuGlo()
{
    echo '<CENTER> [ <A HREF="index.php">' . _WELCOMEGLO . '</A> | <A HREF="index.php?pa=Propose">' . _PRODEF . '</A> | <A HREF="index.php?pa=Demande">' . _ASKDEF . "</A> ]</CENTER><P>\n";
}

function Copyright()
{
    echo "<br><DIV ALIGN=\"center\"><FONT SIZE=1 class='bnewst'>"
         . _MODULEORIG
         . '<br>'
         . _JARGON
         . ' 2.2 '
         . _FOR
         . ' xoops 2.X '
         . _CREATBY
         . ' <A HREF="mailto:webmaster@toplenet.com">Martialito</A> '
         . _DE
         . ' <A HREF="http://www.toplenet.com" TARGET="_blank">http://www.toplenet.com</A></FONT></DIV>';
}

function LettreGlo()
{
    echo '<CENTER>[  ';

    $alphabet = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z',
    ];

    $num = count($alphabet) - 1;

    $counter = 0;

    while (list(, $ltr) = each($alphabet)) {
        echo "<a href=\"jargon-aff.php?lettre=$ltr\">$ltr</a>";

        if ($counter == round($num / 2)) {
            echo " ]\n<br>\n[ ";
        } elseif ($counter != $num) {
            echo "&nbsp;|&nbsp;\n";
        }

        $counter++;
    }

    echo ' | <a href="jargon-aff.php?lettre=autre">' . _OTHERS . "</a> ]</CENTER>\n\n";
}

function LettreGloAj($texte_lettre)
{
    echo "<SELECT NAME=\"lettre\">\n";

    if ($texte_lettre) {
        echo "<OPTION VALUE=\"$texte_lettre\"> $texte_lettre";
    }

    $alphabet = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z',
    ];

    $num = count($alphabet) - 1;

    $counter = 0;

    while (list(, $ltr) = each($alphabet)) {
        echo "<OPTION VALUE=\"$ltr\"> $ltr\n";

        $counter++;
    }

    echo '<OPTION VALUE="autre"> ' . _OTHERS . '';

    echo "</SELECT>\n\n";
}

function NouvDef()
{
    global $xoopsConfig, $xoopsDB, $xoopsUser;

    include 'jargon-config.php';

    $myts = MyTextSanitizer::getInstance();

    echo '<P>';

    [$nbrs] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jargon') . " WHERE affiche='O'"));

    if (0 != $nbrs) {
        echo "<center><B class='bnewst'>10 " . _LASTDEF . '</B><br><br></center>';

        echo '<HR>';

        $TableRep = $xoopsDB->query('SELECT id, idcat, lettre, nom, definition, affiche, lien FROM ' . $xoopsDB->prefix('jargon') . " WHERE affiche='O' ORDER BY id DESC", 10, 0);

        while (list($glo_id, $glo_idcat, $glo_lettre, $glo_nom, $glo_definition, $glo_affiche, $glo_lien) = $xoopsDB->fetchRow($TableRep)) {
            $glo_nom = $myts->displayTarea($glo_nom);

            $allowbbcode = 1;

            $allowhtml = 0;

            $allowsmileys = 1;

            $glo_definition = $myts->displayTarea($glo_definition, $allowhtml, $allowsmileys, $allowbbcode);

            $commD = "<a href=\"jargon-comm.php?sid=$glo_id\" target=\"_top\"><img src=\"images/edit.png\" border=0 alt=\"" . _COMMADD . '"></a>&nbsp;';

            $imprD = "<a href=\"jargon-p-f.php?op=ImprDef&sid=$glo_id\" target=\"_blank\"><img src=\"images/print_printer.png\" border=0 Alt=\"" . _PRINT . '"></a>&nbsp;';

            $envD = "<a href=\"jargon-p-f.php?op=EnvDef&sid=$glo_id\" target=\"_blank\"><img src=\"images/mail_generic.png\" border=0 Alt=\"" . _FRIENDSEND . '"></a>';

            $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('jargon_cat') . " where idcat=$glo_idcat");

            [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

            echo '<center>' . _CAT . "<B>&nbsp;$nomcat<br><br>\" $glo_nom \"</B></center><br><table align='center'><tr><td>$glo_definition</td></tr></table>";

            if ($glo_lien) {
                echo '<br>+ ' . _LINKSASS2 . " <A HREF=\"$glo_lien\" TARGET=\"_blank\">$glo_lien</A>";
            }

            [$nbbs] = $xoopsDB->fetchRow($xoopsDB->query('SELECT count(id) as nbbs FROM ' . $xoopsDB->prefix('jargon_comm') . " WHERE def='$glo_id' AND affiche='O'"));

            if ($nbbs > 0) {
                echo "<br>+ <a href=\"jargon-comm.php?op=LirComm&sid=$glo_id\">$nbbs " . _COM . '</a>';
            }

            echo '<center>';

            if (1 == $anocomm || $xoopsUser) {
                echo "<br>$commD ";
            }

            echo "$imprD $envD";

            if ($xoopsUser) {
                if ($xoopsUser->isAdmin()) {
                    echo "<br><br>[ <A HREF=\"admin/mod-def.php?pa=modif&id=$glo_id\">" . _MODIFY . "</A> | <A HREF=\"admin/suppr-def.php?id=$glo_id\">" . _SUPPR . '</A> ]';
                }
            }

            echo '</center>';

            echo '<HR>';
        }
    }
}
