<div class="card mb-4">
    <div class="card-header">Search</div>
    <div class="card-body">
        <form action="#" method="GET">
            <input class="form-control" type="text" name="search" placeholder="Enter search post..."
                aria-label="Enter search term..." aria-describedby="button-search" class="w-100"
                value="{{request()->query('search')}}" />
        </form>
    </div>
</div>