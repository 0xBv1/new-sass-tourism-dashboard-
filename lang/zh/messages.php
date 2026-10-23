<?php

return [
    'payment' => [
        'invalid-gateway' => '支付网关无效，请重试...',
        'paypal-failed' => '目前无法使用 Paypal 付款，请稍后再试。',
        'paypal-not-captured' => '付款未成功，请稍后重试',
        'canceled' => '付款已取消',
    ],
    'cart' => [
        'cleared' => '购物车已清空',
        'removed_tour' => '游览已删除',
        'loaded' => '购物车加载成功',
        'empty' => '购物车是空的',
        'tour_add_to_cart_successfully' => '旅游已成功添加到购物车',
        'rental_add_to_cart_successfully' => '租赁已成功添加到购物车',
        'invalid_start_date' => '开始日期必须晚于或等于今天:tour',
        'tour-not-available' => ':date 不提供游览',
    ],
    'coupons' => [
        'expired' => '优惠券已过期',
        'not_available' => '优惠券不可用',
        'applied' => '优惠券申请成功',
        'login-first-to-use-coupon' => '您需要先登录才能使用此优惠券',
        'invalid-tours' => '优惠券不适用于选定的旅游团',
        'invalid-tour-categories' => '优惠券不适用于选定的旅游类别',
    ],
    'exceptions' => [
        'not-found' => ':model 未找到',
    ],
    'bookings' => [
        'empty-cart' => '您的购物车是空的，请将旅游添加到您的购物车',
        'created' => '您的预订创建成功',
        'payment-redirect' => '付款处理、重定向......',
        'error' => '出了点问题，请稍后重试',
        'payment-error' => '付款错误：:message',
        'payment_verified' => '付款已验证',
    ],
    'custom-trips' => [
        'created' => '您的请求已成功发送',
    ],
    'tour' => [
        'reviews' => [
            'added' => '评论添加成功',
        ],
    ],
    'contact-request' => [
        'sent' => '您的消息已发送',
        'invalid_or_spam_email' => '抱歉，我们现在无法发送您的消息，请稍后再试',
    ],
    'car-rental' => [
        'found' => '找到匹配的路线',
        'not-found' => '提交的位置没有路线，请尝试选择花药位置',
        'invalid-stop-location' => '无效的停靠位置 (:location_name)',
        'no-price-group-found' => '此路线无可用车辆 会员人数',
        'sent' => '您的请求已发送',
    ],
    'password' => [
        'forget' => '忘记密码 otp 已发送，请检查您的邮件。',
        'otp_expired' => '一次性密码已过期。',
        'otp_invalid' => '一次性密码无效。',
        'reset' => '您的密码已重置。',
    ],
    'notifications' => [
        'greeting' => '你好 :name,',
        'thanks_for_using_our_app' => '感谢您使用我们的应用程序！',
        'password' => [
            'forget' => [
                'otp' => '忘记密码一次性密码',
                'request_received' => '我们已收到重置您密码的请求。',
                'dont_worry' => '别担心我们会帮助你',
                'request_otp' => '您的选择代码：:otp',
                'ignore_mail' => '如果不是您，请忽略此邮件。',
                'try_after_60' => '请在 60 秒后尝试',
            ],
        ],
    ],
    'auth' => [
        'registered' => '您已注册成功，立即登录',
        'logged_in_successfully' => '您已登录成功',
    ],
    'booking-not-found' => '未找到预订',
];
