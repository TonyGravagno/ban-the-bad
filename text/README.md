# Text files

The goal of ban-the-bad is to identify and ban those who don't provide value to an environment. They might demonstrate the lack of value in the words they use, the bots they use, or their malicious acts against systems.

The files in this folder contain words, phrases, regex patterns, and other types of collections. There are lists of words that might be banned as a username, or in the user@domain.tld, or in text that's submitted in a form.

How you use this data is up to you. Will you restrict against only specific words like "foo"? Will you wildcard the words like "\*foo\*"?
The idea here is to use the words in "some process" and when you have found someone using offensive text, use ban-the-bad to take action against them. That process of determination is up to you.

# The Scunthorpe problem

Before you make a [clbuttic](https://www.google.com/search?q=clbuttic) error in how you use this stuff, think carefully about minimizing false positives, and how you will address them..

# Organization

The `text` folder collection has files that are occasionally modified, curated, re-evaluated, etc. Credit to original data sources is listed in the [sources.md](sources.md) doc. The file names here are generally the same as those of the source, with a prefix to identify the source.

There is no guarantee that these files will be kept in sync with the originals. I use these lists to derive my own, as seen in the subfolders. The data is here to use, or not, as you wish.

## Subfolders

Each folder under `text` has files derived from the base file. So the names are the same but the files have been modified to conform to the folder purpose.

### Folder banned_whole_usernames

These files are copied from the original and then modified to check against whole usernames and username@domain.tld. The names here are checked as-is, so the word 'foo' should only be used to match username foo and foo@domain.tld. Do not use these words for partial checks because there will be a lot of false positives, like username foodie and fingerfood@domain.tld.

In this folder is a consolidated banned_whole_usernames.txt which is compiled from Aggregator.php.

### Folder banned_username_substrings

(WIP) These words can be used for substring tests - to ban foodie and fingerfood with a test for \*foo\*. Related words have been removed from this list to avoid over-testing. Ref the Scunthorpe problem above.

### Folder banned_username_regex

(WIP) (As time permits) The above lists are combined and reduced into regex that can be used to test for username equivalence with `^foo$` or to test for substrings and more complexity with something like `/f(0|o)?/i`. Ref the Scunthorpe problem above.

### Other folders

Some folders have data that's been extracted from other files and sorted into individual files, usually based on language. The names of the folders indicate their sources.

## Personalizing

Files like banned_whole_usernames.txt are aggregated from the source data using programs from src/php.

The banned_whole_usernames.txt file and others should be copied elsewhere and then personalized. I have copied the banned_whole_usernames.txt file to banned_whole_usernames_tg.txt to personalize for my own use. When new data is available, I'll run code against it to regenerate the default banned_whole_usernames.txt file, and then get a diff on that to use as a delta for my personalized file.

If you have a better way to approach this, please create a suggestion in an Issue. Thanks.



These files represent my personal choices, which almost certainly won't agree with your own. You can start with my files and edit for your own purposes, or you can combine and edit the other files as you wish.

These files are also subject to revision based on my user feedback, like "my name is footballfan99 and I can't create an account!". Please consider these files as a guide and not plug-n-play.

