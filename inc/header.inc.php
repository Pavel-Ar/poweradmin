<?php

/*  Poweradmin, a friendly web-based admin tool for PowerDNS.
 *  See <https://www.poweradmin.org> for more details.
 *
 *  Copyright 2007-2009  Rejo Zenger <rejo@zenger.nl>
 *  Copyright 2010-2022  Poweradmin Development Team
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * Web interface header
 *
 * @package     Poweradmin
 * @copyright   2007-2010 Rejo Zenger <rejo@zenger.nl>
 * @copyright   2010-2022  Poweradmin Development Team
 * @license     https://opensource.org/licenses/GPL-3.0 GPL
 */
use Poweradmin\AppFactory;

global $iface_style;
global $iface_title;
global $ignore_install_dir;
global $session_key;

header('Content-type: text/html; charset=utf-8');

$app = AppFactory::create();
$app->render('header.html', [
    'iface_title' => $iface_title,
    'iface_style' => $iface_style,
    'file_version' => time(),
    'custom_header' => file_exists('templates/custom/header.html'),
]);

if ($ignore_install_dir == false && file_exists('install')) {
    echo "<div>\n";
    error(ERR_INSTALL_DIR_EXISTS);
    include('inc/footer.inc.php');
    exit();
}

if (isset($_SESSION ["userid"])) {
    $perm_search = do_hook('verify_permission', 'search');
    $perm_view_zone_own = do_hook('verify_permission', 'zone_content_view_own');
    $perm_view_zone_other = do_hook('verify_permission', 'zone_content_view_others');
    $perm_supermaster_view = do_hook('verify_permission', 'supermaster_view');
    $perm_zone_master_add = do_hook('verify_permission', 'zone_master_add');
    $perm_zone_slave_add = do_hook('verify_permission', 'zone_slave_add');
    $perm_supermaster_add = do_hook('verify_permission', 'supermaster_add');
    $perm_is_godlike = do_hook('verify_permission', 'user_is_ueberuser');

    if ($perm_is_godlike && $session_key == 'p0w3r4dm1n') {
        error(ERR_DEFAULT_CRYPTOKEY_USED);
        echo "<br>";
    }

    echo "    <div class=\"menu\">\n";
    echo "    <span class=\"menuitem\"><a href=\"index.php\">" . _('Index') . "</a></span>\n";
    if ($perm_search) {
        echo "    <span class=\"menuitem\"><a href=\"search.php\">" . _('Search zones and records') . "</a></span>\n";
    }
    if ($perm_view_zone_own || $perm_view_zone_other) {
        echo "    <span class=\"menuitem\"><a href=\"list_zones.php\">" . _('List zones') . "</a></span>\n";
    }
    if ($perm_zone_master_add) {
        echo "    <span class=\"menuitem\"><a href=\"list_zone_templ.php\">" . _('List zone templates') . "</a></span>\n";
    }
    if ($perm_supermaster_view) {
        echo "    <span class=\"menuitem\"><a href=\"list_supermasters.php\">" . _('List supermasters') . "</a></span>\n";
    }
    if ($perm_zone_master_add) {
        echo "    <span class=\"menuitem\"><a href=\"add_zone_master.php\">" . _('Add master zone') . "</a></span>\n";
    }
    if ($perm_zone_slave_add) {
        echo "    <span class=\"menuitem\"><a href=\"add_zone_slave.php\">" . _('Add slave zone') . "</a></span>\n";
    }
    if ($perm_supermaster_add) {
        echo "    <span class=\"menuitem\"><a href=\"add_supermaster.php\">" . _('Add supermaster') . "</a></span>\n";
    }
    if ($perm_zone_master_add) {
        echo "    <span class=\"menuitem\"><a href=\"bulk_registration.php\">" . _('Bulk registration') . "</a></span>\n";
    }
    if ($_SESSION ["auth_used"] != "ldap") {
        echo "    <span class=\"menuitem\"><a href=\"change_password.php\">" . _('Change password') . "</a></span>\n";
    }
    echo "    <span class=\"menuitem\"><a href=\"users.php\">" . _('User administration') . "</a></span>\n";
    echo "    <span class=\"menuitem\"><a href=\"index.php?logout\">" . _('Logout') . "</a></span>\n";
    echo "    </div> <!-- /menu -->\n";
}
echo "    <div class=\"content\">\n";
