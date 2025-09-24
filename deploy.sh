#!/bin/bash

# Pixelllo Deployment Script
# This script handles git operations and deployment tasks

echo "Starting deployment process..."

# Function to push changes
push_changes() {
    echo "To push changes to GitHub, you need to:"
    echo ""
    echo "Option 1: Configure SSH key"
    echo "  1. Generate SSH key: ssh-keygen -t ed25519 -C 'your-email@example.com'"
    echo "  2. Add key to GitHub: https://github.com/settings/keys"
    echo "  3. Change remote to SSH: git remote set-url origin git@github.com:infoakhil/pixelllo.git"
    echo "  4. Push: git push origin main"
    echo ""
    echo "Option 2: Use Personal Access Token"
    echo "  1. Create token at: https://github.com/settings/tokens"
    echo "  2. Push with: git push https://YOUR_TOKEN@github.com/infoakhil/pixelllo.git main"
    echo ""
    echo "Option 3: Configure git credentials"
    echo "  git config --global credential.helper store"
    echo "  git push origin main (enter username and password when prompted)"
}

# Function to deploy after pull
deploy() {
    echo "Running deployment tasks..."
    
    # Install/update dependencies
    composer install --no-dev --optimize-autoloader
    
    # Clear and optimize caches
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    
    # Run migrations
    php artisan migrate --force
    
    # Set proper permissions
    sudo chown -R www-data:www-data storage/ bootstrap/cache/
    sudo chmod -R 775 storage/ bootstrap/cache/
    
    echo "Deployment complete!"
}

# Main menu
echo "Select an option:"
echo "1. Push changes to GitHub"
echo "2. Deploy after pulling changes"
echo "3. Exit"

read -p "Enter your choice (1-3): " choice

case $choice in
    1)
        push_changes
        ;;
    2)
        deploy
        ;;
    3)
        echo "Exiting..."
        exit 0
        ;;
    *)
        echo "Invalid choice"
        exit 1
        ;;
esac