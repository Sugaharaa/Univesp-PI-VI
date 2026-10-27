<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="cadastro/cadpie4.css">
	<title>Cadastro de Usuário</title>
</head>

<body>
<form id="formcad" onsubmit="return validarFormulario()" method="POST" action="cadastro/salva_cadastro.php">

    <div id="titulocaduser">
        <h1>Cadastro de Usuário</h1>
    </div>
<hr>
	<h2>Dados Pessoais</h2>
      <div class="campo">
      	<input type="text" name="nome" id="nome" placeholder="Nome Completo" style="width: 400px;" required>
	</div>

	<div style="display: flex; align-items: center; gap: 8px;">
  		<input type="text" name="nomesoc" id="nomesoc" placeholder="Nome Social" style="width: 200px;"> 
  		<label for="dn">Data de Nascimento:
  		<input type="date" name="dn" id="dn" style="width: 120px;" required>
	</div>
	<div style="display: flex; align-items: center; gap: 8px;">
		<input type="email" name="email" id="email" placeholder="e-mail" style="width: 300px;" required>
		<label>Sexo Genético:
			<select name="sexo" style="width: 110px;">
				<option value="0">Masculino</option>
				<option value="1">Feminino</option>
			</select>
		</label>
	</div>
<hr>
	<div>
<div style="white-space: nowrap;">
  <label style="display: inline-block;">
    Digite a Senha de Acesso:
    <input type="password" name="senha1" id="senha1" style="width: 150px;" onblur="validarsenha()" oninput="validarsenha()" title="É necessário de 8 a 12 caracteres entre números, letras maiúsculas, minúsculas e caracter especial!" required>
  </label>
  <div style="display: inline-block; color: red;" id="senhaStatus"></div>
</div>
		<label>Repita a Senha de Acesso: <input type="password" name="senha2" id="senha2" style="width: 150px;" onchange="verificasenha()" required> </label>
	</div>
	<div id="verificasenha"></div>
<hr>
     

            <div id="div1">
            <h2>📝 Termo de Consentimento para Cadastro e Uso de Dados Pessoais e de Saúde</h2>
            <textarea readonly id="area" style="width:100%;height:100px;">
                
		Última atualização: 28 de agosto de 2026

Ao aceitar este termo, o usuário consente com o tratamento de seus dados pessoais e de saúde no âmbito do uso do webapp, de caráter informativo e não comercial, gerido por José Augusto Ressignelli de Lima, CPF nº 223.146.338-57.
1. Dados Coletados
O usuário autoriza o cadastro e armazenamento dos seguintes dados:
Nome completo
Endereço de e-mail
CPF
Sexo
Data de nascimento
Senha pessoal e intransferível
Informações relacionadas à sua saúde, inseridas voluntariamente

2. Finalidade do Tratamento
Os dados serão utilizados exclusivamente para:
Identificação e autenticação do usuário
Geração de relatórios informativos sobre sua situação de saúde
Armazenamento seguro de informações pessoais e médicas
Melhoria contínua do serviço oferecido
Não há qualquer finalidade comercial ou cobrança pelo uso do sistema.

3. Responsabilidade e Gestão
O sistema é de responsabilidade exclusiva de José Augusto Ressignelli de Lima, que se compromete a:
Manter os dados sob sigilo e segurança
Não compartilhar informações com terceiros sem consentimento explícito
Utilizar os dados apenas para os fins descritos neste termo

4. Segurança da Senha
A senha cadastrada é de uso pessoal e intransferível, sendo responsabilidade do usuário mantê-la em sigilo. O sistema não se responsabiliza por acessos indevidos decorrentes de negligência com a senha.

5. Direitos do Usuário
O usuário poderá, a qualquer momento:
Solicitar a exclusão de seus dados
Corrigir informações incorretas
Revogar este consentimento

6. Aceite
Ao clicar em "Aceito", o usuário declara estar ciente e de acordo com os termos acima, autorizando o tratamento de seus dados conforme descrito.

            </textarea>
            </div>

<label for="termo" class="checkbox-label">
  <input type="checkbox" id="termo" name="termo">
  <span class="checkbox-text">Li e aceito os termos e condições acima.</span>
</label><br>	

	<div>
		<button id="salvar" type="submit">Salvar</button>
	</div>
</form>

</body>

</html>