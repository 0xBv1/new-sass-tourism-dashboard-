<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Yajra\DataTables\QueryDataTable;

class DataTableServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        // Only register macros if application is installed
        $isInstalled = file_exists(storage_path('app/installed'));
        
        if ($isInstalled) {
            try {
                QueryDataTable::macro('filterTranslatedColumn', function ($column): QueryDataTable {
                    return $this->filterColumn($column, function ($query, $keyword) use ($column) {
                        return $query->whereTranslationLike($column, "%$keyword%");
                    });
                });
            } catch (\Exception $e) {
                // Silently fail if database is not available (during installation)
            }
        }
    }
}
