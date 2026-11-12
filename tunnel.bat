@echo off
setlocal
title Cloudflare Quick Tunnel - KinPro

echo ===================================================
echo        CLOUDFLARE QUICK TUNNEL - KINPRO
echo ===================================================
echo.

set "CF_BIN=cloudflared"
where cloudflared >nul 2>nul
if errorlevel 1 (
    if exist "C:\Program Files (x86)\cloudflared\cloudflared.exe" (
        set "CF_BIN=C:\Program Files (x86)\cloudflared\cloudflared.exe"
    ) else if exist "C:\Program Files\cloudflared\cloudflared.exe" (
        set "CF_BIN=C:\Program Files\cloudflared\cloudflared.exe"
    ) else (
        echo [ERROR] cloudflared tidak ditemukan.
        echo Silakan unduh dan install cloudflared terlebih dahulu.
        echo https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/downloads/
        echo.
        pause
        exit /b 1
    )
)

if "%~1"=="8000" goto RUN_8000
if "%~1"=="herd" goto RUN_HERD
if not "%~1"=="" goto RUN_CUSTOM_ARG

echo Pilih target aplikasi:
echo  [1] Laravel Herd (https://kinpro.test) - Default
echo  [2] Artisan Serve (http://127.0.0.1:8000)
echo  [3] Custom Port
echo.
set "CHOICE=1"
set /p "CHOICE=Pilihan [1/2/3] (Tekan Enter untuk pilihan 1): "

if "%CHOICE%"=="2" goto RUN_8000
if "%CHOICE%"=="3" goto RUN_CUSTOM_INPUT

:RUN_HERD
echo.
echo [INFO] Menjalankan Cloudflare Quick Tunnel ke https://kinpro.test ...
echo [INFO] Tunggu hingga URL *.trycloudflare.com muncul di bawah.
echo [INFO] Tekan Ctrl+C untuk menghentikan tunnel.
echo.
"%CF_BIN%" tunnel --url https://kinpro.test --no-tls-verify --http-host-header kinpro.test
goto END

:RUN_8000
echo.
echo [INFO] Menjalankan Cloudflare Quick Tunnel ke http://127.0.0.1:8000 ...
echo [INFO] Pastikan "php artisan serve" aktif di jendela terminal lain!
echo [INFO] Tunggu hingga URL *.trycloudflare.com muncul di bawah.
echo [INFO] Tekan Ctrl+C untuk menghentikan tunnel.
echo.
"%CF_BIN%" tunnel --url http://127.0.0.1:8000
goto END

:RUN_CUSTOM_INPUT
echo.
set "PORT=8000"
set /p "PORT=Masukkan nomor port (misal: 8000): "
echo.
echo [INFO] Menjalankan Cloudflare Quick Tunnel ke http://127.0.0.1:%PORT% ...
echo [INFO] Tekan Ctrl+C untuk menghentikan tunnel.
echo.
"%CF_BIN%" tunnel --url http://127.0.0.1:%PORT%
goto END

:RUN_CUSTOM_ARG
echo.
echo [INFO] Menjalankan Cloudflare Quick Tunnel ke %~1 ...
echo [INFO] Tekan Ctrl+C untuk menghentikan tunnel.
echo.
"%CF_BIN%" tunnel --url %~1
goto END

:END
echo.
echo [INFO] Tunnel selesai.
pause
