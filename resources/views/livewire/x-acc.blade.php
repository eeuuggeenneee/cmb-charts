<div>
    <div class="row">
        <div class="col-xl-4 col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center">
                    <h4 class="mb-0 me-3">Latest Data</h4>
                    <span class="display-4 ms-auto" style="font-size: 1rem;"><strong><span
                                id="xacctime"></span></strong></span>
                </div>


                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center">

                        <i class="fas fa-shuttle-space fa-3x mr-3 blink-green" id="blinkingIcon"></i>

                        <span class="display-4"><strong><span id="latestxacc"></span>g</strong></span>
                    </li>
                </ul>
            </div>

            <div class="card mb-2">
                <div class="card-header">
                    <strong>Filter</strong>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="">Select Building:</label>
                        <select class="form-control">
                            <option value="" selected>MF Building</option>
                            <option value="" disabled>LF Building</option>

                        </select>
                    </div>


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

                    <div class="form-group">
                        <label for="sensorSelect">Select Sensor:</label>
                        <select class="form-control" id="sensorSelect" onchange="displaySensorValue()"
                            wire:model="selectedSensor" wire:change="selectedSensor" wire:ignore>
                        </select>
                    </div>
                    <div style="border-top: 1px solid black; margin: 10px 0;">
                        <label for="sensorSelect" class="text-center mt-3">Filter Date:</label>
                        <div class="form-group">
                            <input type="date" wire:ignore class="form-control" id="startDate"
                                wire:model="start_date" wire:change="dateRangeChanged">
                        </div>
                        <h6 class="text-center">TO</h6>
                        <div class="form-group">
                            <input type="date" wire:ignore class="form-control" id="endDate" wire:model="end_date"
                                wire:change="dateRangeChanged">
                        </div>

                        <p class="text-center mt-3">Time: <span id="demo">{{ $slider_value }}</span></p>
                        <div class="slidecontainer">
                            <input type="range" wire:ignore min="0" max="24" step="0.1"
                                value="{{ Str::limit($slider_value, 2, '') }}" class="slider form-control"
                                id="myRange" wire:model="slider_value">
                        </div>


                    </div>
                    <h1></h1>
                </div>
            </div>

        </div>
        <div class="col-xl-8 col-sm-12">
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">

                    <span style="text-align: left;">X Acceleration</span>

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
                    label: 'Sensor',
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
                                    yMin: <?php echo $xAbase; ?>,
                                    yMax: <?php echo $xAbase; ?>,
                                    borderWidth: 2,
                                    borderColor: 'grey'
                                },
                                line2: {
                                    type: 'line',
                                    yMin: <?php echo $xAwarn; ?>,
                                    yMax: <?php echo $xAwarn; ?>,
                                    borderWidth: 2,
                                    borderColor: 'blue'
                                },
                                line3: {
                                    type: 'line',
                                    yMin: <?php echo $xAalarm; ?>,
                                    yMax: <?php echo $xAalarm; ?>,
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
            Livewire.on('sensorDataUpdated', function(data,xAbase, xAalarm, xAwarn, xAccTime, latestXacc, olddata) {


                var currentDate = new Date();
                var formattedDate = currentDate.toLocaleString('en-US', {
                    month: 'short',
                    day: '2-digit',
                    year: '2-digit',

                });
                formattedDate = formattedDate.replace(',', '');
                const fromlivewire = {
                    x: xAccTime,
                    y: latestXacc,
                };

                if (formattedDate == xAccTime) {
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
                        label: 'Sensor',
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
                                        yMin: xAbase, //here
                                        yMax: xAbase, //here
                                        borderWidth: 2,
                                        borderColor: 'grey'
                                    },
                                    line2: {
                                        type: 'line',
                                        yMin: xAwarn, //here
                                        yMax: xAwarn, //here
                                        borderWidth: 2,
                                        borderColor: 'blue'
                                    },
                                    line3: {
                                        type: 'line',
                                        yMin: xAalarm, //here
                                        yMax: xAalarm, //here
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
                fetch('http://127.0.0.1:8000/api/sensor-data/x-acc/' + selectedSensorValue)
                    .then(response => response.json())
                    .then(data => {
                        const reconstructedData = {
                            x: data[0].x,
                            y: parseFloat(data[0].y),
                        };
                        if (arraysEqual(initialDataFromBackend, reconstructedData)) {
                            xacctime.innerHTML = data[0].x;
                            latestxacc.innerHTML = data[0].y;
                        } else {
                            xacctime.innerHTML = data[0].x;
                            latestxacc.innerHTML = data[0].y;
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


                if (parseFloat(latestxacc.innerHTML) > parseFloat(<?php echo $xAalarm; ?>)) {
                    blinkingIcon.style.color = isHidden ? 'transparent' : 'red';
                } else if (parseFloat(latestxacc.innerHTML) > parseFloat(<?php echo $xAwarn; ?>)) {
                    blinkingIcon.style.color = isHidden ? 'transparent' : 'blue';
                } else {
                    blinkingIcon.style.color = isHidden ? 'transparent' : 'green';
                }

            }, 1000);

        });
    </script>




</div>
