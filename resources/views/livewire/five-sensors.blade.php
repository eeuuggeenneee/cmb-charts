<div>    
    <canvas id="lineChart2" width="100" height="500" ></canvas>
   
    <script>
        document.addEventListener('livewire:load', function () {
            var ctx = document.getElementById('lineChart2').getContext('2d');
            var data = @json($data);
    
            var datasets = [];
            var primaryColors = [
            'rgb(37, 150, 190)',
            'rgba(0, 0, 198, 255)',
            'rgba(247, 0, 49, 255)',
            'rgba(247, 247, 49, 255)'
        ];

            for (var i = 0; i <= 3; i++) {
                datasets.push({
                    label: 'Sensor ' + i,
                    data: data[i], 
                    borderColor: getRandomColor(),
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 0,
                    spanGaps: true,
                });
            }
    
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: datasets,
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: [{
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'M D',
                                },
                            },
                            title: {
                                display: false,
                                text: 'Time',
                            },
                        }],
                        y: [{
                            title: {
                                display: true,
                                text: 'Temperature',
                            },
                        }],
                    },
                },
            });
    
            function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }
        });
    </script>
    
    
</div>
