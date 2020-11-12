# BanTheBad Introduction
Quickly ban IP address, range, or block in IPTables

This little set of bash scripts was inspired by a need to ban rogue bad actors that appear in /var/log/syslog in Ubuntu. They're not consistent enough to get banned with a Fail2Ban filter, and not persistent enough for the recidive filter to give them a long-term ban.

When I see these people/bots poking around, I want to ban them. But as we see in the answer to many "how do I manually ban an IP with Fail2Ban", the answer is "you don't". Fail2Ban works on logs. But when a log pattern is found Fail2Ban bans the IP in IPTables, and **that** is what BanTheBad does.

Call it stupid, naive, overkill, or useless, but this is my answer to a problem, and I'm offering it here for whomever wants it.

# Usage

There are three user-facing scripts:

- `ban_single 1.2.3.4` : Adds 1.2.3.4 to the IPTables chain "single". This is for one-off bad guys.
- `ban_subnet 1.2.3` : Adds 1.2.3.0/24 to the IPTables chain "subnet". This is for more organized bad guys who use a whole subnet of IP addresses. You'll see similar random queries in syslog and others from 1.2.3.44, .55, 66, etc. Apparently they have access to that whole subnet, and their provider isn't locking that subnet down, so just block the whole thing.
- `ban_range 1.2.3.x-1.2.3.y` : This will/should ban all IPs from x to y using chain "range". This is not working at the moment. I've created a ticket for it and will get to it ASAP.

I have permissions on all scripts at 770 root:staff. YMMV

Each of the user-facing scripts are very short: They set the chain name, include the ban_common set of functions, and then invoke function `ban_this` with the IP parameter.

The `ban_this` function creates the IPTables chain if it doesn't exist. It inserts the chain at the top of the INPUT chain so that these rules are executed before others. Fail2Ban might remove an IP from the list after a period of time but BanTheBad never forgives. A RETURN is added to the bottom of that chain, and the specified IP spec is added to the top.

You can use any of the above commands with 'd' as the IP spec and it will delete the related rules and chain. Example: `ban_single d`.

On every execution a new script is updated, ban_all_*chain* where again, the chain is *single*, *subnet*, or *range*. The file is nothing more than a script to re-execute all ban_x instructions to rebuild the table. These scripts can get executed after a system restart. Decide for yourself if you want to 'forgive' some IP address(es) and use an editor to remove the command/rule from the ban_all_\* file.

To help avoid duplication of bans implemented with Fail2Ban, use the script `faul2ban_banned_subnets`. (Requires package **sqlite3**). This queries the F2B SQLite ban table. It finds IP blocks with more than 5 actions taken. It returns a simple report showing the jail, IP block, and the total number of blocks ("tickets"). So if there are 3 bans for block 1.2.3.* and 8 for 1.2.4, it only shows the .4 block abd shows that there were 8 offenses. You can use that data to decide about banning the entire subnet.

# Provided Ban List

I am leaving my list of bans in the ban_all_\* files. It seems reasonable that if these people are invading my space then they're as likely to bother you. Since the \_all scripts grow from top to bottom, older IPs will be removed occasionally to see if they're still compromised, and may be added back later. This results in nothing more than another ban list like we see elsewhere. In fact, I might incorporate other ban lists into this to be defensively proactive.

# Prejudice

I use tools to find the GeoLocation of IP addresses. Sometimes I use a WhoIs website. I also use the **geoip-bin** package for CLI lookup on the MaxMind database. For example: `geoiplookup 212.0.0.0`. That tells me the IP is registered in Bulgaria. I get a lot of abuse from Bulgaria, so I tend to ban entire subnets when I get a few hits from there.

I am not shy about blocking entire ISPs or countries. Personally I get zero legitimate traffic (web/email) from the Russian Federation, Estonia, Poland, Viet Nam, Bulgaria, and many other countries. There are servers in the UK that are owned by entities in other countries.

### I ban those servers

I have friends around the world, in those countries, and I understand how insensitive and offensive this is. If you don't like being included in a group that is considered worthy of exclusion, fix the problem! Get your ISPs to verify their clients and shutdown those who abuse the resources. Legally prosecute those who use servers for hacking. And I will certainly white-list the good ISPs and IP addresses that are used for legitimate purposes.

I also understand that IP addresses can be spoofed. I try, and recommend, to only ban based on sources of TCP connections, not UDP.

# License

This code is published under the MIT License - you are free to do whatever you wish with it. 

Comments and enhancements are certainly welcome. Please create a ticket to discuss changes before submitting a PR. I'd like to keep these scripts very small and merging in too much functionality might conflict with that current intent.