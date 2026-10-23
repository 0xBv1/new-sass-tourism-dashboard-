<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Http\Requests\Platform\CompanyRequest;
use App\Models\Tenant;
use App\Services\Tenancy\CompanyInsights;
use App\Services\Tenancy\CompanyProvisioner;
use App\Services\Tenancy\TenantUrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $companies = Tenant::query()->with('domains')->latest()->get();
        $provisioner = app(CompanyProvisioner::class);
        $insights = app(CompanyInsights::class);

        $needsProvision = $companies->mapWithKeys(
            fn (Tenant $company) => [$company->id => $provisioner->needsProvision($company)]
        );

        $profiles = $companies->mapWithKeys(
            fn (Tenant $company) => [$company->id => $insights->forCompany($company, ! $needsProvision[$company->id])]
        );

        return view('platform.companies.index', [
            'companies' => $companies,
            'baseDomain' => TenantUrl::baseDomain(),
            'needsProvision' => $needsProvision,
            'profiles' => $profiles,
            'summary' => $this->summary($companies, $needsProvision, $profiles),
        ]);
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load('domains');
        $ready = ! app(CompanyProvisioner::class)->needsProvision($tenant);

        return view('platform.companies.show', [
            'company' => $tenant,
            'ready' => $ready,
            'baseDomain' => TenantUrl::baseDomain(),
            'profile' => app(CompanyInsights::class)->forCompany($tenant, $ready, request()->boolean('refresh')),
            'loginUrl' => $ready ? TenantUrl::urlFor($tenant->id).'/login' : null,
        ]);
    }

    public function create(): View
    {
        return view('platform.companies.create', [
            'baseDomain' => TenantUrl::baseDomain(),
        ]);
    }

    public function store(CompanyRequest $request): RedirectResponse
    {
        $tenant = null;

        try {
            $tenant = new Tenant([
                'id' => $request->string('slug')->toString(),
                'company_name' => $request->string('company_name')->toString(),
            ]);
            $tenant->admin_name = $request->string('admin_name')->toString();
            $tenant->admin_email = $request->string('admin_email')->toString();
            $tenant->admin_password = Hash::make($request->string('password')->toString());
            $tenant->defer_provisioning = true;
            $tenant->provision_status = 'pending';
            $tenant->provision_done = [];
            $tenant->provision_error = null;
            $tenant->save();

            $tenant->createDomain(TenantUrl::hostFor($tenant->id));
        } catch (\Throwable $exception) {
            Log::error('Company provisioning failed', [
                'message' => $exception->getMessage(),
            ]);

            if ($tenant?->exists) {
                $this->rollback($tenant);
            }

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['company_name' => 'تعذر إنشاء الشركة: '.$exception->getMessage()]);
        }

        return redirect()->route('platform.companies.provision', $tenant);
    }

    public function provision(Tenant $tenant): View
    {
        return view('platform.companies.provision', [
            'tenant' => $tenant,
            'state' => app(CompanyProvisioner::class)->snapshot($tenant),
        ]);
    }

    public function provisionStep(Tenant $tenant): JsonResponse
    {
        return response()->json(app(CompanyProvisioner::class)->advance($tenant));
    }

    protected function summary($companies, $needsProvision, $profiles): array
    {
        $usage = collect($profiles)->pluck('usage')->filter(fn ($row) => is_array($row) && empty($row['error']));

        return [
            'companies' => $companies->count(),
            'ready' => $needsProvision->filter(fn ($needs) => ! $needs)->count(),
            'setup' => $companies->filter(fn (Tenant $company) => $company->provision_status !== 'failed' && $needsProvision[$company->id])->count(),
            'attention' => $companies->where('provision_status', 'failed')->count(),
            'tours' => (int) $usage->sum('tours'),
            'bookings' => (int) $usage->sum('bookings'),
            'clients' => (int) $usage->sum('clients'),
            'hotels' => (int) $usage->sum('hotels'),
            'revenue' => (float) $usage->sum('revenue'),
        ];
    }

    protected function rollback(Tenant $tenant): void
    {
        try {
            $name = $tenant->databaseName();
            if ($tenant->database()->manager()->databaseExists($name)) {
                $tenant->database()->manager()->deleteDatabase($tenant);
            }
        } catch (\Throwable $exception) {
            Log::warning('Failed to drop tenant database during rollback', [
                'message' => $exception->getMessage(),
            ]);
        }

        $tenant->domains()->delete();
        Tenant::withoutEvents(fn () => $tenant->delete());
    }
}
