::[Bat To Exe Converter]
::
::YAwzoRdxOk+EWAnk
::fBw5plQjdG8=
::YAwzuBVtJxjWCl3EqQJgSA==
::ZR4luwNxJguZRRnk
::Yhs/ulQjdF+5
::cxAkpRVqdFKZSzk=
::cBs/ulQjdF+5
::ZR41oxFsdFKZSDk=
::eBoioBt6dFKZSDk=
::cRo6pxp7LAbNWATEpCI=
::egkzugNsPRvcWATEpCI=
::dAsiuh18IRvcCxnZtBJQ
::cRYluBh/LU+EWAnk
::YxY4rhs+aU+JeA==
::cxY6rQJ7JhzQF1fEqQJQ
::ZQ05rAF9IBncCkqN+0xwdVs0
::ZQ05rAF9IAHYFVzEqQJQ
::eg0/rx1wNQPfEVWB+kM9LVsJDGQ=
::fBEirQZwNQPfEVWB+kM9LVsJDGQ=
::cRolqwZ3JBvQF1fEqQJQ
::dhA7uBVwLU+EWDk=
::YQ03rBFzNR3SWATElA==
::dhAmsQZ3MwfNWATElA==
::ZQ0/vhVqMQ3MEVWAtB9wSA==
::Zg8zqx1/OA3MEVWAtB9wSA==
::dhA7pRFwIByZRRnk
::Zh4grVQjdCyDJGyX8VAjFBpHWRe+GG6pDaET+NT16v3Jp1UYNA==
::YB416Ek+ZG8=
::
::
::978f952a14a936cc963da21a135fa983
@echo off
setlocal enabledelayedexpansion

:: The path to the icon file (assuming it's in the same directory as the batch file)
set "ICON_PATH=%~dp0icon.ico"

:: Your batch file commands here
echo The icon path is: %ICON_PATH%

set /p "password=Enter password: "
if NOT "%password%"=="" goto :end
echo Correct password!
:: Your batch file commands here

:: Start Apache
start "" /B "C:\laragon\bin\apache\httpd-2.4.54-win64-VS16\bin\httpd.exe"

:: Start MySQL
start "" /B "C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqld.exe"

:: Start Laravel development server without a new window
cd C:\laragon\www\hr-FinanceMinistry
start "" /B cmd /c "php artisan serve"

:: Wait for 7 seconds
timeout /t 7 /nobreak

:: Check if Chrome is installed and open the link with it
set "chromePath=C:\Program Files\Google\Chrome\Application\chrome.exe"
if exist "%chromePath%" (
    start "" "%chromePath%" http://127.0.0.1:8000
) else (
    echo Chrome not found at "%chromePath%". Trying default system browser...
    start "" http://127.0.0.1:8000
)

:end
