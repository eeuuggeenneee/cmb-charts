<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <div class="row">
        <div class="col-xl-4 col-sm-12 ">
          
           

            <div class="card mb-2 d-none" >
                <div class="card-header">
                    <strong>Filter</strong>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="">Select Building:</label>
                        <select class="form-control">
                            <option value="" selected>MF Building</option>
                            <option value="" disabled>MCB Building</option>

                        </select>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="machineSelect">Select Machine:</label>
                                <select class="form-control" id="machineSelect" onchange="updateSensorOptions()">
                                    <option value="machine1">200A</option>
                                    <option value="machine2">200B</option>
                                    <option value="machine3">200C</option>
                                    <option value="machine4">200D</option>
                                    <option value="machine5">90+</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sensorSelect">Select Sensor:</label>
                                <select class="form-control" id="sensorSelect" onchange="displaySensorValue()"
                                    wire:model="selectedSensor" wire:change="selectedSensor" wire:ignore>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="border-top: 1px solid black; margin: 10px 0;">
                        <div class="row ">
                            <div class="col-5 ">
                                <label for="startDate" class="text-center mt-3">Filter Date:</label>
                                <div class="form-group">
                                    <input type="date" wire:ignore class="form-control" id="startDate"
                                        wire:model="start_date" wire:change="dateRangeChanged">
                                </div>
                            </div>
                            <div class="col-2 text-center mt-5">
                                <h6>TO</h6>
                            </div>
                            <div class="col-5">
                                <label for="endDate" class="text-center mt-3">Filter Date:</label>
                                <div class="form-group">
                                    <input type="date" wire:ignore class="form-control" id="endDate"
                                        wire:model="end_date" wire:change="dateRangeChanged">
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <p class="text-center">Time: <span id="demo">{{ $slider_value }}</span></p>
                                <div class="slidecontainer">
                                    <input type="range" wire:ignore min="0" max="24" step="0.1"
                                        value="{{ Str::limit($slider_value, 2, '') }}" class="slider form-control"
                                        id="myRange" wire:model="slider_value">
                                </div>
                            </div>
                        </div>
                    </div>


                    <h1></h1>
                </div>
            </div>

        </div> 
   
        <div class="col-xl-12 col-sm-12">
            <a class="btn btn-primary mb-3 " style="width: 10%; left: 0px;" href="{{ route('home') }}">
                <p class="card-title"><i class="fa-solid fa-arrow-left"></i> Go Back<br></p>
            </a>
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                    
                    <span style="text-align: left;">Temperature</span>

                    <div style="display: flex; align-items: center;">
                        <div class="line" style="height: 2px; background-color: rgb(255, 99, 132); margin: 0 10px; width: 50px;">
                        </div>
                        <span style="margin-right: 10px;">Median</span>
                        <div class="line" style="height: 2px; background-color: rgb(0, 100, 0); margin: 0 10px; width: 50px;">
                        </div>
                        <span style="margin-right: 10px;">Forecast</span>
                        <div class="line" style="height: 2px; background-color: rgb(169,169,169,0.5); margin: 0 10px; width: 50px;">
                        </div>
                        <span>95% CI</span>
                    </div>

                </div>





                <div class="card-body">
                    <canvas id="myChart" width="500" height="600" wire:ignore></canvas>
                </div>
            </div>

        </div>
    </div>
    <script>
        const sensorOptions = {
            machine1: [{
                    value: 100,
                    label: "Select a Sensor",
                    disabled: true,
                    selected: true
                },
                {
                    value: 0,
                    label: "200A NDE"
                },
                {
                    value: 1,
                    label: "200A DE"
                },
                {
                    value: 2,
                    label: "200A E1"
                },
                {
                    value: 3,
                    label: "200A E2"
                },
            ],
            machine2: [{
                    value: 100,
                    label: "Select a Sensor",
                    disabled: true,
                    selected: true
                },
                {
                    value: 5,
                    label: "200B NDE"
                },
                {
                    value: 6,
                    label: "200B DE"
                },
                {
                    value: 7,
                    label: "200B E1"
                },
                {
                    value: 8,
                    label: "200B E2"
                },
            ],
            machine3: [{
                    value: 100,
                    label: "Select a Sensor",
                    disabled: true,
                    selected: true
                },
                {
                    value: 10,
                    label: "200C NDE"
                },
                {
                    value: 11,
                    label: "200C DE"
                },
                {
                    value: 12,
                    label: "200C E-DE"
                },
                {
                    value: 13,
                    label: "200C E-NDE"
                },
            ],
            machine4: [{
                    value: 100,
                    label: "Select a Sensor",
                    disabled: true,
                    selected: true
                },
                {
                    value: 15,
                    label: "200D NDE"
                },
                {
                    value: 16,
                    label: "200D DE"
                },
                {
                    value: 17,
                    label: "200D E-DE"
                },
                {
                    value: 18,
                    label: "200D E-NDE"
                },
            ],
            machine5: [{
                    value: 100,
                    label: "Select a Sensor",
                    disabled: true,
                    selected: true
                },
                {
                    value: 20,
                    label: "90+ NDE"
                },
                {
                    value: 21,
                    label: "90+ DE"
                },
                {
                    value: 22,
                    label: "90+ E-DE"
                },
                {
                    value: 23,
                    label: "90+ E-NDE"
                },
            ],
        };


        var selectedSensorValue = document.getElementById("sensorSelect").value;

        if (selectedSensorValue == "") {
            selectedSensorValue = 0;
        }


        function updateSelectedSensorValue() {
            selectedSensorValue = document.getElementById("sensorSelect").value;
            console.log("Selected Sensor: " + selectedSensorValue);
        }
        sensorSelect.addEventListener("change", updateSelectedSensorValue);


        var oldData = @json($olddata);


        function updateSensorOptions() {
            const selectedMachine = document.getElementById("machineSelect").value;
            const sensorSelect = document.getElementById("sensorSelect");

            // Clear existing options
            sensorSelect.innerHTML = "";

            // If a machine is selected, show the sensor options
            if (selectedMachine !== "") {
                const sensorOptionsList = sensorOptions[selectedMachine];
                sensorOptionsList.forEach(sensor => {
                    const option = document.createElement("option");
                    option.value = sensor.value;
                    option.text = sensor.label;
                    if (sensor.disabled) {
                        option.disabled = true;
                    }
                    if (sensor.selected) {
                        option.selected = true;
                    }
                    sensorSelect.add(option);
                });

                sensorSelect.removeAttribute("disabled");
            } else {
                sensorSelect.setAttribute("disabled", "disabled");
                sensorSelect.value = "";
            }
        }


        function displaySensorValue() {
            const selectedSensorValue = document.getElementById("sensorSelect").value;

            if (selectedSensorValue !== "") {
                console.log("Selected Sensor Value: " + selectedSensorValue);
            }
        }
        updateSensorOptions();




        document.addEventListener('livewire:load', function() {
            var slider = document.getElementById("myRange");
            var output = document.getElementById("demo");

            function convertToTime(decimalValue) {
                var hours = Math.floor(decimalValue);
                var minutes = Math.round((decimalValue - hours) * 60);
                minutes = (minutes < 10) ? "0" + minutes : minutes;
                return hours + ":" + minutes + ":00";
            }


            output.innerHTML = convertToTime(slider.value);


            Livewire.on('dateTimeUpdated', function(dateTime) {
                console.log(dateTime);
            });


            slider.addEventListener('input', function() {
                output.innerHTML = convertToTime(this.value);

                Livewire.emit('sliderValueChanged', convertToTime(this.value));
            });




            const canvas = document.getElementById('myChart');

            var data2 = @json($data);
            var data4 = @json($forecast);
            var data5 = @json($min);
            var data6 = @json($max);
            const parseDate = date => new Date(date);

            console.log(data5);

            const chartData = data2.map(item => ({
                x: item.x,
                y: item.y
            }));

            const forecastData = data4.map(forecast => ({
                x: forecast.x,
                y: forecast.y
            }));

            const min = data5.map(minn => ({
                x: minn.x,
                y: minn.y
            }));


            const max = data6.map(maxx => ({
                x: maxx.x,
                y: maxx.y
            }));




            const data = {
                labels: chartData.map(item => item.x),
                datasets: [{
                        label: 'Raw Data',
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: 'rgb(255, 99, 132)',
                        borderWidth: 2,
                        data: chartData.map(item => item.y),
                        pointRadius: 1,
                    }, {
                        label: 'Forecast',
                        backgroundColor: 'rgb(0, 100, 0)',
                        borderColor: 'rgb(0, 100, 0)',
                        borderWidth: 2,
                        data: data4,
                        pointRadius: 1,
                    }, {
                        label: 'Min',
                        backgroundColor: 'rgb(169,169,169)',
                        borderColor: 'rgb(169,169,169)',
                        borderWidth: 2,
                        data: data5,
                        pointRadius: 0,

                        fill: false,
                    },
                    {
                        label: 'Max',
                        backgroundColor: 'rgb(169,169,169,0.5)',
                        borderColor: 'rgb(169,169,169,0.5)',
                        borderWidth: 2,
                        data: data6,
                        pointRadius: 0,
                        fill: '-1',
                    }


                ]
            };

            const config = {
                type: 'line',
                data: data,
                options: {
                    plugins: {
                        legend: {
                            display: false,
                            labels: {
                                color: 'rgb(255, 99, 132)'
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'D',
                                },
                            },
                            ticks: {
                                source: 'auto',
                                autoSkip: false,
                            },
                            title: {
                                display: true,
                                text: 'Time',
                            },
                        }],
                        yAxes: [{
                            id: 'y-axis-0',
                            title: {
                                display: true,
                                text: 'X Velocity',
                            },
                            ticks: {},
                        }],
                    },
                },
            };



            var myChart = new Chart(canvas, config);
            var initialDataFromBackend = @json($olddata);
        });
    </script>




</div>
