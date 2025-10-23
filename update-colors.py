#!/usr/bin/env python3
"""
Script to update color palette from blue/indigo to purple theme
Replaces all occurrences in Blade files
"""

import os
import re
from pathlib import Path

# Color mapping: old_color -> new_color
COLOR_MAPPINGS = {
    # Blue to Primary (Purple)
    'bg-blue-50': 'bg-primary-50',
    'bg-blue-100': 'bg-primary-100',
    'bg-blue-200': 'bg-primary-200',
    'bg-blue-300': 'bg-primary-300',
    'bg-blue-400': 'bg-primary-400',
    'bg-blue-500': 'bg-primary-500',
    'bg-blue-600': 'bg-primary-600',
    'bg-blue-700': 'bg-primary-700',
    'bg-blue-800': 'bg-primary-800',
    'bg-blue-900': 'bg-primary-900',
    
    'text-blue-50': 'text-primary-50',
    'text-blue-100': 'text-primary-100',
    'text-blue-200': 'text-primary-200',
    'text-blue-300': 'text-primary-300',
    'text-blue-400': 'text-primary-400',
    'text-blue-500': 'text-primary-500',
    'text-blue-600': 'text-primary-600',
    'text-blue-700': 'text-primary-700',
    'text-blue-800': 'text-primary-800',
    'text-blue-900': 'text-primary-900',
    
    'border-blue-50': 'border-primary-50',
    'border-blue-100': 'border-primary-100',
    'border-blue-200': 'border-primary-200',
    'border-blue-300': 'border-primary-300',
    'border-blue-400': 'border-primary-400',
    'border-blue-500': 'border-primary-500',
    'border-blue-600': 'border-primary-600',
    'border-blue-700': 'border-primary-700',
    'border-blue-800': 'border-primary-800',
    'border-blue-900': 'border-primary-900',
    
    'hover:bg-blue-50': 'hover:bg-primary-50',
    'hover:bg-blue-100': 'hover:bg-primary-100',
    'hover:bg-blue-200': 'hover:bg-primary-200',
    'hover:bg-blue-300': 'hover:bg-primary-300',
    'hover:bg-blue-400': 'hover:bg-primary-400',
    'hover:bg-blue-500': 'hover:bg-primary-500',
    'hover:bg-blue-600': 'hover:bg-primary-600',
    'hover:bg-blue-700': 'hover:bg-primary-700',
    'hover:bg-blue-800': 'hover:bg-primary-800',
    'hover:bg-blue-900': 'hover:bg-primary-900',
    
    'hover:text-blue-50': 'hover:text-primary-50',
    'hover:text-blue-100': 'hover:text-primary-100',
    'hover:text-blue-200': 'hover:text-primary-200',
    'hover:text-blue-300': 'hover:text-primary-300',
    'hover:text-blue-400': 'hover:text-primary-400',
    'hover:text-blue-500': 'hover:text-primary-500',
    'hover:text-blue-600': 'hover:text-primary-600',
    'hover:text-blue-700': 'hover:text-primary-700',
    'hover:text-blue-800': 'hover:text-primary-800',
    'hover:text-blue-900': 'hover:text-primary-900',
    
    'hover:border-blue-50': 'hover:border-primary-50',
    'hover:border-blue-100': 'hover:border-primary-100',
    'hover:border-blue-200': 'hover:border-primary-200',
    'hover:border-blue-300': 'hover:border-primary-300',
    'hover:border-blue-400': 'hover:border-primary-400',
    'hover:border-blue-500': 'hover:border-primary-500',
    'hover:border-blue-600': 'hover:border-primary-600',
    'hover:border-blue-700': 'hover:border-primary-700',
    'hover:border-blue-800': 'hover:border-primary-800',
    'hover:border-blue-900': 'hover:border-primary-900',
    
    'focus:ring-blue-50': 'focus:ring-primary-50',
    'focus:ring-blue-100': 'focus:ring-primary-100',
    'focus:ring-blue-200': 'focus:ring-primary-200',
    'focus:ring-blue-300': 'focus:ring-primary-300',
    'focus:ring-blue-400': 'focus:ring-primary-400',
    'focus:ring-blue-500': 'focus:ring-primary-500',
    'focus:ring-blue-600': 'focus:ring-primary-600',
    'focus:ring-blue-700': 'focus:ring-primary-700',
    'focus:ring-blue-800': 'focus:ring-primary-800',
    'focus:ring-blue-900': 'focus:ring-primary-900',
    
    'focus:border-blue-50': 'focus:border-primary-50',
    'focus:border-blue-100': 'focus:border-primary-100',
    'focus:border-blue-200': 'focus:border-primary-200',
    'focus:border-blue-300': 'focus:border-primary-300',
    'focus:border-blue-400': 'focus:border-primary-400',
    'focus:border-blue-500': 'focus:border-primary-500',
    'focus:border-blue-600': 'focus:border-primary-600',
    'focus:border-blue-700': 'focus:border-primary-700',
    'focus:border-blue-800': 'focus:border-primary-800',
    'focus:border-blue-900': 'focus:border-primary-900',
    
    'dark:bg-blue-900': 'dark:bg-primary-900',
    'dark:text-blue-300': 'dark:text-primary-300',
    'dark:text-blue-400': 'dark:text-primary-400',
    'dark:hover:text-blue-300': 'dark:hover:text-primary-300',
    
    # Indigo to Secondary (Violet)
    'bg-indigo-50': 'bg-secondary-50',
    'bg-indigo-100': 'bg-secondary-100',
    'bg-indigo-400': 'bg-secondary-400',
    'bg-indigo-600': 'bg-secondary-600',
    'bg-indigo-700': 'bg-secondary-700',
    
    'text-indigo-400': 'text-secondary-400',
    'text-indigo-500': 'text-secondary-500',
    'text-indigo-600': 'text-secondary-600',
    'text-indigo-700': 'text-secondary-700',
    'text-indigo-800': 'text-secondary-800',
    
    'border-indigo-400': 'border-secondary-400',
    'border-indigo-700': 'border-secondary-700',
    
    'focus:text-indigo-800': 'focus:text-secondary-800',
    'focus:bg-indigo-100': 'focus:bg-secondary-100',
    'focus:border-indigo-700': 'focus:border-secondary-700',
    'focus:ring-indigo-500': 'focus:ring-primary-500',
}

def update_file(file_path):
    """Update colors in a single file"""
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        original_content = content
        
        # Replace colors from longest to shortest to avoid partial replacements
        for old_color, new_color in sorted(COLOR_MAPPINGS.items(), key=lambda x: len(x[0]), reverse=True):
            content = content.replace(old_color, new_color)
        
        # Special handling for welcome.blade.php custom CSS variables
        if 'welcome.blade.php' in str(file_path):
            # Update CSS custom properties to purple theme
            content = content.replace('--primary: #0056B3;', '--primary: #8b5cf6;')
            content = content.replace('--secondary: #28A745;', '--secondary: #a855f7;')
            content = content.replace('--accent: #1e40af;', '--accent: #d946ef;')
            
            # Update gradient colors in CSS
            content = content.replace('from-[#0056B3]', 'from-primary-600')
            content = content.replace('to-[#28A745]', 'to-secondary-500')
            content = content.replace('from-[#28A745]', 'from-secondary-500')
            content = content.replace('to-[#0056B3]', 'to-primary-600')
            content = content.replace('from-[#1e40af]', 'from-primary-700')
            content = content.replace('to-[#16a34a]', 'to-secondary-600')
            
            # Update text colors
            content = content.replace('text-[#0056B3]', 'text-primary-600')
            content = content.replace('text-[#28A745]', 'text-secondary-500')
            content = content.replace('hover:text-[#0056B3]', 'hover:text-primary-600')
            
            # Update background colors
            content = content.replace('bg-[#0056B3]', 'bg-primary-600')
            content = content.replace('bg-[#28A745]', 'bg-secondary-500')
            content = content.replace('hover:bg-[#0056B3]', 'hover:bg-primary-600')
            
            # Update border colors
            content = content.replace('border-[#0056B3]', 'border-primary-600')
            content = content.replace('border-[#28A745]', 'border-secondary-500')
            content = content.replace('hover:border-[#0056B3]', 'hover:border-primary-600')
            
            # Update rgba colors in CSS
            content = content.replace('rgba(0, 86, 179', 'rgba(139, 92, 246')  # #8b5cf6
            content = content.replace('rgba(40, 167, 69', 'rgba(168, 85, 247')  # #a855f7
            content = content.replace('rgba(30, 64, 175', 'rgba(109, 40, 217')  # #6d28d9
        
        # Only write if content changed
        if content != original_content:
            with open(file_path, 'w', encoding='utf-8') as f:
                f.write(content)
            return True
        return False
    except Exception as e:
        print(f"Error processing {file_path}: {e}")
        return False

def main():
    """Main function to update all blade files"""
    resources_dir = Path('resources/views')
    
    if not resources_dir.exists():
        print(f"Error: {resources_dir} not found!")
        return
    
    # Find all .blade.php files
    blade_files = list(resources_dir.rglob('*.blade.php'))
    
    print(f"Found {len(blade_files)} blade files to process...")
    
    updated_count = 0
    for blade_file in blade_files:
        if update_file(blade_file):
            updated_count += 1
            print(f"✓ Updated: {blade_file}")
    
    print(f"\n{'='*60}")
    print(f"Summary: Updated {updated_count} out of {len(blade_files)} files")
    print(f"{'='*60}")
    print("\nColor mappings applied:")
    print("  blue-* → primary-* (Purple)")
    print("  indigo-* → secondary-* (Violet)")
    print("\nNext steps:")
    print("  1. Run: npm run build")
    print("  2. Clear cache: php artisan optimize:clear")
    print("  3. Test the application")

if __name__ == '__main__':
    main()
