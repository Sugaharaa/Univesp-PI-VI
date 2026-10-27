let graficoInstancia = null;

function abrirPopup() {

  const popup = document.getElementById("popup");
  const relatorioTabela = document.getElementById("relatorio-tabela");
  const graficoCanvas = document.getElementById("grafico-relatorio");

  // Limpa conteúdo anterior
  relatorioTabela.innerHTML = "";
  graficoCanvas.getContext("2d").clearRect(0, 0, graficoCanvas.width, graficoCanvas.height);

  // Carrega os dados do CSV via PHP
  fetch("carregar_csv.php")
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
          <th>FC (bpm)</th>
          <th>PAS</th>
          <th>PAD</th>
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

      // Prepara dados para o gráfico
      const labels = dados.map(l => `${l[0]} ${l[1]}`); // Data + Hora
      const pas = dados.map(l => parseInt(l[3]));
      const pad = dados.map(l => parseInt(l[4]));

    // Destroi gráfico anterior, se existir
    if (graficoInstancia) {
      graficoInstancia.destroy();
      graficoInstancia = null;
    }

      // Cria gráfico com Chart.js
      graficoInstancia = new Chart(graficoCanvas, {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            {
              label: "PAS (sistólica)",
              data: pas,
              borderColor: "#e74c3c",
              backgroundColor: "rgba(231, 76, 60, 0.2)",
              fill: true,
              tension: 0.3
            },
            {
              label: "PAD (diastólica)",
              data: pad,
              borderColor: "#3498db",
              backgroundColor: "rgba(52, 152, 219, 0.2)",
              fill: true,
              tension: 0.3
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            title: {
              display: true,
              text: "Evolução da Pressão Arterial",
              font: {
                size: 18
              }
            },
            legend: {
              position: "top"
            }
          },
          scales: {
            y: {
              title: {
                display: true,
                text: "mmHg"
              },
              beginAtZero: false
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

      // Exibe o popup
      popup.style.display = "block";
    })
    .catch(err => {
      relatorioTabela.innerHTML = `<p>Erro ao carregar dados: ${err}</p>`;
    });
}

function fecharPopup() {
  const popup = document.getElementById("popup");
  const relatorioTabela = document.getElementById("relatorio-tabela");
  const graficoCanvas = document.getElementById("grafico-relatorio");

  // Esconde o popup
  popup.style.display = "none";

  // Limpa tabela
  relatorioTabela.innerHTML = "";

  // Destroi gráfico se existir
  if (graficoInstancia) {
    graficoInstancia.destroy();
    graficoInstancia = null;
  }

  // Limpa canvas manualmente (opcional)
  const ctx = graficoCanvas.getContext("2d");
  ctx.clearRect(0, 0, graficoCanvas.width, graficoCanvas.height);

}

