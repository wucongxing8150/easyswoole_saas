@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../easyswoole/phpunit/bin/co-phpunit
php "%BIN_TARGET%" %*
