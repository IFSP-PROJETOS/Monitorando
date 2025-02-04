<?php
    use App\Models\User;
    $allNames = null;
    $name = null;
    $usuario = null;
    $usuarios = User::all();
    if(isset(Auth::user()->id)){
        $usuario = Auth::user();
        $allNames =  $usuario->nome;
        $name = explode(' ', $allNames);
        $allNames = $name[count($name)-1];
        $name = $name[0];
    }


        $mobile = FALSE;
        $user_agents = array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric");
        foreach($user_agents as $user_agent){
            if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== FALSE) {
                $mobile = TRUE;
                $modelo = $user_agent;
                break;
            }
        } 
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script>
        function openNav() {
            document.getElementById("menuButton").style.display = 'none';
            document.getElementById("myNav").style.width = "70%";
        }
        function closeNav() {
            document.getElementById("menuButton").style.display = 'block';
            document.getElementById("myNav").style.width = "0%";
        }

        $(function () {
            border = 1;
            $(".profile").click(function () {
                if(border == 1){
                    $(this).css('border-bottom-left-radius', '0');
                    $(this).css('border-bottom-right-radius', '0');
                    $(this).css('border-top-left-radius', '3vh');
                    $(this).css('border-top-right-radius', '3vh');
                    $(this).css('transition', 'border-radius 0s');
                    $('#arrow').css('transform', 'rotate(-90deg)');
                    $('#arrow').css('transition', 'transform .3s linear');
                    border--;
                } else{
                    $('#arrow').css('transform', 'rotate(90deg)');
                    $('#arrow').css('transition', 'transform .3s linear');
                    $(this).css('transition', 'border-radius .7s cubic-bezier(1, 0, 1, 1)');
                    $(this).css('border-radius', '7vh');
                    border++;
                }
                $(this).next().toggleClass("collapsed");
            });
        });

    </script>
    
<head>
    <link rel="icon" href="{{ asset('assets/png/icon.png') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('/css/topbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/index.css') }}">

    <meta name="theme-color" content="#193D6F">

</head>

<body>
    <?php if($mobile){ ?>
        <div id="myNav" class="overlay">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <div class="overlay-content">
                 <?php if(!empty($name)){ ?>
                    <div id="profileContainer">
                        <button class="profile">
                            <img src="{{ asset('/assets/svg/profile.svg') }}" alt="Profile" id="Perfil">                
                                <?php if(!($name !== $allNames)){ ?>
                                    <text>{{ $name }}</text>
                                <?php } else{?>
                                    <text>{{ $name . " " . $allNames }}</text>
                                <?php } ?>
                            <img src="{{ asset('/assets/svg/right-arrow.svg') }}" alt="arrow" id="arrow">
                        </button>
                        <div class="collapsible-wrapper collapsed">
                            <div class="collapsible">
                                <a class="menu-item" href="{{ route('profile', ['id' => $usuario->id]) }}">
                                    Perfil
                                    <img src="{{ asset('/assets/svg/profile.svg') }}" alt="Profile" id="Perfil"> 
                                </a>
                                <form class="menu-item" method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit">
                                        Sair
                                        <img src="{{ asset('/assets/svg/logout.svg') }}" alt="Logout" id="logout">
                                    </button>
                                </form>
                            </div>
                        </div>                  
                    </div>
                    @yield('links')
                    <div id="topFilter">
                        <form id="formSearch" action="{{ route('pesquisar') }}" method="GET">
                            <button id="search" type="submit"><img src="{{ asset('assets/svg/search.svg')}}"></button>
                            <input id="inputSearch" type="text" placeholder="Pesquisa.." name="pesquisa">
                        </form>
                    </div>
                <?php }else{ ?>
                    
                    @yield('links')
                    <div id="buttonRegister">
                        <button class="button_new"><a href="{{ route('cadastro') }}"> @lang('lang.Registre-se') </a></button>
                    </div>
            
            <?php } ?>
                    <div id="topFilter">
                        <form id="formSearch" action="{{ route('pesquisar') }}" method="GET">
                            <button id="search" type="submit"><img src="{{ asset('assets/svg/search.svg')}}"></button>
                            <input id="inputSearch" type="text" placeholder="Pesquisa.." name="pesquisa">
                        </form>
                    </div>
                    
            </div>
        </div>
        <div id="background">
            <span id="menuButton" onclick="openNav()"><img src="{{ asset('/assets/svg/menu.svg') }}" alt="Menu" id="menuSvg"></span>          
        <div>
        {{-- @if(session()->has('search'))
            @if(session('pesquisaUsuarios')->isEmpty() && session('pesquisaMonitorias')->isEmpty())
                <p>Nenhum resultado foi encontrado para o termo "{{session('search')}}"</p>
            @else
                @if(!session('pesquisaUsuarios')->isEmpty())
                    @foreach(session('pesquisaUsuarios') as $resultadoUsuarios)
                        <a id="{{$resultadoUsuarios->id}}" class="modalBtn" href="{{ route('profile', ['id' => $resultadoUsuarios->id]) }}">
                            <p>{{$resultadoUsuarios->nome}}</p>
                            <p>{{$resultadoUsuarios->prontuario}}</p>
                        </a>
                    @endforeach
                @endif
                @if(!session('pesquisaMonitorias')->isEmpty())
                    @foreach(session('pesquisaMonitorias') as $resultadoMonitorias)
                        <div id="content-all">
                            <a id="{{$resultadoMonitorias->id}}" class="modalBtn" href="{{ route('monitorias.informacoes', ['id' => $resultadoMonitorias->id]) }}">
                                <div id="card">
                                    <?php 
                                        $date = new DateTime($resultadoMonitorias->data);
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
                                    <p id="hour">{{$resultadoMonitorias->hora_inicio." - ".$resultadoMonitorias->hora_fim}} </p>
                                    <p>{{ $resultadoMonitorias->conteudo }}</p>
                                    <p class="users"> 
                                        <?php 
                                            $monitoringMonitor = $resultadoMonitorias->monitor;
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
                                        <p id="blank"></p>
                                    <?php }?>
                                    <p>{{ $resultadoMonitorias->local }}</p> 
                                    <p id="limit">
                                        <img src="{{ asset('assets/svg/user-group.svg') }}" id="user">
                                        <text>Participantes {{ $resultadoMonitorias->num_inscritos }}</text>
                                    </p>
                                    <p>{{ $resultadoMonitorias->descricao }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            @endif
        @else
           
        @endif --}}
    @yield('conteudo')
    <?php }else{ ?>   
        <?php if(empty($name)){ ?>
            <div class="topnav noLogin">
                @yield('links')
                <div id="buttonRegister">
                    <button class="button_new"><a href="{{ route('cadastro') }}"> @lang('lang.Registre-se') </a></button>
                </div>
            </div> 
            <?php }else{ ?>              
                <div id="profileContainer">
                    <button class="profile" >
                        <img src="{{ asset('/assets/svg/profile.svg') }}" alt="Profile" id="Perfil"> 
                        <?php if(!($name !== $allNames)){ ?>
                            <text>{{ $name }}</text>
                        <?php } else{?>
                            <text>{{ $name . " " . $allNames }}</text>
                        <?php } ?>
                        
                    </button>
                    <div class="collapsible-wrapper collapsed">
                        <div class="collapsible">
                            <a class="menu-item"  href="{{ route('profile', ['id' => $usuario->id]) }}">
                                Perfil
                                <img src="{{ asset('/assets/svg/profile.svg') }}" alt="Profile" id="Perfil"> 
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">
                                    Sair
                                    <img src="{{ asset('/assets/svg/logout.svg') }}" alt="Logout" id="logout">
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="topnav">
                    
                    <div id="topFilterAll">
                        <form id="formSearchAll" action="{{ route('pesquisar') }}" method="GET">
                            <button id="searchAll" type="submit"><img src="{{ asset('assets/svg/search.svg')}}"></button>
                            <input id="inputSearchAll" type="text" placeholder="Pesquisa.." name="pesquisa">
                        </form>
                    </div>
                    
                    <div id="center">
                        @yield('links')
                    </div>
                    
                </div> 
            <?php }?>
        
        @if(session()->has('search'))
            @if(session('pesquisaUsuarios')->isEmpty() && session('pesquisaMonitorias')->isEmpty())
                <p>Nenhum resultado foi encontrado para o termo "{{session('search')}}"</p>
            @else
                @if(!session('pesquisaUsuarios')->isEmpty())
                   <section>
                        <div id="cardSearch">
                            <h2>Pessoas</h2>
                            @foreach(session('pesquisaUsuarios') as $resultadoUsuarios)
                                <a id="{{$resultadoUsuarios->id}}" class="modalBtn" href="{{ route('profile', ['id' => $resultadoUsuarios->id]) }}">
                                    @if(isset($resultadoUsuarios->foto))
                                        <img src="{{ $resultadoUsuarios->foto }}"/> 
                                    @else
                                        <img id="profile" src="{{ asset('assets/svg/profile.svg')}}"/> 
                                    @endif
                                   <div class="center">
                                        <h3>{{$resultadoUsuarios->nome}}</h3>
                                        <h4 id="emailUser">{{$resultadoUsuarios->email}}</h4>
                                   </div>
                                </a>
                                <hr>
                            @endforeach
                        </div>
                   </section>
                @endif
                @if(!session('pesquisaMonitorias')->isEmpty())
    
                        <section>
                            <?php
                                $cont = 0;
                            ?>
                            @foreach(session('pesquisaMonitorias') as $monitoria)
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
                            @endforeach
                            @if($repetida == false) 
                                @foreach(session('pesquisaMonitorias') as $monitoria)
                                    <div id="content-all">
                                        <div id="content">
                                            <hr>
                                            <div >
                                                <h3 id="titleDiscipline">{{ $monitoria->codigo }}</h3>
                                                <h3 id="nameDiscipline">{{ $monitoria->disciplina }}</h3>   
                                            </div>
                                        </div>
                                        <button><a href="{{ route('monitorias.verTodas', ['codigo' => $monitoria->codigo]) }}">ver todos</a></button>
                                    </div>
                            
                            
    
                                    <div id="scroll">
                                        @foreach(session('pesquisaMonitorias') as $resultadoMonitorias)
                                            <div id="content-all">
                                                <a id="{{$resultadoMonitorias->id}}" class="modalBtn" href="{{ route('monitorias.informacoes', ['id' => $resultadoMonitorias->id]) }}">
                                                    <div id="card">
                                                        <?php 
                                                            $date = new DateTime($resultadoMonitorias->data);
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
                                                        <p id="hour">{{$resultadoMonitorias->hora_inicio." - ".$resultadoMonitorias->hora_fim}} </p>
                                                        <p class="users"> 
                                                            <?php 
                                                                $monitoringMonitor = $resultadoMonitorias->monitor;
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
                                                            <p id="blank"></p>
                                                        <?php }?>
                                                        <p id="limit">
                                                            <img src="{{ asset('assets/svg/user-group.svg') }}" id="user">
                                                            <text>Participantes {{ $resultadoMonitorias->num_inscritos }}</text>
                                                        </p>
                                                        @if(isset($inscrito))
                                                        @foreach($inscrito as $monitoriaInscrita)
                                                            @if($monitoriaInscrita->id == $resultadoMonitorias->id)
                                                                <?php 
                                                                    $usuarioInscrito = true;
                                                                    break; 
                                                                ?>
                                                            @else
                                                                <?php
                                                                    $usuarioInscrito = false;
                                                                ?>
                                                            @endif
                                                        @endforeach
                                                        @if($usuarioInscrito == true)
                                                            <form method="POST" action="{{ route('cancelamentoInscricao') }}">
                                                                @csrf
                                                                <input type="hidden" name="monitoria_id" value="{{ $resultadoMonitorias->id }}" />
                                                                <button id="details" type="submit">
                                                                    <text>Cancelar</text>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form method="POST" action="{{ route('inscricao') }}">
                                                                @csrf
                                                                <input type="hidden" name="monitoria_id" value="{{ $resultadoMonitorias->id }}" />
                                                                <button id="details" type="submit">
                                                                    <text>Inscrever-se</text>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @else
                                                            <form method="POST" action="{{ route('inscricao') }}">
                                                                @csrf
                                                                <input type="hidden" name="monitoria_id" value="{{ $resultadoMonitorias->id }}" />
                                                                <button id="details" type="submit">
                                                                    <text>Inscrever-se</text>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </section>
                    @endif
                @endif
            @else
                @yield('conteudo')
            @endif
        
    <?php } ?>
    <footer>
        <div class="rowFooter">
            <div id="comecoFooter">
                <img id="logoFooter" src="{{ asset('/assets/png/Monitorando2.png') }}">
            </div>
            <div class="textFooter ">
                <h2>Canais</h2>
                <h3 class="logosFooter"><a href="https://www.youtube.com/channel/UC4h1uvG3epGzdxZNYYyVrBQ" target="_Blank"><img id="youtube" src="{{ asset('/assets/svg/youtube.svg') }}">Monitorando</a></h3>
                <h3 class="logosFooter"><a href="https://blogmonitorando.blogspot.com" target="_Blank"><img id="blog" src="{{ asset('/assets/svg/blog.svg') }}">Monitorando</a></h3>
                <h3 class="logosFooter"><img id="email" src="{{ asset('/assets/svg/email.svg') }}">equipe.loading06@gmail.com</h3>

            </div>
            <div class="textFooter">
                <h2>Equipe</h2>
                <h3>Ana Beatriz Silva Nascimento</h3>
                <h3>Fernanda Cesar da Silva</h3>
                <h3>Gustavo Angelozi Frederico</h3>
                <h3>Larissa Yumi Ohashi</h3>
                <h3>Mariana Souza Santos</h3>
                <h3>Wilson de Souza Oliveira Junior</h3>
            </div>
        </div>
        <img src="{{ asset('assets/svg/footer.svg') }}">
    </footer>
</body>