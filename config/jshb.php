<?php

return [
    'doc_upload_api_url' => env('DOC_UPLOAD_API_URL'),
    'doc_api_token' => env('DOC_API_TOKEN'),
    'otp_dev_email' => env('OTP_DEV_EMAIL', 'gouravatced@gmail.com'),
    'otp_expiry_minutes' => env('OTP_EXPIRY_MINUTES', 10),
    'mail_noreply_username' => env('MAIL_NOREPLY_USERNAME', 'no-reply@adms.jshb.computered.co.in'),
    'mail_system_username' => env('MAIL_SYSTEM_USERNAME', 'system@adms.jshb.computered.co.in'),
    'mail_username' => env('MAIL_USERNAME', 'support@adms.jshb.computered.co.in'),
    'mail_security_username' => env('MAIL_SECURITY_USERNAME', 'security@adms.jshb.computered.co.in'),
    'allottee_portal_url' => env('ALLOTTEE_PORTAL_URL', 'http://localhost/jshb-allottees'),
    'allottee_app_url' => env('ALLOTTEE_APP_URL', config('app.url') . '/login'),
];
