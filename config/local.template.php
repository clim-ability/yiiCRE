<?php
/**
 * @copyright 2018 University Library of Freiburg
 * @copyright 2018 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */

/**
 * USAGE of local.template.php:
 * Copy it to local.php.
 * Open local.php and delete this and the previous two comment lines.
 *
 * This file is the place to set private configuration data, such as usernames,
 * passwords, and other private data.
 *
 * Make sure all placeholders are set to their private values according to the
 * local configuration.
 *
 * If you add new options to this file NEVER FORGET to add these options with
 * appropriate placeholders to local.template.php and inform other users of the
 * repository that they need the new local configuration.
 * Also never add local.php directly to the repository!
 *
 * @author Michael Kahle <michael.kahle@geographie.uni-freiburg.de>
 */

// The example for DB Connection assumes a Postgres DB running on localhost
// with default port and DB-name 'db'.

// Main Database
define('LOCAL_CONFIG_DB_USER', 'creUser');
define('LOCAL_CONFIG_DB_PASSWORD', 'crePassword');
define('LOCAL_CONFIG_DB_CONNECTION', 'pgsql:host=localhost;port=5432;dbname=cre');

// Test Database: Will be used for Unittests
define('LOCAL_CONFIG_DB_TEST_USER', 'creUser');
define('LOCAL_CONFIG_DB_TEST_PASSWORD', 'crePassword');
define('LOCAL_CONFIG_DB_TEST_CONNECTION', 'pgsql:host=localhost;port=5432;dbname=cre-test');

// Security
define('LOCAL_CONFIG_COOKIE_VALIDATION_KEY', 'a cryptographically secure random string');
define('CRE_MD5_SALT', 'a cryptographically secure random string');
define('FREIDOK_MD5_SALT', 'another cryptographically secure random string');
define('CRE_WRITE_BLOCKED', false);

// Email
define('CRE_ADMIN_EMAIL', 'admin@example.com');

// Proxy
define('LOCAL_PROXY', 'tcp://proxy.example.com');

