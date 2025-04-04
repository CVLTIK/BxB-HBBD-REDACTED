#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}Connecting to BxB Heimdall Dashboard...${NC}"

# SSH into the server and execute commands
ssh -p 16652 bxbheimdalldashboardprj@35.192.146.69 << 'ENDSSH'
    echo -e "\033[0;32mConnected to server\033[0m"
    cd public/wp-content/plugins/bxb-layout-dashboard-alpha
    echo -e "\033[0;34mPulling latest changes from main branch...\033[0m"
    git pull origin main
    echo -e "\033[0;32mDeployment complete!\033[0m"
ENDSSH

echo -e "${GREEN}Deployment process finished.${NC}" 