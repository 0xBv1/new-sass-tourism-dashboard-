<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>الشركة بتتجهز</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #f7f7f7; color: #000733; font-family: Tahoma, sans-serif; }
        main { width: min(460px, calc(100% - 32px)); background: #fff; border: 1px solid #e6e8ee; border-radius: 18px; padding: 28px; }
        h1 { margin: 0 0 8px; font-size: 26px; }
        p { margin: 0; color: #777; line-height: 1.7; }
        strong { color: #000733; }
    </style>
</head>
<body>
<main>
    <h1>الشركة بتتجهز</h1>
    <p>موقع {{ $company }} هيفتح بعد <strong id="wait">01:00</strong>. السب دومين لسه بيتسجل.</p>
</main>
<script>
    const opensAt = {{ (int) $opensAt }};
    const wait = document.getElementById('wait');
    const timer = setInterval(() => {
        const left = Math.max(0, opensAt - Math.floor(Date.now() / 1000));
        const minutes = String(Math.floor(left / 60)).padStart(2, '0');
        const seconds = String(left % 60).padStart(2, '0');
        wait.textContent = minutes + ':' + seconds;
        if (left === 0) {
            clearInterval(timer);
            window.location.reload();
        }
    }, 1000);
</script>
</body>
</html>
