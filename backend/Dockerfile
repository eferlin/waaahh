# Base da imagem
FROM ubuntu:20.04

# Configurar variáveis de ambiente
ENV DEBIAN_FRONTEND=noninteractive

# Atualizar pacotes e instalar dependências
RUN apt update && apt upgrade -y && \
    apt install -y --no-install-recommends \
        nginx \
        php-fpm \
        php-mysql \
        supervisor \
        curl \
        unzip \
        nano \
        dnsutils \
        mysql-client \
        gettext && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar a aplicação (mas NÃO arquivos de configuração)
COPY ./src/ /var/www/html/

# Ajustar permissões para o diretório da aplicação
RUN chmod -R 755 /var/www/html && \
    chown -R www-data:www-data /var/www/html

RUN mkdir -p /run/php
RUN chown -R www-data:www-data /run/php

# Criar diretório onde os ConfigMaps serão montados no Kubernetes
RUN mkdir -p /etc/confs

# Expor a porta para o servidor HTTP
EXPOSE 80

# Configurar o entrypoint e comando padrão
ENTRYPOINT ["/etc/confs/entrypoint.sh"]