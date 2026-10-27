<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>PI-6 - Univesp</title>
  <script src="js/cadpie4.js" defer></script>
  <script src="js/validar_senha.js" defer></script>
  <style>

.popup {
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #4CAF50;
  color: white;
  padding: 20px 30px;
  border-radius: 5px;
  opacity: 1;
  transition: opacity 0.5s ease-out;
  z-index: 9999; /* Garante que fique acima de outros elementos */
}


body {
  margin: 0;
  font-family: Arial, sans-serif;
  display: flex;
  padding-top: 60px; /* altura do cabeçalho fixo */
}
    #menu {
      width: 250px;
      background-color: #2c3e50;
      color: #ecf0f1;
      padding: 20px;
      box-sizing: border-box;
      height: 100vh;
    }
    #menu h3 {
      margin-top: 0;
    }
    .menu-item {
      cursor: pointer;
      margin: 10px 0;
      font-weight: bold;
    }
    .menu-item:hover {
      color: #1abc9c;
    }
    .submenu {
      display: none;
      margin-left: 15px;
    }
    .submenu div {
      cursor: pointer;
      margin: 5px 0;
    }
    .submenu div:hover {
      text-decoration: underline;
    }
    #conteudo {
      flex-grow: 1;
      padding: 20px;
    }
  </style>
</head>
<body>
<div style="
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  background-color: #34495e;
  color: white;
  padding: 15px;
  text-align: center;
  font-size: 24px;
  font-weight: bold;
  z-index: 1000;
">
  PI-6 - Acompanhamento de Saúde Individual
	UNIVESP
</div>

<div id="menu">
  <h3>Menu</h3>
  <div class="menu-item" onclick="mostrarConteudo('cadastro/cadastro.php')">Cadastrar</div>
  <div class="menu-item" onclick="toggleSubmenu('solicitacoes')">Acessar</div>
  <div class="submenu" id="solicitacoes">
    <div onclick="mostrarConteudo('acesso/acessouser.php')">Usuário</div>
  </div>
  <div class="menu-item" onclick="mostrarConteudo('sobre.html')">Sobre</div>
  <div class="menu-item" onclick="mostrarConteudo('contato.html')">Contato</div>

</div>

<div id="conteudo">
  <h2>Bem-vindo!</h2>
  <p>Selecione uma opção no menu para visualizar o conteúdo.</p>
  <?php
  if (isset($_GET['salvo'])){
      echo '<div id="popup" class="popup">Salvo com sucesso!</div>';
      echo '<script>
      var popup = document.getElementById("popup");
      popup.style.display = "block";
    
      setTimeout(function() {
        popup.style.opacity = "0";
        setTimeout(() => {
            popup.style.display = "none";
            popup.style.opacity = "1";  // Reseta a opacidade para usos futuros
        }, 500);
      }, 2000);
      </script>';
  }
?>
</div>

<script>
  function toggleSubmenu(id) {
    const submenu = document.getElementById(id);
    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
  }

function mostrarConteudo(url) {

  if (url) {
    fetch(url)
      .then(response => {
        if (!response.ok) {
          throw new Error('Erro ao carregar o conteúdo');
        }
        return response.text();
      })
      .then(data => {
        conteudo.innerHTML = data;
      })
      .catch(error => {
        conteudo.innerHTML = `<p style="color:red;">${error.message}</p>`;
      });
  }
}

</script>

</body>
</html>
