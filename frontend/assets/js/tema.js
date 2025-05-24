// 🔁 Alterna entre claro → médio → escuro → claro
function alternarTema() {
  const atual = getCookie('modo_tema') || 'claro';
  const proximo = atual === 'claro' ? 'medio' : (atual === 'medio' ? 'escuro' : 'claro');
  document.cookie = 'modo_tema=' + proximo + '; path=/';
  location.reload();
}

// 🍪 Lê cookie
function getCookie(nome) {
  const match = document.cookie.match('(^|;)\\s*' + nome + '\\s*=\\s*([^;]+)');
  return match ? match.pop() : '';
}

// 🚀 Aplica o tema ao carregar
window.addEventListener('DOMContentLoaded', () => {
  const tema = getCookie('modo_tema') || 'claro';
  document.body.classList.remove('tema-claro', 'tema-medio', 'tema-escuro');
  document.body.classList.add('tema-' + tema);
});
