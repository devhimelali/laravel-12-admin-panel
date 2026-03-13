<?php

namespace App\DataTables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Role> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('name', function ($row) {
                return ucwords($row->name);
            })
            ->editColumn('is_active', function ($row) {
                $text = $row->is_active ? 'Active' : 'Inactive';
                $bgColor = $row->is_active ? 'bg-success' : 'bg-danger';
                return '<span class="badge table-badge ' . $bgColor . ' fw-medium fs-10">' . $text . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y');
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '<div class="action-icon d-inline-flex">';
                $actionBtn .= '<a href="permissions.html" class="me-2 d-flex align-items-center p-2 border rounded"><i class="ti ti-shield"></i></a>';
                $actionBtn .= '<button type="button" class="me-2 d-flex align-items-center p-2 border rounded edit" data-id="' . $row->id . '"><i class="ti ti-edit"></i></button>';
                $actionBtn .= '<button type="button" class="d-flex align-items-center p-2 border rounded delete" data-id="' . $row->id . '"><i class="ti ti-trash"></i></button>';
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->rawColumns(['is_active', 'action'])
            ->addIndexColumn()
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Role>
     */
    public function query(Role $model): QueryBuilder
    {
        $query = $model->newQuery()->with('permissions');

        if ($status = request('status')) {
            $query->where('is_active', $status);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('role-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('S.N'),
            Column::make('name')
                ->title('Role Name'),
            Column::make('display_name'),
            Column::make('description'),
            Column::make('is_active')
                ->title('Status')
                ->width(100),
            Column::make('created_at')
                ->title('Created Date')
                ->width(100),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Role_' . date('YmdHis');
    }
}
