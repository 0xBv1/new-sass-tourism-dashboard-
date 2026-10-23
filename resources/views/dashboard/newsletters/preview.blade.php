<!DOCTYPE html>
<html>
<head>
    <title>{{ $newsletter->subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-bottom: 3px solid #007bff;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $newsletter->subject }}</h1>
        <p><small>Status: {{ ucfirst($newsletter->status) }}</small></p>
    </div>
    
    <div class="content">
        {!! $newsletter->body !!}
    </div>
    
    <div class="footer">
        <p>This is a preview of the newsletter.</p>
    </div>
</body>
</html>

