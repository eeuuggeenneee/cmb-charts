<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

</head>
@livewireStyles

<body class="antialiased">
   

    <nav class="navbar navbar-expand-lg navbar-light shadow" style="background-color: white">
        <img src="{{ asset('storage/pbi.jpg') }}" width="125px" height="40px">
        <a class="navbar-brand" href="#">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
              
           
            </ul>
        </div>
    </nav>

    <div class="container px-5 py-5">
        {{-- <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            Compressor 200B
                        </div>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                    <div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-4">200A NDE</div>
                                    <div class="col-4"><i class="fa-solid fa-temperature-three-quarters"></i> 23</div>
                                    <div class="col-4">200A NDE</div>
                                </div>
                     
                            </li>
                            <li class="list-group-item">200A DE</li>
                            <li class="list-group-item">200A E1</li>
                            <li class="list-group-item">200A E2</li>
                          </ul>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            Compressor 200B
                        </div>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                    <div>
                       
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            Compressor 200C
                        </div>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                    <div>
                      
                    </div>
                </div>
            </div>




        </div> --}}

        <div class="card">
            <div class="card-header">
              Compressor
            </div>
            <div class="card-body">
                {{-- @livewire('real-time-chart') --}}
                @livewire('x-acc')
            </div>
          </div>

    </div>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/1.4.0/chartjs-plugin-annotation.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    @livewireScripts
</body>




</html>
