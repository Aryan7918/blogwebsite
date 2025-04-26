<div class="card mb-4">
    <div class="card-header">Categories</div>
    <div class="card-body">
        <div class="row">
            <form action="#" method="get">
                <div class="col-sm-12">
                    <select name="category" id="name" class="select2 form-control">
                        <option></option>
                        @foreach ($categories as $category )
                        <option value="{{$category->id}}" {{ request()->
                            query('category')==$category->id?'selected':''}}>{{ $category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 mt-2">
                    <button type="submit" class="btn btn-primary btn-sm" id="addCategory">Submit
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>