@extends('platform.layout')

@section('title', 'Perfect Solution | الشركات')
@section('heading', 'شركات Perfect Solution')
@section('lead', 'What you want is here. تابع شركات السياحة، حسابات المديرين، والجولات والحجوزات من مكان واحد.')

@section('actions')
    <a class="button" href="{{ route('platform.companies.create') }}">شركة جديدة</a>
@endsection

@section('content')
    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    <section class="stats">
        <article class="stat"><span>الشركات</span><strong>{{ number_format($summary['companies']) }}</strong></article>
        <article class="stat"><span>جاهزة للعمل</span><strong>{{ number_format($summary['ready']) }}</strong></article>
        <article class="stat"><span>قيد الإنشاء</span><strong>{{ number_format($summary['setup']) }}</strong></article>
        <article class="stat"><span>تحتاج متابعة</span><strong>{{ number_format($summary['attention']) }}</strong></article>
        <article class="stat"><span>الجولات</span><strong>{{ number_format($summary['tours']) }}</strong></article>
        <article class="stat"><span>الحجوزات</span><strong>{{ number_format($summary['bookings']) }}</strong></article>
        <article class="stat"><span>العملاء</span><strong>{{ number_format($summary['clients']) }}</strong></article>
        <article class="stat"><span>إيراد الحجوزات المدفوعة</span><strong>{{ number_format($summary['revenue'], 2) }}</strong></article>
    </section>

    <section class="card">
        @if ($companies->isEmpty())
            <h2>لسه مفيش شركات</h2>
            <p class="muted" style="margin-top:8px">ابدأ أول شركة، وهنجهز قاعدتها وحساب المدير والنطاق.</p>
        @else
            <div class="toolbar">
                <input class="search" id="company-search" type="search" placeholder="ابحث بالاسم أو البريد أو النطاق">
                <div class="filters" id="company-filters">
                    <button class="chip active" type="button" data-filter="all">الكل</button>
                    <button class="chip" type="button" data-filter="ready">جاهزة</button>
                    <button class="chip" type="button" data-filter="pending">قيد الإنشاء</button>
                    <button class="chip" type="button" data-filter="failed">تحتاج متابعة</button>
                    <button class="chip" type="button" data-filter="suspended">موقوفة</button>
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>الشركة</th>
                        <th>المدير</th>
                        <th>النطاق</th>
                        <th>الجولات</th>
                        <th>الحجوزات</th>
                        <th>العملاء</th>
                        <th>الفنادق</th>
                        <th>المدفوع</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="company-rows">
                    @foreach ($companies as $company)
                        @php
                            $profile = $profiles[$company->id];
                            $usage = $profile['usage'] ?? null;
                            $host = $company->domains->first()?->domain;
                            $status = $company->isSuspended() ? 'suspended' : ($company->provision_status ?: 'pending');
                            if ($status !== 'suspended' && ! empty($needsProvision[$company->id]) && $status === 'ready') {
                                $status = 'pending';
                            }
                            $search = strtolower(implode(' ', array_filter([
                                $company->company_name,
                                $company->id,
                                $host,
                                $profile['admin_name'] ?? null,
                                $profile['admin_email'] ?? null,
                                $usage['site_title'] ?? null,
                            ])));
                        @endphp
                        <tr data-status="{{ $status }}" data-search="{{ $search }}">
                            <td>
                                <a class="company-name" href="{{ route('platform.companies.show', $company) }}">{{ $company->company_name }}</a>
                                <span class="sub">{{ $usage['site_title'] ?? $company->id }} · {{ optional($company->created_at)->format('Y-m-d') }}</span>
                            </td>
                            <td>
                                {{ $profile['admin_name'] ?: '—' }}
                                <span class="sub">{{ $profile['admin_email'] ?: 'لا يوجد بريد محفوظ' }}</span>
                            </td>
                            <td>
                                {{ $host ?: '—' }}
                                <span class="sub">{{ $company->databaseName() }}</span>
                            </td>
                            <td class="num">{{ is_array($usage) && empty($usage['error']) ? number_format($usage['tours']) : '—' }}</td>
                            <td class="num">{{ is_array($usage) && empty($usage['error']) ? number_format($usage['bookings']) : '—' }}</td>
                            <td class="num">{{ is_array($usage) && empty($usage['error']) ? number_format($usage['clients']) : '—' }}</td>
                            <td class="num">{{ is_array($usage) && empty($usage['error']) ? number_format($usage['hotels']) : '—' }}</td>
                            <td class="num">{{ is_array($usage) && empty($usage['error']) ? number_format($usage['revenue'], 2) : '—' }}</td>
                            <td>
                                @if ($status === 'suspended')
                                    <span class="pill failed">موقوفة</span>
                                @elseif ($status === 'ready')
                                    <span class="pill ready">جاهزة</span>
                                @elseif ($status === 'failed')
                                    <span class="pill failed">تحتاج متابعة</span>
                                @else
                                    <span class="pill pending">قيد الإنشاء {{ $profile['steps_done'] }}/{{ $profile['steps_total'] }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('platform.companies.show', $company) }}">التفاصيل</a>
                                    @if ($status === 'suspended')
                                        <a href="{{ route('platform.companies.show', $company) }}">تشغيل</a>
                                    @elseif ($company->isOpeningSoon())
                                        <span class="sub">هتفتح بعد دقيقة</span>
                                    @elseif (! empty($needsProvision[$company->id]))
                                        <a href="{{ route('platform.companies.provision', $company) }}">متابعة</a>
                                    @elseif ($host)
                                        <a href="{{ \App\Services\Tenancy\TenantUrl::urlFor($company->id) }}/login">الداشبورد</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <p class="muted note" id="company-empty" hidden>لا توجد شركات مطابقة للبحث.</p>
            @if ($baseDomain === 'localhost' || str_ends_with($baseDomain, '.localhost') || str_ends_with($baseDomain, '.test'))
                <p class="muted note">أرقام النشاط بتتحدث من قاعدة كل شركة جاهزة. إنشاء الشركة على خطوات، وتقدر تقفل الصفحة وترجع من «متابعة».</p>
            @else
                <p class="muted note">أرقام النشاط بتتحدث من قاعدة كل شركة جاهزة. على السيرفر يكفي سجل DNS واحد <code>*.{{ $baseDomain }}</code>.</p>
            @endif
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        const search = document.getElementById('company-search');
        const rows = [...document.querySelectorAll('#company-rows tr')];
        const empty = document.getElementById('company-empty');
        let filter = 'all';

        function apply() {
            const term = (search?.value || '').trim().toLowerCase();
            let visible = 0;
            rows.forEach((row) => {
                const matchesFilter = filter === 'all' || row.dataset.status === filter;
                const matchesTerm = term === '' || (row.dataset.search || '').includes(term);
                const show = matchesFilter && matchesTerm;
                row.hidden = !show;
                if (show) visible++;
            });
            if (empty) empty.hidden = visible !== 0;
        }

        search?.addEventListener('input', apply);
        document.getElementById('company-filters')?.addEventListener('click', (event) => {
            const chip = event.target.closest('[data-filter]');
            if (!chip) return;
            filter = chip.dataset.filter;
            document.querySelectorAll('#company-filters .chip').forEach((item) => item.classList.toggle('active', item === chip));
            apply();
        });
    </script>
@endpush
