<?php

namespace App\DataTables;

use App\Models\LoginHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LoginHistoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<LoginHistory>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('user', function (LoginHistory $row) {
                $name = e($row->user?->name ?? 'Unknown user');
                $email = e($row->user?->email ?? '');

                return '<div class="d-flex flex-column align-items-start gap-1 w-100 min-w-0 text-start">'
                    .'<span class="d-block w-100 fw-bold text-wrap">'.$name.'</span>'
                    .'<span class="d-block w-100 text-wrap text-muted small">'.$email.'</span>'
                    .'</div>';
            })
            ->addColumn('ip_address', function (LoginHistory $row) {
                return e($row->ip);
            })
            ->addColumn('location_device', function (LoginHistory $row) {
                $d = is_array($row->details) ? $row->details : [];

                $location = collect([
                    data_get($d, 'city'),
                    data_get($d, 'regionName'),
                    data_get($d, 'country'),
                ])->filter()->implode(', ');

                if ($location === '') {
                    $location = '—';
                }

                $device = trim(sprintf(
                    '%s on %s (%s)',
                    (string) data_get($d, 'browser_name', 'Unknown'),
                    (string) data_get($d, 'os_name', 'Unknown'),
                    (string) data_get($d, 'device_type', 'desktop')
                ));

                return '<div><span class="d-block">'.e($location).'</span>'
                    .'<span class="text-muted small d-block">'.e($device).'</span></div>';
            })
            ->addColumn('role', function (LoginHistory $row) {
                return '<span class="badge table-badge bg-secondary fw-medium fs-10">'.e($row->type).'</span>';
            })
            ->addColumn('login_time', function (LoginHistory $row) {
                if ($row->created_at === null) {
                    return '—';
                }

                return e(Carbon::parse($row->created_at)->format('d M Y, h:i A'));
            })
            ->addColumn('action', function (LoginHistory $row) {
                $url = route('login-history.details', $row);

                return '<div class="action-icon d-inline-flex justify-content-center">'
                    .'<a href="javascript:void(0);" class="view-login-details d-flex align-items-center justify-content-center p-2 border rounded text-decoration-none text-body" data-url="'.e($url).'" title="View">'
                    .'<i class="ti ti-eye"></i>'
                    .'</a>'
                    .'</div>';
            })
            ->rawColumns(['user', 'location_device', 'role', 'action']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<LoginHistory>
     */
    public function query(LoginHistory $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('user')
            ->orderByDesc('created_at');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('login-history-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(4, 'desc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('user')
                ->addClass('text-start text-wrap align-middle')
                ->width(200),
            Column::make('ip_address')
                ->title('IP Address'),
            Column::make('location_device')
                ->title('Location & Device'),
            Column::make('role')
                ->title('Role')
                ->addClass('text-center'),
            Column::make('login_time')
                ->title('Login Time')
                ->addClass('text-nowrap'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center action-table-data'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LoginHistory_'.date('YmdHis');
    }
}
