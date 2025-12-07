#!/bin/bash

# Nome: download_mp3_youtube.sh
# Descrizione: Scarica audio da YouTube come MP3 in /etc/rfpi/music/
# Uso: sudo bash download_mp3_youtube.sh "URL_YOUTUBE"

# Verifica se l'URL è fornito
if [ -z "$1" ]; then
    echo "❌ Errore: Nessun URL fornito."
    echo "👉 Uso: sudo $0 'https://www.youtube.com/watch?v=...'"
    exit 1
fi

# Cartella di output (personalizzata)
OUTPUT_DIR="/etc/rfpi/music"

# Verifica se la cartella esiste, altrimenti la crea
if [ ! -d "$OUTPUT_DIR" ]; then
    echo "📁 Creo la cartella $OUTPUT_DIR..."
    sudo mkdir -p "$OUTPUT_DIR"
    sudo chmod 777 "$OUTPUT_DIR"  # Permessi di scrittura per tutti (aggiusta se necessario)
fi

# Funzione per installare yt-dlp
install_yt_dlp() {
    echo "🔍 yt-dlp non è installato. Installazione in corso..."
    if ! command -v pip3 &>/dev/null; then
        echo "📦 Installo Python e pip..."
        sudo apt update && sudo apt install -y python3 python3-pip
    fi
    sudo pip3 install --upgrade yt-dlp
}

# Verifica se yt-dlp è installato
if ! command -v yt-dlp &>/dev/null; then
    install_yt_dlp
fi

# Scarica l'audio in MP3
echo "⬇️ Downloading: $1"
yt-dlp \
    -x \
    --audio-format mp3 \
    --audio-quality 0 \
    --output "${OUTPUT_DIR}/%(title)s.%(ext)s" \
    --no-check-certificate \
    "$1"

# Messaggio di completamento
if [ $? -eq 0 ]; then
    echo "🎉 Audio salvato in: ${OUTPUT_DIR}/"
    ls -l "$OUTPUT_DIR" | grep ".mp3"  # Mostra i file scaricati
else
    echo "❌ Download fallito. Verifica:"
    echo "  1. L'URL è corretto e pubblico"
    echo "  2. La cartella $OUTPUT_DIR è scrivibile"
    echo "  3. La connessione Internet è attiva"
fi