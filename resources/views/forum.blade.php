@extends('topbar.topbar')

@section('conteudo')

    <!DOCTYPE html>
    <html lang="pt-br">

        <head>
            <meta charset="utf-8" />
            <title> Monitorando - {{ $topico->topico }} </title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="{{ asset('/css/forum.css') }}">
            <link rel="icon" href="{{ asset('assets/png/icon.png') }}">
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
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
                $(document).ready(function() {
                    $(document).on('click', '#adicionarResposta', function(e) {
                        e.preventDefault(); 
                        $("#novaResposta").append('<form id="formResposta" method="POST" class="column" action="{{ route('monitorias.forum', ['id' => $monitoria_id, 'topico' => $topico->id]) }}" enctype="multipart/form-data">' +
                                                        '@csrf' +
                                                        '<label for="resposta">Resposta</label>' +
                                                        '<textarea type="text" name="resposta">{{ old('resposta') }}</textarea>' + 
                                                        '<div class="row"><label id="labelAvatar" for="avatarFile"><h5>Enviar foto</h5></label><input type="file" class="form-control-file" name="imagem" id="avatarFile" aria-describedby="fileHelp" buttonText="Your label here.">' +
                                                        '<button class="button trash cancel marginButton" type="button" id="fecharResposta"><img src="{{ asset("assets/svg/plus.svg") }}" alt="Plus"></button>' +
                                                        '<button class="button" type="submit"><img src="{{ asset("assets/svg/save.svg") }}" alt="Save"></button></div>' +
                                                    '</form>');
                        $("#adicionarResposta").remove();
                    });
                    $(document).on('click', '#fecharResposta', function(e) {
                        e.preventDefault();
                        $("#formResposta").remove();
                        $("#novaResposta").append('<button id="adicionarResposta" type="button" class="button">Responder</button>');
                    });
                });
            </script>

            <section>
                <div class="row principal">
                    <div id="mensagens">
                        {{ session()->has('editado') ? session('editado') : '' }}
                        {{ $errors->has('mensagem') ? $errors->first('mensagem') : '' }}
                        {{ $errors->has('resposta') ? $errors->first('resposta') : '' }}
                        {{ $errors->has('imagem') ? $errors->first('imagem') : '' }}
                        <div>
                            @foreach($mensagens as $mensagem)
                                <div id="mensagem{{$mensagem->id}}">
                                    <?php
                                        $exibir = explode(':', $mensagem->mensagem);
                                    ?>
                                    @if(isset($exibir) && $exibir[0] == "primeira mensagem")
                                        @foreach($usuarios as $usuario)
                                            @if($usuario->id == $topico->user_id)
                                                <h3><b>{{ $usuario->nome }}</b><br>{{ $topico->topico }}</h3>

                                            @endif
                                        @endforeach
                                        <?php
                                            $mensagemCriadora = $mensagem;
                                        ?>

                                        <div id="esquerda">
                                            <h4>{{ $exibir[1] }}</h4>

                                            <?php
                                                $tipoArquivo = null;
                                                if(isset($mensagem->imagem)){
                                                    $tipoArquivo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $mensagem->imagem);
                                                }
                                            ?>
                                            @if(isset($tipoArquivo))
                                                @if($tipoArquivo == "application/pdf")
                                                    <iframe src="{{ $mensagem->imagem }}" height="200" width="300"></iframe>
                                                @else
                                                    <img height="200" width="300" src="{{ $mensagem->imagem }}" />
                                                @endif
                                            @endif
                                            <div class="row">
                                                @if($mensagem->user_id == Auth::user()->id)
                                                    <button type="button" id="editarResposta{{$mensagem->id}}" class="button"><img src="{{ asset("assets/svg/edit.svg") }}" alt="Edit"></button>
                                                    <a id="excluirResposta{{$mensagem->id}}" class="button trash" href="{{ route('monitorias.excluir.mensagem', ['id' => $mensagem->id]) }}"><img src="{{ asset("assets/svg/trash.svg") }}" alt="Trash"></a>
                                                    <script>
                                                        $(document).ready(function() {
                                                            $(document).on('click', "#editarResposta{{$mensagem->id}}", function(e) {
                                                                e.preventDefault(); 
                                                                $("#mensagem{{$mensagem->id}}").append('<form method="POST" id="editarMensagem" class="column" action="{{ route('monitorias.editar.mensagem', ['id' => $mensagem->id]) }}" enctype="multipart/form-data">' +
                                                                                        '@csrf' +
                                                                                        '<div id="forumMensagem">' + 
                                                                                            '<label >Editar texto</label>' +
                                                                                            '<textarea name="mensagem" form="editarMensagem">{{ $mensagem->mensagem ?? old('mensagem') }}</textarea>' + 
                                                                                            '<div class="row"><label id="labelAvatar" for="avatarFile"><h5>Enviar foto</h5></label><input type="file" class="form-control-file" name="imagem" id="avatarFile" aria-describedby="fileHelp" buttonText="Your label here.">' +
                                                                                            '<button id="marginButton" class="button trash" type="submit" name="apagarAnexo"><img src="{{ asset("assets/svg/trash.svg") }}" alt="Trash"></button>' +
                                                                                            '<button class="button trash cancel" type="button" id="fecharEdicao{{$mensagem->id}}"><img src="{{ asset("assets/svg/plus.svg") }}" alt="Plus"></button>' +
                                                                                            '<button class="button" type="submit"><img src="{{ asset("assets/svg/save.svg") }}" alt="Save"></button></div>' +
                                                                                        
                                                                                        '</div>' +
                                                                                    '</form>');
                                                                $("#editarResposta{{$mensagem->id}}").remove();
                                                                $("#excluirResposta{{$mensagem->id}}").remove();
                                                            });
                                                            $(document).on('click', '#fecharEdicao{{$mensagem->id}}', function(e) {
                                                                e.preventDefault();
                                                                $("#editarMensagem").remove();
                                                                $("#mensagem{{$mensagem->id}}").append('<div class="row"><button type="button" id="editarResposta{{$mensagem->id}}" class="button"><img src="{{ asset("assets/svg/edit.svg") }}" alt="Edit"></button>' +
                                                                    '<a id="excluirResposta{{$mensagem->id}}" class="button trash" href="{{ route('monitorias.excluir.mensagem', ['id' => $mensagem->id]) }}"><img src="{{ asset("assets/svg/trash.svg") }}" alt="Trash"></a>'
                                                                );
                                                            });
                                                        });
                                                    </script>
                                                @endif
                                            </div>
                                        </div>

                                    @else
                                        @if($mensagem->user_id == $topico->user_id)
                                            <div id="esquerda" >
                                                @foreach($usuarios as $usuario)
                                                    @if($usuario->id == $mensagem->user_id)
                                                        <h4 class="border"><b>{{ $usuario->nome }}</b><br>{{ $mensagem->mensagem }}</h4>
                                                    @endif
                                                @endforeach
                                                <?php
                                                    $tipoArquivo = null;
                                                    if(isset($mensagem->imagem)){
                                                        $tipoArquivo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $mensagem->imagem);
                                                    }
                                                ?>
                                                @if(isset($tipoArquivo))
                                                    @if($tipoArquivo == "application/pdf")
                                                        <iframe src="{{ $mensagem->imagem }}" height="200" width="300"></iframe>
                                                    @else
                                                        <img height="200" width="300" src="{{ $mensagem->imagem }}" />
                                                    @endif
                                                @endif
                                                <div class="row">
                                                    @if($mensagem->user_id == Auth::user()->id)
                                                        <button type="button" id="editarResposta{{$mensagem->id}}" class="button"><img src="{{ asset("assets/svg/edit.svg") }}" alt="Edit"></button>
                                                        <a id="excluirResposta{{$mensagem->id}}" class="button trash" href="{{ route('monitorias.excluir.mensagem', ['id' => $mensagem->id]) }}"><img src="{{ asset("assets/svg/trash.svg") }}" alt="Trash"></a>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $(document).on('click', "#editarResposta{{$mensagem->id}}", function(e) {
                                                                    e.preventDefault(); 
                                                                    $("#mensagem{{$mensagem->id}}").append('<form method="POST" id="editarMensagem" class="column" action="{{ route('monitorias.editar.mensagem', ['id' => $mensagem->id]) }}" enctype="multipart/form-data">' +
                                                                                            '@csrf' +
                                                                                            '<div id="forumMensagem">' + 
                                                                                                '<label >Editar texto</label>' +
                                                                                                '<textarea name="mensagem" form="editarMensagem">{{ $mensagem->mensagem ?? old('mensagem') }}</textarea>' + 
                                                                                                '<div class="row"><label id="labelAvatar" for="avatarFile"><h5>Enviar foto</h5></label><input type="file" class="form-control-file" name="imagem" id="avatarFile" aria-describedby="fileHelp" buttonText="Your label here.">' +
                                                                                                '<button class="button trash marginButton" type="submit" name="apagarAnexo"><img src="{{ asset("assets/svg/trash.svg") }}" alt="Trash"></button>' +
                                                                                                '<button class="button trash cancel" type="button" id="fecharEdicao{{$mensagem->id}}"><img src="{{ asset("assets/svg/plus.svg") }}" alt="Plus"></button>' +
                                                                                                '<button class="button" type="submit"><img src="{{ asset("assets/svg/save.svg") }}" alt="Save"></button></div>' +
                                                                                            
                                                                                            '</div>' +
                                                                                        '</form>');
                                                                    $("#editarResposta{{$mensagem->id}}").remove();
                                                                    $("#excluirResposta{{$mensagem->id}}").remove();
                                                                });
                                                                $(document).on('click', '#fecharEdicao{{$mensagem->id}}', function(e) {
                                                                    e.preventDefault();
                                                                    $("#editarMensagem").remove();
                                                                    $("#mensagem{{$mensagem->id}}").append('<div class="row"><button type="button" id="editarResposta{{$mensagem->id}}" class="button"><img src="{{ asset("assets/svg/edit.svg") }}" alt="Edit"></button>' +
                                                                        '<a id="excluirResposta{{$mensagem->id}}" class="button trash" href="{{ route('monitorias.excluir.mensagem', ['id' => $mensagem->id]) }}"><img src="{{ asset("assets/svg/trash.svg") }}" alt="Trash"></a>'
                                                                    );
                                                                });
                                                            });
                                                        </script>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div id="direita">
                                                @foreach($usuarios as $usuario)
                                                    @if($usuario->id == $mensagem->user_id)
                                                        <h4><b>{{ $usuario->nome }}</b><br>{{ $mensagem->mensagem }}</h4>

                                                    @endif
                                                @endforeach
                                                <?php
                                                    $tipoArquivo = null;
                                                    if(isset($mensagem->imagem)){
                                                        $tipoArquivo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $mensagem->imagem);
                                                    }
                                                ?>
                                                @if(isset($tipoArquivo))
                                                    @if($tipoArquivo == "application/pdf")
                                                        <iframe src="{{ $mensagem->imagem }}" height="200" width="300"></iframe>
                                                    @else
                                                        <img height="200" width="300" src="{{ $mensagem->imagem }}" />
                                                    @endif
                                                @endif
                                                <div class="row direita">
                                                    @if($mensagem->user_id == Auth::user()->id)
                                                        <button type="button" id="editarResposta{{$mensagem->id}}" class="button"><img src="{{ asset("assets/svg/edit.svg") }}" alt="Edit"></button>
                                                        <a id="excluirResposta{{$mensagem->id}}" class="button trash" href="{{ route('monitorias.excluir.mensagem', ['id' => $mensagem->id]) }}"><img src="{{ asset("assets/svg/trash.svg") }}" alt="Trash"></a>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $(document).on('click', "#editarResposta{{$mensagem->id}}", function(e) {
                                                                    e.preventDefault(); 
                                                                    $("#mensagem{{$mensagem->id}}").append('<form method="POST" id="editarMensagem" class="column" action="{{ route('monitorias.editar.mensagem', ['id' => $mensagem->id]) }}" enctype="multipart/form-data">' +
                                                                                            '@csrf' +
                                                                                            '<div id="forumMensagem">' + 
                                                                                                '<label >Editar texto</label>' +
                                                                                                '<textarea name="mensagem" form="editarMensagem">{{ $mensagem->mensagem ?? old('mensagem') }}</textarea>' + 
                                                                                                '<div class="row"><label id="labelAvatar" for="avatarFile"><h5>Enviar foto</h5></label><input type="file" class="form-control-file" name="imagem" id="avatarFile" aria-describedby="fileHelp" buttonText="Your label here.">' +
                                                                                                '<button id="marginButton" class="button trash" type="submit" name="apagarAnexo"><img src="{{ asset("assets/svg/trash.svg") }}" alt="Trash"></button>' +
                                                                                                '<button class="button trash cancel" type="button" id="fecharEdicao{{$mensagem->id}}"><img src="{{ asset("assets/svg/plus.svg") }}" alt="Plus"></button>' +
                                                                                                '<button class="button" type="submit"><img src="{{ asset("assets/svg/save.svg") }}" alt="Save"></button></div>' +
                                                                                            
                                                                                            '</div>' +
                                                                                        '</form>');
                                                                    $("#editarResposta{{$mensagem->id}}").remove();
                                                                    $("#excluirResposta{{$mensagem->id}}").remove();
                                                                });
                                                                $(document).on('click', '#fecharEdicao{{$mensagem->id}}', function(e) {
                                                                    e.preventDefault();
                                                                    $("#editarMensagem").remove();
                                                                    $("#mensagem{{$mensagem->id}}").append('<div class="row direita"><button type="button" id="editarResposta{{$mensagem->id}}" class="button"><img src="{{ asset("assets/svg/edit.svg") }}" alt="Edit"></button>' +
                                                                        '<a id="excluirResposta{{$mensagem->id}}" class="button trash" href="{{ route('monitorias.excluir.mensagem', ['id' => $mensagem->id]) }}"><img src="{{ asset("assets/svg/trash.svg") }}" alt="Trash"></a>'
                                                                    );
                                                                });
                                                            });
                                                        </script>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div id="novaResposta">
                            <button id="adicionarResposta" type="button" class="button">Responder</button>
                            {{ $errors->has('resposta') ? $errors->first('resposta') : '' }}
                        </div>
    
                    </div>

                    <div id="forum">
                        <h2>Fórum</h2>

                        @foreach($todosTopicos as $topicos)
                            @if($topicos->monitoria_id == $monitoria_id)
                                    <div id="topico{{$topicos->id}}">
                                            <div class="row">
                                                <a id="listForum" href="{{ route('monitorias.forum', ['id' => $monitoria_id, 'topico' => $topicos->id]) }}">
                                                    <div class="row">
                                                        <div>
                                                            @foreach($usuarios as $usuario)
                                                                @if($usuario->id == $topicos->user_id)
                                                                    <h5>{{ $usuario->nome }}</h5>
                                                                @endif
                                                            @endforeach
                                                            <h4>{{$topicos->topico}}</h4>
                                                        </div>
                                                        <img src="{{ asset('assets/svg/right-arrow.svg') }}" alt="Right Arrow">  
                                                    </div>
                                                </a>
                                            </div>
                                    </div>  
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>
        </body>

    </html>

@endsection