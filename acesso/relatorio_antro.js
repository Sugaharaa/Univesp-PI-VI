let graficoInstancia = null;
let graficoInstancia2 = null;
let graficoInstancia3 = null;
let graficoInstancia4 = null;

function abrirPopup() {
  const popup = document.getElementById("popup");
  const canvasMassa = document.getElementById("grafico-massa");
  const canvasIMC = document.getElementById("grafico-imc");
  const canvasCirAbd = document.getElementById("grafico-cirabd");
  const canvasRel = document.getElementById("grafico-relacao");
  const relatorioTabela = document.getElementById("relatorio-tabela");

  // Limpa conteúdo anterior
  relatorioTabela.innerHTML = "";

  fetch("carregar_csv3.php")
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
          <th>Massa</th>
          <th>Estatura</th>
          <th>IMC</th>
          <th>Circunferência Abdominal</th>
          <th>Relação Abdome-Quadril</th>                    
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

      const labelsMassa = dados.map(l => `${l[0]}`);
      const massa = dados.map(l => parseFloat(l[1]));

      const labelsIMC = dados.map(l => `${l[0]}`);
      const imc = dados.map(l => parseFloat(l[3]));

      const labelsCirAbd = dados.map(l => `${l[0]}`);
      const cirabd = dados.map(l => parseFloat(l[4]));

      const labelsRelacao = dados.map(l => `${l[0]}`);
      const relacao = dados.map(l => parseFloat(l[5]));

      // Destroi gráficos anteriores
      if (graficoInstancia) {
        graficoInstancia.destroy();
        graficoInstancia = null;
      }
      if (graficoInstancia2) {
        graficoInstancia2.destroy();
        graficoInstancia2 = null;
      }
      if (graficoInstancia3) {
        graficoInstancia3.destroy();
        graficoInstancia3 = null;
      }
      if (graficoInstancia4) {
        graficoInstancia4.destroy();
        graficoInstancia4 = null;
      }
      // Gráfico Em Massa
      graficoInstancia = new Chart(canvasMassa, {
        type: "line",
        data: {
          labels: labelsMassa,
          datasets: [{
            label: "Masas em kg",
            data: massa,
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
              max: 200,
              title: {
                display: true,
                text: "kg"
              }
            },
            x: {
              title: {
                display: true,
                text: "Data"
              }
            }
          }
        }
      });
      // Gráfico IMC
      graficoInstancia2 = new Chart(canvasIMC, {
        type: "line",
        data: {
          labels: labelsIMC,
          datasets: [{
            label: "IMC kg/m²",
            data: imc,
            borderColor: "#0e0357ff",
            backgroundColor: "rgba(76, 14, 248, 0.93)",
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              min: 5,
              max: 100,
              title: {
                display: true,
                text: "kg/m²"
              }
            },
            x: {
              title: {
                display: true,
                text: "Data"
              }
            }
          }
        }
      });
      // Gráfico Circ Abd
      graficoInstancia3 = new Chart(canvasCirAbd, {
        type: "line",
        data: {
          labels: labelsCirAbd,
          datasets: [{
            label: "Circ. Abdominal (cm)",
            data: cirabd,
            borderColor: "#060606ff",
            backgroundColor: "rgba(234, 233, 237, 0.2)",
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              min: 20,
              max: 400,
              title: {
                display: true,
                text: "cm"
              }
            },
            x: {
              title: {
                display: true,
                text: "Data"
              }
            }
          }
        }
      });   
      // Gráfico Relacao
      graficoInstancia4 = new Chart(canvasRel, {
        type: "line",
        data: {
          labels: labelsRelacao,
          datasets: [{
            label: "Rel Circ Abdome-Quadril",
            data: relacao,
            borderColor: "#ef0808ff",
            backgroundColor: "rgba(193, 61, 94, 0.2)",
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              min: 0.1,
              max: 4.0,
              title: {
                display: true,
                text: "U"
              }
            },
            x: {
              title: {
                display: true,
                text: "Data"
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
  if (graficoInstancia3) {
    graficoInstancia3.destroy();
    graficoInstancia3 = null;
  }
  if (graficoInstancia4) {
    graficoInstancia4.destroy();
    graficoInstancia4 = null;
  }
}
