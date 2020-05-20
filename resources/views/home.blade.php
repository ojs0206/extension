@extends('layout.master')

@section('title')
    HOME
@endsection

@section('page-summary')
    HOME
@endsection

@section('description')

@endsection

@section('styles')
    <style>
        .url-width {
            width: 20% !important;
        }

        .sidenav {
            height: 100%;
            width: 15%;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: whitesmoke;
            overflow-x: hidden;
            transition: 0.5s;
            padding: 10px;
            border-right: 2px solid lightgrey;
        }
        .sidenav a {
            padding: 18px 8px 18px 15px;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
            color: black;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: blue;
        }

        .sidenav img {
            width: 30px;
            height: 30px;
            margin-right: 0;
            margin-bottom: auto;
            margin-top: auto;
            margin-left: 15px;
        }

        .sidenav p {
            font-size: 15px;
            margin: auto;
            font-weight: 400;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            .sidenav a {font-size: 18px;}
        }

    </style>
@endsection


@section('content')
{{--<div class="row">--}}
    {{--<div class="col-sm-2">--}}
        {{--<div id="sidenav" class="sidenav">--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-10">--}}

    {{--</div>--}}
{{--</div>--}}
    <div class="main-content">
        <div class="wrap-content container" id="container">
            <div class="container-fluid container-fullw">
                <div class="row">
                    <div class="col-12">
                        <div align="center">
                            <h3>

                            </h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">

                        <label for="backgroundURL">Select Background URL: </label>


                        <select id="backgroundURL" name="backgroundURL">
                            @foreach($urls as $url)
                                <option value="<?=$url->url;?>" url-id="<?=$url->id;?>" url-width="<?=$url->width;?>" url-height="<?=$url->height;?>"><?=$url->url;?></option>
                            @endforeach

                        </select>


                    </div>
                </div>

            </div>
        </div>
        @foreach($urls as $url)
            <input type="hidden"  id="<?=$url->id;?>" value="<?=asset('/assets/images').'/'.$url->image_path;?>">
        @endforeach

    </div>

    <div id="main_table"
         style="min-height: 1px; position: relative; background-size: cover; background-repeat: no-repeat; background-position: center;">
        <img id="bk-img">
    </div>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Insert Url</h4>
                </div>
                <div class="modal-body">
                    <div>

                        <input type="text" class="form-control" id="url" name="url" placeholder="">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btnClose" class="btn btn-primary btn-sm btn-o btn-squared"
                            data-dismiss="modal">
                        Close
                    </button>
                    <button id="btnSave" type="button" class="btn btn-primary btn-sm btn-squared">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>


    <input type="text" id="firstCellId" style="display:none"/>
    <input type="text" id="secondCellId" style="display:none"/>



@endsection

@section('js4page')

@endsection

@section('js4event')


    <script>
        var server_host_url = "/extension/public/index.php";
        function getWidth(str) {
            var arr = str.split('_');
            return arr[2];
        }

        function getHeight(str) {
            var arr = str.split('_');
            return arr[1];
        }
        var all_data = {};
        var image_path;

        function addTable(tableWidth, tableHeight) {

            var real_width = $('.main-content').width();

            console.log("Real Width" + real_width);
            console.log($(window).width());
            var rate = tableWidth / real_width;
            tableHeight = tableHeight / rate;
            tableWidth = real_width;
            console.log(real_width);
            var cnt = 0;
            console.log("URL = " + image_path);

            var height_pixel;
            var width_pixel;
            //var height = $('#main_table').height();


            console.log(tableWidth);
            console.log(tableHeight);


            width_pixel = 80;


            var width_size = (tableWidth / width_pixel);
            var height_size = width_size;
            height_pixel = parseInt(tableHeight/height_size);

            console.log(height_pixel + ' ' + width_pixel);
            console.log(width_size + ' ' + height_size);

            var tbl = document.createElement('table');
            tbl.style.borderColor = "black";
            tbl.style.position = "relative";
            tbl.style.width = '100%';
            tbl.style.height = '100%';
            tbl.setAttribute('border', '1');
            var tbdy = document.createElement('tbody');
            var mainTable = $('#main_table');

            for (var i = 0; i < height_pixel; i++) {
                var tr = document.createElement('tr');
                for (var j = 0; j < width_pixel; j++) {
                    var td = document.createElement('td');
                    td.style.width = height_size + 'px';
                    td.style.height = width_size + 'px';
                    var id = "_" + i + "_" + j;
                    td.setAttribute("id", id);

                    var val = all_data[id];
                    if (val != null && val != "") {
                        td.style.backgroundColor = "gray";
                    }
                    td.onclick = function () {
                        var text = $("#backgroundURL").val();
                        if(text == "") {
                            alert("Input background url");
                            return ;
                        }

                        cnt = cnt + 1;
                        if (cnt == 2) {
                            cnt = 0;
                        }

                        var myId = this.id;
                        if (cnt == 1) {
                            $("#firstCellId").val(myId);
                        }
                        else {
                            $("#secondCellId").val(myId);
                        }

                        $(this).css("background-color", "lightgray");

                        console.log(myId);
                        if (cnt == 0) {
                            $("#myModal").modal("show");
                        }

                    };


                    tr.appendChild(td);

                }
                tbdy.appendChild(tr);
            }
            tbl.appendChild(tbdy);

//            while (body.firstChild) {
//                body.removeChild(body.firstChild);
//            }

            var bkImg = $('<img id="bk-img">');
            bkImg.css('position', 'absolute');
            mainTable.html('');
            var real_width = $(window).width();
            //mainTable.width(real_width);
            bkImg.attr('src', image_path);
            mainTable.append(bkImg);
            mainTable.append(tbl);
            console.log(mainTable.width());
            console.log(real_width);
            bkImg.width(mainTable.width());
            bkImg.height(mainTable.height());
        }

        var isLoad = 0;
        var url;
        var url_id;
        var width;
        var height;

        function loadData() {
            all_data = {};

            var mainTable = $('#main_table');
            var tableWidth = mainTable.width();

            var topOffset = mainTable.position().top;
            var windowHeight = $(window).height();
            var tableHeight = windowHeight - topOffset;
            if(url == "") {
                addTable(tableWidth, tableHeight);
                return ;
            }
            $.ajax({
                url: '<?=url('/get/cell-info')?>',//server_host_url + "/get/cell-info",
                type: "get", //send it through get method
                data: {
                    "url": url
                },
                dataType:'json',
                success: function(response) {

                    $.each(response,function(i,result){
                        var start_cell_x = parseInt(result["start_x"]);
                        var start_cell_y = parseInt(result["start_y"]);
                        var end_cell_x = parseInt(result["end_x"]);
                        var end_cell_y = parseInt(result["end_y"]);
                        for(var x = start_cell_x; x <= end_cell_x; x ++) for(var y = start_cell_y; y <= end_cell_y; y ++) {
                            all_data["_" + y + "_" + x] = result["redirect_url"];
                        }
                    });
                    console.log(all_data);
                    image_path = $("#" + url_id).val();
                    addTable(width, height);
                },
                error: function(xhr) {

                }
            });
        }

        $("#bk-img").attr('src', url).load(function(){
            console.log("ISLOAD = " + isLoad);
            if(isLoad == 0) {
                isLoad = 1;
                console.log("Path = " + url);
                console.log(this.width + " " + this.height);
                addTable(this.width, this.height, url);
            }
        });

        jQuery(document).ready(function () {










            $("#myModal").modal({
                show: false
            });

            url = $( "#backgroundURL option:selected" ).val();
            url_id = $( "#backgroundURL option:selected" ).attr('url-id');
            height = $( "#backgroundURL option:selected" ).attr('url-height');
            width = $( "#backgroundURL option:selected" ).attr('url-width');

            console.log("URL = " + url);
            console.log(url_id);

            //console.log(getHeight("a_b_c_100_200"));
            //console.log(getWidth("a_b_c_100_200"));
            $('#backgroundURL').on('change', function() {
                url = $( "#backgroundURL option:selected" ).val();
                url_id = $( "#backgroundURL option:selected" ).attr('url-id');
                height = $( "#backgroundURL option:selected" ).attr('url-height');
                width = $( "#backgroundURL option:selected" ).attr('url-width');
                isLoad = 0;
                console.log("Change");
                console.log(url);
                console.log(url_id);
                loadData(url);
            });
            loadData(url);






            $("#btnClose").on("click", function () {

                var firstCell = $("#firstCellId").val();
                var secondCell = $("#secondCellId").val();
                $("#" + firstCell).css("background-color", "transparent");
                $("#" + secondCell).css("background-color", "transparent");
                $("#url").val('');
            });

            $("#btnSave").on("click", function () {

                var redict_url = $("#url").val();
                if(redict_url == "") {
                    alert("Input redirect url");
                    return ;
                }
                var url = $( "#backgroundURL option:selected" ).val();
                console.log('URL Selected');
                console.log(url);
                var firstCell = $("#firstCellId").val();
                var secondCell = $("#secondCellId").val();
                var firstWidth = parseInt(getWidth(firstCell));
                var firstHeight = parseInt(getHeight(firstCell));

                var secondWidth = parseInt(getWidth(secondCell));
                var secondHeight = parseInt(getHeight(secondCell));

                if (firstHeight > secondHeight) {
                    var c = firstHeight;
                    firstHeight = secondHeight;
                    secondHeight = c;
                }

                if (firstWidth > secondWidth) {
                    var cc = firstWidth;
                    firstWidth = secondWidth;
                    secondWidth = cc;
                }
                console.log(firstWidth + " " + secondWidth);
                console.log(firstHeight + " " + secondHeight);



                for (var i = firstHeight; i <= secondHeight; i++) {
                    for (var j = firstWidth; j <= secondWidth; j++) {
                        var id = "_" + i + "_" + j;
                        all_data[id] = redict_url;
                        if (redict_url != "") {
                            $("#" + id).css("background-color", "gray");
                        }
                        else {
                            $("#" + id).css("background-color", "transparent");
                        }
                    }
                }

                $("#myModal").modal("hide");
                $("#url").val("");
                $.ajax({
                    url: server_host_url + "/save/cell",
                    type: "post", //send it through get method
                    data: {
                        "start_x": firstWidth,
                        "start_y": firstHeight,
                        "end_x": secondWidth,
                        "end_y": secondHeight,
                        "url": url,
                        "redirect_url": redict_url
                    },
                    dataType:'json',
                    success: function(response) {
                        console.log('Success');
                        console.log(response);

                    },
                    error: function(xhr) {
                        console.log('Error');
                        console.log(xhr);
                    }
                });

            });
        });
    </script>

@endsection