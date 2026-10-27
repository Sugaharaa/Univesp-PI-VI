const matrizDados = [];

document.getElementById("capturarBtn").addEventListener("click", () => {

  const data = document.getElementById("data").value.trim();
  const hora = document.getElementById("hora").value.trim();
  const fc = document.getElementById("fc").value.trim();
  const pas = document.getElementById("pas").value.trim();
  const pad = document.getElementById("pad").value.trim();


if (!data || !hora || !pas || !pad) {
	alert("Necessário os valores de data, horário e da pressão!");
	return;
}

  const timestamp = new Date(`${data}T${hora}`);

  matrizDados.push({ data, hora, fc, pas, pad, timestamp });

  // Ordena por data/hora crescente
  matrizDados.sort((a, b) => a.timestamp - b.timestamp);

  // Mantém no máximo 20 registros
  if (matrizDados.length > 20) {
    matrizDados.splice(0, matrizDados.length - 20);
    alert("É possível cadastrar até 20 valores!");
  }

  preencherTabelaPA();
});

function preencherTabelaPA() {
  const corpoTabela = document.getElementById("corpo-tabela");
  corpoTabela.innerHTML = "";

  matrizDados.forEach((dado, index) => {
    let classificaCor = "";
 

    if (dado.pad > 79) {
      if (dado.pad > 109 || dado.pas > 179) {
        classificaCor = "#e74c3c"; // vermelho
      } else if ((dado.pad > 99 && dado.pad < 110) || (dado.pas > 159 && dado.pas < 180)) {
        classificaCor = "#e67e22"; // laranja
      } else if ((dado.pad > 89 && dado.pad < 100) || (dado.pas > 139 && dado.pas < 160)) {
        classificaCor = "#f1c40f"; // amarelo
      } else if ((dado.pad > 79 && dado.pad < 90) || (dado.pas > 119 && dado.pas < 140)) {
        classificaCor = "#0000FF"; //azul
      } else {
        classificaCor = "#2ecc71"; // verde
      }
    } else {
      if (dado.pas > 119) {
        classificaCor = "#8B4513"; // marrom
      } else {
        classificaCor = "#2ecc71"; // verde
      }
    }

    const linha = document.createElement("tr");

    linha.innerHTML = `
      <td>${dado.data}</td>
      <td>${dado.hora}</td>
      <td>${dado.fc}</td>
      <td style="color: ${classificaCor};">${dado.pas}</td>
      <td style="color: ${classificaCor};">${dado.pad}</td>
      <td>
        <div class="container-botao">
          <button class="btn-excluir" data-index="${index}">x<span class="legenda">Excluir</span></button>
        </div>
      </td>
    `;

    corpoTabela.appendChild(linha);
  });

  // Adiciona os eventos de exclusão
  document.querySelectorAll(".btn-excluir").forEach(botao => {
    botao.addEventListener("click", (e) => {
      const indice = parseInt(e.currentTarget.getAttribute("data-index"));
      matrizDados.splice(indice, 1); // Remove da matriz
      preencherTabelaPA(); // Atualiza a tabela
    });
  });
}