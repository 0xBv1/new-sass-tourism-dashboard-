<?php

return [
    'payment' => [
        'invalid-gateway' => 'Gateway di pagamento non valido, riprova...',
        'paypal-failed' => 'Pagamento Paypal non disponibile al momento, riprova più tardi.',
        'paypal-not-captured' => 'Il pagamento non è stato acquisito, riprova più tardi',
        'canceled' => 'Pagamento annullato',
    ],
    'cart' => [
        'cleared' => 'Carrello svuotato',
        'removed_tour' => 'Tour rimosso',
        'loaded' => 'Carrello caricato con successo',
        'empty' => 'Il carrello è vuoto',
        'tour_add_to_cart_successfully' => 'Tour aggiunto al carrello con successo',
        'rental_add_to_cart_successfully' => 'Noleggio aggiunto al carrello con successo',
        'invalid_start_date' => 'La data di inizio deve essere successiva o uguale a oggi per :tour',
        'tour-not-available' => 'Tour non disponibile alle :date',
    ],
    'coupons' => [
        'expired' => 'Coupon scaduto',
        'not_available' => 'Il buono non è disponibile',
        'applied' => 'Coupon applicato correttamente',
        'login-first-to-use-coupon' => 'Dovresti prima effettuare il login per utilizzare questo coupon',
        'invalid-tours' => 'Coupon non disponibile per tour selezionati',
        'invalid-tour-categories' => 'Coupon non disponibile per le categorie di tour selezionate',
    ],
    'exceptions' => [
        'not-found' => ':model Non trovato',
    ],
    'bookings' => [
        'empty-cart' => 'Il tuo carrello è vuoto, aggiungi i tour al carrello',
        'created' => 'La tua prenotazione è stata creata con successo',
        'payment-redirect' => 'Elaborazione dei pagamenti, reindirizzamento........',
        'error' => 'Qualcosa è andato storto, riprova più tardi',
        'payment-error' => 'Errore nel pagamento: :message',
        'payment_verified' => 'Pagamento verificato',
    ],
    'custom-trips' => [
        'created' => 'La tua richiesta è stata inviata con successo',
    ],
    'tour' => [
        'reviews' => [
            'added' => 'Recensione aggiunta con successo',
        ],
    ],
    'contact-request' => [
        'sent' => 'Il tuo messaggio è stato inviato',
        'invalid_or_spam_email' => 'Siamo spiacenti, non possiamo inviare il tuo messaggio in questo momento, riprova più tardi',
    ],
    'car-rental' => [
        'found' => 'Trovato percorso corrispondente',
        'not-found' => 'Nessun percorso per le località inviate, prova a scegliere un\'altra località',
        'invalid-stop-location' => 'Posizione della fermata non valida (:location_name)',
        'no-price-group-found' => 'Nessuna auto disponibile per questo numero di membri del percorso',
        'sent' => 'La tua richiesta è stata inviata',
    ],
    'password' => [
        'forget' => 'Password dimenticata o inviata, controlla la posta.',
        'otp_expired' => 'OTP scaduta.',
        'otp_invalid' => 'OTP non valida.',
        'reset' => 'La tua password è stata reimpostata.',
    ],
    'notifications' => [
        'greeting' => 'CIAO :name,',
        'thanks_for_using_our_app' => 'Grazie per aver utilizzato la nostra applicazione!',
        'password' => [
            'forget' => [
                'otp' => 'Dimentica la password OTP',
                'request_received' => 'Abbiamo ricevuto una richiesta per reimpostare la tua password.',
                'dont_worry' => 'Non preoccuparti, ti aiuteremo',
                'request_otp' => 'Il tuo codice di scelta: :otp',
                'ignore_mail' => 'Ignora questa mail se non tu.',
                'try_after_60' => 'Riprova dopo 60 secondi',
            ],
        ],
    ],
    'auth' => [
        'registered' => 'Ti sei registrato con successo, accedi adesso',
        'logged_in_successfully' => 'Hai effettuato l\'accesso con successo',
    ],
    'booking-not-found' => 'Prenotazione non trovata',
];
