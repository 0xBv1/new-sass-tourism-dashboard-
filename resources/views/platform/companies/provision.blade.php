@extends('platform.layout')

@section('title', 'Perfect Solution | '.$tenant->company_name)
@section('heading', 'تجهيز '.$tenant->company_name)
@section('lead', 'كل خطوة لوحدها. لو قفلت الصفحة تقدر ترجع تكمل من قائمة الشركات.')

@section('content')
    <div class="card" style="max-width:760px">
        <ol class="steps" id="steps"></ol>

        <div class="error" id="error" hidden></div>
        <div class="status" id="done" hidden></div>

        <div class="actions" style="margin-top:18px">
            <button type="button" id="retry" hidden>إعادة المحاولة</button>
            <a class="button ghost" href="{{ route('platform.companies.index') }}">قائمة الشركات</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="application/json" id="provision-state">{!! json_encode($state, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
    <script>
        const stepUrl = @json(route('platform.companies.provision.step', $tenant));
        const token = document.querySelector('meta[name="csrf-token"]').content;
        let state = JSON.parse(document.getElementById('provision-state').textContent);
        let running = false;

        const stepsEl = document.getElementById('steps');
        const errorEl = document.getElementById('error');
        const doneEl = document.getElementById('done');
        const retryEl = document.getElementById('retry');

        function render() {
            stepsEl.innerHTML = '';
            state.steps.forEach((step) => {
                const item = document.createElement('li');
                item.className = step.state + (running && step.state === 'current' ? ' running' : '');
                const mark = document.createElement('span');
                mark.className = 'dot';
                const text = document.createElement('span');
                text.textContent = step.label + (step.detail ? ' (' + step.detail + ')' : '');
                item.append(mark, text);
                stepsEl.append(item);
            });

            errorEl.hidden = !state.error;
            errorEl.textContent = state.error || '';
            retryEl.hidden = state.status !== 'failed';

            if (state.status === 'ready') {
                doneEl.hidden = false;
                doneEl.textContent = '';
                const link = document.createElement('a');
                link.href = state.login_url;
                link.textContent = 'فتح داشبورد الشركة';
                doneEl.append('تم إنشاء الشركة. ', link);
            } else if (state.login_url && state.status === 'failed') {
                doneEl.hidden = false;
                doneEl.textContent = '';
                const link = document.createElement('a');
                link.href = state.login_url;
                link.textContent = 'فتح داشبورد الشركة';
                doneEl.append('الحساب جاهز. ', link);
            } else {
                doneEl.hidden = true;
            }
        }

        async function requestStep() {
            const response = await fetch(stepUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error('تعذر تنفيذ الخطوة (' + response.status + ')');
            }

            return response.json();
        }

        async function advance() {
            if (running || state.status === 'ready') {
                return;
            }

            running = true;
            render();

            try {
                state = await requestStep();
                running = false;
                render();

                if (state.busy) {
                    setTimeout(advance, 1000);
                    return;
                }

                if (state.status === 'pending') {
                    advance();
                }
            } catch (error) {
                running = false;
                state.status = 'failed';
                state.error = error.message;
                render();
            }
        }

        retryEl.addEventListener('click', () => {
            state.status = 'pending';
            state.error = null;
            advance();
        });

        render();
        if (state.status === 'pending') {
            advance();
        }
    </script>
@endpush
