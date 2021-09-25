@extends('topbar.topbar')

@section('conteudo')

    <!DOCTYPE html>
    <html lang="pt-br">

        <head>
            <meta charset="utf-8" />
            <title> Monitorando - {{ $topico->topico }} </title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="{{ asset('/css/index.css') }}">
            <link rel="icon" href="{{ asset('assets/png/icon.png') }}">
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <!-- mediaquery -->
        </head>

        <body>
            @section('links')
                <a href="{{ route('index') }}"> HOME </a>
                <a class="active" href="{{ route('monitorias') }}"> @lang('lang.Monitorias') </a>
                <a href="{{ route('calendario') }}"> @lang('lang.Calendario') </a>
                <a href="#quem somos"> @lang('lang.QuemSomos') </a>   
            @endsection 
            <script>
                $(document).ready(function() {
                    $("#adicionarResposta").click(function(e) {
                        e.preventDefault(); 
                        $("#novaResposta").append('<form method="POST" action="{{ route('monitorias.forum', ['id' => $monitoria_id, 'topico' => $topico->id]) }}" enctype="multipart/form-data">' +
                                                        '@csrf' +
                                                        '<label for="resposta">Resposta</label>' +
                                                        '<textarea type="text" name="resposta">{{ old('resposta') }}</textarea>' + 
                                                        '<input type="file" class="form-control-file" name="imagem" id="avatarFile" aria-describedby="fileHelp">' +
                                                        '<small id="fileHelp" class="form-text text-muted"><br/>Insira uma imagem ou um arquivo pdf</small>' +
                                                        '<button type="submit">Enviar Resposta</button>' +
                                                    '</form>');
                        $("#adicionarResposta").remove();
                    });
                });
            </script>
            <div id="mensagens">
                {{ session()->has('editado') ? session('editado') : '' }}
                {{ $errors->has('mensagem') ? $errors->first('mensagem') : '' }}
                {{ $errors->has('resposta') ? $errors->first('resposta') : '' }}
                {{ $errors->has('imagem') ? $errors->first('imagem') : '' }}
                <h3>{{ $topico->topico }}</h3>
                @foreach($mensagens as $mensagem)
                    <p>{{ $mensagem->mensagem }}</p>
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
                            <img src="{{ $mensagem->imagem }}" />
                        @endif
                    @endif
                    @if($mensagem->user_id == Auth::user()->id)
                        <button type="button" id="editarResposta{{$mensagem->id}}">Editar mensagem</button>
                        <button type="button" id="excluirResposta{{$mensagem->id}}"><a href="{{ route('monitorias.excluir.mensagem', ['id' => $mensagem->id]) }}">Excluir mensagem</a></button>
                        <script>
                            $(document).ready(function() {
                                var editar = true;
                                $("#editarResposta{{$mensagem->id}}").click(function(e) {
                                    e.preventDefault(); 
                                    $("#mensagens").append('<form method="POST" id="editarMensagem" action="{{ route('monitorias.editar.mensagem', ['id' => $mensagem->id]) }}" enctype="multipart/form-data">' +
                                                            '@csrf' +
                                                            '<div id="forumMensagem">' + 
                                                                '<textarea name="mensagem" form="editarMensagem">{{ $mensagem->mensagem ?? old('mensagem') }}</textarea>' + 
                                                                '<input type="file" class="form-control-file" name="imagem" id="avatarFile" aria-describedby="fileHelp">' +
                                                                '<small id="fileHelp" class="form-text text-muted"><br/>Insira uma imagem ou um arquivo pdf</small>' +
                                                                '<button type="submit" name="apagarAnexo">Apagar anexo</button>' +
                                                                '<button type="submit">Editar Mensagem</button>' +
                                                            '</div>' +
                                                        '</form>');
                                    $("#editarResposta{{$mensagem->id}}").remove();
                                    $("#excluirResposta{{$mensagem->id}}").remove();
                                });
                            });
                        </script>
                    @endif
                @endforeach
            </div>
            <div id="novaResposta">
                <button id="adicionarResposta" type="button">Adicionar uma resposta</button>
                {{ $errors->has('resposta') ? $errors->first('resposta') : '' }}
            </div>
        </body>

    </html>

@endsection