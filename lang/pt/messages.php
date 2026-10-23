<?php

return [
    'payment' => [
        'invalid-gateway' => 'Gateway de pagamento inválido, tente novamente...',
        'paypal-failed' => 'O pagamento via Paypal não está disponível no momento. Tente novamente mais tarde.',
        'paypal-not-captured' => 'O pagamento não foi capturado, tente novamente mais tarde',
        'canceled' => 'Pagamento cancelado',
    ],
    'cart' => [
        'cleared' => 'Carrinho limpo',
        'removed_tour' => 'Tour removido',
        'loaded' => 'Carrinho carregado com sucesso',
        'empty' => 'O carrinho está vazio',
        'tour_add_to_cart_successfully' => 'Tour adicionado ao carrinho com sucesso',
        'rental_add_to_cart_successfully' => 'Aluguel adicionado ao carrinho com sucesso',
        'invalid_start_date' => 'A data de início deve ser posterior ou igual a hoje para :tour',
        'tour-not-available' => 'Tour não disponível em :date',
    ],
    'coupons' => [
        'expired' => 'Cupom expirado',
        'not_available' => 'O cupom não está disponível',
        'applied' => 'Cupom aplicado com sucesso',
        'login-first-to-use-coupon' => 'Você deve fazer login primeiro para usar este cupom',
        'invalid-tours' => 'Cupom não disponível para passeios selecionados',
        'invalid-tour-categories' => 'Cupom não disponível para categorias de passeios selecionadas',
    ],
    'exceptions' => [
        'not-found' => ':model Não encontrado',
    ],
    'bookings' => [
        'empty-cart' => 'Seu carrinho está vazio, adicione passeios ao seu carrinho',
        'created' => 'Sua reserva foi criada com sucesso',
        'payment-redirect' => 'Processamento de pagamento, redirecionamento........',
        'error' => 'Algo deu errado, tente novamente mais tarde',
        'payment-error' => 'Erro de pagamento: :message',
        'payment_verified' => 'Pagamento verificado',
    ],
    'custom-trips' => [
        'created' => 'Sua solicitação foi enviada com sucesso',
    ],
    'tour' => [
        'reviews' => [
            'added' => 'Comentário adicionado com sucesso',
        ],
    ],
    'contact-request' => [
        'sent' => 'Sua mensagem foi enviada',
        'invalid_or_spam_email' => 'Desculpe, não podemos enviar sua mensagem agora. Tente novamente mais tarde',
    ],
    'car-rental' => [
        'found' => 'Rota correspondente encontrada',
        'not-found' => 'Nenhuma rota para os locais enviados, tente escolher outros locais',
        'invalid-stop-location' => 'Local de parada inválido (:location_name)',
        'no-price-group-found' => 'Nenhum carro disponível para esta rota número de membros',
        'sent' => 'Sua solicitação foi enviada',
    ],
    'password' => [
        'forget' => 'Esqueci a senha enviada, verifique seu e-mail.',
        'otp_expired' => 'OTP Expirou.',
        'otp_invalid' => 'OTP inválido.',
        'reset' => 'Sua senha foi redefinida.',
    ],
    'notifications' => [
        'greeting' => 'Oi :name,',
        'thanks_for_using_our_app' => 'Obrigado por usar nosso aplicativo!',
        'password' => [
            'forget' => [
                'otp' => 'Esqueci a senha OTP',
                'request_received' => 'Recebemos uma solicitação para redefinir sua senha.',
                'dont_worry' => 'Não se preocupe, nós iremos ajudá-lo',
                'request_otp' => 'Seu código de opção: :otp',
                'ignore_mail' => 'Ignore este e-mail se não você.',
                'try_after_60' => 'Por favor, tente após 60 segundos',
            ],
        ],
    ],
    'auth' => [
        'registered' => 'Você se registrou com sucesso, faça login agora',
        'logged_in_successfully' => 'Você fez login com sucesso',
    ],
    'booking-not-found' => 'Reserva não encontrada',
];
