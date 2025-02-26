# 🏗️ 📜 Guia de Instalação do Helm Chart no Kubernetes 🎩🚢

Este documento fornece um passo a passo detalhado de como instalar um **Helm Chart** no Kubernetes.

---

## 📌 **Pré-requisitos**
Antes de instalar o Helm Chart, certifique-se de que tem os seguintes requisitos atendidos:

✅ **Cluster Kubernetes** configurado e em execução (`kubectl` deve estar funcional).  
✅ **Kubectl instalado** - [Guia de Instalação do Kubectl](https://kubernetes.io/docs/tasks/tools/install-kubectl/)  
✅ **Helm instalado** - [Guia de Instalação do Helm](https://helm.sh/docs/intro/install/)

Para verificar se o `kubectl` está funcionando corretamente, execute:
```bash
   kubectl cluster-info
```
Para verificar se o helm está instalado corretamente:
```bash
   helm version
```
🔹 Passo 1: Adicionar o Repositório do Helm Chart
Se o Chart está em um repositório Helm (como o ArtifactHub ou um repositório privado), você pode adicioná-lo usando:
```bash
   helm repo add meu-repo https://minhaempresa.com/helm
```
* Após adicionar o repositório, rodar o comando abaixo para atualizar a lista de repositórios:

```bash
   helm repo update
```

* Para listar os Charts disponíveis (se adicionou um repositório Helm):
```bash
   helm search repo meu-repo
```

* Se você já sabe qual repositório deseeja instalar, basta rodar o comando abaixo definindo sua secolha. Como exemplo será usado o helm do *nginx-ingress* na namespace ixcsoft

```bash
   helm install ng-ingress ingress-nginx/ingress-nginx -n ixcsoft
```

# 📌 Verificar Instalação
**Após a instalação, verifique se os recursos foram criados corretamente:**

```bash
   helm list -n ixcsoft
```

**Para verificar a saída detalhada do Helm:**

```bash
   helm status meu-release -n ixcsoft
```
# 📌 🔹 Desinstalar o Helm Chart

Se precisar remover a implantação do Helm:
helm uninstall meu-release -n meu-namespace

helm list -n meu-namespace

kubectl delete namespace meu-namespace


# 📜 Estrutura do Helm Chart

### A estrutura básica do Helm Chart é:

```plaintext
📂 meu-chart/
┣ 📂 templates/      → Manifests Kubernetes (Deployments, Services, Ingress, etc.)
┣ 📄 values.yaml     → Arquivo de configuração customizável
┣ 📄 Chart.yaml      → Metadados do Chart
┣ 📄 README.md       → Documentação
┗ 📄 _helpers.tpl    → Templates auxiliares
```

---


###### 🚀 "O impossível é apenas uma questão de código." 💻✨
