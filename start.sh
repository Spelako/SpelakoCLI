#!/usr/bin/env sh
while :
do
php SpelakoCLI.php --core="../SpelakoCore/SpelakoCore.php" --config="config.json"
echo "Waiting for 10 seconds, press Ctrl+C to quit ..."; sleep 10
done