  </div> <!-- fim da container -->

  <!-- 🌗 Botão de alternância de tema -->
  <div class="text-center py-3">
    <button onclick="alternarTema()" class="btn btn-secundario">
      <i class="bi bi-moon-stars-fill me-1"></i> Alternar tema
    </button>
  </div>

  <!-- Bootstrap Bundle JS (inclui Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Scripts adicionais por página (opcional) -->
  <?php if (!empty($extraScripts)) echo $extraScripts; ?>

</body>
</html>
