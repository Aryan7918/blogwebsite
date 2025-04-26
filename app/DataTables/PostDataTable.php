<?php

namespace App\DataTables;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PostDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                $button = '';
                if ($row->trashed()) {
                    $button .= '<button type="button" class="btn btn-sm btn-primary" data-id="' . $row->id . '" id="restoreTrashed"><i class="fas fa-recycle"></i></button>';
                    $button .= '<button type="button" class=" ml-2 btn btn-sm btn-danger delete-post-btn" data-id="' . $row->id . '" ><i class="fa fa-trash"></i></button>';
                } else {
                    if (Auth::user()->can('edit_post')) {
                        $button .= '<a href="' . route("posts.edit", [$row->id]) . '" class="btn btn-sm btn-primary" ><i class="fa fa-pencil-alt"></i></a> ';
                    }
                    if (Auth::user()->can('delete_post')) {
                        $button .= '<button type="button" class="ml-2 btn btn-sm btn-danger delete-post-btn" data-toggle="modal" data-id="' . $row->id . '" data-target="#deletePostModal"><i class="fa fa-trash"></i></button>';
                    }
                }
                $html = '<div class="d-flex">' . $button . '</div>';
                return $html;
            })
            ->addColumn('status', function ($row) {
                return '<select name="status" class="statusClass" data-id="' . $row->id . '" ' . ($row->trashed() ? 'disabled' : '') . '>
                <option value="Active" ' . ($row->status == 'Active' ? 'selected' : '') . '>Active</option>
                <option value="Inactive" ' . ($row->status == 'Inactive' ? 'selected' : '') . '>Inactive</option>
                </select>';
            })
            ->addColumn('select', function ($row) {
                $button = '';
                if (Auth::user()->can('delete_post')) {
                    $button .= '<input type="checkbox" name="post_checkbox"  class="class_checkbox text-center" data-id="' . $row->id . '">';
                }
                $html = '<div class="d-flex justify">' . $button . '</div>';
                return $html;
            })
            ->setRowId('id')
            ->rawColumns(['action', 'status', 'select']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Post $model): QueryBuilder
    {
        $query = $model->newQuery();
        if (request()->status == "Active") {
            $query = $query->onlyTrashed();
        } else {
            $query = $query->withoutTrashed();
        }
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
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
            Column::computed("select", '<input type="checkbox" class="checkAllBtn"  id="checkAllBtn" style="cursor:pointer">')
                ->titleAttr('')
                ->orderable(false)
                ->visible(Auth::user()->canAny(['edit_post', 'delete_post']) ? true : false),
            Column::make('id'),
            Column::make('title'),
            Column::make('slug'),
            Column::make('status'),
            Column::make('action')->orderable(false)->visible(Auth::user()->canany(['edit_post', 'delete_post']) ? true : false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Post_' . date('YmdHis');
    }
}
