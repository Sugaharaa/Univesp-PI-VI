function getSexoCodigo() {
  const el = document.getElementById('sexo');
  return el ? String(el.value) : '0';
}

function isFeminino() {
  return getSexoCodigo() === '1';
}

function calculaIMC() {
  let sign = '';
  let massa = parseFloat(document.getElementById('massa').value);
  let estatura = parseFloat(document.getElementById('estatura').value);

  if (!isNaN(massa) && !isNaN(estatura) && estatura > 0) {
    const imc = (massa / Math.pow(estatura, 2)) * 10000;

    if (imc < 18.5) {
      sign = 'Abaixo do peso (magreza)';
    } else if (imc < 25) {
      sign = 'Peso normal';
    } else if (imc < 30) {
      sign = 'Sobrepeso';
    } else if (imc < 35) {
      sign = 'Obesidade grau I';
    } else if (imc < 40) {
      sign = 'Obesidade grau II';
    } else {
      sign = 'Obesidade grau III (muito elevada)';
    }
    document.getElementById('resultIMC').innerHTML = 'IMC: ' + imc.toFixed(2) + ' kg/m² - ' + sign;
  } else {
    document.getElementById('resultIMC').innerHTML = '';
  }
}

function calculacirabd() {
  let cirabd = parseFloat(document.getElementById('cirabd').value);
  let etnia = document.getElementById('etnia').value;
  let result = '';
  const feminino = isFeminino();

  if (isNaN(cirabd)) {
    document.getElementById('resultcirabd').innerHTML = '';
    return;
  }

  if (etnia === '0') {
    if (feminino) {
      result = cirabd > 80 ? 'Acima do normal' : 'Dentro dos padrões de normalidade';
    } else {
      result = cirabd > 94 ? 'Acima do normal' : 'Dentro dos padrões de normalidade';
    }
  } else {
    if (feminino) {
      result = cirabd > 80 ? 'Acima do normal' : 'Dentro dos padrões de normalidade';
    } else {
      result = cirabd > 90 ? 'Acima do normal' : 'Dentro dos padrões de normalidade';
    }
  }

  document.getElementById('resultcirabd').innerHTML = result;
}

function calcularcq() {
  let cirabd = parseFloat(document.getElementById('cirabd').value);
  let cirqua = parseFloat(document.getElementById('cirqua').value);
  let result = '';
  const feminino = isFeminino();

  if (isNaN(cirabd) || isNaN(cirqua) || cirqua === 0) {
    alert('É necessário preencher os valores da circunferência abdominal e do quadril!');
    return;
  }

  const rcp = cirabd / cirqua;

  if (feminino) {
    result = rcp > 0.85 ? 'Acima do normal' : 'Dentro dos padrões de normalidade';
  } else {
    result = rcp > 0.95 ? 'Acima do normal' : 'Dentro dos padrões de normalidade';
  }

  const alvo = document.getElementById('resultcintquad');
  if (alvo) {
    alvo.innerHTML = result;
  }
}
