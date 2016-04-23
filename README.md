# scripture-journal
Web app to record journals for religion classes at BYU-Idaho
<a href="http://howtoterminal.com/scripture-journal">Scripture Journal</a>
<br>
## [Codeanywhere](https://codeanywhere.com/) Quick Setup
1. Make a container
2. Paste in the shell: 
```
git clone https://github.com/zvakanaka/scripture-journal.git
echo -e '$dbHost = "127.0.0.1";\n$dbUser = "root";' >> scripture-journal/db/set_local_credentials.sql
sudo mysql -uroot < scripture-journal/db/setup-db.sql
```
