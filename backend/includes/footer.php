  </div> <!-- fim da container -->

  <div class="text-center py-3">
    <button onclick="alternarTema()" class="btn btn-secondary">
      <i class="bi bi-moon-stars-fill me-1"></i> Alternar tema
    </button>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function alternarTema() {
      const atual = document.documentElement.getAttribute('data-bs-theme') || 'light';
      const novoTema = atual === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-bs-theme', novoTema);
      document.cookie = `modo_tema=${novoTema}; path=/; max-age=31536000`;
    }
  </script>

  <?php if (!empty($extraScripts)) echo $extraScripts; ?>
</body>
</html>
