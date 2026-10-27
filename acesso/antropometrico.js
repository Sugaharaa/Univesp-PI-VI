const matrizDados = [];

document.getElementById("capturarBtn1").addEventListener("click", () => {
	const dataAtual = new Date();
	const dia = dataAtual.getDate();
	const mes = dataAtual.getMonth() + 1;
	const ano = dataAtual.getFullYear();
	const data = `${dia}/${mes}/${ano}`;

  let massa = document.getElementById("massa").value.trim();
  let estatura = document.getElementById("estatura").value.trim();

  let resultIMC = document.getElementById("resultIMC").innerHTML;
  const matchIMC = resultIMC.match(/IMC: ([\d.]+) kg\/m²/);
  const imc = matchIMC ? parseFloat(matchIMC[1]) : 0

  let cirabd = document.getElementById("cirabd").value.trim();
  let cirqua = document.getElementById("cirqua").value.trim();
  const etnia = document.getElementById("etnia").value;

if (!massa && !estatura && !cirabd && !cirqua) {
	alert("Necessário ao menos um valor Antropométrico!");
	return;
}
massa = parseFloat(massa) || 0;
estatura = parseFloat(estatura) || 0;
cirabd = parseFloat(cirabd) || 0;
cirqua = parseFloat(cirqua) || 0;

let relabdqua = 0;
if (cirabd !== 0 && cirqua !== 0){
	relabdqua = (cirabd / cirqua).toFixed(2);
}
	
  matrizDados.push({ data, massa, estatura, imc, cirabd, relabdqua });

  // Mantém no máximo 48 registros
  if (matrizDados.length > 48) {
    matrizDados.splice(0, matrizDados.length - 48);
    alert("É possível cadastrar até 48 valores!");
  }

  preencherTabelaAntro();
});

function preencherTabelaAntro() {
  const corpoTabela = document.getElementById("corpo-tabela");
  corpoTabela.innerHTML = "";

  matrizDados.forEach((dado, index) => {
    let classificaCor = "";
    let classificaCorABD = "";
    let classificaCorRel = "";

      if (dado.imc > 40) {
        classificaCor = "#e74c3c"; // vermelho
      } else if (dado.imc > 35 && dado.imc <= 40) {
        classificaCor = "#e67e22"; // laranja
      } else if (dado.imc > 30 && dado.imc <= 35) {
        classificaCor = "#f1c40f"; // amarelo
      } else {
        classificaCor = "#2ecc71"; // verde
      }

    const linha = document.createElement("tr");

    linha.innerHTML = `
      <td>${dado.data}</td>
      <td>${dado.massa}</td>
      <td>${dado.estatura}</td>
      <td style="color: ${classificaCor};">${dado.imc}</td>
      <td>${dado.cirabd}</td>
      <td>${dado.relabdqua}</td>
      <td>
        <div class="container-botao">
          <button class="btn-excluir" type="button" data-index="${index}">x<span class="legenda">Excluir</span></button>
	    <button class="btn-avaliar" type="button" data-imc="${dado.imc}" data-cirabd="${dado.cirabd}" data-cirqua="${dado.relabdqua}">+<span class="legenda">Avaliar</span></button>
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
      preencherTabelaAntro(); // Atualiza a tabela
    });
  });
  // Abrir o modal e passar os dados a função
  document.querySelectorAll(".btn-avaliar").forEach(botao => {
    botao.addEventListener("click", (e) => {
	const imc = parseFloat(e.currentTarget.getAttribute("data-imc"));
	const cirabd = parseFloat(e.currentTarget.getAttribute("data-cirabd"));
	const cirqua = parseFloat(e.currentTarget.getAttribute("data-cirqua"));
	abrirModal(imc, cirabd, cirqua);
    });
  });
}