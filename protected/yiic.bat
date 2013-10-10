@echo off

rem -------------------------------------------------------------
rem  Yii command line script for Windows.
rem  This is the bootstrap script for running yiic on Windows.
rem -------------------------------------------------------------

@setlocal

set BIN_PATH=%~dp0

set PHP_COMMAND="C:\program files (x86)\xampp\php\php.exe"

"%PHP_COMMAND%" "%BIN_PATH%yiic.php" %*

@endlocal