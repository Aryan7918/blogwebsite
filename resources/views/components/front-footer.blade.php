<footer class="py-2 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">
            Copyright &copy; Your Website 2024
        </p>
    </div>
</footer>
<!-- Bootstrap core JS-->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- Core theme JS-->
<script src="{{asset('js/FrontScripts.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.min.js')}}"></script>
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script>
    $('#name').select2({
        placeholder: 'Select Category',
        width:'100%',
    });
    $. ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]'). attr('content')
        }
    });
</script>