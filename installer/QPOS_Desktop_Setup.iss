; ============================================================
;  QPOS Desktop Installer — Muhammad_Waseem 
;  Wraps the pre-built Electron app into a Windows installer
; ============================================================

#define MyAppName "QPOS"
#define MyAppVersion "1.0.0"
#define MyAppPublisher "Alkyne Solutions"
#define MyInstallDir "C:\QPOS"
#define ElectronBuild "C:\QPOS_Output\electron\win-unpacked"

[Setup]
AppId={{B2C3D4E5-F6A7-8901-BCDE-F12345678901}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
AppPublisher={#MyAppPublisher}
DefaultDirName={#MyInstallDir}
DisableDirPage=yes
DefaultGroupName={#MyAppName}
DisableProgramGroupPage=yes
OutputDir=C:\QPOS_Output
OutputBaseFilename=QPOS_Desktop_Setup_v1.0
Compression=lzma2/ultra64
SolidCompression=yes
WizardStyle=modern
PrivilegesRequired=admin
MinVersion=6.1
UninstallDisplayName=QPOS - Alkyne Solutions
UninstallDisplayIcon={#MyInstallDir}\QPOS.exe

[Languages]
Name: "english"; MessagesFile: "compiler:Default.isl"

[Messages]
WelcomeLabel1=Welcome to QPOS Setup
WelcomeLabel2=This will install QPOS -  POS System on your computer.%n%nQPOS is a complete Point of Sale system that runs entirely offline.%n%nClick Next to continue.
FinishedHeadingLabel=Installation Complete
FinishedLabel=QPOS has been successfully installed.%n%nA shortcut has been created on your Desktop.%n%nClick Finish to launch QPOS for the first time.

[Tasks]
Name: "desktopicon"; Description: "Create a Desktop shortcut for QPOS"; GroupDescription: "Additional shortcuts:"

[Dirs]
Name: "{#MyInstallDir}"
Name: "{#MyInstallDir}\userdata"

[Files]
; ---- Entire pre-built Electron app ----
Source: "{#ElectronBuild}\*"; \
    DestDir: "{#MyInstallDir}"; \
    Flags: recursesubdirs createallsubdirs ignoreversion

[Icons]
; Desktop shortcut
Name: "{autodesktop}\QPOS"; \
    Filename: "{#MyInstallDir}\QPOS.exe"; \
    WorkingDir: "{#MyInstallDir}"; \
    Comment: "Open QPOS POS System"; \
    Tasks: desktopicon

; Start Menu shortcuts
Name: "{group}\QPOS"; \
    Filename: "{#MyInstallDir}\QPOS.exe"; \
    WorkingDir: "{#MyInstallDir}"; \
    Comment: "Open QPOS POS System"

Name: "{group}\Uninstall QPOS"; \
    Filename: "{uninstallexe}"

[Run]
; Launch QPOS after install
Filename: "{#MyInstallDir}\QPOS.exe"; \
    Description: "Launch QPOS now"; \
    Flags: postinstall nowait skipifsilent

[UninstallRun]
; Kill QPOS processes before uninstall
Filename: "taskkill"; \
    Parameters: "/F /IM QPOS.exe /T"; \
    Flags: runhidden waituntilterminated
Filename: "taskkill"; \
    Parameters: "/F /IM mysqld.exe /T"; \
    Flags: runhidden waituntilterminated

[Code]
function InitializeSetup(): Boolean;
begin
  Result := True;
  if DirExists('{#MyInstallDir}') then begin
    if MsgBox('QPOS is already installed.' + #13#10 +
              'This will update/reinstall it.' + #13#10 + #13#10 +
              'Your data will NOT be deleted. Continue?',
              mbConfirmation, MB_YESNO) = IDNO then
      Result := False;
  end;
end;
