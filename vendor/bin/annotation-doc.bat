@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../easyswoole/http-annotation/bin/annotation-doc
php "%BIN_TARGET%" %*
