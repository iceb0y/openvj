# OpenVJ Tools

### check_issues

Find which essential PHP extension is missing.

You should **execute this script before deployment** to check your environment.

```bash
#cd openvj/tools
php check_issues.php
```

### generate_RSA

Generate a new RSA private & public key pair.

You should generate your own key pair **after a new deployment** via this script and copy the result into `src/app/config/app.ini`.

```bash
#cd openvj/tools
php generate_RSA.php
```

### import_ACL

Import OpenVJ ACL rules into the database.

You should execute this script **after a new deployment** to initialize the database.

```bash
#cd openvj/tools
php import_ACL.php
```

### create_root_account

Create an OpenVJ account with the highest privilege (OPENVJ_GROUP_ROOT). For debug propose only.

```bash
#cd openvj/tools
php create_root_account.php
```

The new account:

```
Username: root
Password: openvjroot
```

**Notice**: This account could only be logged in from local (127.0.0.1).
