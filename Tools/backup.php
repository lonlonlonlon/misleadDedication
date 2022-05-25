<?php
/**
 * TODO: ausbauen
 */
$folder = date("Y_m_d--H-i-s");
$folderToBackup = "/home/lol/.steam/steam/steamapps/compatdata/233860/pfx/drive_c/users/steamuser/AppData/Local/kenshi/save/";
$backupName = "kenshiSaveGame";
ensure("/home/lol/automatedBackup/$backupName/");
ensure("/home/lol/automatedBackup/$backupName/$folder/Banane/");
ensure("/home/lol/automatedBackup/$backupName/$folder/Banane2/");
ensure("/home/lol/automatedBackup/$backupName/$folder/quicksave/");
ensure("/home/lol/automatedBackup/$backupName/$folder/autosave0/");
ensure("/home/lol/automatedBackup/$backupName/$folder/autosave1/");
ensure("/home/lol/automatedBackup/$backupName/$folder/autosave2/");
ensure("/home/lol/automatedBackup/$backupName/$folder/KUMO!/");
system("cp -R $folderToBackup/Banane/ /home/lol/automatedBackup/$backupName/$folder/Banane");
system("cp -R $folderToBackup/Banane2/ /home/lol/automatedBackup/$backupName/$folder/Banane2");
system("cp -R $folderToBackup/quicksave/ /home/lol/automatedBackup/$backupName/$folder/quicksave");
system("cp -R $folderToBackup/autosave0/ /home/lol/automatedBackup/$backupName/$folder/autosave0");
system("cp -R $folderToBackup/autosave1/ /home/lol/automatedBackup/$backupName/$folder/autosave1");
system("cp -R $folderToBackup/autosave2/ /home/lol/automatedBackup/$backupName/$folder/autosave2");
system("cp -R $folderToBackup/KUMO!/ /home/lol/automatedBackup/$backupName/$folder/KUMO!");

function ensure (string $path = null) {
    if (empty($path)) {
        return;
    }
    if(!is_dir($path)) {
        system("mkdir $path");
    }
}