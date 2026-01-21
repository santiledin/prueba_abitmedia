#!/bin/sh
set -e

if [ -f "yii" ]; then
    echo "Yii2 seems to be installed already."
else
    echo "Installing Yii2 Basic..."
    composer create-project --prefer-dist yiisoft/yii2-app-basic /tmp/yii2
    cp -r /tmp/yii2/. .
    rm -rf /tmp/yii2
    echo "Yii2 installed."
fi

# Set permissions if needed (chmod 777 runtime web/assets is common in dev)
chmod -R 777 runtime web/assets
