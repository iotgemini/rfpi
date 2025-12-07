#!/bin/bash

# Configurazioni
SCRIPT_DIR=$(dirname "$(realpath "$0")")
LOG_FILE="$SCRIPT_DIR/install_lcd5.log"
SCRIPTS=("install_flows_lcd5" "install_touch_lcd5")

# Funzione per loggare i messaggi
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

# Inizio installazione
log "🟢 Avvio installazione sistema LCD5"
log "Cartella degli script: $SCRIPT_DIR"

# Esegui tutti gli script in ordine
for script in "${SCRIPTS[@]}"; do
    SCRIPT_PATH="$SCRIPT_DIR/$script.sh"
    
    if [ -f "$SCRIPT_PATH" ]; then
        log "🔵 Esecuzione script: $script.sh"
        chmod +x "$SCRIPT_PATH" 2>/dev/null
        
        # Esegui lo script e cattura output
        if bash "$SCRIPT_PATH" 2>&1 | tee -a "$LOG_FILE"; then
            log "✅ $script.sh completato con successo"
        else
            log "❌ $script.sh fallito (codice errore: ${PIPESTATUS[0]})"
            log "🛑 Installazione interrotta"
            exit 1
        fi
    else
        log "❌ Script $script.sh non trovato in $SCRIPT_DIR"
        exit 1
    fi
done

# Completamento
log "🟢 Installazione completata con successo!"
echo "Guarda il log completo: $LOG_FILE"

# Riavvio opzionale
read -p "Vuoi riavviare il sistema? [y/N] " choice
if [[ "$choice" =~ [yY] ]]; then
    log "🔄 Riavvio del sistema..."
    sudo reboot
fi