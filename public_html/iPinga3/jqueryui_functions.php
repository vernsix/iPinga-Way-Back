<?php

/*
Vern Six MVC Framework version 3.0

Copyright (c) 2007-2015 by Vernon E. Six, Jr.
Author's websites: http://www.iPinga.com and http://www.VernSix.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to use
the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice, author's websites and this permission notice
shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
IN THE SOFTWARE.
*/
defined('__VERN') or die('Restricted access');

/*
* What skin should we be using?
* I assume you have a database table for the user and the client. Both of these table should have a column named
* 'skin'.  If the user->skin is specified it is used, otherwise the client->skin is used if it's
* specified and if both are empty, I use my personal favorite 'cupertino'
*
* @param v6_table $user
* @param v6_table $client
*/
function v6_skin($user, $client = NULL)
{
    $userSkin = trim($user->field['skin']);

    if (isset($client) == true) {
        $clientSkin = trim($client->field['skin']);
    } else {
        $clientSkin = '';
    }

    if (empty($userSkin) == false) {
        $result = $userSkin;
    } else if (empty($clientSkin) == false) {
        $result = $clientSkin;
    } else {
        $result = 'cupertino';
    }
    return $result;
}

function v6_ArrayOfSkins()
{
    $skins                   = array();
    $skins["black-tie"]      = "Black Tie";
    $skins["blitzer"]        = "Blitzer";
    $skins["cupertino"]      = "Cupertino";
    $skins["dark-hive"]      = "Dark Hive";
    $skins["dot-luv"]        = "Dot Luv";
    $skins["eggplant"]       = "Eggplant";
    $skins["excite-bike"]    = "Excite Bike";
    $skins["flick"]          = "Flick";
    $skins["hot-sneaks"]     = "Hot Sneaks";
    $skins["humanity"]       = "Humanity";
    $skins["le-frog"]        = "Le Frog";
    $skins["ui-lightness"]   = "UI Lightness";
    $skins["mint-choc"]      = "Mint Chocolate";
    $skins["overcast"]       = "Overcast";
    $skins["pepper-grinder"] = "Pepper Grinder";
    $skins["redmond"]        = "Redmond";
    $skins["smoothness"]     = "Smoothness";
    $skins["south-street"]   = "South Street";
    $skins["start"]          = "Start";
    $skins["sunny"]          = "Sunny";
    $skins["swanky-purse"]   = "Swanky Purse";
    $skins["trontastic"]     = "Trontastic";
    $skins["ui-darkness"]    = "UI Darkness";
    $skins["vader"]          = "Vader";
    return $skins;
}


?>