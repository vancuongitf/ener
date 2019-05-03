<script>
    function genSearchValue(alias) {
        var str = alias;
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g, " ");
        str = str.replace(/ + /g, " ");
        str = str.trim();
        while (str.includes("  ") > 0) {
            str = str / replace("  ", " ");
        }
        return str;
    }
    $(document).ready(function() {
        $("#search_input").on('keyup', function (e) {
            if (e.keyCode == 13) {
                window.location.href = "<?php echo url('admin/search?page=1&query=');?>".concat(genSearchValue($('#search_input').val()));
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
        <li class="nav-item">
                <a class="nav-link white-text-hover" href="{{url('admin/users')}}">Users</a>
            </li>
    </ul>
    <div class="form-inline my-2 my-lg-0">
        <input id="search_input" type="text" class="form-control" >
    </div>
    <div class="form-inline">
        <a class="btn btn-primary" href="{{url('admin/logout')}}">Logout</a>
    </div>
</nav>
