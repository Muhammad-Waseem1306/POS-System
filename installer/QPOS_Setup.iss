; ============================================================
;  QPOS Installer — Muhammad_Waseem Electronics
;  Built with Inno Setup 6
; ============================================================

#define MyAppName "QPOS - Waseem Electronics"
#define MyAppVersion "1.0.0"
#define MyAppPublisher "Muhammad_Waseem"
#define MyAppURL "http://127.0.0.1:8000"
#define MyInstallDir "C:\QPOS"

[Setup]
AppId={{A1B2C3D4-E5F6-7890-ABCD-EF1234567890}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
AppPublisher={#MyAppPublisher}
AppPublisherURL={#MyAppURL}
DefaultDirName={#MyInstallDir}
DisableDirPage=yes
DefaultGroupName={#MyAppName}
DisableProgramGroupPage=yes
OutputDir=C:\QPOS_Output
OutputBaseFilename=QPOS_Setup_v1.0
SetupIconFile=
Compression=lzma2/ultra64
SolidCompression=yes
WizardStyle=modern
WizardSmallImageFile=
; Require admin rights
PrivilegesRequired=admin
; Windows 7 minimum
MinVersion=6.1

[Languages]
Name: "english"; MessagesFile: "compiler:Default.isl"

[Messages]
WelcomeLabel1=Welcome to QPOS Setup
WelcomeLabel2=This will install QPOS - Waseem Electronics POS System on your computer.%n%nClick Next to continue.
FinishedHeadingLabel=QPOS Installation Complete
FinishedLabel=QPOS has been successfully installed on your computer.%n%nClick Finish to complete setup and run the first-time configuration.

[Types]
Name: "full"; Description: "Full Installation (Recommended)"

[Components]
Name: "main"; Description: "QPOS Application"; Types: full; Flags: fixed
Name: "php"; Description: "PHP 8.1 Runtime"; Types: full; Flags: fixed
Name: "mariadb"; Description: "MariaDB Database Engine"; Types: full; Flags: fixed

[Tasks]
Name: "desktopicon_start"; Description: "Create 'Start QPOS' shortcut on Desktop"; GroupDescription: "Desktop Shortcuts:"
Name: "desktopicon_stop"; Description: "Create 'Stop QPOS' shortcut on Desktop"; GroupDescription: "Desktop Shortcuts:"

[Dirs]
Name: "{#MyInstallDir}"
Name: "{#MyInstallDir}\app"
Name: "{#MyInstallDir}\app\storage\logs"
Name: "{#MyInstallDir}\app\storage\framework\cache\data"
Name: "{#MyInstallDir}\app\storage\framework\sessions"
Name: "{#MyInstallDir}\app\storage\framework\views"
Name: "{#MyInstallDir}\app\storage\app\public"
Name: "{#MyInstallDir}\mariadb\data"
Name: "{#MyInstallDir}\backups"

[Files]
; ---- Laravel Application ----
Source: "C:\Users\Usama Imran\POS system\pos for electronics\*"; \
    DestDir: "{#MyInstallDir}\app"; \
    Flags: recursesubdirs createallsubdirs ignoreversion; \
    Excludes: ".git,node_modules,installer,.env,.env.example,*.log,storage\framework\cache\*,storage\framework\sessions\*,storage\framework\views\*"

; ---- Production .env ----
Source: "C:\Users\Usama Imran\POS system\pos for electronics\.env.production"; \
    DestDir: "{#MyInstallDir}\app"; \
    DestName: ".env.production"; \
    Flags: ignoreversion

; ---- PHP 8.1 Portable ----
; NOTE: Extract your PHP 8.1 NTS zip to: C:\QPOS_Build\php8.1\
; then point the Source here:
Source: "C:\QPOS_Build\php8.1\*"; \
    DestDir: "{#MyInstallDir}\php"; \
    Flags: recursesubdirs createallsubdirs ignoreversion

; ---- MariaDB Portable ----
; NOTE: Extract MariaDB zip to: C:\QPOS_Build\mariadb\
; then point the Source here:
Source: "C:\QPOS_Build\mariadb\*"; \
    DestDir: "{#MyInstallDir}\mariadb"; \
    Flags: recursesubdirs createallsubdirs ignoreversion

; ---- MariaDB config ----
Source: "scripts\my.ini"; \
    DestDir: "{#MyInstallDir}\mariadb"; \
    Flags: ignoreversion

; ---- Batch Scripts ----
Source: "scripts\start.bat"; DestDir: "{#MyInstallDir}"; Flags: ignoreversion
Source: "scripts\stop.bat"; DestDir: "{#MyInstallDir}"; Flags: ignoreversion
Source: "scripts\install.bat"; DestDir: "{#MyInstallDir}"; Flags: ignoreversion

[Icons]
; Start QPOS — Desktop
Name: "{autodesktop}\Start QPOS"; \
    Filename: "{#MyInstallDir}\start.bat"; \
    WorkingDir: "{#MyInstallDir}"; \
    Comment: "Start QPOS POS System"; \
    Tasks: desktopicon_start

; Stop QPOS — Desktop
Name: "{autodesktop}\Stop QPOS"; \
    Filename: "{#MyInstallDir}\stop.bat"; \
    WorkingDir: "{#MyInstallDir}"; \
    Comment: "Stop QPOS POS System"; \
    Tasks: desktopicon_stop

; Start Menu
Name: "{group}\Start QPOS"; Filename: "{#MyInstallDir}\start.bat"; WorkingDir: "{#MyInstallDir}"
Name: "{group}\Stop QPOS"; Filename: "{#MyInstallDir}\stop.bat"; WorkingDir: "{#MyInstallDir}"
Name: "{group}\Uninstall QPOS"; Filename: "{uninstallexe}"

[Run]
; Run first-time setup AFTER installation
Filename: "{#MyInstallDir}\install.bat"; \
    Description: "Run first-time database setup (Required)"; \
    Flags: postinstall waituntilterminated runascurrentuser; \
    StatusMsg: "Setting up database..."

[UninstallRun]
; Stop servers before uninstall
Filename: "{#MyInstallDir}\stop.bat"; \
    Flags: waituntilterminated runhidden

[Code]
// Check if port 3306 or 8000 are already in use (optional warning)
function InitializeSetup(): Boolean;
begin
  Result := True;
  if DirExists('C:\QPOS') then begin
    if MsgBox('QPOS is already installed at C:\QPOS.' + #13#10 +
              'Continuing will overwrite the existing installation.' + #13#10 + #13#10 +
              'Do you want to continue?',
              mbConfirmation, MB_YESNO) = IDNO then
      Result := False;
  end;
end;
