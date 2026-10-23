@extends('platform.layout')

@section('title', 'Perfect Solution | شركة جديدة')
@section('heading', 'شركة جديدة')
@section('lead', 'بنبني الهوية الرقمية للشركة: القاعدة، الجداول، حساب المدير، والنطاق.')

@section('content')
    <div class="card" style="max-width:760px">
        <form method="POST" action="{{ route('platform.companies.store') }}">
            @csrf
            <div class="split">
                <div>
                    <label for="company_name">اسم الشركة</label>
                    <input id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                    @error('company_name') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="slug">النطاق الفرعي</label>
                    <input id="slug" name="slug" value="{{ old('slug') }}" placeholder="acme" required>
                    <div class="muted">النتيجة: <span id="preview">slug</span>.{{ $baseDomain }}</div>
                    @error('slug') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="split">
                <div>
                    <label for="admin_name">اسم مدير الشركة</label>
                    <input id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required>
                    @error('admin_name') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="admin_email">بريد مدير الشركة</label>
                    <input id="admin_email" type="email" name="admin_email" value="{{ old('admin_email') }}" required>
                    @error('admin_email') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="split">
                <div>
                    <label for="password">كلمة مرور الداشبورد</label>
                    <input id="password" type="password" name="password" required>
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="password_confirmation">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
            </div>

            <div class="actions" style="margin-top:18px">
                <button type="submit">بدء الإنشاء</button>
                <a class="button ghost" href="{{ route('platform.companies.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const slug = document.getElementById('slug');
        const preview = document.getElementById('preview');
        const render = () => preview.textContent = slug.value || 'slug';
        slug.addEventListener('input', render);
        render();
    </script>
@endpush
