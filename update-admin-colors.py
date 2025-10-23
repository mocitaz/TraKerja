import os
import glob
import re

files_updated = 0

# Admin-specific color mappings
admin_mappings = {
    # Gray text to purple text
    'text-gray-800': 'text-primary-800',
    'text-gray-900': 'text-primary-900',
    'text-gray-700': 'text-primary-700',
    'text-gray-600': 'text-primary-600',
    'text-gray-500': 'text-primary-500',
    
    # Hover states
    'hover:text-gray-900': 'hover:text-primary-900',
    'hover:text-gray-700': 'hover:text-primary-700',
    
    # Active backgrounds
    'bg-gray-100': 'bg-primary-50',
    'bg-gray-50': 'bg-primary-50',
    'hover:bg-gray-50': 'hover:bg-primary-100',
    'hover:bg-gray-100': 'hover:bg-primary-100',
    
    # Border colors
    'hover:border-gray-300': 'hover:border-primary-300',
    
    # Green colors to purple for success states
    'text-green-600': 'text-primary-600',
    'text-green-100': 'text-primary-100',
    'text-green-200': 'text-primary-200',
    'bg-green-50': 'bg-primary-50',
    'bg-green-100': 'bg-primary-100',
    'bg-green-600': 'bg-primary-600',
    'bg-green-900': 'bg-primary-900',
    'text-green-300': 'text-primary-300',
    'text-green-400': 'text-primary-400',
    'text-green-800': 'text-primary-800',
    'border-green-500': 'border-primary-500',
    'border-green-300': 'border-primary-300',
    'hover:border-green-300': 'hover:border-primary-300',
    
    # Yellow colors to secondary purple
    'border-yellow-500': 'border-secondary-500',
    'bg-yellow-50': 'bg-secondary-50',
    'border-yellow-300': 'border-yellow-300',
    'hover:border-yellow-300': 'hover:border-secondary-300',
}

# Admin and Livewire admin files
admin_files = [
    'resources/views/admin/*.blade.php',
    'resources/views/livewire/admin/*.blade.php',
    'resources/views/components/admin-layout.blade.php',
    'resources/views/layouts/admin.blade.php',
]

for pattern in admin_files:
    for file_path in glob.glob(pattern):
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            original_content = content
            
            # Apply mappings
            for old_color, new_color in admin_mappings.items():
                content = content.replace(old_color, new_color)
            
            # Only write if changed
            if content != original_content:
                with open(file_path, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"✓ Updated: {file_path}")
                files_updated += 1
        except Exception as e:
            print(f"✗ Error updating {file_path}: {str(e)}")

print(f"\n🎨 Admin Purple Theme Update Complete!")
print(f"Updated {files_updated} files")
