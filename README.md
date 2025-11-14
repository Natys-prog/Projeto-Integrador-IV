# Projeto-Integrador-IV

Apresentação: https://docs.google.com/presentation/d/171NGLUDUXdxX8BBj_S2r95NhKpmYSSFS/edit?usp=sharing&ouid=105442219488268034996&rtpof=true&sd=true

Linguagem: Principal PHP + Html

Banco: MYSQL

API: 

Starting server:
php -S localhost:8000

Para criar a Base de dados:
localhost:8000/?init=1

# [Setup]
instalar o PHP:
powershell -c "& ([ScriptBlock]::Create((irm 'https://www.php.net/include/download-instructions/windows.ps1'))) -Version 8.4"

mysql:
https://dev.mysql.com/downloads/installer

Configurando o PHP mysql
https://www.php.net/manual/en/pdo.installation.php

E trocando o seguinte trecho:
`?extension_dir =`

para o diretorio do php, exemplo: 
```extension_dir = "C:\%user%\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.4_Microsoft.Winget.Source_8wekyb3d8bbwe\ext"```