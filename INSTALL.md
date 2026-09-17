# POS Monitor Installation

## Requirements

- Windows
- XAMPP installed at `C:\xampp`
- Apache and MySQL from XAMPP
- An existing `onpos` database containing the POS tables
- PowerShell

The installer creates only the `monitor_users` table. It does not replace POS tables or the POS-owned `audittrail` table.

## Install

1. Copy this project folder to the target computer.
2. Open PowerShell in the project folder.
3. Run:

   ```powershell
   Set-ExecutionPolicy -Scope Process Bypass
   .\install-monitor.ps1
   ```

4. Enter the MySQL root password when prompted.
5. Open the account setup page and create the first monitor account:

   `http://localhost/monitor/create_user.php`

6. After the account is created, remove or rename `create_user.php`.
7. Log in at:

   `http://localhost/monitor/login.php`

## Configuration

The installer creates `config.local.php`. It contains the database password and is excluded from Git by `.gitignore`.

If XAMPP is installed somewhere other than `C:\xampp`, edit `$xamppRoot` in `install-monitor.ps1` before running it.

## Remote access

Start Apache and MySQL locally first. Then run ngrok separately:

```powershell
ngrok http 80
```

Do not commit `config.local.php` or `ngrok.log`.
