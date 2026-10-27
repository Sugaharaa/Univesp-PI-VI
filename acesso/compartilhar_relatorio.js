function textoRelatorio() {
  const tabela = document.getElementById('relatorio-tabela');
  return (tabela ? tabela.innerText : '').trim().substring(0, 1500);
}

function compartilharWhatsApp(titulo) {
  const corpo = textoRelatorio();
  const msg = corpo ? `${titulo}\n\n${corpo}` : titulo;
  window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`, '_blank');
}

function compartilharEmail(titulo) {
  const corpo = textoRelatorio();
  const subject = encodeURIComponent(titulo);
  const body = encodeURIComponent(corpo || 'Segue meu relatório de saúde do PI-6.');
  window.location.href = `mailto:?subject=${subject}&body=${body}`;
}

function voltarInicio() {
  window.location.href = 'telaprincuser.php';
}
