### Server configuration
- PHP >=7.2  
- mySQL >=5.7

### Template path
`wp-content/themes/<theme_name>`  
Read information about template build in `wp-content/themes/<theme_name>/readme.md`

### Wordpress Admin Panel:
URL path: `/wp-admin`  
User: `chstu_admin`  
Pass: `12Qk@8S7qk28!U0pVH` (must be changed on production)

### For local developing
- Recovery dump from `db.sql`. All URLs in the dump `http://<projectname>.local` (dont'change). Every data and structure changes in local DB must export to dump `db.sql` 
- Create local config: go to `env` directory, copy `wp-config_sample.php`, rename to `wp-config_local.php`, and set your defines
- Create virtual host `<projectname>.local` in your server
- Go to `Settings - Permalinks - Custom Structure`, set `/%category%/%postname%/`