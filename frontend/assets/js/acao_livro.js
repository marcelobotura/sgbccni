document.addEventListener('DOMContentLoaded', () => {
  const botoesAcao = document.querySelectorAll('.btn-acao-livro');

  botoesAcao.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      const livroId = btn.dataset.livroId;
      const acao = btn.dataset.acao;
      const origem = btn.dataset.origem || 'historico';

      if (!livroId || !acao) return;

      try {
        const formData = new FormData();
        formData.append('livro_id', livroId);
        formData.append('acao', acao);
        formData.append('origem', origem);

        const response = await fetch('/sgbccni/frontend/usuario/acao_livro.php', {
          method: 'POST',
          body: formData
        });

        if (!response.ok) throw new Error('Erro ao enviar requisição.');

        const result = await response.text(); // Pode ser JSON se quiser customizar depois

        // Exemplo: muda texto ou cor do botão após a ação
        if (acao === 'lido') {
          btn.textContent = '✅ Lido';
          btn.disabled = true;
        } else if (acao === 'favorito') {
          btn.textContent = '⭐ Favorito';
          btn.disabled = true;
        } else if (acao === 'remover') {
          const card = btn.closest('.card');
          if (card) card.remove();
        }

        exibirMensagem('✅ Ação realizada com sucesso.', 'sucesso');

      } catch (err) {
        console.error(err);
        exibirMensagem('❌ Erro ao executar ação.', 'erro');
      }
    });
  });

  function exibirMensagem(msg, tipo) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${tipo === 'sucesso' ? 'success' : 'danger'} mt-3`;
    alert.textContent = msg;

    document.body.prepend(alert);
    setTimeout(() => alert.remove(), 4000);
  }
});
