<?php
$baseUrl= (isset($_SERVER['HTTPS']) ? "https://" : "http://").''.$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['SCRIPT_NAME']),'',$_SERVER['SCRIPT_NAME']);
$homeUrl= $baseUrl."administrator/";
return [
    'senderEmail' => 'Admin Russindo',
    'senderName' => 'Admin Russindo',
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'passwordResetTokenExpire' => 3600,
    'manipulateId' => 0.0075,
    'suratLink' => 'https://staff.sekolahalhuda.sch.id',
    'drive' => [
        'application_name'=> 'Google Drive Smart School',
        'client_id'       => NULL,
        'client_secret'   => NULL,
        'redirect_uri'    => $homeUrl."callback/index",
        'folder'          => '1ifxN2Y2CttELIY7OLUnVo9-8t2b-_XsF',
        'permission'      => 'public',
        'scopes'          => [
            \Google_Service_Drive::DRIVE,
            \Google_Service_Oauth2::USERINFO_EMAIL,
            \Google_Service_Oauth2::USERINFO_PROFILE,
            \Google_Service_Drive::DRIVE_READONLY,
            \Google_Service_Drive::DRIVE_METADATA_READONLY,
            \Google_Service_Drive::DRIVE_METADATA,
            \Google_Service_Drive::DRIVE_FILE,
            \Google_Service_Drive::DRIVE_SCRIPTS
        ],
        'accessType'     => 'offline',
        'approvalPrompt' => 'force',
        'token' => "../public/credentials/token.json",
        'credentials'=>'../public/credentials/credentials.json',
        'urlOpen' => 'https://drive.google.com/uc?export=view&id=',
    ],
];