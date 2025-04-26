<?php

namespace App\DataTables;

// use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ModuleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('role', function ($row) {
                // return $row->id;
                return '<select name="role" class="roleClass" data-id="' . $row->id . '">
                <option value="reader" ' . ($row->hasRole('reader')  ? 'selected' : '') . '>Reader</option>
                <option value="admin" ' . ($row->hasRole('admin') ? 'selected' : '') . '>Admin</option>
                <option value="author" ' . ($row->hasRole('author') ? 'selected' : '') . '>Author</option>
                </select>';
            })
            ->addColumn('create permission', function ($row) {
                $edithtml = '<label class="switch">';
                if ($row->can("create_post")) {
                    $edithtml .= '<input type="checkbox" checked="" class="checkboxSwitch" id="createCheckbox" data-name="create_post" data-id= "' . $row->id . '" >';
                } else {
                    $edithtml .= '<input type="checkbox"  class="checkboxSwitch" id="createCheckbox" data-name="create_post" data-id= "' . $row->id . '" >';
                }
                $edithtml .= ' <div class="slider">
                    <div class="circle"><svg class="cross" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 365.696 365.696" y="0" x="0" height="6" width="6" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g><path data-original="#000000" fill="currentColor" d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0"></path></g>
                    </svg><svg class="checkmark" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 24 24" y="0" x="0" height="10" width="10" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g><path class="" data-original="#000000" fill="currentColor" d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"></path></g>
                    </svg></div></div></label>';
                return $edithtml;
            })
            ->addColumn('edit permission', function ($row) {
                $edithtml = '<label class="switch">';
                if ($row->can("edit_post")) {
                    $edithtml .= '<input type="checkbox" checked="" class="checkboxSwitch" id="editCheckbox" data-name="edit_post" data-id= "' . $row->id . '" >';
                } else {
                    $edithtml .= '<input type="checkbox"  class="checkboxSwitch" id="editCheckbox" data-name="edit_post" data-id= "' . $row->id . '" >';
                }
                $edithtml .= ' <div class="slider">
                    <div class="circle"><svg class="cross" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 365.696 365.696" y="0" x="0" height="6" width="6" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g><path data-original="#000000" fill="currentColor" d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0"></path></g>
                    </svg><svg class="checkmark" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 24 24" y="0" x="0" height="10" width="10" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g><path class="" data-original="#000000" fill="currentColor" d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"></path></g>
                    </svg></div></div></label>';
                return $edithtml;
            })
            ->addColumn('delete permission', function ($row) {
                $edithtml = '<label class="switch">';
                if ($row->can("delete_post")) {
                    $edithtml .= '<input type="checkbox" checked="" class="checkboxSwitch" id="editCheckbox" data-name="delete_post" data-id= "' . $row->id . '" >';
                } else {
                    $edithtml .= '<input type="checkbox"  class="checkboxSwitch" id="editCheckbox" data-name="delete_post" data-id= "' . $row->id . '" >';
                }
                $edithtml .= ' <div class="slider">
                    <div class="circle"><svg class="cross" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 365.696 365.696" y="0" x="0" height="6" width="6" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g><path data-original="#000000" fill="currentColor" d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0"></path></g>
                    </svg><svg class="checkmark" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 24 24" y="0" x="0" height="10" width="10" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g><path class="" data-original="#000000" fill="currentColor" d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"></path></g>
                    </svg></div></div></label>';
                return $edithtml;
            })
            ->addColumn('publish permission', function ($row) {
                $edithtml = '<label class="switch">';
                if ($row->can("publish_post")) {
                    $edithtml .= '<input type="checkbox" checked="" class="checkboxSwitch" id="editCheckbox" data-name="publish_post" data-id= "' . $row->id . '" >';
                } else {
                    $edithtml .= '<input type="checkbox"  class="checkboxSwitch" id="editCheckbox" data-name="publish_post" data-id= "' . $row->id . '" >';
                }
                $edithtml .= ' <div class="slider">
                    <div class="circle"><svg class="cross" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 365.696 365.696" y="0" x="0" height="6" width="6" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g><path data-original="#000000" fill="currentColor" d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0"></path></g>
                    </svg><svg class="checkmark" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 24 24" y="0" x="0" height="10" width="10" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g><path class="" data-original="#000000" fill="currentColor" d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"></path></g>
                    </svg></div></div></label>';
                return $edithtml;
            })
            ->setRowId('id')
            ->rawColumns(['role', 'create permission', 'edit permission', 'delete permission', 'publish permission']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('module-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'ASC')
            ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('fname')->title('First Name'),
            Column::make('email')->title('Email'),
            Column::make('role')->title('Role'),
            Column::make('create permission')->title('Create'),
            Column::make('edit permission')->title('Edit'),
            Column::make('delete permission')->title('Delete'),
            Column::make('publish permission')->title('Publish'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Module_' . date('YmdHis');
    }
}
