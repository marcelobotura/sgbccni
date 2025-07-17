  </div> <!-- fim da container -->

  <footer class="bg-light text-center py-3 mt-4 border-top">
    <div class="container">
      <p class="mb-1 small text-muted">© <?= date('Y') ?> Biblioteca Comunitária CNI</p>
     
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  

  <?php if (!empty($extraScripts)) echo $extraScripts; ?>
</body>
</html>
