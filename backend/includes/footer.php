    </div> <!-- Fim do conteúdo principal -->

    <footer class="text-center py-3 small text-muted bg-light mt-auto">
        <span>&copy; <?= date('Y') ?> Biblioteca Comunitária CNI</span>
    </footer>

    <!-- Bootstrap Bundle com Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS de tema (modo escuro/claro) -->
    <script src="<?= URL_BASE ?>assets/js/tema.js"></script>

    <!-- Scripts adicionais (opcional) -->
    <?php if (!empty($extraScripts)) echo $extraScripts; ?>

  </body>
</html>
