@ECHO OFF
SETLOCAL ENABLEEXTENSIONS
 
ECHO *** START: GIT REPO COMPARE ***
 
set keep=10
set source_pathMASTER=<<DIRECTORY--WHERE--RESULTS--ARE--STORED>>
set source_pathGITA=<<DIRECTORY--OF--REPO--TO--COMPARE>>
set source_pathGITB=<<DIRECTORY--OF--REPO--TO--COMPARE>>

set gitA_name=gitAName\
set gitA_sourceA=wp-config 
set gitA_mapA=wp-config

set gitA_sourceB=wp-content 
set gitA_mapB=wp-content

set gitA_sourceC=public 
set gitA_mapC=fav-icons

set gitB_name=gitBName\
set gitB_sourceA=wp-config 
set gitB_mapA=wp-config

set gitB_sourceB=mu-plugins
set gitB_mapB=wp-content\mu-plugins

set gitB_sourceC=theme
set gitB_mapC=wp-content\themes

set gitB_sourceD=third-party-plugins-forked
set gitB_mapD=wp-content\plugins

set gitB_sourceE=fav-icons
set gitB_mapE=fav-icons

set folder_name=%date:~-4,4%-%date:~-7,2%-%date:~0,2%
set log_file=logs\%date:~-4,4%-%date:~-7,2%-%date:~0,2%.log
 
>> %log_file%   ECHO ---------------------------------
 
>> %log_file%   ECHO ---- START
 
>> %log_file%   ECHO ---- TIME: %time%
 
>> %log_file%   ECHO. 
 
ECHO *** START: create local folder ***
mkdir %source_pathMASTER%%folder_name%
ECHO.
ECHO CREATED %source_pathMASTER%%folder_name% 
>> %log_file%   ECHO CREATED %source_pathMASTER%%folder_name% 
>> %log_file%   ECHO. 
ECHO.
ECHO *** END: create local folders ***
 
ECHO *** START: create compare folders ***
ECHO.
 
ROBOCOPY %source_pathGITA%%gitA_sourceA% %source_pathMASTER%%folder_name%\%gitA_name%%gitA_sourceA% /E /COPY:DAT /DCOPY:T /R:5 /W:10
ROBOCOPY %source_pathGITA%%gitA_sourceB% %source_pathMASTER%%folder_name%\%gitA_name%%gitA_sourceB% /E /COPY:DAT /DCOPY:T /R:5 /W:10
ROBOCOPY %source_pathGITA%%gitA_sourceC% %source_pathMASTER%%folder_name%\%gitA_name%%gitA_mapC% /E /COPY:DAT /DCOPY:T /R:5 /W:10

ROBOCOPY %source_pathGITB%%gitB_sourceA% %source_pathMASTER%%folder_name%\%gitB_name%%gitB_sourceA% /E /COPY:DAT /DCOPY:T /R:5 /W:10
ROBOCOPY %source_pathGITB%%gitB_sourceB% %source_pathMASTER%%folder_name%\%gitB_name%%gitB_mapB% /E /COPY:DAT /DCOPY:T /R:5 /W:10 /xf *.md
ROBOCOPY %source_pathGITB%%gitB_sourceC% %source_pathMASTER%%folder_name%\%gitB_name%%gitB_mapC% /E /COPY:DAT /DCOPY:T /R:5 /W:10
ROBOCOPY %source_pathGITB%%gitB_sourceD% %source_pathMASTER%%folder_name%\%gitB_name%%gitB_mapD% /E /COPY:DAT /DCOPY:T /R:5 /W:10
ROBOCOPY %source_pathGITB%%gitB_sourceE% %source_pathMASTER%%folder_name%\%gitB_name%%gitB_mapE% /E /COPY:DAT /DCOPY:T /R:5 /W:10

ECHO.
ECHO *** END: create compare folders ***

ECHO *** START: tidy up folders ***
for /f "skip=%keep% delims=" %%F in ('dir "%source_pathMASTER%" /b /ad-h /o-d') DO (
ECHO.
ECHO DELETING %source_pathMASTER%%%F
ECHO.
>> %log_file%   ECHO DELETED %source_pathMASTER%%%F
>> %log_file%   ECHO. 
rd /s /q %source_pathMASTER%%%F
)
 
ECHO *** END: tidy up local folders ***
 
ECHO.

>> %log_file%   ECHO ---------------------------------
 
>> %log_file%   ECHO ---- END
 
>> %log_file%   ECHO ---- TIME: %time%
 
>> %log_file%   ECHO. 
 
ECHO *** START: winmerge compare ***
"C:/Program Files (x86)/WinMerge/WinMergeU.exe" /r /e %source_pathMASTER%%folder_name%\%gitA_name% %source_pathMASTER%%folder_name%\%gitB_name%
PAUSE

"C:/Program Files (x86)/WinMerge/WinMergeU.exe" /r /e %source_pathMASTER%%folder_name%\%gitB_name% %source_pathMASTER%%folder_name%\%gitA_name%
PAUSE
EXIT /B %ERRORLEVEL%