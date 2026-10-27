<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="acesso/acessouser.css" />
	<title>Acesso de Usuário</title>
</head>

<body>
<form id="formacesso" method="POST" action="acesso/acesso_cadastro.php" autocomplete="off">

    <div id="titulocaduser">
        <h1>Acesso de Usuário</h1>
    </div>
<hr>
	<div style="align-items: center; gap: 8px;">
		<label style="font-weight: bold;">E-mail: <input type="email" name="email" id="email" placeholder="e-mail" style="width: 300px;" required></label><br><br>
    	<label style="font-weight: bold;">Senha: <input type="password" name="senha" id="senha" style="width: 150px; " placeholder="senha" required></label>
		<hr><br><a href="javascript:esqueceu_acesso()">Esqueceu seu acesso? Clique aqui.</a>
	</div>
<hr>
	<div>
		<button style="font-weight: bold;" id="entrar" type="submit">Entrar</button>
	</div>
</form>
    <div id="eventModalEsqSenha">
            <h2>Recuperar acesso</h2><hr><br>
            <p>O envio automático de senha por e-mail ainda não está disponível.</p>
            <p>Entre em contato pela página <strong>Contato</strong> (e-mail ou WhatsApp) informando o e-mail cadastrado para solicitar ajuda do suporte.</p>
            <br>
            <button type="button" id="btnIrContato">Ir para Contato</button>&nbsp;&nbsp;
            <button type="button" id="btnModalFechar">Fechar</button>
    </div>

</body>
<script>
    const newEvent = document.getElementById('eventModalEsqSenha');
    newEvent.style.display = 'none';

    document.getElementById('btnModalFechar').addEventListener('click', () => {
        newEvent.style.display = 'none';
    });

    document.getElementById('btnIrContato').addEventListener('click', () => {
        if (typeof mostrarConteudo === 'function') {
            mostrarConteudo('contato.html');
        } else {
            window.location.href = 'contato.html';
        }
        newEvent.style.display = 'none';
    });

    function esqueceu_acesso(){
        newEvent.style.display = 'block';
    }
</script>
</html>
