<?php

return [
    'payment' => [
        'invalid-gateway' => 'Ungültiges Zahlungsgateway, versuchen Sie es erneut...',
        'paypal-failed' => 'Paypal-Zahlung ist derzeit nicht verfügbar. Bitte versuchen Sie es später erneut.',
        'paypal-not-captured' => 'Die Zahlung wurde nicht erfasst. Bitte versuchen Sie es später noch einmal',
        'canceled' => 'Zahlung storniert',
    ],
    'cart' => [
        'cleared' => 'Warenkorb geleert',
        'removed_tour' => 'Tour entfernt',
        'loaded' => 'Warenkorb erfolgreich geladen',
        'empty' => 'Der Warenkorb ist leer',
        'tour_add_to_cart_successfully' => 'Tour erfolgreich zum Warenkorb hinzugefügt',
        'rental_add_to_cart_successfully' => 'Vermietung erfolgreich zum Warenkorb hinzugefügt',
        'invalid_start_date' => 'Das Startdatum muss nach oder gleich heute für :tour liegen.',
        'tour-not-available' => 'Tour nicht verfügbar am :date',
    ],
    'coupons' => [
        'expired' => 'Gutschein abgelaufen',
        'not_available' => 'Gutschein ist nicht verfügbar',
        'applied' => 'Gutschein erfolgreich angewendet',
        'login-first-to-use-coupon' => 'Um diesen Gutschein nutzen zu können, müssen Sie sich zunächst anmelden',
        'invalid-tours' => 'Der Gutschein ist für ausgewählte Touren nicht verfügbar',
        'invalid-tour-categories' => 'Der Gutschein ist für ausgewählte Tourkategorien nicht verfügbar',
    ],
    'exceptions' => [
        'not-found' => ':model Nicht gefunden',
    ],
    'bookings' => [
        'empty-cart' => 'Ihr Warenkorb ist leer. Bitte fügen Sie Touren zu Ihrem Warenkorb hinzu',
        'created' => 'Ihre Buchung wurde erfolgreich erstellt',
        'payment-redirect' => 'Zahlungsabwicklung, Weiterleitung.......',
        'error' => 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal',
        'payment-error' => 'Zahlungsfehler: :message',
        'payment_verified' => 'Zahlung bestätigt',
    ],
    'custom-trips' => [
        'created' => 'Ihre Anfrage wurde erfolgreich gesendet',
    ],
    'tour' => [
        'reviews' => [
            'added' => 'Bewertung erfolgreich hinzugefügt',
        ],
    ],
    'contact-request' => [
        'sent' => 'Ihre Nachricht wurde gesendet',
        'invalid_or_spam_email' => 'Leider können wir Ihre Nachricht derzeit nicht senden. Bitte versuchen Sie es später erneut',
    ],
    'car-rental' => [
        'found' => 'Passende Route gefunden',
        'not-found' => 'Keine Route für übermittelte Standorte. Versuchen Sie, andere Standorte auszuwählen',
        'invalid-stop-location' => 'Ungültiger Stopport (:location_name)',
        'no-price-group-found' => 'Für diese Routenanzahl ist kein Auto verfügbar',
        'sent' => 'Ihre Anfrage wurde gesendet',
    ],
    'password' => [
        'forget' => 'Passwort vergessen otp gesendet, überprüfen Sie Ihre E-Mails.',
        'otp_expired' => 'OTP abgelaufen.',
        'otp_invalid' => 'Ungültiges OTP.',
        'reset' => 'Ihr Passwort wurde zurückgesetzt.',
    ],
    'notifications' => [
        'greeting' => 'Hallo :name,',
        'thanks_for_using_our_app' => 'Vielen Dank, dass Sie unsere Anwendung nutzen!',
        'password' => [
            'forget' => [
                'otp' => 'OTP-Passwort vergessen',
                'request_received' => 'Wir haben eine Anfrage zum Zurücksetzen Ihres Passworts erhalten.',
                'dont_worry' => 'Machen Sie sich keine Sorgen, wir helfen Ihnen',
                'request_otp' => 'Ihr Opt-Code: :otp',
                'ignore_mail' => 'Ignorieren Sie diese E-Mail, wenn nicht Sie.',
                'try_after_60' => 'Bitte versuchen Sie es nach 60 Sekunden',
            ],
        ],
    ],
    'auth' => [
        'registered' => 'Sie haben sich erfolgreich registriert. Melden Sie sich jetzt an',
        'logged_in_successfully' => 'Sie haben sich erfolgreich angemeldet',
    ],
    'booking-not-found' => 'Buchung nicht gefunden',
];
