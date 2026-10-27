let graficoInstancia = null;
let graficoInstancia2 = null;

function abrirPopup() {
  const popup = document.getElementById("popup");
  const canvasJejum = document.getElementById("grafico-jejum");
  const canvasPos = document.getElementById("grafico-pos");
  const relatorioTabela = document.getElementById("relatorio-tabela");

  // Limpa conteúdo anterior
  relatorioTabela.innerHTML = "";

  fetch("carregar_csv2.php")
    .then(response => response.json())
    .then(dados => {
      if (dados.length === 0) {
        relatorioTabela.innerHTML = "<p>Nenhum dado disponível.</p>";
        return;
      }

      // Cria tabela HTML
      const tabela = document.createElement("table");
      tabela.className = "tabela-saude";

      const thead = document.createElement("thead");
      thead.innerHTML = `
        <tr>
          <th>Data</th>
          <th>Hora</th>
          <th>Glicemia</th>
          <th>Tipo</th>
          <th>Tempo do Tipo</th>
        </tr>
      `;
      tabela.appendChild(thead);

      const tbody = document.createElement("tbody");
      dados.forEach(linha => {
        const tr = document.createElement("tr");
        linha.forEach(valor => {
          const td = document.createElement("td");
          td.textContent = valor;
          tr.appendChild(td);
        });
        tbody.appendChild(tr);
      });
      tabela.appendChild(tbody);
      relatorioTabela.appendChild(tabela);

      // Filtra os dados por tipo
      const dadosJejum = dados.filter(l => l[3] === "Em jejum");
      const dadosPos = dados.filter(l => l[3] === "Pos Prandial");

      const labelsJejum = dadosJejum.map(l => `${l[0]} ${l[1]}`);
      const glicemiaJejum = dadosJejum.map(l => parseInt(l[2]));

      const labelsPos = dadosPos.map(l => `${l[0]} ${l[1]} (${l[4]})`);
      const glicemiaPos = dadosPos.map(l => parseInt(l[2]));

      // Destroi gráficos anteriores
      if (graficoInstancia) {
        graficoInstancia.destroy();
        graficoInstancia = null;
      }
      if (graficoInstancia2) {
        graficoInstancia2.destroy();
        graficoInstancia2 = null;
      }

      // Gráfico Em Jejum
      graficoInstancia = new Chart(canvasJejum, {
        type: "line",
        data: {
          labels: labelsJejum,
          datasets: [{
            label: "Glicemia - Em Jejum",
            data: glicemiaJejum,
            borderColor: "#2ecc71",
            backgroundColor: "rgba(46, 204, 113, 0.2)",
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              min: 0,
              max: 600,
              title: {
                display: true,
                text: "mg/dL"
              }
            },
            x: {
              title: {
                display: true,
                text: "Data e Hora"
              }
            }
          }
        }
      });

      // Gráfico Pós-Prandial
      graficoInstancia2 = new Chart(canvasPos, {
        type: "line",
        data: {
          labels: labelsPos,
          datasets: [{
            label: "Glicemia - Pós-Prandial",
            data: glicemiaPos,
            borderColor: "#e67e22",
            backgroundColor: "rgba(230, 126, 34, 0.2)",
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              min: 0,
              max: 600,
              title: {
                display: true,
                text: "mg/dL"
              }
            },
            x: {
              title: {
                display: true,
                text: "Data e Hora"
              }
            }
          }
        }
      });

      popup.style.display = "block";
    })
    .catch(err => {
      relatorioTabela.innerHTML = `<p>Erro ao carregar dados: ${err}</p>`;
    });
}
function fecharPopup() {
  const popup = document.getElementById("popup");
  const relatorioTabela = document.getElementById("relatorio-tabela");

  popup.style.display = "none";
  relatorioTabela.innerHTML = "";

  if (graficoInstancia) {
    graficoInstancia.destroy();
    graficoInstancia = null;
  }
  if (graficoInstancia2) {
    graficoInstancia2.destroy();
    graficoInstancia2 = null;
  }
}
