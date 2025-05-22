function alternarTema() {
  const atual = getCookie('modo_tema') || 'claro';
  const proximo = atual === 'claro' ? 'medio' : (atual === 'medio' ? 'dark' : 'claro');
  document.cookie = 'modo_tema=' + proximo + '; path=/';
  location.reload();
}

function getCookie(nome) {
  const match = document.cookie.match('(^|;)\\s*' + nome + '\\s*=\\s*([^;]+)');
  return match ? match.pop() : '';
}