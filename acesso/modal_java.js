function abrirModal(imc, cirabd, cirqua) {
  document.getElementById('form1').style.opacity = '0.5';
  document.getElementById('modalRelatorio').style.display = 'block';
  document.getElementById('form1').classList.add('form-inativo');
  imcModal(imc);
  circAbdModal(cirabd);
  relAbdQua(cirqua);
}

function fecharModal() {
  document.getElementById('form1').style.opacity = '1';
  document.getElementById('modalRelatorio').style.display = 'none';
}

function imcModal(imc) {
  let classe;
  if (imc > 40) {
    classe = 'Obesidade Grau 3';
  } else if (imc >= 35) {
    classe = 'Obesidade Grau 2';
  } else if (imc >= 30) {
    classe = 'Obesidade Grau 1';
  } else if (imc >= 25) {
    classe = 'Sobrepeso';
  } else if (imc >= 18.5) {
    classe = 'Peso Normal';
  } else if (imc >= 17) {
    classe = 'Baixo Peso';
  } else if (imc > 0) {
    classe = 'Muito Baixo Peso';
  } else {
    classe = 'Não avaliado';
  }

  document.getElementById('resultaimc').innerHTML = classe;
}

function circAbdModal(cirabd) {
  let classe = '';
  let etnia = parseInt(document.getElementById('etnia').value, 10);
  let sexo = parseInt(document.getElementById('sexo').value, 10);

  if (etnia === 1) {
    if (sexo === 0 && cirabd > 90) {
      classe = 'Risco Cardiovascular Alto';
    }
    if (sexo === 1 && cirabd > 85) {
      classe = 'Risco Cardiovascular Alto';
    }
  } else {
    if (sexo === 0) {
      if (cirabd > 102) {
        classe = 'Risco Cardiovascular Altíssimo';
      } else if (cirabd >= 94) {
        classe = 'Risco Cardiovascular Alto';
      } else if (cirabd >= 90) {
        classe = 'Risco Cardiovascular Médio';
      } else {
        classe = 'Risco Cardiovascular Normal';
      }
    } else if (sexo === 1) {
      if (cirabd > 88) {
        classe = 'Risco Cardiovascular Altíssimo';
      } else if (cirabd >= 84) {
        classe = 'Risco Cardiovascular Alto';
      } else if (cirabd >= 80) {
        classe = 'Risco Cardiovascular Médio';
      } else {
        classe = 'Risco Cardiovascular Normal';
      }
    }
  }

  if (classe === '' || !cirabd) {
    classe = 'Não avaliado';
  }

  const live = document.getElementById('resultcirabd');
  const modal = document.getElementById('resultcirabd2');
  if (live) live.innerHTML = classe;
  if (modal) modal.innerHTML = classe;
}

function relAbdQua(relacao) {
  let classe = '';
  let sexo = parseInt(document.getElementById('sexo').value, 10);

  if (relacao === 0 || relacao === null || relacao === undefined || isNaN(relacao)) {
    classe = 'Não avaliado';
  } else if (sexo === 0) {
    classe = relacao >= 0.9 ? 'Valor acima do normal' : 'Valor normal';
  } else {
    classe = relacao >= 0.85 ? 'Valor acima do normal' : 'Valor normal';
  }

  document.getElementById('resultabdqua').innerHTML = classe;
}
