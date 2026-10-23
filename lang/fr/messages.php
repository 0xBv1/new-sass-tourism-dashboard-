<?php

return [
    'payment' => [
        'invalid-gateway' => 'Passerelle de paiement invalide, réessayez...',
        'paypal-failed' => 'Le paiement Paypal n\'est pas disponible pour le moment, veuillez réessayer plus tard.',
        'paypal-not-captured' => 'Le paiement n\'est pas capturé, veuillez réessayer plus tard',
        'canceled' => 'Paiement annulé',
    ],
    'cart' => [
        'cleared' => 'Panier vidé',
        'removed_tour' => 'Visite supprimée',
        'loaded' => 'Panier chargé avec succès',
        'empty' => 'Le panier est vide',
        'tour_add_to_cart_successfully' => 'Visite ajoutée au panier avec succès',
        'rental_add_to_cart_successfully' => 'Location ajoutée au panier avec succès',
        'invalid_start_date' => 'La date de début doit être postérieure ou égale à aujourd\'hui pour :tour',
        'tour-not-available' => 'Visite non disponible à :date',
    ],
    'coupons' => [
        'expired' => 'Coupon expiré',
        'not_available' => 'Le coupon n\'est pas disponible',
        'applied' => 'Coupon appliqué avec succès',
        'login-first-to-use-coupon' => 'Vous devez d\'abord vous connecter pour utiliser ce coupon',
        'invalid-tours' => 'Coupon non disponible pour les visites sélectionnées',
        'invalid-tour-categories' => 'Le coupon n\'est pas disponible pour les catégories de circuits sélectionnées',
    ],
    'exceptions' => [
        'not-found' => ':model Introuvable',
    ],
    'bookings' => [
        'empty-cart' => 'Votre panier est vide, veuillez ajouter des visites à votre panier',
        'created' => 'Votre réservation créée avec succès',
        'payment-redirect' => 'Traitement des paiements, redirection ........',
        'error' => 'Quelque chose s\'est mal passé, veuillez réessayer plus tard',
        'payment-error' => 'Erreur de paiement : :message',
        'payment_verified' => 'Paiement vérifié',
    ],
    'custom-trips' => [
        'created' => 'Votre demande a été envoyée avec succès',
    ],
    'tour' => [
        'reviews' => [
            'added' => 'Avis ajouté avec succès',
        ],
    ],
    'contact-request' => [
        'sent' => 'Votre message a été envoyé',
        'invalid_or_spam_email' => 'Désolé, nous ne pouvons pas envoyer votre message pour le moment, veuillez réessayer plus tard',
    ],
    'car-rental' => [
        'found' => 'Itinéraire correspondant trouvé',
        'not-found' => 'Aucun itinéraire pour les emplacements soumis, essayez d\'en choisir d\'autres.',
        'invalid-stop-location' => 'Emplacement d\'arrêt non valide (:location_name)',
        'no-price-group-found' => 'Aucune voiture disponible pour cet itinéraire nombre de membres',
        'sent' => 'Votre demande a été envoyée',
    ],
    'password' => [
        'forget' => 'Mot de passe oublié envoyé par otp, vérifiez votre courrier.',
        'otp_expired' => 'OTP expiré.',
        'otp_invalid' => 'OTP invalide.',
        'reset' => 'Votre mot de passe a été réinitialisé.',
    ],
    'notifications' => [
        'greeting' => 'Salut :name,',
        'thanks_for_using_our_app' => 'Merci d\'utiliser notre application !',
        'password' => [
            'forget' => [
                'otp' => 'Oublier le mot de passe OTP',
                'request_received' => 'Nous avons reçu une demande de réinitialisation de votre mot de passe.',
                'dont_worry' => 'Ne vous inquiétez pas, nous allons vous aider',
                'request_otp' => 'Votre code d\'option : :otp',
                'ignore_mail' => 'Ignorez ce courrier sinon vous.',
                'try_after_60' => 'Veuillez essayer après 60 secondes',
            ],
        ],
    ],
    'auth' => [
        'registered' => 'Vous vous êtes inscrit avec succès, connectez-vous maintenant',
        'logged_in_successfully' => 'Vous vous êtes connecté avec succès',
    ],
    'booking-not-found' => 'Réservation introuvable',
];
