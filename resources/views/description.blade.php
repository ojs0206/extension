@extends('layout.master')

@section('title')
    Graphical Interface -- {{$hint}}
@endsection

@section('css4page')
    <style>
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
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }

        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .cancelModal {
            color: dimgrey;
            font-size: 1vw;
            text-align: center;
            font-weight: bold;
        }

        .cancelModal:hover,
        .cancelModal:focus {
            color: blue;
        }

        .saveModal {
            color: dimgrey;
            font-size: 1vw;
            text-align: center;
            font-weight: bold;
        }

        .saveModal:hover,
        .saveModal:focus {
            color: blue;
        }

        /*The slider*/
        .slidecontainer {
            width: 100%;
        }

        .slider {
            -webkit-appearance: none;
            width: 100%;
            height: 5px;
            background: #d3d3d3;
            outline: none;
            opacity: 0.7;
            -webkit-transition: .2s;
            transition: opacity .2s;
        }

        .slider:hover {
            opacity: 1;
        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 15px;
            height: 15px;
            background: blue;
            cursor: default;
        }

        .slider::-moz-range-thumb {
            width: 15px;
            height: 15px;
            background: blue;
            cursor: default;
        }

        /*Table*/
        th {
            font-size: 1vw;
            color: black;
        }

        tr {
            border: 1px solid whitesmoke;
            padding-bottom: 1%;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var height = document.getElementsByClassName('navbar')[0].clientHeight;
            console.log(height);
            var sidenav = document.getElementById('sidenav');
            sidenav.style.paddingTop = height + 5 + "px";
        });
    </script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-2">
            <div id="sidenav" class="sidenav">
                <div class="row" style="padding-left: 5%; padding-top: 8%; display: flex">
                    <a href="<?=url('/payment/billing_rate_setting');?>">
                        <p style="font-size: 1.2vw;">Back to All Settings</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="row" style="padding-bottom: 30px;">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <p style="text-align: center; font-size: 3vw;">Edit Custom Budget Setting -- {{$hint}}</p>
                </div>
            </div>
            <div class="row" style="padding-left: 10%;" id="summary">
                <div class="col-sm-1"></div>
                <div class="col-sm-4" style="border: 7px solid whitesmoke; padding-top: 1%; padding-bottom: 1%;">
                    <div id="chartClicks" style="height: 300px; width: 100%;"></div>
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-4" style="border: 7px solid whitesmoke; padding-top: 1%; padding-bottom: 1%;">
                    <div id="chartSpent" style="height: 300px; width: 100%;"></div>
                </div>
            </div>

            <div class="row" style="padding-left: 10%; padding-top: 5%;" id="">
                <div class="col-sm-4"></div>
                <div class="col-sm-4" style="border: 7px solid whitesmoke; padding-top: 1%; padding-bottom: 1%;">
                    <p style="padding-bottom: 10%;"><font size="5" color="black">Budget</font>
                        <br><strong style="color: black; font-size: 2vw;">{{$click -> currency}}</strong><strong
                                style="color: black; font-size: 2vw;" id="dailyBudget_1">
                            @if ($budget_type -> budget_type == 0)
                                ${{round((($click -> monthly_threshold) / 30), 1)}}
                            @else
                                ${{round((($click -> budget) / 30), 1)}}
                            @endif
                        </strong><font size="3"
                                                                                                        color="black">
                            daily average</font>
                        <br><strong style="color: black; font-size: 2vw;">{{$click -> currency}}</strong><strong
                                style="color: black; font-size: 2vw;" id="monthlyBudget">
                            @if ($budget_type -> budget_type == 0)
                                ${{round($click -> monthly_threshold, 2)}}
                            @else
                                ${{round($click -> budget, 2)}}
                            @endif
                        </strong><font size="3"
                                                                                                        color="black">
                            monthly maximum</font>
                    </p>
                    <hr>
                    <button class="btn btn-primary" id="budgetEdit">Edit</button>
                </div>
            </div>

            <!-- The Modal -->
            <div id="editModal" class="modal">
                <!-- Modal content -->
                <form action="<?=url('/payment')?>" method="post" id="newBudgetSubmit" name="newBudgetSubmit">
                    <input type="hidden" value="{{$hint}}" id="urlName" name="urlName">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="row">
                            <p style="padding-left: 5%; padding-top: 3%;"><font size="5">Edit budget</font></p>
                        </div>
                        <div class="row">
                            <div class="col-sm-8" style="margin: 10px;">
                                <div class="row">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-3" style="text-align: center;">
                                        <p><u style=""></u><strong
                                                    style="font-size: 2vw">{{$click -> currency}}$</strong> <strong
                                                    id="dailyBudget" style="font-size: 2vw">
                                                @if ($budget_type -> budget_type == 0)
                                                    {{round((($click -> monthly_threshold) / 30), 2)}}
                                                @else
                                                    {{round(($click -> budget) / 30, 2)}}
                                                @endif</strong></p>
                                        <hr style="border: 1px blue;">
                                    </div>
                                    <div class="col-sm-1.5" style="padding-top: 3%;">
                                        <p style="font-size: 0.65vw">per day<br>average</p>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 5%;">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-4" style="padding-left: 5%;">
                                        <p><strong>{{$click -> currency}}$</strong> <strong id="currentBudget">
                                                @if ($budget_type -> budget_type == 0)
                                                    {{round($click -> monthly_threshold, 2)}}
                                                @else
                                                    {{round($click -> budget, 2)}}
                                                @endif
                                            </strong> monthly maximium</p>
                                    </div>
                                </div>
                                <div class="slidecontainer" style="padding-bottom: 5%;">
                                    <input type="range" min="1" max="10000" class="slider" id="editBudget"
                                           name="editBudget" onchange="updateValue(this.value);"
                                           style="width: 80%;"><label for="editBudget"></label>
                                </div>
                                <div class="row" style="padding-left: 5%;">
                                    <p style="font-size: 1.5vw;"> For <span id="dayLeft"></span> days left in <span
                                                id="monthName"></span>, you will spend a maximum of {{round($click -> currency, 2)}}<span
                                                id="remainBudget"></span></p>
                                    <table style="width: 90%;">
                                        <tr>
                                            <th>How your budget works</th>
                                        </tr>
                                        <tr>
                                            <th>When you pay for</th>
                                            <th>How much it costs</th>
                                            <th>If you change your mind</th>
                                        </tr>
                                        <tr>
                                            <td>.....</td>
                                            <td>.....</td>
                                            <td>.....</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-3" style="margin: 10px; padding-top: 5%;">
                                <div style="border: 2px solid whitesmoke; vertical-align: center; margin: auto;">
                                    <p style="font-size: 20px; color: black;">Estimated performance</p>
                                    <p style="font-size: 10px; color: black;">Limited clicks</p>
                                    <p style="font-size: 10px; color: black;">The estimation is based on ......</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9"></div>
                            <div class="col-sm-1">
                                <p class="cancelModal">Cancel</p>
                            </div>
                            <div class="col-sm-1">
                                <p class="saveModal">Save</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js4page')
    {{--Chart draw Canvas--}}
    <script>

        var obj = {};
        var currency = "{{$click->currency}}";
        @if ($budget_type -> budget_type == 0)
            var clickCut = {{$click -> rate_per_click}};
            var clickCount = {{count($count)}};
            var urlBudget = {{$click -> monthly_threshold}};
        @else
            var clickCut = {{$click -> click_cut}};
            var clickCount = {{count($count)}};
            var urlBudget = {{$click -> budget}};
        @endif
        var urlName = "{{$hint}}";

        var select_data = [];
        @foreach($count as $clicked)
            select_data.push("{{$clicked -> click_time}}");
        @endforeach
        select_data.sort(function(a,b){
            // Turn your strings into dates, and then subtract them
            // to get a value that is either negative, positive, or zero.
            return new Date(b) - new Date(a);
        });
        var filtered = select_data.filter(function (el) {
            return el != "";
        });

        select_data = filtered;
        for (var i = 0 ; i < select_data.length; i++){
            select_data[i] = select_data[i].split(" ")[0];
        }

        obj = {};
        select_data.forEach(function(item) {
            if (typeof obj[item] == 'number') {
                obj[item]++;
            } else {
                obj[item] = 1;
            }
        });

        function drawchart(data) {
            // Get Left Budget and Days
            var monthData = '{ "monthlyDays" : [' +
                '{"days": 31 , "name": "January"},' +
                '{"days": 28 , "name": "February"},' +
                '{"days": 31 , "name": "March"},' +
                '{"days": 30 , "name": "April"},' +
                '{"days": 31 , "name": "May"},' +
                '{"days": 30 , "name": "June"},' +
                '{"days": 31 , "name": "July"},' +
                '{"days": 31 , "name": "August"},' +
                '{"days": 30 , "name": "September"},' +
                '{"days": 31 , "name": "October"},' +
                '{"days": 30 , "name": "November"},' +
                '{"days": 31 , "name": "December"} ]}';
            var monthObject = JSON.parse(monthData);
            var currentDate = new Date();
            var leftDaysCount = monthObject.monthlyDays[currentDate.getMonth()].days - currentDate.getDate();
            var currentMonthName = monthObject.monthlyDays[currentDate.getMonth()].name;
            document.getElementById('dayLeft').innerHTML = leftDaysCount;
            document.getElementById('monthName').innerHTML = currentMonthName;
            document.getElementById('remainBudget').innerHTML = urlBudget - clickCut * clickCount;
            console.log(leftDaysCount + "::" + currentMonthName);

            var clickChart = new CanvasJS.Chart("chartClicks", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Clicks:  " + data.length
                },
                axisY: {
                    // title: "Clicks",
                    includeZero: true,
                    interval: 1
                },
                axisX: {
                    // title: "Date",
                    // gridThickness: 2
                },
                data: [{
                    type: "line",
                    connectNullData: true,
                    dataPoints: [
                        {x: new Date(data[0]), y: obj[data[0]]},
                        {x: new Date(data[1]), y: obj[data[1]]},
                        {x: new Date(data[2]), y: obj[data[2]]},
                        {x: new Date(data[3]), y: obj[data[3]]},
                        {x: new Date(data[4]), y: obj[data[4]]},
                        {x: new Date(data[5]), y: obj[data[5]]},
                        {x: new Date(data[6]), y: obj[data[6]]},
                        {x: new Date(data[7]), y: obj[data[7]]},
                        {x: new Date(data[8]), y: obj[data[8]]},
                        {x: new Date(data[9]), y: obj[data[9]]},
                        {x: new Date(data[10]), y: obj[data[10]]}
                    ]
                }]
            });
            clickChart.render();

            var spentChart = new CanvasJS.Chart("chartSpent", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Amount Spent: $" + (clickCut * data.length).toFixed(2)
                },
                axisY: {
                    // title: "Spent",
                    includeZero: false
                    // interval: 1
                },
                axisX: {
                    // title: "Date"
                    // gridThickness: 2
                },
                data: [{
                    type: "line",
                    connectNullData: true,
                    dataPoints: [
                        {x: new Date(data[0]), y: obj[data[0]] * clickCut},
                        {x: new Date(data[1]), y: obj[data[1]] * clickCut},
                        {x: new Date(data[2]), y: obj[data[2]] * clickCut},
                        {x: new Date(data[3]), y: obj[data[3]] * clickCut},
                        {x: new Date(data[4]), y: obj[data[4]] * clickCut},
                        {x: new Date(data[5]), y: obj[data[5]] * clickCut},
                        {x: new Date(data[6]), y: obj[data[6]] * clickCut},
                        {x: new Date(data[7]), y: obj[data[7]] * clickCut},
                        {x: new Date(data[8]), y: obj[data[8]] * clickCut},
                        {x: new Date(data[9]), y: obj[data[9]] * clickCut},
                        {x: new Date(data[10]), y: obj[data[10]] * clickCut}
                    ]
                }]
            });
            spentChart.render();
        }

        window.onload = function ()
        {
            drawchart(select_data);
        };

        {{--The popup Modal--}}
        // Get the modal
        var modal = document.getElementById("editModal");

        // Get the button that opens the modal
        var btn = document.getElementById("budgetEdit");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        var span1 = document.getElementsByClassName("cancelModal")[0];
        var savebtn = document.getElementsByClassName("saveModal")[0];


        savebtn.onclick = function () {
            document.forms["newBudgetSubmit"].submit();
        };
        // When the user clicks the button, open the modal
        btn.onclick = function () {
            modal.style.display = "block";
        };

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        };
        span1.onclick = function () {
            modal.style.display = "none";
        };

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        {{--The slider javascript--}}
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        var slider = document.getElementById("editBudget");
        var output = document.getElementById("currentBudget");
        var output_daily = document.getElementById("dailyBudget");

        slider.value = urlBudget;

        function updateValue(currentValue) {
            slider.value = currentValue;
            document.getElementById('remainBudget').innerHTML = currentValue - clickCut * clickCount;
            output.innerHTML = numberWithCommas(currentValue);
            output_daily.innerHTML = (currentValue / 30).toFixed(2);
        }

        slider.oninput = function () {
            output.innerHTML = this.value;
        };



    </script>


@endsection

@section('js4event')
    <script>


    </script>
@endsection
