#!/bin/bash

# Script para corrigir temas das views financeiras para dark mode

VIEWS_DIR="/Users/lucaslopes/Downloads/tasksskala/tasksskala-laravel/resources/views/admin"

# Encontrar todos os arquivos blade.php nas pastas financeiras
find "$VIEWS_DIR" -name "*.blade.php" | \
grep -E "(dashboard-financeira|fluxo-caixa|tipos-custo|categorias-financeiras|contas-bancarias|contas-pagar|contas-receber|fornecedores|importacao-ofx)" | \
while read file; do
    echo "Processing: $file"
    
    # Substituições para o tema escuro
    sed -i '' 's/bg-white/card-dark/g' "$file"
    sed -i '' 's/text-gray-900/text-primary-dark/g' "$file"
    sed -i '' 's/text-gray-800/text-primary-dark/g' "$file"
    sed -i '' 's/text-gray-700/text-muted-dark/g' "$file"
    sed -i '' 's/text-gray-600/text-muted-dark/g' "$file"
    sed -i '' 's/text-gray-500/text-muted-dark/g' "$file"
    sed -i '' 's/border-gray-200/border-gray-700/g' "$file"
    sed -i '' 's/border-gray-300/border-gray-600/g' "$file"
    sed -i '' 's/bg-gray-50/bg-gray-800/g' "$file"
    sed -i '' 's/bg-gray-100/bg-gray-800/g' "$file"
    sed -i '' 's/hover:bg-gray-100/hover:bg-gray-700/g' "$file"
    sed -i '' 's/hover:bg-gray-50/hover:bg-gray-700/g' "$file"
    
    # Substituições específicas para inputs e selects
    sed -i '' 's/class="\([^"]*\)border-gray-300\([^"]*\)"/class="\1input-dark\2"/g' "$file"
    sed -i '' 's/rounded-md border-gray-300/input-dark rounded-md/g' "$file"
    
    # Substituições para tabelas
    sed -i '' 's/class="min-w-full divide-y divide-gray-200"/class="min-w-full divide-y divide-gray-700 table-dark-custom"/g' "$file"
    sed -i '' 's/class="bg-gray-50"/class="bg-gray-800"/g' "$file"
    
    # Substituições para botões
    sed -i '' 's/bg-blue-500 hover:bg-blue-700 text-white/btn-primary-dark/g' "$file"
    sed -i '' 's/bg-green-500 hover:bg-green-700 text-white/bg-green-600 hover:bg-green-700 text-white/g' "$file"
    sed -i '' 's/bg-yellow-500 hover:bg-yellow-700 text-white/bg-yellow-600 hover:bg-yellow-700 text-white/g' "$file"
    sed -i '' 's/bg-red-500 hover:bg-red-700 text-white/bg-red-600 hover:bg-red-700 text-white/g' "$file"
    sed -i '' 's/bg-gray-500 hover:bg-gray-700 text-white/btn-secondary-dark/g' "$file"
    
    echo "Updated: $file"
done

echo "Dark theme fixes applied!"