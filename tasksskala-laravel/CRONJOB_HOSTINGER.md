# Configuração de Cronjobs na Hostinger

## URLs Disponíveis

### 1. Executar todo o scheduler do Laravel (recomendado)
```
https://intranet.skalacode.com/cron/run-scheduler?token=skala2024cron
```
Esta URL executa todos os comandos agendados, incluindo o relatório diário.

### 2. Executar apenas o relatório diário do WhatsApp
```
https://intranet.skalacode.com/cron/daily-report?token=skala2024cron
```
Esta URL executa apenas o comando de relatório diário.

## Como configurar na Hostinger

1. Acesse o painel da Hostinger
2. Vá em **Avançado > Cron Jobs**
3. Clique em **Adicionar Novo Cron Job**

### Para executar o scheduler a cada minuto (recomendado):
- **Comando**: `curl -s "https://intranet.skalacode.com/cron/run-scheduler?token=skala2024cron"`
- **Frequência**: A cada minuto (`* * * * *`)

### Para executar apenas o relatório diário às 18:00:
- **Comando**: `curl -s "https://intranet.skalacode.com/cron/daily-report?token=skala2024cron"`
- **Frequência**: Diariamente às 18:00 (`0 18 * * *`)

## Segurança

- **Token de segurança**: `skala2024cron`
- Para alterar o token, edite o arquivo `.env`:
  ```
  CRON_TOKEN=seu_novo_token_aqui
  ```

## Logs

Os logs são salvos em:
- Laravel logs: `storage/logs/laravel.log`
- Relatório WhatsApp: `storage/logs/whatsapp-daily-report.log`

## Testar manualmente

Para testar se está funcionando, acesse no navegador:
```
https://intranet.skalacode.com/cron/daily-report?token=skala2024cron
```

Você deve ver uma resposta JSON indicando sucesso ou erro.

## Respostas esperadas

### Sucesso:
```json
{
    "success": true,
    "message": "Relatório diário enviado com sucesso",
    "output": "...",
    "timestamp": "2025-07-28 20:30:00"
}
```

### Erro de token:
```json
{
    "error": "Unauthorized"
}
```