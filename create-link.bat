echo off
set pluginPath=..\moodledev39\mod\tab

rem remove the current link
..\outils\junction -d src

rem set the link
..\outils\junction src %pluginPath%

pause