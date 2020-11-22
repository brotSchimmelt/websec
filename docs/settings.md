# Settings & Configurations
---

This is a short overview over the configuration and setting files for the hacking platform.

## Server Configurations

The ```config.php``` file defines important constants for the project (server name, paths etc.). It is setup by the ```setup_docker.sh``` script.


## Database Connections
The ```db_login.php``` file contains the credentials for the **login** database. It is also setup by the ```setup_docker.sh``` script.

The ```db_shop.php```file contains the credentials for the **shop** database. It is also setup by the ```setup_docker.sh``` script.

The default credentials in both files are only placeholder values that should not be used in a production environment.


## Hacking Platform Settings
The ```settings.json``` file stores all settings for the hacking platform. The (recommended) default settings are stored in the ```settings.backup``` file. Settings can either be changed directly in the JSON file or in the admin area of the shop. All settings and their effects are documented below:


- **login**: enables or disables the login page and replaces it with an appropriate error page. Expects either *true* or *false* as value.
<br>

- **registration**: enables or disables the registration page and replaces it with an appropriate error page. Expects either *true* or *false* as value.
<br>

- **difficulty**: sets the difficulty level for the challenges. There is at the moment only a 'hard' and a 'normal' level. Expects either *true* (hard) or *false* (normal) as value.
<br>

- **badge_links**: links to external resources (e.g. Learnweb) for the hacking challenges. The links only come into effect, if a challenge has not been solved yet. Otherwise, the badges are automatically redirecting to the scoreboard. Every link has to start with *https* or *http*.
<br>

- **domains**: list of all allowed domains for the registration process. By default, only WWU mail addresses are allowed. Expects an array of string values.
<br>

- **usernames**: list of all forbidden user names for the registration process. By default, all names that are used as fake user names in the challenges are forbidden to avoid confusion. Expects an array of string values.
<br>

- **learnweb**: link to the current WebSec Learnweb course. The link has to start with *https* or *http*.

