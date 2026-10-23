@extends('platform.layout')

@php
    $usage = $profile['usage'] ?? null;
    $hasUsage = is_array($usage) && empty($usage['error']);
    $host = $company->domains->first()?->domain;
    $status = $company->isSuspended() ? 'suspended' : ($ready ? 'ready' : ($company->provision_status ?: 'pending'));
@endphp

@section('title', 'Perfect Solution | '.$company->company_name)
@section('heading', $company->company_name)
@section('lead', ($host ?: $company->id.'.'.$baseDomain).' — What you want is here')

@section('actions')
    @if ($company->isSuspended())
        <form method="post" action="{{ route('platform.companies.resume', $company) }}">
            @csrf
            <button class="button" type="submit">تشغيل الشركة</button>
        </form>
    @elseif ($opensAt)
        <span class="button ghost" id="open-wait" data-opens-at="{{ $opensAt }}">هتفتح بعد دقيقة</span>
    @elseif ($loginUrl)
        <a class="button" href="{{ $loginUrl }}">فتح الداشبورد</a>
    @else
        <a class="button" href="{{ route('platform.companies.provision', $company) }}">متابعة الإنشاء</a>
    @endif
    <a class="button ghost" href="{{ route('platform.companies.show', ['tenant' => $company, 'refresh' => 1]) }}">تحديث الأرقام</a>
@endsection

@section('content')
    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    <div class="grid-2">
        <section class="card">
            <div class="actions">
                <h2>بيانات الشركة</h2>
                @if ($status === 'suspended')
                    <span class="pill failed">موقوفة</span>
                @elseif ($status === 'ready')
                    <span class="pill ready">جاهزة</span>
                @elseif ($status === 'failed')
                    <span class="pill failed">تحتاج متابعة</span>
                @else
                    <span class="pill pending">قيد الإنشاء {{ $profile['steps_done'] }}/{{ $profile['steps_total'] }}</span>
                @endif
            </div>
            <div class="facts">
                <div><span>المعرف</span><strong>{{ $company->id }}</strong></div>
                <div><span>تاريخ الإنشاء</span><strong>{{ optional($company->created_at)->format('Y-m-d H:i') ?: '—' }}</strong></div>
                <div><span>آخر تحديث</span><strong>{{ optional($company->updated_at)->format('Y-m-d H:i') ?: '—' }}</strong></div>
                <div><span>قاعدة البيانات</span><strong>{{ $company->databaseName() }}</strong></div>
                <div><span>ملفات الشركة</span><strong>{{ $profile['storage_ready'] ? 'المجلد جاهز' : 'لم يُنشأ بعد' }}</strong></div>
                <div><span>النطاقات</span><strong>{{ $company->domains->pluck('domain')->join(' ، ') ?: '—' }}</strong></div>
            </div>
            @if ($company->provision_error)
                <p class="error" style="margin-top:16px">{{ $company->provision_error }}</p>
            @endif
        </section>

        <section class="card">
            <h2>التواصل</h2>
            <div class="facts">
                <div><span>مدير الداشبورد</span><strong>{{ $profile['admin_name'] ?: '—' }}</strong></div>
                <div><span>بريد المدير</span><strong>{{ $profile['admin_email'] ?: '—' }}</strong></div>
                <div><span>هاتف الموقع</span><strong>{{ $hasUsage ? ($usage['phone'] ?: '—') : '—' }}</strong></div>
                <div><span>بريد الموقع</span><strong>{{ $hasUsage ? ($usage['public_email'] ?: '—') : '—' }}</strong></div>
                <div><span>العنوان</span><strong>{{ $hasUsage ? ($usage['address'] ?: '—') : '—' }}</strong></div>
                <div><span>اسم الموقع</span><strong>{{ $hasUsage ? ($usage['site_title'] ?: '—') : '—' }}</strong></div>
            </div>
        </section>
    </div>

    <section class="card" style="margin-top:14px">
        <h2>نشاط الشركة</h2>
        @if (! $ready)
            <p class="muted" style="margin-top:8px">أرقام الجولات والحجوزات تظهر بعد اكتمال إنشاء القاعدة.</p>
        @elseif (! empty($usage['error']))
            <p class="error" style="margin-top:8px">تعذر قراءة قاعدة الشركة: {{ $usage['error'] }}</p>
        @else
            <div class="metrics">
                <div class="metric"><span>المستخدمون</span><strong>{{ number_format($usage['users']) }}</strong></div>
                <div class="metric"><span>العملاء</span><strong>{{ number_format($usage['clients']) }}</strong></div>
                <div class="metric"><span>الجولات</span><strong>{{ number_format($usage['tours']) }}</strong></div>
                <div class="metric"><span>الفنادق</span><strong>{{ number_format($usage['hotels']) }}</strong></div>
                <div class="metric"><span>الغرف</span><strong>{{ number_format($usage['rooms']) }}</strong></div>
                <div class="metric"><span>الرحلات</span><strong>{{ number_format($usage['trips']) }}</strong></div>
                <div class="metric"><span>المقالات</span><strong>{{ number_format($usage['blogs']) }}</strong></div>
                <div class="metric"><span>تأجير السيارات</span><strong>{{ number_format($usage['car_rentals']) }}</strong></div>
                <div class="metric"><span>رحلات مخصصة</span><strong>{{ number_format($usage['custom_trips']) }}</strong></div>
                <div class="metric"><span>الحجوزات</span><strong>{{ number_format($usage['bookings']) }}</strong></div>
                <div class="metric"><span>حجوزات معلّقة</span><strong>{{ number_format($usage['pending_bookings']) }}</strong></div>
                <div class="metric"><span>حجوزات مدفوعة</span><strong>{{ number_format($usage['paid_bookings']) }}</strong></div>
            </div>
            <p class="muted note">إجمالي المدفوع {{ number_format($usage['revenue'], 2) }} حسب المبلغ المحفوظ في كل حجز.</p>

            <h2 style="margin-top:22px">آخر الحجوزات</h2>
            @if (empty($usage['recent_bookings']))
                <p class="muted" style="margin-top:8px">لا توجد حجوزات بعد.</p>
            @else
                <div class="table-wrap" style="margin-top:12px">
                    <table>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>العميل</th>
                            <th>الحالة</th>
                            <th>الدفع</th>
                            <th>المبلغ</th>
                            <th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($usage['recent_bookings'] as $booking)
                            <tr>
                                <td>{{ $booking['id'] }}</td>
                                <td>{{ $booking['name'] ?: '—' }}<span class="sub">{{ $booking['email'] }}</span></td>
                                <td>{{ $booking['status'] }}</td>
                                <td>{{ $booking['payment_status'] }}</td>
                                <td class="num">{{ number_format($booking['total_price'], 2) }}</td>
                                <td>{{ $booking['created_at'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    </section>

    <section class="card" style="margin-top:14px">
        <h2>إيقاف أو حذف</h2>
        @if ($company->isSuspended())
            <p class="muted" style="margin-top:8px">الموقع متوقف عن الزوار. البيانات والسب دومين لسه موجودين.</p>
        @else
            <p class="muted" style="margin-top:8px">الإيقاف يقفل الموقع مؤقتاً من غير ما يمسح البيانات.</p>
            <form method="post" action="{{ route('platform.companies.suspend', $company) }}" style="margin-top:12px" onsubmit="return confirm('إيقاف الشركة؟ الموقع هيتقفل والبيانات هتفضل محفوظة.')">
                @csrf
                <button class="button ghost" type="submit">إيقاف الشركة</button>
            </form>
        @endif

        <p class="muted" style="margin-top:18px">الحذف نهائي: قاعدة البيانات والسب دومين وملفات الشركة.</p>
        <form method="post" action="{{ route('platform.companies.destroy', $company) }}" onsubmit="return confirm('حذف الشركة نهائياً؟ مش هتقدر ترجعها.')">
            @csrf
            @method('DELETE')
            <div class="confirm-row">
                <div>
                    <label for="confirmation">اكتب {{ $company->id }} للتأكيد</label>
                    <input id="confirmation" name="confirmation" autocomplete="off" required>
                    @error('confirmation')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <button class="button danger" type="submit">حذف الشركة</button>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        const wait = document.getElementById('open-wait');
        if (wait) {
            const opensAt = Number(wait.dataset.opensAt);
            const timer = setInterval(() => {
                const left = Math.max(0, opensAt - Math.floor(Date.now() / 1000));
                const minutes = Math.floor(left / 60);
                const seconds = String(left % 60).padStart(2, '0');
                wait.textContent = 'هتفتح بعد ' + minutes + ':' + seconds;
                if (left === 0) {
                    clearInterval(timer);
                    window.location.reload();
                }
            }, 1000);
        }
    </script>
@endpush
