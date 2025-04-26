<?php

namespace App\DataTables;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TagsDataTable extends DataTable
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
                    $button .= '<button type="button" class="btn btn-sm btn-primary" data-id="' . $row->id . '" id="restoreTrashed"><i class="fas fa-recycle mt-1"></i></button>';
                    $button .= '<button type="button" class=" ml-2 btn btn-sm btn-danger delete-category-btn" data-id="' . $row->id . '" ><i class="fa fa-trash"></i></button>';
                } else {
                    if (Auth::user()->can('edit_category')) {
                        $button .= '<button type="button" class="btn btn-sm btn-primary edit-category-btn" data-toggle="modal" data-id="' . $row->id . '" data-target="#editCategoryModal"><i class="fa fa-pencil-alt"></i></button>
            ';
                    }
                    if (Auth::user()->can('delete_category')) {
                        $button .= '<button type="button" class=" ml-2 btn btn-sm btn-danger delete-category-btn" data-toggle="modal" data-id="' . $row->id . '" data-target="#deleteCategoryModal"><i class="fa fa-trash"></i></button>';
                    }
                }
                $html = '<div class="d-flex justify">' . $button . '</div>';
                return $html;
            })

            ->addColumn('select', function ($row) {
                $button = '';
                if (Auth::user()->can('delete_category')) {
                    $button .= '<input type="checkbox" name="category_checkbox"  class="class_checkbox text-center" data-id="' . $row->id . '">';
                }
                $html = '<div class="d-flex justify">' . $button . '</div>';
                return $html;
            })
            ->setRowId('id')
            ->rawColumns(['action', 'select']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Tag $model): QueryBuilder
    {
        $query = $model->newQuery();
        if (request()->status == 'Active') {
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
            ->setTableId('tags-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(0)
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
            Column::computed("select", '<input type="checkbox" class="checkAllBtn"  id="checkAllBtn" style="cursor:pointer">')
                ->titleAttr('')
                ->orderable(false)
                ->visible(Auth::user()->canAny(['edit_category', 'delete_category']) ? true : false),
            Column::make('id')->addClass('text-left'),
            Column::make('name'),
            Column::make('action')->orderable(false)->visible(Auth::user()->canany(['edit_category', 'delete_category'])),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Tags_' . date('YmdHis');
    }
}
