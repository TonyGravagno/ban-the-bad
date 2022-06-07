# src Folder

This folder has subfolders 'php' and 'shell'.

## php

The PHP programs are used to process source lists and ultimately to create banned_whole_usernames.txt.
Processing classes inherit functionality from BaseListProcessor.

I run ParseTuralus.php to break down the CSV into multiple language files.
Then ParseFreeWebHeaders.
Then Aggregator which pulls the above lists into a single list: banned_whole_usernames.txt.
Then GenerateTG to create my own personalized file, banned_whole_usernames_tg.txt.

OR, ProcessAll runs all of the above in the correct order.

I welcome you to compare the _tg verson to the base to see changes that I prefer, but create your own _initials.txt file that has your own preferences.

## shell

These are BASH scripts that accept one or more IP addresses or ranges, and creates Fail2Ban rules for them.

Core functionality comes from ban_common.

All of the shell scripts need documentation.


## License

**(C) 2022 Tony Gravagno : MIT Licenced : Irony acknowledged**


