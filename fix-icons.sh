#!/bin/bash

# Script to replace all storage/logos/icon.png with SVG icons

echo "Fixing icon references in all Blade files..."

# Define the SVG icon
SVG_ICON='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>'

# Find all files with storage/logos/icon.png and replace them
find resources/views -name "*.blade.php" -exec grep -l "storage/logos/icon.png" {} \; | while read file; do
    echo "Processing: $file"
    
    # Replace img tags with SVG
    sed -i.bak 's|<img src="{{ asset('\''storage\/logos\/icon\.png'\'') }}"[^>]*>|'"$SVG_ICON"'|g' "$file"
    
    # Clean up backup files
    rm -f "$file.bak"
done

echo "Icon replacement completed!"
echo "Files processed:"
find resources/views -name "*.blade.php" -exec grep -l "storage/logos/icon.png" {} \;
