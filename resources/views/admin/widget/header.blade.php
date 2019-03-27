<script>
    $(document).ready(function() {
        $("#search_input").on('keyup', function (e) {
            if (e.keyCode == 13) {
                window.location.href = "<?php echo url('admin/search?page=1&query=');?>".concat($('#search_input').val());
            }
        });
    });
</script>
<nav class="navbar navbar-expand-lg navbar-light bg-primary sticky-top">
    <a class="navbar-brand white-text-hover" href="{{ url('admin') }}" style="margin-right: 50px;">Ener Admin Page</a>
    <ul class="navbar-nav mr-auto p-2">
        <li class="nav-item">
            <a class="nav-link white-text-hover" href="{{url('admin/tag')}}">Post Tags</a>
        </li>
    </ul>
    <div class="form-inline my-2 my-lg-0">
        <input id="search_input" type="text" class="form-control" >
    </div>
    <div class="form-inline">
        <a class="btn btn-primary" href="{{url('admin/logout')}}">Logout</a>
    </div>
</nav>
