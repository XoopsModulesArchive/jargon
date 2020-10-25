<?php

// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       < http://xoops.eti.br >                             //
// ------------------------------------------------------------------------- //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
function jargon_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    define('_INTHISCAT', 'dans la cat&eacute;gorie');

    $sql = 'SELECT id, idcat, lettre, nom, definition FROM ' . $xoopsDB->prefix('jargon') . " WHERE affiche='O'";

    if (0 != $userid) {
        $sql .= ' AND author=' . $userid . ' ';
    }

    // because count() returns 1 even if a supplied variable

    // is not an array, we must check if $querryarray is really an array

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((nom LIKE '%$queryarray[0]%' OR definition LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";

            $sql .= "(nom LIKE '%$queryarray[$i]%' OR definition LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') ';
    }

    $sql .= 'ORDER BY nom DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $i = 0;

    $ret = [];

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['image'] = 'images/info.png';

        $ret[$i]['link'] = 'jargon-aff.php?lettre=' . $myrow['lettre'] . '&idcat=' . $myrow['idcat'] . '&nom=' . $myrow['nom'] . '';

        $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('jargon_cat') . ' WHERE idcat=' . $myrow['idcat'] . '');

        [$tempidcat, $tempnomcat] = $xoopsDB->fetchRow($result_cat);

        $ret[$i]['title'] = $myrow['nom'] . ' - ' . _INTHISCAT . ' : ' . $tempnomcat;

        $i++;
    }

    return $ret;
}
