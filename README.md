### SimpleBan

Allows you to ban a player or their IP and ID!

If you have any problems please create an issue I will try to answer them as soon as possible. And feel free to star the project

Finally, if you are looking for private plugins at a good price, I can make some on my discord: yakuuuuuuuuuuuuuuu_

### Config

```yaml
##Message
no-perm: "You don't have the permission"
not-player: "The player is not connected"
ban-succes: "You banned {player}"
not-ban: "This player is not banned"

##Ban
ban-ip:
  in-chat: true
  message-in-chat: "{player} ip was banned for {time} reason : {reason} by {staff}"
  kick-message: "Your ip were banned by {staff} for {time} for {reason}"
  permission: "ban-ip.cmd"
  connection-message: "Your ip are banned for : {time}\nYou have been banned for: {reason}\nYou were banned by : {staff}"
ban-id:
  in-chat: true
  message-in-chat: "{player} id was banned for {time} reason : {reason} by {staff}"
  kick-message: "Your id were banned by {staff} for {time} for {reason}"
  permission: "ban-id.cmd"
  connection-message: "You id are banned for : {time}\nYou have been banned for: {reason}\nYou were banned by : {staff}"
ban:
  in-chat: true
  message-in-chat: "{player} was banned for {time} reason : {reason} by {staff}"
  kick-message: "You were banned by {staff} for {time} for {reason}"
  permission: "ban.cmd"
  connection-message: "You are banned for : {time}\nYou have been banned for: {reason}\nYou were banned by : {staff}"
ban-perm:
  in-chat: true
  message-in-chat: "{player} was banned definitely reason : {reason} by {staff}"
  kick-message: "You were banned by {staff} definitely for {reason}"
  permission: "ban.cmd"
  connection-message: "You are permanently banned\nYou have been banned for: {reason}\nYou were banned by : {staff}"
unban:
  permission: "unban.cmd"
  succes: "You have unbanned {name}"
unban-ip:
  permission: "unban-ip.cmd"
  succes: "You have unbanned {ip}"
unban-id:
  permission: "unban-id.cmd"
  succes: "You have unbanned {id}"

```
### Features
| `BanId` | ✔ 
| `BanIP` | ✔ 
| `TempBan` | ✔ 
| `PermBan` | ✔ | 
