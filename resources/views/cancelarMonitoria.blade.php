@extends('topbar.topbar')

@section('conteudo')

    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="utf-8" />
        <title> Monitorando </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/index.css') }}">
        <link rel="icon" href="{{ asset('assets/png/icon.png') }}">

        <!-- mediaquery -->
    </head>

    <body>
        @section('links')
            <a href="{{ route('index') }}"> HOME </a>
            <a class="active" href="{{ route('monitorias') }}"> @lang('lang.Monitorias') </a>
            <a href="{{ route('calendario') }}"> @lang('lang.Calendario') </a>
            <a href="{{ route('quem.somos') }}"> @lang('lang.QuemSomos') </a>   
        @endsection 
        <script>
            var i = 0;
            $(document).ready(function(){
            
                /*$('#noButton').on('click', function() {
                        $(".modal-content").css('display', 'none');
                    });
                });*/
            });
            
        </script>
        <img src="{{ asset('assets/svg/banner.svg') }}" alt="banner_monitorando" id="banner">

        <?php
            $cont = 0;
        ?>
        @foreach($monitorias as $monitoria)
            <?php
                $repetida = false;
            ?>
            @if($cont === 0)
                <?php 
                    $monitoriaCodigo[$cont] = $monitoria->codigo;
                    $cont++;
                    $repetida = false;
                ?>
            @else
                @foreach($monitoriaCodigo as $monitoriaRepetida)
                    @if($monitoriaRepetida == $monitoria->codigo)
                        <?php
                            $repetida = true;
                            break;
                        ?>
                    @else
                        <?php
                            $monitoriaCodigo[$cont] = $monitoria->codigo;
                            $cont++;
                            $repetida = false;
                        ?>
                    @endif
                @endforeach
            @endif
            @if($repetida == false)
                <section>
                    <div id="content-all">
                        <div id="content">
                            <hr>
                            <div id="discipline">
                                <h3 id="titleDiscipline">{{ $monitoria->codigo }}</h3>
                                <h3 id="nameDiscipline">{{ $monitoria->disciplina }}</h3>   
                            </div>
                        </div>
                    </div>
                    <div id="scroll">
                        @foreach ($monitorias->where('codigo', $monitoria->codigo) as $monitoriaCard)
                            
                            <div class="modal-content" id="modal-{{$monitoriaCard->id}}">
                                    <p>Todos os dados relacionados a essa monitoria serão excluídos do sistema. Tem certeza que deseja mesmo cancelá-la?</p>
                                    <div class="row-right">
                                        <button type="button" class="exit-2" id="noButton-{{$monitoriaCard->id}}">Não</button>
                                        <form method="POST" action="{{ route('monitorias.cancelar') }}">
                                            @csrf
                                            <input type="hidden" name="monitoria_id" value="{{ $monitoriaCard->id }}" />
                                            <button class="exit-2" id="cancelarMonitoria"type="submit"> Sim </button>
                                        </form>
                                    </div>
                                </div>
                            <div id="card">
                                <a href="{{ route('monitorias.informacoes', ['id' => $monitoriaCard->id]) }}">
                                    <!-- <p>{{ $monitoria->conteudo }}</p> -->
                                    <?php 
                                        $monitoringTime = $monitoriaCard->data;
                                        $monitoringT = explode('T',$monitoringTime);
                                        $monitoringTime = $monitoringT[count($monitoringT)-1];
                                        $monitoringT = $monitoringT[0];
                                                
                                        $date = new DateTime($monitoringT);
                                        $n = $date->getTimestamp();
                                        $data = date('D', $n);
                                        $semana = array(
                                            'Sun' => 'Domingo',
                                            'Mon' => 'Segunda-Feira',
                                            'Tue' => 'Terça-Feira',
                                            'Wed' => 'Quarta-Feira',
                                            'Thu' => 'Quinta-Feira',
                                            'Fri' => 'Sexta-Feira',
                                            'Sat' => 'Sábado'
                                        );
    
                                    ?>
                                    <p id="date">{{ date("d/m", $n) . " • " . $semana["$data"]}}</p>
                                    <p id="hour">{{$monitoriaCard->hora_inicio." - ".$monitoriaCard->hora_fim}} </p>
                                    <p class="users"> 
                                        <?php 
                                            $monitoringMonitor = $monitoriaCard->monitor;
                                            $monitoringM = explode(' e ', $monitoringMonitor);
                                            $monitoringMonitor = $monitoringM[count($monitoringM)-1];
                                            $monitoringM = $monitoringM[0];
                                            $monitor1 = $usuarios->where('prontuario', $monitoringMonitor)->first();
                                            $monitor2 = $usuarios->where('prontuario', $monitoringM)->first();
                                        ?>
                                        <img src="{{ asset('assets/svg/user.svg') }}" id="user">
                                        @if(isset($monitor1))
                                            <text>{{ $monitor1->nome }}</text>    
                                        @else
                                            <text>{{ $monitoringMonitor }}</text>    
                                        @endif
                                    </p>
                                    <?php if(!($monitoringM === $monitoringMonitor)){ ?>
                                        <p class="users">
                                        <img src="{{ asset('assets/svg/user.svg') }}" id="user">
                                        @if(isset($monitor2))
                                            <text>{{ $monitor2->nome }}</text>
                                        @else
                                            <text>{{ $monitoringM }}</text>    
                                        @endif
                                    </p>
                                    <?php } else{?>   
                                        <p class="users"></p>
                                    <?php }?>
                                    <!-- <p>{{ $monitoria->local }}</p> -->
                                    <p id="limit">
                                        <img src="{{ asset('assets/svg/user-group.svg') }}" id="user">
                                        <text>Participantes {{ $monitoriaCard->num_inscritos }}</text>
                                    </p>
                                </a>
                                <button class="buttonCancelarMonitoria" type="button" id="modalBtn-{{$monitoriaCard->id}}">Cancelar a monitoria</button>
                                
                            </div>
                            <script>
                                var modal{{$monitoriaCard->id}} = document.getElementById('modal-{{$monitoriaCard->id}}');
                                var modalBtn{{$monitoriaCard->id}} = document.getElementById('modalBtn-{{$monitoriaCard->id}}');
                                var closeBtn = document.getElementById("noButton-{{$monitoriaCard->id}}");
                                
                                modalBtn{{$monitoriaCard->id}}.addEventListener('click', function() {
                                    modal{{$monitoriaCard->id}}.style.display = "block";
                                });
    
                                closeBtn.addEventListener('click', function() {
                                    modal{{$monitoriaCard->id}}.style.display = "none";
                                });

                                $(window).on('click',function(e){
                                    if(!(($(e.target).closest(modal{{$monitoriaCard->id}}).length > 0 ) || ($(e.target).closest(modalBtn{{$monitoriaCard->id}}).length > 0))){
                                        $("#modal-{{$monitoriaCard->id}}").css('display', 'none');
                                    }
                                });
    
                                /*$(document).ready(function() {
                                    $('#modalBtn-{{$monitoriaCard->id}}').on('click', function() {
                                        $("#modal-{{$monitoriaCard->id}}").attr('style', 'display:block;');
                                    });
                        
                                    $('.exit').on('click', function() {
                                        $("#modal-{{$monitoriaCard->id}}").css('display', 'none');
                                    });
                        
                                    $(document).on('click',function(e){
                                        if(!(($(e.target).closest("#modal").length > 0 ) || ($(e.target).closest("#modalBtn").length > 0))){
                                            $("#modal-{{$monitoriaCard->id}}").css('display', 'none');
                                        }
                                    });
                                });*/
                            </script>
                                <!-- <p>{{ $monitoria->descricao }}</p> -->
                            
                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach

    </body>

    </html>
@endsection