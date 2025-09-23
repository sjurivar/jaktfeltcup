#!/bin/bash

# Manual deployment script for hjellum.net
# Usage: ./deploy.sh

echo "🚀 Starting deployment to hjellum.net..."

# Configuration
HOST="hjellum.net"
USERNAME="your_username"
REMOTE_PATH="/home/$USERNAME/public_html/jaktfeltcup"
LOCAL_PATH="."

# Create deployment package
echo "📦 Creating deployment package..."
mkdir -p deploy

# Copy necessary files
cp -r views deploy/
cp -r src deploy/
cp -r config deploy/
cp -r data deploy/
cp -r handlers deploy/
cp -r database deploy/
cp index.php deploy/
cp .htaccess deploy/
cp README.md deploy/
cp DEPLOYMENT.md deploy/

# Create production config
cp config/config.production.php deploy/config/config.php

# Create .htaccess for production
cat > deploy/.htaccess << 'EOF'
RewriteEngine On

# Redirect all requests to index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"

# Cache static files
<FilesMatch "\.(css|js|png|jpg|jpeg|gif|ico|svg)$">
    ExpiresActive On
    ExpiresDefault "access plus 1 month"
</FilesMatch>
EOF

# Create deployment info
echo "Deployed: $(date)" > deploy/DEPLOY_INFO.txt
echo "Manual deployment" >> deploy/DEPLOY_INFO.txt

echo "📤 Uploading files to server..."
# Upload files (replace with your preferred method)
# Option 1: Using rsync
# rsync -avz --delete deploy/ $USERNAME@$HOST:$REMOTE_PATH/

# Option 2: Using scp
# scp -r deploy/* $USERNAME@$HOST:$REMOTE_PATH/

# Option 3: Using FTP/SFTP
# sftp $USERNAME@$HOST << EOF
# put -r deploy/* $REMOTE_PATH/
# quit
# EOF

echo "✅ Deployment package created in 'deploy/' directory"
echo "📋 Next steps:"
echo "1. Upload the contents of 'deploy/' to your server"
echo "2. Set correct permissions (755 for directories, 644 for files)"
echo "3. Configure your database settings in config/config.php"
echo "4. Test the deployment at https://hjellum.net/jaktfeltcup/"

# Cleanup
rm -rf deploy
echo "🧹 Cleanup completed"
