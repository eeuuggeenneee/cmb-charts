<div>
    <div class="row">
        <div class="col-xl-4 col-sm-12">
            <div class="container">
                <div class="row">
                    <a class="btn btn-success mb-3" style="width: 15%; margin-right: 1%" href="{{ route('zvel') }}">
                        <p class="card-title">Z-Axis<br>Velocity</p>
                    </a>
                    <a class="btn btn-success mb-3 mr-2" style="width: 22%; margin-right: 1%" href="{{ route('zacc') }}">
                        <p class="card-title">Z-Axis<br>Acceleration</p>
                    </a>
                    <a class="btn btn-success mb-3 mr-2" style="width: 15%; margin-right: 1%" href="{{ route('xvel') }}">
                        <p class="card-title">X-Axis<br>Velocity</p>
                    </a>
                    <a class="btn btn-success mb-3 mr-2" style="width: 22%; margin-right: 1%" href="{{ route('xacc') }}">
                        <p class="card-title">X-Axis<br>Acceleration</p>
                    </a>
                    <a class="btn btn-success mb-3" style="width: 22%;" href="{{ route('home') }}">
                        <p>Temperature</p>
                    </a>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center">
                    <h4 class="mb-0 me-3">Latest Data</h4>
                    <span class="display-4 ms-auto" style="font-size: 1rem;"><strong><span
                                id="zveltime"></span></strong></span>
                </div>


                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center">

                        <i class="fas fa-shuttle-space fa-3x mr-3 blink-green" id="blinkingIcon"></i>

                        <span class="display-4"><strong><span id="latestzvel"></span>mm/s</strong></span>
                    </li>
                </ul>
            </div>

            <   <div class="card mb-2">
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
        <div class="col-xl-8 col-sm-12">
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">

                    <span style="text-align: left;">Z Velocity</span>

                    <div style="display: flex; align-items: center;">
                        <div class="line" style="height: 2px; background-color: gray; margin: 0 10px; width: 50px;">
                        </div>
                        <span style="margin-right: 10px;">Base</span>
                        <div class="line" style="height: 2px; background-color: blue; margin: 0 10px; width: 50px;">
                        </div>
                        <span style="margin-right: 10px;">Warning</span>
                        <div class="line" style="height: 2px; background-color: red; margin: 0 10px; width: 50px;">
                        </div>
                        <span>Alarm</span>
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

            var data2 = @json($data);


            const canvas = document.getElementById('myChart');
            const chartData = data2.map(item => ({
                x: item.x,
                y: item.y
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
                }]
            };
            const config = {
                type: 'line',
                data: data,
                options: {
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
                            title: {
                                display: false,
                                text: 'Time',
                            },
                        }],
                        yAxes: [{
                            id: 'y-axis-0',
                            title: {
                                display: true,
                                text: 'X Velocity',
                            },
                            ticks: {

                            },
                        }],
                        legend: {
                            display: false,
                        },
                    },
                    plugins: {
                        annotation: {
                            annotations: {
                                line1: {
                                    type: 'line',
                                    yMin: <?php echo $zVbase; ?>,
                                    yMax: <?php echo $zVbase; ?>,
                                    borderWidth: 2,
                                    borderColor: 'grey'
                                },
                                line2: {
                                    type: 'line',
                                    yMin: <?php echo $zVwarn; ?>,
                                    yMax: <?php echo $zVwarn; ?>,
                                    borderWidth: 2,
                                    borderColor: 'blue'
                                },
                                line3: {
                                    type: 'line',
                                    yMin: <?php echo $zValarm; ?>,
                                    yMax: <?php echo $zValarm; ?>,
                                    borderWidth: 2,
                                    borderColor: 'red'
                                },
                            }
                        },
                    },


                },
            };

            var myChart = new Chart(canvas, config);
            var initialDataFromBackend = @json($olddata);
            Livewire.on('sensorDataUpdated', function(data,zValarm, zVwarn, zVbase, latestZvel, zVelTime, olddata) {


                var currentDate = new Date();
                var formattedDate = currentDate.toLocaleString('en-US', {
                    month: 'short',
                    day: '2-digit',
                    year: '2-digit',

                });
                formattedDate = formattedDate.replace(',', '');
                const fromlivewire = {
                    x: zVelTime,
                    y: latestZvel,
                };

                if (formattedDate == zVelTime) {
                    initialDataFromBackend = fromlivewire;
                } else {

                }

                myChart.destroy();

                const updatedChartData = data.map(item => ({
                    x: item.x,
                    y: item.y
                }));



                const updatedData = {
                    labels: updatedChartData.map(item => item.x),
                    datasets: [{
                        label: 'Raw Data',
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: updatedChartData.map(item => item.y),
                        pointRadius: 1,
                    }]
                };
                myChart = new Chart(canvas, {
                    type: 'line',
                    data: updatedData,
                    options: {
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
                                ticks: {

                                },
                            }],
                        },
                        plugins: {
                            annotation: {
                                annotations: {
                                    line1: {
                                        type: 'line',
                                        yMin: zVbase, //here
                                        yMax: zVbase, //here
                                        borderWidth: 2,
                                        borderColor: 'grey'
                                    },
                                    line2: {
                                        type: 'line',
                                        yMin: zVwarn, //here
                                        yMax: zVwarn, //here
                                        borderWidth: 2,
                                        borderColor: 'blue'
                                    },
                                    line3: {
                                        type: 'line',
                                        yMin: zValarm, //here
                                        yMax: zValarm, //here
                                        borderWidth: 2,
                                        borderColor: 'red'
                                    },
                                }
                            }
                        },
                    },

                });
            });

            function addData(chart, newData) {
                chart.data.labels.push(newData.x);
                chart.data.datasets[0].data.push(newData.y);
                chart.update();
            }

            let isFirstLoad = true;


            function arraysEqual(arr1, arr2) {
                return JSON.stringify(arr1) === JSON.stringify(arr2);
            }

            function fetchDataAndAddToChart() {
                console.log("Selected Sensor " + selectedSensorValue);
                var zveltime = document.getElementById("zveltime");
                var latestzvel = document.getElementById("latestzvel");
                fetch('http://127.0.0.1:8000/api/sensor-data/z-vel/' + selectedSensorValue)
                    .then(response => response.json())
                    .then(data => {
                        const reconstructedData = {
                            x: data[0].x,
                            y: parseFloat(data[0].y),
                        };
                        if (arraysEqual(initialDataFromBackend, reconstructedData)) {
                            zveltime.innerHTML = data[0].x;
                            latestzvel.innerHTML = data[0].y;
                        } else {
                            zveltime.innerHTML = data[0].x;
                            latestzvel.innerHTML = data[0].y;
                            console.log("New data", reconstructedData);
                            initialDataFromBackend = reconstructedData;
                            addData(myChart, reconstructedData);
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }
            fetchDataAndAddToChart();
            setInterval(fetchDataAndAddToChart, 1000);

            var blinkingIcon = document.getElementById('blinkingIcon');

            var isHidden = false;
            setInterval(function() {
                isHidden = !isHidden;


                if (parseFloat(latestzvel.innerHTML) > parseFloat(<?php echo $zValarm; ?>)) {
                    blinkingIcon.style.color = isHidden ? 'transparent' : 'red';
                } else if (parseFloat(latestzvel.innerHTML) > parseFloat(<?php echo $zVwarn; ?>)) {
                    blinkingIcon.style.color = isHidden ? 'transparent' : 'blue';
                } else {
                    blinkingIcon.style.color = isHidden ? 'transparent' : 'green';
                }

            }, 1000);

        });
    </script>




</div>
