
@if (session()->has('success'))
<div class="alert alert-success alert-dismissible fade show p-2 m-0" id="hasSuccessMsg" role="alert" style="height: 2.5rem">
    <p class="p-0 m-0 pr-4 pl-2">{{ session('success') }}</p>
    <button type="button" class="close p-2 pr-2" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif
@if (session()->has('danger'))
<div class="alert alert-danger alert-dismissible fade show p-2 m-0" role="alert" style="height: 2.5rem">
    <p class="p-0 m-0 pr-4 pl-2">{{ session('danger') }}</p>
    <button type="button" class="close p-2 pr-2" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif
