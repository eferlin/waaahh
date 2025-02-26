#!/bin/bash
set -e  # Encerra o script em caso de erro

# Função para inicializar o backend
start_backend() {
  echo "Iniciando o backend com Supervisord..."
  exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
}

# Log para acompanhar o argumento recebido (se houver)
if [[ -n "$1" ]]; then
  echo "Argumento recebido: $1"
fi

# Inicializar o backend
start_backend