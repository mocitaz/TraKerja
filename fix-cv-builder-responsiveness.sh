#!/bin/bash

# Script to fix responsiveness in all CV Builder Livewire components

echo "Fixing CV Builder responsiveness..."

# List of files to fix
files=(
    "resources/views/livewire/cv-builder/education-form.blade.php"
    "resources/views/livewire/cv-builder/skills-form.blade.php"
    "resources/views/livewire/cv-builder/organization-form.blade.php"
    "resources/views/livewire/cv-builder/achievement-form.blade.php"
    "resources/views/livewire/cv-builder/project-form.blade.php"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "Processing $file..."
        
        # Fix header responsiveness
        sed -i '' 's/flex items-center justify-between/flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4/g' "$file"
        
        # Fix button responsiveness
        sed -i '' 's/class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition flex items-center gap-2"/class="w-full sm:w-auto px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition flex items-center justify-center gap-2"/g' "$file"
        
        # Fix card layout responsiveness
        sed -i '' 's/flex items-start justify-between/flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 sm:gap-4/g' "$file"
        
        # Fix content area
        sed -i '' 's/flex-1/flex-1 min-w-0/g' "$file"
        
        # Fix actions area
        sed -i '' 's/flex items-center gap-2 ml-4/flex items-center justify-end sm:justify-start gap-1 sm:gap-2 flex-shrink-0/g' "$file"
        
        # Fix action buttons
        sed -i '' 's/p-1 text-gray-400 hover:text-gray-600/p-1.5 sm:p-1 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-100/g' "$file"
        sed -i '' 's/p-2 text-primary-600 hover:bg-primary-50 rounded/p-1.5 sm:p-2 text-primary-600 hover:bg-primary-50 rounded/g' "$file"
        sed -i '' 's/p-2 text-red-600 hover:bg-red-50 rounded/p-1.5 sm:p-2 text-red-600 hover:bg-red-50 rounded/g' "$file"
        
        # Fix icon sizes
        sed -i '' 's/w-5 h-5/w-4 h-4 sm:w-5 sm:h-5/g' "$file"
        
        # Fix text wrapping
        sed -i '' 's/font-semibold text-gray-900/font-semibold text-gray-900 truncate/g' "$file"
        sed -i '' 's/text-sm text-gray-700/text-sm text-gray-700 truncate/g' "$file"
        sed -i '' 's/text-sm text-gray-500/text-sm text-gray-500 break-words/g' "$file"
        sed -i '' 's/text-sm text-gray-600 mt-2/text-sm text-gray-600 mt-2 break-words/g' "$file"
        
        echo "✓ Fixed $file"
    else
        echo "⚠ File not found: $file"
    fi
done

echo "✅ CV Builder responsiveness fixes completed!"
