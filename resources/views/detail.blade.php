@extends('layout.master')

@section('title')
    Detail Information
@endsection

@section('page-summary')
    Detail Information
@endsection

@section('description')

@endsection



@section('content')

    <div class="container">
        <div class="container-fluid container-fullw">
            <div class="row">

                <div class="col-sm-8">
                    <h1>Click List</h1>
                </div>
                <div class="col-sm-2 text-right">
                    <a class="btn btn-wide btn-primary" href="#" id="id-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                </div>
                <div class="col-sm-2 text-right">
                    <a class="btn btn-wide btn-primary" href="#" id="id-export"><i class="fa fa-save"></i> Export</a>
                </div>


            </div>

            <div class="row">
                <table class="table table-striped table-hover" id="user-table" >
                    <thead>
                    <tr>
                        <th class="">NO</th>
                        <th class="">Source</th>
{{--                        <th class="">IP Address</th>--}}
                        <th class="">Redirect</th>
                        <th class="">Click Date/Time</th>

                    </tr>
                    </thead>
                    <tbody>

                    @foreach($urls as $index => $url)
                        <tr>
                            <td class=""><?=($index + 1)?></td>

                            <td>
                                <span style="width: 250px;word-wrap:break-word; display:inline-block;">
                                    <?=$url->source?>
                                </span>

                            </td>
{{--                            <td>--}}
{{--                                <?=$url->source_ip?>--}}
{{--                            </td>--}}

                            <td class="">
                                <span style="width: 250px;word-wrap:break-word; display:inline-block;">
                                    <?=$url->redirect_url?>
                                </span>

                            </td>
                            <td class="">
                                <?=$url->click_time?>
                            </td>


                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if(count($urls) == 0)
                    <div class="center">No data available</div>
                @endif

            </div>
        </div>
    </div>


    <div class="modal fade in" id="id-modal" tabindex="-1" role="dialog" aria-labelledby="Quiz" aria-hidden="true">
        <form method="post" enctype="multipart/form-data" id="id-user-form" >
            {{csrf_field()}}
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                        <h4 class="modal-title" id="id-modal-title"></h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="control-label" for="id-name"> User Name: </label>
                            <input type="text" class="form-control" id="id-name" name="username"  required="true">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="id-password"> Password: </label>
                            <input type="password" class="form-control" name="password" id = "id-password" required="true">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="id-repeat"> RepeatPassword: </label>
                            <input type="password" class="form-control" name="repeat_password" id = "id-repeat" required="true">
                        </div>



                        <div class="row">

                            <div class="col-sm-12 ">
                                <div class="form-group ">
                                    <label class="control-label" for="id-type"> User Type: </label>
                                    <select id="id-type">
                                        @if($type == 'Admin')
                                            <option value="Manager">Manager</option>
                                        @endif
                                            <option value="User">User</option>
                                    </select>
                                    <input type = "text" id = "id-select-type" name = "select_type" style="display: none;">

                                </div>
                            </div>


                        </div>

                        <input type="hidden" name="user_id" id="id-user-id">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-o" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" id="id-btn-submit">
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('js4page')

@endsection

@section('js4event')

    <script>


        jQuery(document).ready(function() {


            $("#id-export").on("click", function (event) {
                var store_id = {{$store_id}};
                window.location =  "<?=url('/count/detail/report?store_id=')?>" + store_id;
            });


            $("#id-refresh").on("click", function (event) {
                window.location.reload();




            });
        });
    </script>

@endsection