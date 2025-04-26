<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class UsersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                $button = '';
                if ($row->trashed()) {
                    $button .= '<button type="button" class="btn btn-sm btn-primary" data-id="' . $row->id . '" id="restoreTrashed"><i class="fas fa-recycle"></i></button>';
                    $button .= '<button type="button" class=" ml-2 btn btn-sm btn-danger delete-user-btn" data-id="' . $row->id . '" ><i class="fa fa-trash"></i></button>';
                } else {
                    if (Auth::user()->can('edit_user')) {
                        $button .= '<a href="' . route("users.edit", [$row->id]) . '" class="btn btn-sm btn-primary mx-1" ><i class="fa fa-pencil-alt"></i></a>';
                    }
                    if (Auth::user()->can('delete_user')) {
                        // $button .= '<button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-danger mx-1" id="" ><i class="fa fa-trash"></i></button>';
                        $button .= '<button type="button" class=" ml-2 btn btn-sm btn-danger delete-user-btn" data-toggle="modal" data-id="' . $row->id . '" ><i class="fa fa-trash"></i></button>';
                    }
                }
                $html = '<div class="d-flex justify">' . $button . '</div>';
                return $html;
            })
            ->addColumn('status', function ($row) {
                return '<select name="status" class="statusClass" data-id="' . $row->id . '" ' . ($row->trashed() ? 'disabled' : '') . '>
                <option value="Active" ' . ($row->status == 'Active' ? 'selected' : '') . '>Active</option>
                <option value="Inactive" ' . ($row->status == 'Inactive' ? 'selected' : '') . '>Inactive</option>
                </select>';
            })
            ->addColumn('role', function ($row) {
                return '<select name="role" class="roleClass" data-id="' . $row->id . '" ' . ($row->trashed() ? 'disabled' : '') . '>
                <option value="reader" ' . ($row->hasRole('reader')  ? 'selected' : '') . '>Reader</option>
                <option value="admin" ' . ($row->hasRole('admin') ? 'selected' : '') . '>Admin</option>
                <option value="author" ' . ($row->hasRole('author') ? 'selected' : '') . '>Author</option>
                </select>';
            })
            ->addColumn('select', function ($row) {
                $button = '';
                if (Auth::user()->can('delete_user')) {
                    $button .= '<input type="checkbox" name="user_checkbox"  class="class_checkbox text-center" data-id="' . $row->id . '">';
                }
                $html = '<div class="d-flex justify">' . $button . '</div>';
                return $html;
            })
            ->setRowId('id')
            ->rawColumns(['action', 'status', 'role', 'select']);
    }

    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery();
        // dd(request()->status);
        if (request()->status == 'Active') {
            // $query = $query->whereStatus(request()->status);
            $query = $query->onlyTrashed();
        } else {
            $query = $query->withoutTrashed();
        }
        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::computed("select", '<input type="checkbox" class="checkAllBtn"  id="checkAllBtn" style="cursor:pointer">')
                ->titleAttr('')
                ->orderable(false)
                ->visible(Auth::user()->canAny(['edit_user', 'delete_user']) ? true : false),
            Column::make('id'),
            Column::make('fname')->title('First Name'),
            Column::make('lname')->title('Last Name'),
            Column::make('email')->title('Email'),
            Column::make('mobile')->title('Phone No'),
            Column::make('role')->orderable(false),
            Column::make('status')->title('Status')->addClass('text-center')->orderable(false),
            Column::make('action')->orderable(false)->visible(Auth::user()->canany(['edit_user', 'delete_user']))->addClass('text-center'),

        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
