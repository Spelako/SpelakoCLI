@echo off
title SpelakoCLI
:start
php SpelakoCLI.php --core="../SpelakoCore/SpelakoCore.php" --config="config.json"
timeout /t 10 /nobreak
goto start