<?php

return [
    'payment' => [
        'invalid-gateway' => 'Invalid Payment Gateway, try again...',
        'paypal-failed' => 'Paypal payment not available right now, please try again later.',
        'paypal-not-captured' => 'Payment is not captured, please try again later',
        'canceled' => 'Payment Cancelled'
    ],
    'cart' => [
        'cleared' => 'Cart cleared',
        'removed_tour' => 'Tour removed',
        'loaded' => 'Cart Loaded Successfully',
        'empty' => 'Cart is empty',
        'tour_add_to_cart_successfully' => 'Tour added to cart successfully',
        'rental_add_to_cart_successfully' => 'Rental added to cart successfully',
        'invalid_start_date' => 'Start date must be after or equal today for :tour',
        'tour-not-available' => 'Tour Not Available at :date',
    ],
    'coupons' => [
        'expired' => 'Coupon Expired',
        'not_available' => 'Coupon is not available',
        'applied' => 'Coupon applied successfully',
        'login-first-to-use-coupon' => 'You should login first to use this coupon',
        'invalid-tours' => 'Coupon not available for selected tours',
        'invalid-tour-categories' => 'Coupon not available for selected tour categories',
    ],
    'exceptions' => [
        'not-found' => ':model Not Found',
    ],
    'bookings' => [
        'empty-cart' => 'Your cart is empty, Please Add tours to your cart',
        'created' => 'Your booking created successfully',
        'payment-redirect' => 'Payment Processing, Redirecting........',
        'error' => 'Something went wrong, please try again later',
        'payment-error' => 'Payment Error: :message',
        'payment_verified' => 'Payment Verified',
    ],
    'custom-trips' => [
        'created' => 'Your request has been sent successfully',
    ],
    'tour' => [
        'reviews' => [
            'added' => 'Review added successfully',
        ],
    ],
    'contact-request' => [
        'sent' => 'Your message has been sent',
        'invalid_or_spam_email' => 'Sorry, We can\'t send your message right now, Please try again later',
    ],
    'car-rental' => [
        'found' => 'Found Matched Route',
        'not-found' => 'No Route for submitted locations, try pick anther locations',
        'invalid-stop-location' => 'Invalid Stop Location (:location_name)',
        'no-price-group-found' => 'No Available Car for this route number of members',
        'sent' => 'Your request have been sent',
    ],
    'password' => [
        'forget' => 'Forgot password otp sent, check your mail.',
        'otp_expired' => 'OTP Expired.',
        'otp_invalid' => 'Invalid OTP.',
        'reset' => 'Your password has been reset.',
    ],
    'notifications' => [
        'greeting' => 'Hi :name,',
        'thanks_for_using_our_app' => 'Thank you for using our application!',
        'password' => [
            'forget' => [
                'otp' => 'Forget Password OTP',
                'request_received' => 'We have received a request to reset your password.',
                'dont_worry' => "Don't worry we will help you",
                'request_otp' => 'Your opt code: :otp',
                'ignore_mail' => 'Ignore this mail if not you.',
                'try_after_60' => 'Please try after 60 seconds'
            ]
        ],
    ],
    'auth' => [
        'registered' => 'You have registered successfully, Login Now',
        'logged_in_successfully' => 'You have logged in successfully',
    ],
    'booking-not-found' => 'Booking Not Found'
];
