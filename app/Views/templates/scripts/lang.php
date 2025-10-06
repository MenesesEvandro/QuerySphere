window.LANG = {
      <?php
      // Carrega o arquivo de linguagem manualmente
      $languageFile =
          APPPATH .
          'Language/' .
          \Config\Services::language()->getLocale() .
          '/App.php';
      $translations = file_exists($languageFile) ? include $languageFile : [];

      // Grupos desejados
      $groups = [
          'general',
          'connection',
          'master_password',
          'workspace',
          'objects_browser',
          'scripts',
          'charts',
          'feedback',
          'server_check',
          'agent',
          'event',
          'schema_editor',
      ];

      $i = 0;
      // Itera sobre os grupos especificados
      foreach ($groups as $group) {
          if (isset($translations[$group]) && is_array($translations[$group])) {
              foreach ($translations[$group] as $key => $value) {
                  // Verifica se $value é uma string para evitar erro de conversão
                  if (is_string($value)) {
                      echo "\n";
                      if ($i > 0) {
                          echo ',';
                      }
                      echo "'$key': '$value'";
                      $i++;
                  } else {
                      echo "// '$key': [Array - valor não é string] \n";
                  }
              }
          } else {
              echo "// Grupo: $group \n";
              echo '// Grupo não encontrado ou não é um array.';
          }
      }
      echo "\n";
      ?>
    };

