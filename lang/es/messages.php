<?php

return [
    'payment' => [
        'invalid-gateway' => 'Pasarela de pago no válida, inténtalo de nuevo...',
        'paypal-failed' => 'El pago por Paypal no está disponible en este momento, inténtalo de nuevo más tarde.',
        'paypal-not-captured' => 'El pago no se captura, inténtalo de nuevo más tarde.',
        'canceled' => 'Pago cancelado',
    ],
    'cart' => [
        'cleared' => 'Carrito borrado',
        'removed_tour' => 'Tour eliminado',
        'loaded' => 'Carro cargado exitosamente',
        'empty' => 'El carrito está vacío.',
        'tour_add_to_cart_successfully' => 'Tour agregado al carrito exitosamente',
        'rental_add_to_cart_successfully' => 'Alquiler añadido al carrito con éxito',
        'invalid_start_date' => 'La fecha de inicio debe ser posterior o igual a hoy para :tour',
        'tour-not-available' => 'Tour no disponible a las :date',
    ],
    'coupons' => [
        'expired' => 'Cupón caducado',
        'not_available' => 'El cupón no está disponible',
        'applied' => 'Cupón aplicado exitosamente',
        'login-first-to-use-coupon' => 'Primero debes iniciar sesión para usar este cupón.',
        'invalid-tours' => 'Cupón no disponible para tours seleccionados',
        'invalid-tour-categories' => 'Cupón no disponible para categorías de viajes seleccionadas',
    ],
    'exceptions' => [
        'not-found' => ':model Extraviado',
    ],
    'bookings' => [
        'empty-cart' => 'Su carrito está vacío, agregue tours a su carrito',
        'created' => 'Tu reserva creada con éxito',
        'payment-redirect' => 'Procesamiento de pagos, redireccionamiento...',
        'error' => 'Algo salió mal, por favor inténtalo de nuevo más tarde.',
        'payment-error' => 'Error de pago: :message',
        'payment_verified' => 'Pago verificado',
    ],
    'custom-trips' => [
        'created' => 'Su solicitud ha sido enviada exitosamente',
    ],
    'tour' => [
        'reviews' => [
            'added' => 'Revisión agregada exitosamente',
        ],
    ],
    'contact-request' => [
        'sent' => 'Tu mensaje ha sido enviado',
        'invalid_or_spam_email' => 'Lo sentimos, no podemos enviar tu mensaje en este momento. Inténtalo de nuevo más tarde.',
    ],
    'car-rental' => [
        'found' => 'Ruta coincidente encontrada',
        'not-found' => 'No hay ruta para las ubicaciones enviadas, intente elegir otras ubicaciones',
        'invalid-stop-location' => 'Ubicación de parada no válida (:ubicación_nombre)',
        'no-price-group-found' => 'No hay coche disponible para esta ruta número de miembros',
        'sent' => 'Su solicitud ha sido enviada',
    ],
    'password' => [
        'forget' => 'Olvidé mi contraseña otp enviada, revisa tu correo.',
        'otp_expired' => 'La OTP expiró.',
        'otp_invalid' => 'OTP no válida.',
        'reset' => 'Su contraseña ha sido restablecida.',
    ],
    'notifications' => [
        'greeting' => 'Hola :name,',
        'thanks_for_using_our_app' => '¡Gracias por utilizar nuestra aplicación!',
        'password' => [
            'forget' => [
                'otp' => 'Olvidar contraseña OTP',
                'request_received' => 'Hemos recibido una solicitud para restablecer su contraseña.',
                'dont_worry' => 'No te preocupes nosotros te ayudaremos',
                'request_otp' => 'Su código de opción: :otp',
                'ignore_mail' => 'Ignora este correo si no eres tú.',
                'try_after_60' => 'Inténtalo después de 60 segundos.',
            ],
        ],
    ],
    'auth' => [
        'registered' => 'Te has registrado exitosamente, Inicia sesión ahora',
        'logged_in_successfully' => 'Has iniciado sesión correctamente',
    ],
    'booking-not-found' => 'Reserva no encontrada',
];
