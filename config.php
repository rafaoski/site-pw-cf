<?php

/**
 * ProcessWire Configuration File
 *
 * Site-specific configuration for ProcessWire.
 * This config.php file was generated by the ProcessExportProfile module.
 *
 * Please see the file /wire/config.php which contains all configuration options you may
 * specify here. Simply copy any of the configuration options from that file and paste
 * them into this file in order to modify them.
 *
 * ProcessWire
 * Copyright (C) 2017 by Ryan Cramer
 * Licensed under MPL 2.0
 *
 * https://processwire.com
 *
 */

if(!defined("PROCESSWIRE")) die();

/*** SITE CONFIG *************************************************************************/

/** @var Config $config */

$config->prependTemplateFile = '_init.php';
$config->appendTemplateFile = '_main.php';
$config->useMarkupRegions = true;
$config->useFunctionsAPI = true;
$config->defaultAdminTheme = 'AdminThemeUikit';

// https://processwire.com/blog/posts/webp-images-on-an-existing-site/
$config->useWebP = true;

// https://processwire.com/blog/posts/processwire-core-updates-2.5.14/#multiple-templates-or-parents-for-users
// $config->userTemplateIDs = array(3, 54);
// $config->usersPageIDs = array(29, 1176);

/**
 * Example Custom Config
 * https://processwire.com/api/variables/config/
 * https://processwire.com/blog/posts/pw-3.0.87/#new-field-template-context-settings
 */

/** Advanced Configuration **/
// $config->advanced = true;

/** Show demo site **/
// $config->demo = true;

/** https://processwire.com/blog/posts/pw-3.0.99/ **/
// $config->noHTTPS = true;

/**  ignore HTTPS for this hostname only: **/
// $config->noHTTPS = 'dev.processwire.com';

/** ignore HTTPS for these hostnames: **/
//     $config->noHTTPS = [
//       'dev.processwire.com',
//       'stage.processwire.com',
//       'localhost:8888',
//     ];

/*** INSTALLER CONFIG ********************************************************************/


