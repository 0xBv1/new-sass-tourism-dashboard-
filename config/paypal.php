<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'client_id'         => env('PAYPAL_CLIENT_ID', "AS5r5XUR842ZKxGk2LeTcjhvB8jpgHmo9wLrXE54VW9OZ7D93bl3ZU_AJHC0aIw-4cXpYbofdSZS8vgN"),
        'client_secret'     => env('PAYPAL_CLIENT_SECRET', "EDAdkODb9vAJ0q63UoRn5evejupTqpzEusdvcM4O0BM-iVgJ09-CcCIYCSjJkpWbaApKcEzEiOpnfPaC"),
        'app_id'            => env('PAYPAL_APP_ID', 'APP-80W284485P519543T'),
    ],
    'live' => [
        'client_id'         => env('PAYPAL_CLIENT_ID', "AaBMIxddJOhCglMuvL9hSPZ0jWv32grzO_1aH4HNq7er31leSuDPdgBof3mfcWcxl1MlmO18Ks0x2YYt"),
        'client_secret'     => env('PAYPAL_CLIENT_SECRET', "EDwdQzHmJ_rIskzrB124l8iun_QLBssZobcGBAo2C6TWto0N4vhJIpRPnxw0Pdy9SxFO7VOuR-Qqnv6x"),
        'app_id'            => env('PAYPAL_APP_ID', 'APP-USBAQDK4S6HDE'),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
];
