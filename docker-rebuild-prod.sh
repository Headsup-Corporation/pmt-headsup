#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# Step 1: Build the Docker image with no cache
echo "Building Docker image with no cache..."
docker-compose down 
docker build --no-cache -t pmt-hcorp/helper:latest .

# Step 2: Bring up the Docker Compose services
echo "Starting Docker Compose services..."
docker-compose up -d 
