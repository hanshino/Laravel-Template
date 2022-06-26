<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class RolesTable.
 */
class RolesTable extends DataTableComponent
{
    /**
     * @var string
     */
    protected $model = Role::class;

    /**
     * You must implement the configure method on your component.
     * The only configuration method that is required is setPrimaryKey.
     * The primary key is a field on your model that acts as a unique identifier for the row. I.e. an ID, a UUID, etc.
     *
     * https://rappasoft.com/docs/laravel-livewire-tables/v2/usage/configuration
     *
     * @return void
     */
    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return Role::with('permissions:id,name,description')
            ->withCount('users')
            ->when($this->getFilter('search'), fn ($query, $term) => $query->search($term));
    }

    public function columns(): array
    {
        return [
            Column::make(__('Type'))
                ->sortable(),
            Column::make(__('Name'))
                ->sortable(),
            Column::make(__('Permissions')),
            Column::make(__('Number of Users'), 'users_count')
                ->sortable(),
            Column::make(__('Actions')),
        ];
    }

    public function rowView(): string
    {
        return 'backend.auth.role.includes.row';
    }
}
